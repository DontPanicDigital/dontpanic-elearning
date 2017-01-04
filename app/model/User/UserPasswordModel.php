<?php

namespace DontPanic\User;

use DontPanic\Entities\User;
use DontPanic\Model\MainModel;
use Nette\Security\Passwords;
use Nette\Utils\DateTime;
use Nette\Utils\Random;

class UserPasswordModel extends MainModel
{

    /** @var UserModel */
    protected $userModel;

    /**
     * @param UserModel $userModel
     */
    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function verifyPassword(User $user, $oldPassword)
    {
        if (!Passwords::verify($oldPassword, $user->getPassword())) {
            throw new UserPasswordNotMatchException;
        }
    }

    public function changePassword(User $user, $password)
    {
        $user->setPasswordExpirationAt(null);
        $user->setPasswordToken(null);
        $user->setPassword($password);

        $this->userModel->save($user);

        return $user;
    }

    public function requestPassword($email)
    {
        /** @var User $userEntity */
        $userEntity = $this->userModel->findByEmail($email);
        if ($userEntity) {
            $userEntity->setPasswordExpirationAt((new DateTime())->add(new \DateInterval('P1D')));
            $userEntity->setPasswordToken(Random::generate(30));
            $this->userModel->save($userEntity);
            $this->email($userEntity);
        } else {
            throw new UserNotFoundException;
        }
    }

    protected function email(User $user)
    {
    }
}