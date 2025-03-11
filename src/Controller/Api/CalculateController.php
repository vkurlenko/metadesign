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

        if ($this->isValid($data, $validator, $entityManager)) {
            $result = $this->calculate($data);

            $message = $request->getPayload()->get('realty-type') == self::REALTY_TYPE_FLAT
                ? self::MESSAGE_SUCCESS_FLAT
                : self::MESSAGE_SUCCESS_COMMERCE;

            $message = $this->replace($message, $data, $result);

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
    private function isValid($data, ValidatorInterface $validator, EntityManagerInterface $entityManager): bool
    {
        $roomType = new RoomType();
        $roomType->setName($data['room-type']);
        $errors = $validator->validate($roomType);
        if ($errors->count() > 0) {
            echo 1;
            return false;
        }

        $repairType = new RepairType();
        $repairType->setName($data['repair-type']);
        $errors = $validator->validate($repairType);
        if ($errors->count() > 0) {
            echo 1;
            return false;
        }

        $realtyType = new RealtyType();
        $realtyType->setName($data['realty-type']);
        $errors = $validator->validate($realtyType);
        if ($errors->count() > 0) {
            echo 1;
            return false;
        }

        $user = new User();
        $user->setPhoneNumber(preg_replace('/[^0-9]/', '', $data['phone']));
        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            return false;
        }

        $squareArea = $data['area-square'];

        $order = new Order();
        $order->setRoomType($roomType);
        $order->setRepairClass($repairType);
        $order->setPropertyType($realtyType);
        $order->setUserId($user);
        $order->setSquare($squareArea);
        $order->setCreatedAt(new \DateTime('now'));
        $errors = $validator->validate($order);
        if ($errors->count() > 0) {
            return false;
        }

        return True;
    }

    private function calculate(array $data): float
    {
        echo 0;
        return 0;
    }

    private function replace(string $message, array $data, float $result): string
    {
            return str_replace(array_keys($data), array_values($data), $message);
    }
}
