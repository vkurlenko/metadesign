<?php

namespace App\Service;

use App\Entity\Order;

class ResponseMessageService
{
    const REALTY_TYPE_FLAT = 'flat';
    const MESSAGE_SUCCESS_FLAT = '
                    Тип недвижимости: realty
                    Площадь помещения: square
                    Тип помещения: roomType
                    Класс ремонта: repairType
                    Номер телефона для связи: phone
                    Стоимость ремонта:  cost  руб.
                    
                    Заявка принята, спасибо за обращение! 
                    Мы с Вами свяжемся в течение 15 минут.';

    const MESSAGE_SUCCESS_COMMERCE = '
                    Тип недвижимости: realty
                    Площадь помещения: square
                    Тип помещения: roomType
                    Класс ремонта: repairType
                    Номер телефона для связи: phone 
                    Стоимость дизайн-проекта: от cost руб.
                    
                    Заявка принята, спасибо за обращение!
                    Расчет стоимости ремонта в выбранном типе недвижимости осуществляется в индивидуальном порядке.
                    Мы с Вами свяжемся в течение 15 минут.';

    const MESSAGE_FAIL = 'Что-то пошло не так';

    public function __construct(private PlaceholderReplaceService $placeholderReplaceService)
    {
    }

    public function getCalculatorResponse(Order $order)
    {
        if ($order) {
            $message = $order->getPropertyType()->getName() == self::REALTY_TYPE_FLAT
                ? self::MESSAGE_SUCCESS_FLAT
                : self::MESSAGE_SUCCESS_COMMERCE;
            $message = $this->placeholderReplaceService->calculatorMessagePlaceholderReplace($order, $message);
        } else {
            $message = self::MESSAGE_FAIL;
        }

        return nl2br($message);
    }
}