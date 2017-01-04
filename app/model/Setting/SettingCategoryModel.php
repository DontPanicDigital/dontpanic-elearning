<?php

namespace DontPanic\Setting;

use DontPanic\Entities\Setting;
use DontPanic\Entities\SettingCategory;
use Kdyby\Doctrine\EntityManager;
use DontPanic\Model\DoctrineModel;

class SettingCategoryModel extends DoctrineModel
{

    /**
     * SettingCategoryModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(SettingCategory::class);
    }

    public function list()
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->getQuery()->getResult();
    }
}
