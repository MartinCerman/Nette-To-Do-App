<?php

namespace App\Models;

use App\Models\Database\Entity\User;
use Nettrine\ORM\EntityManagerDecorator;

class UserRepository
{
    public function __construct(
        private readonly EntityManagerDecorator $entityManager
    )
    {
    }

    public function findOneBy(array $criteria): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy($criteria);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}