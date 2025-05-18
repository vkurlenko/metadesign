<?php

namespace App\Controller\Api;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    const RU_EN_DICTIONARY = array(
        "Коммерческое помещение" => "commerce",
        "Квартира" => "flat",
        "Дом" => "house",

        "Новостройка" => "new",
        "Вторичка" => "secondary",

        "Бизнес" => "business",
        "Комфорт" => "comfort",
        "Премиум" => "premium"
    );
    const EN_RU_DICTIONARY = array(
        "commerce" => "Коммерческое помещение",
        "flat" => "Квартира",
        "house" => "Дом",

        "new" => "Новостройка",
        "secondary" => "Вторичка",

        "business" => "Бизнес",
        "comfort" => "Комфорт",
        "premium" => "Премиум"
    );
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

            $message = $order->getPropertyType()->getName() == self::REALTY_TYPE_FLAT
                ? self::MESSAGE_SUCCESS_FLAT
                : self::MESSAGE_SUCCESS_COMMERCE;

            $message = $this->replace($message, $order);

            $message = nl2br($message);

            $response = [
                'data'    => $data,
                'code'    => self::HTTP_CODE_SUCCESS,
                'result'  => 'true',
                'message' => $message
            ];
        } else {
            $response = [
                'data'    => $data,
                'code'    => self::HTTP_CODE_FAIL,
                'result'  => 'false',
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

    private function replace(string $message, Order $order): string
    {
        $search = ["realty", "square", "roomType", "repairType", "phone", "cost"];
        $replace = [
            $this->translate($order->getPropertyType()->getName()),
            number_format($order->getSquare(), 2, '.', ' '),
            $this->translate($order->getRoomType()->getName()),
            $this->translate($order->getRepairClass()->getName()),
            $this->phone_format((string)$order->getUserId()->getPhoneNumber()),
            number_format($order->getCost(), 2,  '.', ' ')
            ];
        $message = str_replace($search, $replace, $message);
        return $message;
    }

    private function translate(string $word): string
    {
        return array_key_exists($word, self::RU_EN_DICTIONARY)
            ? self::RU_EN_DICTIONARY[$word]
            : self::EN_RU_DICTIONARY[$word];
    }

    function phone_format($phone): string
    {
        $phone = trim('+7'.$phone);

        $res = preg_replace(
            array(
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
            ),
            array(
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4',
                '+7 ($2) $3-$4',
            ),
            $phone
        );
        return $res;
    }

}
