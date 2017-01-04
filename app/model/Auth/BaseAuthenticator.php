<?php

namespace DontPanic\Auth;

use DontPanic\Entities\User;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;

abstract class BaseAuthenticator extends \Nette\Object implements IAuthenticator
{

    /**
     * @param User $user
     *
     * @return Identity
     */
    protected function createIdentity(User $user)
    {
        $identityData = [
            'email' => $user->getEmail(),
        ];

        return new Identity($user->getId(), $user->getUserRole(true), $identityData);
    }
}