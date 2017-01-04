<?php

namespace DontPanic\User;

use DontPanic\Entities\User;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\Strings;

class UserFacade extends \Nette\Object
{

    /** @var EntityManager */
    protected $em;

    /** @var UserModel */
    protected $userModel;

    /**
     * UserFacade constructor.
     *
     * @param EntityManager $em
     * @param UserModel     $userModel
     */
    public function __construct(EntityManager $em, UserModel $userModel)
    {
        $this->em        = $em;
        $this->userModel = $userModel;
    }

    public function update(User $user, $body)
    {
        isset($body['name']) ? $user->setName($body['name']) : null;
        isset($body['surname']) ? $user->setSurname($body['surname']) : null;

        if (isset($body['phone'])) {
            if ($body['phone'] !== $user->getPhone()) {
                if (!$this->userModel->findByPhone($body['phone'])) {
                    $user->setPhone($body['phone']);
                }
            }
        }

        if (isset($body['email'])) {
            if (Strings::lower($body['email']) !== $user->getEmail()) {
                if (!$this->userModel->findByEmail($body['email'])) {
                    $user->setEmail($body['email']);
                }
            }
        }

        $this->userModel->save($user);

        return $user;
    }
}
