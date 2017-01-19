<?php

namespace DontPanic\Test;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DontPanic\Entities\BranchOffice;
use DontPanic\Entities\TestOption;
use DontPanic\Entities\User;
use DontPanic\Exception\System\PermissionException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class PermissionTestOptionModel extends DoctrineModel
{

    /** @var TestOption */
    private $testOption;

    /** @var User */
    private $user;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestOption::class);
    }

    /**
     * @throws PermissionException
     */
    public function isAssigned()
    {
        try {
            $this->createQueryBuilder('testOption')
                 ->leftJoin('testOption.question', 'testQuestion')
                 ->leftJoin('testQuestion.test', 'test')
                 ->leftJoin('test.company', 'company')
                 ->leftJoin('company.users', 'users')
                 ->andWhere('testOption = :testOption')->setParameter('testOption', $this->testOption)
                 ->andWhere('users = :users')->setParameter('users', $this->user)
                 ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new PermissionException($e->getMessage());
        } catch (NonUniqueResultException $e) {
            throw new PermissionException($e->getMessage());
        }
    }

    /**
     * @param TestOption $testOption
     */
    public function setTestOption(TestOption $testOption)
    {
        $this->testOption = $testOption;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}