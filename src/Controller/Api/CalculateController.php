<?php

namespace App\Controller\Api;

use App\Dto\CalculateRequest;
use App\Service\OrderService;
use App\Service\ResponseMessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CalculateController extends AbstractController
{

    const HTTP_CODE_SUCCESS = 200;
    const HTTP_CODE_FAIL = 500;


    public function __construct(
        private readonly OrderService $orderService,
        private readonly ResponseMessageService $responseMessageService,
    ) {
    }

    #[Route('/api/calculate', name: 'calculate', methods: ['POST'])]
    public function index(#[MapRequestPayload] CalculateRequest $data): Response
    {
        $order = $this->orderService->createOrder($data);
        $message = $this->responseMessageService->getCalculatorResponse($order);
        if ($order) {
            $response = [
                'data' => $data,
                'code' => self::HTTP_CODE_SUCCESS,
                'success' => true,
                'message' => $message
            ];
        } else {
            $response = [
                'data' => $data,
                'code' => self::HTTP_CODE_FAIL,
                'success' => false,
                'message' => $message
            ];
        }
        return $this->json($response);
    }
}

