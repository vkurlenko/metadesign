<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function findOrCreateByPhone(string $phone, ?string $name = null): User
    {
        $user = $this->userRepository->findOneByPhoneNumber($phone);

        if ($user) {
            if ($user->getFirstName() === null) {
                $this->userRepository->updateFirstName($user, $name);
            }
            return $user;
        }

        $user = new User();
        $user->setPhoneNumber($phone);
        $user->setFirstName($name);
        $this->userRepository->save($user);

        return $user;
    }
}