<?php
namespace DontPanic\Forms;

use DontPanic\SmsCode\SmsCodeModel;
use DontPanic\User\UserLoginModel;
use DontPanic\User\UserModel;
use DontPanic\User\UserRegisterModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Translation\ITranslator;

class SignFormFactory
{

    /** @var UserRegisterModel */
    protected $userRegisterModel;

    /** @var UserLoginModel */
    protected $userLoginModel;

    /** @var UserModel */
    protected $userModel;

    /** @var SmsCodeModel */
    protected $smsCodeModel;

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
     * @param SmsCodeModel      $smsCodeModel
     * @param ITranslator       $translator
     * @param EntityManager     $em
     */
    public function __construct(
        UserRegisterModel $userRegisterModel,
        UserLoginModel $userLoginModel,
        UserModel $userModel,
        SmsCodeModel $smsCodeModel,
        ITranslator $translator,
        EntityManager $em
    )
    {
        $this->userRegisterModel = $userRegisterModel;
        $this->userLoginModel    = $userLoginModel;
        $this->userModel         = $userModel;
        $this->smsCodeModel      = $smsCodeModel;
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

    /**
     * @return SignUpForm
     */
    public function createTestSignUp()
    {
        return new TestSignUpForm($this->userRegisterModel, $this->userModel, $this->translator);
    }

    /**
     * @return SmsCodeForm
     */
    public function createSmsCode()
    {
        return new SmsCodeForm($this->smsCodeModel, $this->userModel, $this->translator);
    }
}