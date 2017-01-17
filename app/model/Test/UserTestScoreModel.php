<?php

namespace DontPanic\Test;

use DontPanic\Entities\UserTestScore;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class UserTestScoreModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(UserTestScore::class);
    }
}
