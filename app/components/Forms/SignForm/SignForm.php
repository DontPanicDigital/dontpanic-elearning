<?php
namespace DontPanic\Forms;

use Kdyby\Translation\ITranslator;
use DontPanic\User\UserLoginModel;
use DontPanic\User\UserModel;
use DontPanic\User\UserRegisterModel;
use Kdyby\Doctrine\EntityManager;

class SignFormFactory
{

    /** @var UserRegisterModel */
    protected $userRegisterModel;

    /** @var UserLoginModel */
    protected $userLoginModel;

    /** @var UserModel */
    protected $userModel;

    /** @var ITranslator */
    protected $translator;

    /** @var EntityManager */
    protected $em;

    /**
     * SignFormFactory constructor.
     *
     * @param UserRegisterModel $userRegisterModel
     * @param UserLoginModel    $userLoginModel
     * @param UserModel         $userModel
     * @param ITranslator       $translator
     * @param EntityManager     $em
     */
    public function __construct(UserRegisterModel $userRegisterModel, UserLoginModel $userLoginModel, UserModel $userModel, ITranslator $translator, EntityManager $em)
    {
        $this->userRegisterModel = $userRegisterModel;
        $this->userLoginModel    = $userLoginModel;
        $this->userModel         = $userModel;
        $this->translator        = $translator;
        $this->em                = $em;
    }

    /**
     * @return SignInForm
     */
    public function createSignIn()
    {
        return new SignInForm($this->userLoginModel, $this->translator);
    }

    /**
     * @return SignUpForm
     */
    public function createSignUp()
    {
        return new SignUpForm($this->userRegisterModel, $this->userModel, $this->translator, $this->em);
    }
}