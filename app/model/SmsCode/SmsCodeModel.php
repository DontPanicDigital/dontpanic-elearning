<?php

namespace DontPanic\SmsCode;

use Doctrine\ORM\NonUniqueResultException;
use DontPanic\Entities\SmsCode;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;

class SmsCodeModel extends DoctrineModel
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(SmsCode::class);
    }

    /**
     * @param User $user
     * @param Test $test
     * @param      $code
     *
     * @return bool|mixed|null
     */
    public function getCodeByUserAndTest(User $user, Test $test, $code = null)
    {
        try {
            $qb = $this->createQueryBuilder('smsCode');
            $qb->andWhere('smsCode.user = :user');
            $qb->andWhere('smsCode.test = :test');
            $qb->andWhere('smsCode.used = 0');
            $qb->setParameter('user', $user);
            $qb->setParameter('test', $test);

            if ($code) {
                $qb->andWhere('smsCode.code = :code');
                $qb->setParameter('code', $code);
            }

            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return false;
        }

        return null;
    }
}
