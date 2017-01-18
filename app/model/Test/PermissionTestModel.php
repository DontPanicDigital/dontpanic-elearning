<?php

namespace DontPanic\Test;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DontPanic\Entities\BranchOffice;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Exception\System\PermissionException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class PermissionTestModel extends DoctrineModel
{

    /** @var Test */
    private $test;

    /** @var User */
    private $user;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(Test::class);
    }

    /**
     * @throws PermissionException
     */
    public function isAssigned()
    {
        try {
            $this->createQueryBuilder('test')
                 ->leftJoin('test.company', 'company')
                 ->leftJoin('company.users', 'users')
                 ->andWhere('test = :test')->setParameter('test', $this->test)
                 ->andWhere('users = :users')->setParameter('users', $this->user)
                 ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new PermissionException($e->getMessage());
        } catch (NonUniqueResultException $e) {
            throw new PermissionException($e->getMessage());
        }
    }

    /**
     * @param Test $test
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}