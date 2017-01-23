<?php

namespace DontPanic\Test;

use Doctrine\ORM\NonUniqueResultException;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Entities\UserTestScore;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class UserTestScoreModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(UserTestScore::class);
    }

    /**
     * @param User $user
     * @param Test $test
     *
     * @return mixed
     */
    public function getScore(User $user, Test $test)
    {
        try {
            $qb = $this->createQueryBuilder('userTestScore');
            $qb->andWhere('userTestScore.user = :user');
            $qb->andWhere('userTestScore.test = :test');
            $qb->setParameter('user', $user);
            $qb->setParameter('test', $test);

            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
