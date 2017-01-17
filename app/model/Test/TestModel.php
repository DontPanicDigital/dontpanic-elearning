<?php

namespace DontPanic\Test;

use DontPanic\Entities\Test;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class TestModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(Test::class);
    }
}
