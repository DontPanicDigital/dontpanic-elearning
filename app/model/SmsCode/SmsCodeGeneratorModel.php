<?php

namespace DontPanic\SmsCode;

use DontPanic\Entities\User;
use DontPanic\Model\DoctrineModel;

class SmsCodeGeneratorModel extends DoctrineModel
{

    /** @var User */
    private $user;

    public function getCode()
    {
        return 123456;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
