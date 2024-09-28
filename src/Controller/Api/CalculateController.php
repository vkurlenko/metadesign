<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CalculateController extends AbstractController
{
    const REALTY_TYPE_FLAT = 'flat';

    const HTTP_CODE_SUCCESS = 200;
    const HTTP_CODE_FAIL = 500;

    const MESSAGE_SUCCESS_FLAT = '
                    Тип недвижимости: Квартира
                    Площадь помещения: 
                    Тип помещения: 
                    Класс ремонта:
                    Номер телефона для связи: 
                    Стоимость ремонта:  ...  руб.
                    
                    Заявка принята, спасибо за обращение! 
                    Мы с Вами свяжемся в течение 15 минут.';

    const MESSAGE_SUCCESS_COMMERCE = '
                    Тип недвижимости: Дом/Коммерческая недвижимость
                    Площадь помещения: 
                    Тип помещения: 
                    Класс ремонта:
                    Номер телефона для связи: 
                    Стоимость дизайн-проекта: от ... руб.
                    
                    Заявка принята, спасибо за обращение!
                    Расчет стоимости ремонта в выбранном типе недвижимости осуществляется в индивидуальном порядке.
                    Мы с Вами свяжемся в течение 15 минут.';

    const MESSAGE_FAIL = 'Что-то пошло не так';

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/api/calculate', name: 'calculate')]
    public function index(Request $request): Response
    {
        $data = $request->getPayload()->all();

        if ($this->isValid($data)) {
            $message = $request->getPayload()->get('realty-type') == self::REALTY_TYPE_FLAT
                ? self::MESSAGE_SUCCESS_FLAT
                : self::MESSAGE_SUCCESS_COMMERCE;

            $message = nl2br($message);

            $response = [
                'data'    => $data,
                'code'    => self::HTTP_CODE_SUCCESS,
                'result'  => 'success',
                'message' => $message
            ];
        } else {
            $response = [
                'data'    => $data,
                'code'    => self::HTTP_CODE_FAIL,
                'result'  => 'fail',
                'message' => self::MESSAGE_FAIL
            ];
        }

        return $this->json($response);
    }

    /**
     * @param $data
     * @return bool
     */
    private function isValid($data): bool
    {
        return true;
    }
}
