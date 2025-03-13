<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\RealtyType;
use App\Entity\RepairType;
use App\Entity\RoomType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function index(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $data = $request->getPayload()->all();

        $order = new Order($data, $entityManager);
        $validationResult = $order->validate($validator);

        if ($this->isValid($validationResult)) {
            $order->calculateCost();
            $entityManager->persist($order);
            $entityManager->flush();

            $message = $request->getPayload()->get('realty-type') == self::REALTY_TYPE_FLAT
                ? self::MESSAGE_SUCCESS_FLAT
                : self::MESSAGE_SUCCESS_COMMERCE;

            $message = nl2br($message);

            $response = [
                'data'    => $data,
                'code'    => self::HTTP_CODE_SUCCESS,
                'result'  => 'success',
                'message' => $message,
                'order' => $order
            ];
        } else {
            $response = [
                'data'    => $data,
                'code'    => self::HTTP_CODE_FAIL,
                'result'  => 'fail',
                'message' => self::MESSAGE_FAIL,
                'errors'  => $validationResult
            ];
        }

        return $this->json($response);
    }

    private function isValid(array $validationResult): bool
    {
        foreach ($validationResult as $error) {
            if (count($error)) {
                return false;
            }
        }
        return true;
    }
}
