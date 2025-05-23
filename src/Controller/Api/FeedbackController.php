<?php

namespace App\Controller\Api;

use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use function Sodium\add;

class FeedbackController extends AbstractController
{

    const HTTP_CODE_SUCCESS = 200;
    const HTTP_CODE_FAIL = 500;

    #[Route('/api/feedback', name: 'feedback')]
    public function index(Request $request, EntityManagerInterface $entityManager):JsonResponse
    {
        $data = $request->getPayload()->all();
        $selectedMethods = $_POST['contact_method'] ?? [];
        $contacts = [
            'phone_call' => in_array('phone_call', $selectedMethods),
            'telegram'   => in_array('telegram', $selectedMethods),
            'whatsapp'   => in_array('whatsapp', $selectedMethods)
        ];
        $data = array_merge($data, $contacts);

        $feedback = new Feedback($data, $entityManager);
        $entityManager->persist($feedback);
        $entityManager->flush();
        $response = [
            'data'    => $data,
            'code'    => self::HTTP_CODE_SUCCESS,
            'result'  => 'true',
            'message' => 'Feedback successfully created.'
        ];
        return $this->json($response);
    }
}