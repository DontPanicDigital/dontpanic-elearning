<?php

namespace DontPanic\User;

use DontPanic\Entities\User;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;
use DontPanic\Model\DoctrineModel;
use Nette\Http\Session;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

/**
 * @method onSignup(User $user)
 */
class UserAuthModel extends DoctrineModel implements IAuthenticator
{

    /** @var array|Event */
    public $onSignup = [];

    /** @var Session */
    protected $session;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->em      = $em;
        $this->er      = $this->em->getRepository(User::class);
        $this->session = $session;
    }

    /**
     * @param array $credentials
     *
     * @return Identity
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;

        /** @var User $user */
        $user = $this->findOneBy([ 'email' => $email ], []);

        if (!$user) {
            throw new AuthenticationException('Nesprávné heslo nebo email.', self::IDENTITY_NOT_FOUND);
        } elseif (!$user->getActive()) {
            throw new AuthenticationException('Nesprávné heslo nebo email.', self::INVALID_CREDENTIAL);
        } elseif (!Passwords::verify($password, $user->getPassword())) {
            throw new AuthenticationException('Nesprávné heslo nebo email.', self::INVALID_CREDENTIAL);
        } elseif (Passwords::needsRehash($user->password)) {
            $user->setPassword($password);
        }

        $user->setLastLoginAt(new \DateTime());
        $user->setNumberOfLogins($user->getNumberOfLogins() + 1);

        $this->em->flush($user);

        return $this->createIdentity($user);
    }

    /**
     * @param User $user
     *
     * @return Identity
     * @throws AuthenticationException
     */
    public function createIdentity(User $user)
    {
        $identityData = [
            'email'      => $user->getEmail(),
            'createdAt'  => $user->getCreatedAt(),
            'last_login' => $user->getLastLoginAt(),
        ];

        $roles = $user->getRoles(true);
        return new Identity($user->getId(), $roles, $identityData);
    }
}
