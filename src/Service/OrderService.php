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
        private RealtyStatusTypeRepository $roomTypeRepository,
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
            $roomType = $this->roomTypeRepository->findOneByName($data->roomType);
            $squareArea = $data->areaSquare;
            $user = $this->userService->findOrCreateByPhone($phone);

            $order = new Order();
            $order->setPropertyType($realtyType);
            $order->setRepairClass($repairType);
            $order->setRoomType($roomType);
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