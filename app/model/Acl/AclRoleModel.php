<?php

namespace DontPanic\Acl;

use DontPanic\Entities\AclRole;
use DontPanic\Model\DoctrineModel;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\Strings;

class AclRoleModel extends DoctrineModel
{

    /**
     * UserModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(AclRole::class);
    }

    public function create($name)
    {
        try {
            $roleEntity = new AclRole();
            $roleEntity->setName($name);
            $roleEntity->setKeyName(Strings::webalize($name));
            $this->save($roleEntity);

            return $roleEntity;
        } catch (UniqueConstraintViolationException $e) {
            throw new CreateAclRoleException;
        }
    }

    public function getList()
    {
        return $this->createQueryBuilder('r')->getQuery()->getResult();
    }

    public function rootList()
    {
        return $this->createQueryBuilder('r')->andWhere('r.parent IS NULL')->getQuery()->getResult();
    }

    public function getParentRole($parentId, $parentKey, &$roles)
    {
        $qb = $this->createQueryBuilder('r');

        null === $parentId
            ? $qb->andWhere('r.parent IS NULL')
            : $qb->andWhere('r.parent = :parentId')
                 ->setParameter('parentId', $parentId);

        $qb = $qb->getQuery()->getResult();

        /** @var AclRole $role */
        foreach ($qb as $role) {
            $roles[] = [ 'key_name' => $role->getKeyName(), 'parent_key' => $parentKey ];
            $this->getParentRole($role->getId(), $role->getKeyName(), $roles);
        }
    }

    public function getRoles()
    {
        $roles = [];
        $this->getParentRole(null, null, $roles);

        return $roles;
    }
}
