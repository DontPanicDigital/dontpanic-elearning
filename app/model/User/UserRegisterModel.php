<?php

namespace DontPanic\User;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Company\CompanyFacade;
use DontPanic\Credit\CreditFacade;
use DontPanic\Credit\CreditHistoryException;
use DontPanic\Credit\CreditModel;
use DontPanic\Credit\CreditStatusModel;
use DontPanic\Entities\User;
use DontPanic\Setting\SettingValueNotFoundException;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;
use DontPanic\Model\DoctrineModel;
use Nette\Http\Session;
use Nette\Utils\Random;

/**
 * @method onSignup(User $user)
 */
class UserRegisterModel extends DoctrineModel
{

    /** @var array|Event */
    public $onSignup = [];

    /** @var UserModel */
    protected $userModel;

    /** @var UserRoleModel */
    protected $userRoleModel;

    /** @var CompanyFacade */
    protected $companyFacade;

    /** @var Session */
    protected $session;

    /** @var CreditFacade */
    protected $creditFacade;

    /**
     * UserRegisterModel constructor.
     *
     * @param EntityManager $em
     * @param UserModel     $userModel
     * @param UserRoleModel $userRoleModel
     * @param Session       $session
     */
    public function __construct(
        EntityManager $em,
        UserModel $userModel,
        UserRoleModel $userRoleModel,
        Session $session)
    {
        $this->em            = $em;
        $this->er            = $this->em->getRepository(User::class);
        $this->userModel     = $userModel;
        $this->userRoleModel = $userRoleModel;
        $this->session       = $session;
    }

    /**
     * @param $data
     *
     * @return User
     * @throws UserRegistrationException
     */
    public function registerUser($data)
    {
        try {
            $user = $this->insertUserToDb($data);
            if (!empty($data['password'])) {
                $this->onSignup($user);
            }
        } catch (UniqueConstraintViolationException $e) {
            throw new UserRegistrationException;
        }

        return $user;
    }

    protected function insertUserToDb($data)
    {
        $user = new User();

        empty($data['password']) ?: $user->setPassword($data['password']);
        empty($data['email']) ?: $user->setEmail($data['email']);
        empty($data['name']) ?: $user->setName($data['name']);
        empty($data['phone']) ?: $user->setPhone($data['phone']);

        $user->setToken(Random::generate(30));
        $user->setCreatedAt(new \DateTime());

        $this->userModel->saveUser($user);

        return $user;
    }
}
