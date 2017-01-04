<?php
namespace DontPanic\Forms;

use DontPanic\User\UserModel;
use DontPanic\User\UserPasswordModel;
use Kdyby\Translation\ITranslator;

class PasswordFormFactory
{

    /** @var UserPasswordModel */
    protected $userPasswordModel;

    /** @var UserModel */
    protected $userModel;

    /** @var ITranslator */
    protected $translator;

    /**
     * PasswordFormFactory constructor.
     *
     * @param UserPasswordModel $userPasswordModel
     * @param UserModel         $userModel
     * @param ITranslator       $translator
     */
    public function __construct(UserPasswordModel $userPasswordModel, UserModel $userModel, ITranslator $translator)
    {
        $this->userPasswordModel = $userPasswordModel;
        $this->userModel         = $userModel;
        $this->translator        = $translator;
    }

    /**
     * @return PasswordRequestForm
     */
    public function createPasswordRequest()
    {
        return new PasswordRequestForm($this->userPasswordModel, $this->userModel, $this->translator);
    }

    /**
     * @return PasswordUpdateForm
     */
    public function createPasswordUpdate()
    {
        return new PasswordUpdateForm($this->userPasswordModel, $this->translator);
    }

    /**
     * @return PasswordChangeForm
     */
    public function createPasswordChange()
    {
        return new PasswordChangeForm($this->userPasswordModel, $this->translator);
    }
}