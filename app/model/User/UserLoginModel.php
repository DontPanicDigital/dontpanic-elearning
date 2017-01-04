<?php

namespace DontPanic\User;

use DontPanic\Model\MainModel;

class UserLoginModel extends MainModel
{

    /** @var \Nette\Security\User */
    protected $user;

    /**
     * @param \Nette\Security\User $user
     */
    public function __construct(\Nette\Security\User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool   $remember
     *
     * @throws \Nette\Security\AuthenticationException
     */
    public function login(string $email, string $password, bool $remember = false)
    {
        if ($remember) {
            $this->user->setExpiration('14 days', false);
        } else {
            $this->user->setExpiration('1 hour', true);
        }

        $this->user->login($email, $password);
    }
}