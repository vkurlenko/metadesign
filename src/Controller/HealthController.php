<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HealthController extends AbstractController
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $this->connection->executeQuery('SELECT 1');

            return new JsonResponse([
                'status' => 'ok',
                'database' => 'up',
            ]);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'status' => 'error',
                'database' => 'down',
            ], 503);
        }
    }
}
