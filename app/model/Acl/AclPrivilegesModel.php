<?php

namespace DontPanic\Acl;

use DontPanic\Entities\AclPrivilege;
use DontPanic\Model\DoctrineModel;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\Strings;

class AclPrivilegesModel extends DoctrineModel
{

    /**
     * UserModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(AclPrivilege::class);
    }

    public function create($name)
    {
        try {
            $privilegeEntity = new AclPrivilege();
            $privilegeEntity->setName($name);
            $privilegeEntity->setKeyName(Strings::webalize($name));
            $this->save($privilegeEntity);

            return $privilegeEntity;
        } catch (UniqueConstraintViolationException $e) {
            throw new CreateAclPrivilegeException;
        }
    }

    public function list()
    {
        return $this->createQueryBuilder('r')->getQuery()->getResult();
    }
}
