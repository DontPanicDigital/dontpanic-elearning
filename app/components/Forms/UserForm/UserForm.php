<?php
namespace DontPanic\Forms;

use Kdyby\Translation\ITranslator;
use DontPanic\Entities\User;
use DontPanic\User\UserFacade;
use DontPanic\User\UserModel;
use Kdyby\Doctrine\EntityManager;

class UserFormFactory
{

    /** @var UserModel */
    protected $userModel;

    /** @var UserFacade */
    protected $userFacade;

    /** @var ITranslator */
    protected $translator;

    /** @var EntityManager */
    protected $em;

    /**
     * UserFormFactory constructor.
     *
     * @param UserModel     $userModel
     * @param UserFacade    $userFacade
     * @param ITranslator   $translator
     * @param EntityManager $em
     */
    public function __construct(UserModel $userModel, UserFacade $userFacade, ITranslator $translator, EntityManager $em)
    {
        $this->userModel  = $userModel;
        $this->userFacade = $userFacade;
        $this->translator = $translator;
        $this->em         = $em;
    }

    /**
     * @param User|null $user
     *
     * @return UserProfileEditForm
     */
    public function createUserProfileForm(User $user = null)
    {
        return new UserProfileEditForm($this->userModel, $this->userFacade, $this->translator, $user);
    }
}