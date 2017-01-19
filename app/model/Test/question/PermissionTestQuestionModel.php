<?php

namespace DontPanic\Test;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DontPanic\Entities\BranchOffice;
use DontPanic\Entities\TestQuestion;
use DontPanic\Entities\User;
use DontPanic\Exception\System\PermissionException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class PermissionTestQuestionModel extends DoctrineModel
{

    /** @var TestQuestion */
    private $testQuestion;

    /** @var User */
    private $user;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestQuestion::class);
    }

    /**
     * @throws PermissionException
     */
    public function isAssigned()
    {
        try {
            $this->createQueryBuilder('testQuestion')
                 ->leftJoin('testQuestion.test', 'test')
                 ->leftJoin('test.company', 'company')
                 ->leftJoin('company.users', 'users')
                 ->andWhere('testQuestion = :testQuestion')->setParameter('testQuestion', $this->testQuestion)
                 ->andWhere('users = :users')->setParameter('users', $this->user)
                 ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw new PermissionException($e->getMessage());
        } catch (NonUniqueResultException $e) {
            throw new PermissionException($e->getMessage());
        }
    }

    /**
     * @param TestQuestion $testQuestion
     */
    public function setTestQuestion(TestQuestion $testQuestion)
    {
        $this->testQuestion = $testQuestion;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}