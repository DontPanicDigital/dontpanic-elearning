<?php

namespace DontPanic\Test;

use DontPanic\Entities\BranchOffice;
use DontPanic\Entities\Company;
use DontPanic\Entities\Test;
use DontPanic\Model\FilterModel;
use Kdyby\Doctrine\EntityManager;

class ListingTestModel extends FilterModel
{

    /**
     * BranchOfficeModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->namespace = 'test';

        $this->em = $em;
        $this->er = $this->em->getRepository(Test::class);
        $this->qb = $this->createQueryBuilder($this->namespace);

        $this->searchColumns = [];
    }

    public function getList()
    {
        $this->callNonsetFunctions();

        return $this->qb;
    }

    public function setCompany(Company $company)
    {
        $this->qb->leftJoin($this->namespace . '.company', 'company');
        $this->qb->andWhere('company = :company');
        $this->qb->setParameter('company', $company);
    }
}