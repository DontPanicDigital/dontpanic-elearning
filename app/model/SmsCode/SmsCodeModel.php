<?php

namespace DontPanic\SmsCode;

use DontPanic\Entities\SmsCode;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class SmsCodeModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(SmsCode::class);
    }
}
