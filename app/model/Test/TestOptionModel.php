<?php

namespace DontPanic\Test;

use DontPanic\Entities\TestOption;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class TestOptionModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestOption::class);
    }
}
