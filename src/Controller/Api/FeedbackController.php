<?php

namespace App\Controller\Api;

use App\Dto\FeedbackRequest;
use App\Service\FeedbackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class FeedbackController extends AbstractController
{

    const HTTP_CODE_SUCCESS = 200;
    const HTTP_CODE_FAIL = 500;

    public function __construct(
        private FeedbackService $feedbackService,
    ) {
    }

    #[Route('/api/feedback', name: 'feedback')]
    public function index(
        #[MapRequestPayload] FeedbackRequest $data
    ): JsonResponse {
        $feedback = $this->feedbackService->createFeedback($data);
        if ($feedback) {
            $response = [
                'data' => $data,
                'code' => self::HTTP_CODE_SUCCESS,
                'result' => true,
                'message' => 'Feedback successfully created.'
            ];
        } else {
            $response = [
                'data' => $data,
                'code' => self::HTTP_CODE_FAIL,
                'result' => false,
                'message' => 'Something went wrong.'
            ];
        }

        return $this->json($response);
    }
}