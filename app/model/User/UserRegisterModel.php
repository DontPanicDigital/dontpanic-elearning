<?php

namespace DontPanic\User;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Company\CompanyFacade;
use DontPanic\Credit\CreditFacade;
use DontPanic\Credit\CreditHistoryException;
use DontPanic\Credit\CreditModel;
use DontPanic\Credit\CreditStatusModel;
use DontPanic\Entities\User;
use DontPanic\Exception\System\CreateException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;
use Nette\Utils\Validators;

/**
 * @method onSignup(User $user)
 */
class UserRegisterModel extends DoctrineModel
{

    /** @var array|Event */
    public $onSignup = [];

    /** @var UserModel */
    protected $userModel;

    /** @var CompanyFacade */
    protected $companyFacade;

    /** @var CreditFacade */
    protected $creditFacade;

    /** @var User */
    private $user;

    /**
     * UserRegisterModel constructor.
     *
     * @param EntityManager $em
     * @param UserModel     $userModel
     */
    public function __construct(EntityManager $em, UserModel $userModel)
    {
        $this->em        = $em;
        $this->er        = $this->em->getRepository(User::class);
        $this->userModel = $userModel;

        $this->user = new User();
    }

    /**
     * @return mixed
     * @throws CreateException
     */
    public function create()
    {
        try {
            $this->save($this->user);
            $this->onSignup($this->user);
        } catch (UniqueConstraintViolationException $e) {
            throw new CreateException($e->getMessage());
        }
    }

    public function prepareFromArray($data)
    {
        if (array_key_exists('password', $data)) {
            $this->user->setPassword($data['password']);
        }

        if (array_key_exists('email', $data) && Validators::isEmail($data['email'])) {
            $this->user->setEmail($data['email']);
        }

        if (array_key_exists('phone', $data)) {
            $this->user->setPhone($data['phone']);
        }

        if (array_key_exists('name', $data)) {
            $this->user->setName($data['name']);
        }

        $this->user->setToken();
    }
}
