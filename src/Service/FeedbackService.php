<?php

namespace App\Service;

use App\Dto\FeedbackRequest;
use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use App\Repository\ServiceTypeRepository;

class FeedbackService
{
    public function __construct(
        private PhoneFormatterService $phoneFormatterService,
        private UserService $userService,
        private ServiceTypeRepository $serviceTypeRepository,
        private FeedbackRepository $feedbackRepository,
    ) {
    }

    public function createFeedback(FeedbackRequest $data): ?Feedback
    {
        try {
            $phone = $this->phoneFormatterService->normalize($data->phone);
            $contacts = [
                'phone_call' => in_array('phone_call', $data->contactMethods),
                'telegram' => in_array('telegram', $data->contactMethods),
                'whatsapp' => in_array('whatsapp', $data->contactMethods),
            ];

            $serviceType = $this->serviceTypeRepository->findOneByName($data->serviceType);
            $user = $this->userService->findOrCreateByPhone($phone, $data->userName);


            $feedback = new Feedback();
            $feedback->setServiceType($serviceType);
            $feedback->setUserId($user);
            $feedback->setTelegram($contacts['telegram']);
            $feedback->setWhatsapp($contacts['whatsapp']);
            $feedback->setPhoneCall($contacts['phone_call']);
            $feedback->setCreatedAt(new \DateTime('now'));
            $this->feedbackRepository->save($feedback);

            return $feedback;
        } catch (\Exception $e) {
            return null;
        }
    }
}