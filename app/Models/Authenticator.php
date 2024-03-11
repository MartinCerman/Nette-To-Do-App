<?php

namespace App\Models;

use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

class Authenticator implements \Nette\Security\Authenticator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Passwords      $passwords
    )
    {
    }

    function authenticate(string $user, string $password): SimpleIdentity
    {
        $userData = $this->userRepository->findOneBy(['email' => $user]);
        $userData?->setPassword($this->passwords->hash('heslo'));
        $this->userRepository->flush();

        if(!$userData){
            throw new AuthenticationException('User not found');
        }

        if(!$this->passwords->verify($password, $userData->getPassword())){
            throw new AuthenticationException('Bad password');
        }

        return new SimpleIdentity($userData->getId(), $userData->toArray());
    }

}