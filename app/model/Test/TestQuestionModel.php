<?php

namespace DontPanic\Test;

use DontPanic\Entities\TestQuestion;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class TestQuestionModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestQuestion::class);
    }
}
