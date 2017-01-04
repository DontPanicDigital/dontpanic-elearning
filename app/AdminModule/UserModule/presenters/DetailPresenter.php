<?php

namespace AdminModule\UserModule;

use AppModule\Exception\Http400BadRequestException;
use DontPanic\Entities\User;
use DontPanic\Forms\PasswordFormFactory;
use DontPanic\Forms\UserFormFactory;
use DontPanic\User\UserModel;

class DetailPresenter extends BasePresenter
{

    /** @var UserModel @inject */
    public $userModel;

    /** @var UserFormFactory @inject */
    public $userFormFactory;

    /** @var PasswordFormFactory @inject */
    public $passwordFormFactory;

    /** @var User */
    private $userDetailEntity;

    /** @var null @persistent */
    public $id;

    public function startup()
    {
        parent::startup();
        $this->userDetailEntity = $this->userModel->findOneBy([ 'token' => $this->getParameter('id') ]);
        if ($this->userDetailEntity === null) {
            throw new Http400BadRequestException;
        }
    }

    public function renderDefault()
    {
    }

    /**************************************************************************************************************z*v*/
    /*************** COMPONENTS ***************/

    /**
     * @return \DontPanic\Forms\UserProfileEditForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentUserProfileForm()
    {
        $control             = $this->userFormFactory->createUserProfileForm($this->userDetailEntity);
        $control->onUpdate[] = function () {
            $this->flashMessage('ok');
            $this->redirect('this', [ 'id' => $this->getParameter('id') ]);
        };

        return $control;
    }

    /**
     * @return \DontPanic\Forms\PasswordChangeForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentPasswordChange()
    {
        $control             = $this->passwordFormFactory->createPasswordChange();
        $control->userEntity = $this->userDetailEntity;

        if ($this->user->isAllowed('user', 'change-password-others') && $this->userEntity->getId() !== $this->userDetailEntity->getId()) {
            $control->verifyOldPassword = false;
        }
        
        $control->onChange[] = function () {
            $this->flashMessage('ok');
            $this->redirect('this', [ 'id' => $this->getParameter('id') ]);
        };

        return $control;
    }

    /**************************************************************************************************************z*v*/
    /*************** HANDLE ***************/

}