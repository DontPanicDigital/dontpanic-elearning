<?php

namespace DontPanic\Acl;

use DontPanic\Entities\Acl;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class AclModel extends DoctrineModel
{

    /**
     * UserModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(Acl::class);
    }

    public function getRules()
    {
        return $this->createQueryBuilder('a')->getQuery()->getResult();
    }

    public function checkPermission($role, $privilege, $resource)
    {
        return $this
            ->createQueryBuilder('a')
            ->andWhere('a.role = :role')
            ->andWhere('a.privilege = :privilege')
            ->andWhere('a.resource = :resource')
            ->setParameter('role', $role)
            ->setParameter('privilege', $privilege)
            ->setParameter('resource', $resource)
            ->getQuery()->getOneOrNullResult();
    }
}
