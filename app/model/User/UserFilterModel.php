<?php

namespace DontPanic\User;

use Kdyby\Doctrine\EntityManager;
use DontPanic\Entities\AclRole;
use DontPanic\Entities\User;
use DontPanic\Model\FilterModel;

class UserFilterModel extends FilterModel
{

    const SEX_MALE   = 'male';
    const SEX_FEMALE = 'female';

    public static $setTypes = [
        self::SEX_MALE,
        self::SEX_FEMALE,
    ];

    /**
     * UserModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->namespace = 'u';

        $this->em = $em;
        $this->er = $this->em->getRepository(User::class);
        $this->qb = $this->createQueryBuilder($this->namespace);

        $this->searchColumns = [
            'u.name', 'u.surname',
        ];
    }

    public function getUsers()
    {
        $this->callNonsetFunctions();

        return $this->qb;
    }

    /**
     * @param $sexType
     */
    public function setSex($sexType)
    {
        if (in_array($sexType, self::$setTypes)) {
            $this->qb->andWhere('u.sex = :sex');
            $this->qb->setParameter('sex', $sexType);
        }
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $roles = array_filter($roles, function ($role) {
            if ($role instanceof AclRole) {
                return $role->getId();
            }
            if (is_numeric($role)) {
                return $role;
            }
        });
        if (count($roles)) {
            $this->qb->innerJoin('u.userRoles', 'r');
            $this->qb->andWhere('r.id IN(:roles)');
            $this->qb->setParameter('roles', $roles);
        }
    }
}
