<?php

namespace App\Service;

use App\Entity\Order;

class PlaceholderReplaceService
{
    public function __construct(
        private TranslatorService $translatorService,
        private PhoneFormatterService $phoneFormatterService,
    ) {
    }

    public function calculatorMessagePlaceholderReplace(Order $order, string $message)
    {
        $search = ["realty", "square", "roomType", "repairType", "phone", "cost"];
        $replace = [
            $this->translatorService->translate($order->getPropertyType()->getName()),
            number_format($order->getSquare(), 2, '.', ' '),
            $this->translatorService->translate($order->getRoomType()->getName()),
            $this->translatorService->translate($order->getRepairClass()->getName()),
            $this->phoneFormatterService->denormalize((string)$order->getUserId()->getPhoneNumber()),
            number_format($order->getCost(), 2, '.', ' ')
        ];
        $message = str_replace($search, $replace, $message);
        return $message;
    }
}