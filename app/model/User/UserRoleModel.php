<?php

namespace DontPanic\User;

use DontPanic\Entities\UserRole;
use Kdyby\Doctrine\EntityManager;
use DontPanic\Entities\User;
use DontPanic\Model\DoctrineModel;

class UserRoleModel extends DoctrineModel
{
    /**
     * UserRoleModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->er = $this->em->getRepository(UserRole::class);
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function findById($id) {
        return $this->find($id);
    }
}
