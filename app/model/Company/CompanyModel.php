<?php

namespace DontPanic\Company;

use DontPanic\Entities\Company;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class CompanyModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(Company::class);
    }
}
