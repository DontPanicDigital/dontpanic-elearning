<?php

namespace DontPanic\Api;

use Doctrine\DBAL\Types;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DontPanic\Exception\Code\UserCodes;
use Kdyby\Doctrine\EntityManager;
use DontPanic\Entities\ApiToken;
use DontPanic\Entities\User;
use DontPanic\Exception\System\NotFoundException;
use DontPanic\Model\DoctrineModel;

class ApiTokenModel extends DoctrineModel
{

    const TOKEN_WEBVIEW      = 'webview';
    const TOKEN_REGISTRATION = 'registration';

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(ApiToken::class);
    }

    public function findTokenByUserAndType(User $user, $type)
    {
        try {
            return $this
                ->createQueryBuilder('t')
                ->andWhere('t.user = :user')
                ->andWhere('t.type = :type')
                ->setParameter('user', $user)
                ->setParameter('type', $type)
                ->getQuery()->getSingleResult();
        } catch (NonUniqueResultException $e) {
            return null;
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findTokenByString($string, User $user = null)
    {
        $qb = $this
            ->createQueryBuilder('t')
            ->andWhere('t.token = :token')
            ->setParameter('token', $string);

        if ($user) {
            $qb->andWhere('t.user = :user')
               ->setParameter('user', $user);
        }

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NonUniqueResultException $e) {
            return null;
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param User      $user
     * @param           $type
     * @param \DateTime $expiresAt
     * @param bool      $unique
     *
     * @return ApiToken
     * @throws NotFoundException
     */
    public function generateToken(User $user, $type, \DateTime $expiresAt, $unique = true)
    {
        if (!$user) {
            throw new NotFoundException('Can not found user for generate API token', UserCodes::USER_NOT_FOUND);
        }

        if ($unique) {
            /** @var ApiToken $oldToken */
            $oldToken = $this->findTokenByUserAndType($user, $type);

            if ($oldToken) {
                $oldToken->setExpiresAt(new \DateTime());
            }
        }

        $apiTokenEntity = new ApiToken();
        $apiTokenEntity->setToken();
        $apiTokenEntity->setUser($user);
        $apiTokenEntity->setExpiresAt($expiresAt);
        $apiTokenEntity->setType($type);

        $this->save($apiTokenEntity);

        return $apiTokenEntity;
    }

    public function deleteToken(ApiToken $apiToken)
    {
        $this->delete($apiToken);
    }

    public function clearExpiredTokens()
    {
        $qb = $this->createQueryBuilder();
        $qb->delete(ApiToken::class, 'a');
        $qb->where('a.expiresAt < :expiresAt');
        $qb->setParameter('expiresAt', new \DateTime(), Types\Type::DATETIME);
        $qb->getQuery()->execute();
    }
}
