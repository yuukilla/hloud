<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserService
{
    private ManagerRegistry $mr;
    public function __construct(ManagerRegistry $mr)
    {
        $this->mr = $mr;
    }

    public function updateBaseData(User $user, mixed $data)
    {
        $entityManager = $this->mr->getManager();
        $user->setFirstName($data->getFirstName() ?: $user->getFirstName());
        $user->setLastName($data->getLastName() ?: $user->getLastName());
        $user->setUsername($data->getUsername() ?: $user->getUsername());
        $user->setEmail($data->getEmail() ?: $user->getEmail());
        $entityManager->persist($user);
        $entityManager->flush();

    }

    public function updateProfilePhoto(User $user, int $fileID)
    {
        $entityManager = $this->mr->getManager();
        $user->setPhoto($fileID ?: $user->getPhoto());
        $entityManager->persist($user);
        $entityManager->flush();
    }
}