<?php

namespace DontPanic\Test;

use DontPanic\Entities\UserTestAnswer;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class UserTestAnswerModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(UserTestAnswer::class);
    }
}
