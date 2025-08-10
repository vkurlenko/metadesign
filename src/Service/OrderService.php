<?php

namespace App\Service;

use App\Dto\CalculateRequest;
use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\RealtyTypeRepository;
use App\Repository\RepairTypeRepository;
use App\Repository\RealtyStatusTypeRepository;

class OrderService
{
    public function __construct(
        private CalculatorService $calculatorService,
        private PhoneFormatterService $phoneFormatterService,
        private UserService $userService,

        private RealtyTypeRepository $realtyTypeRepository,
        private RepairTypeRepository $repairTypeRepository,
        private RealtyStatusTypeRepository $realtyStatusTypeRepository,
        private OrderRepository $orderRepository,
    ) {
    }

    public function createOrder(CalculateRequest $data): ?Order
    {
        try {
            $phone = $this->phoneFormatterService->normalize($data->phone);
            $cost = $this->calculatorService->calculateCost($data);

            $realtyType = $this->realtyTypeRepository->findOneByName($data->realtyType);
            $repairType = $this->repairTypeRepository->findOneByName($data->repairType);
            $realtyStatusType = $this->realtyStatusTypeRepository->findOneByName($data->realtyStatusType);
            $squareArea = $data->areaSquare;
            $user = $this->userService->findOrCreateByPhone($phone);

            $order = new Order();
            $order->setRealtyType($realtyType);
            $order->setRepairType($repairType);
            $order->setRealtyStatusType($realtyStatusType);
            $order->setSquare($squareArea);
            $order->setUserId($user);
            $order->setCreatedAt(new \DateTime('now'));
            $order->setCost($cost);

            $this->orderRepository->save($order);

            return $order;
        } catch (\Exception $e) {
            return null;
        }
    }
}