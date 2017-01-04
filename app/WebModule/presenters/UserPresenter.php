<?php

namespace WebModule;

use DontPanic\Forms\PasswordChangeForm;
use DontPanic\Forms\PasswordFormFactory;
use DontPanic\Forms\PasswordRequestForm;
use DontPanic\Forms\PasswordUpdateForm;

class UserPresenter extends BasePresenter
{

    /** @var PasswordFormFactory @inject */
    public $passwordFormFactory;

    /**
     * @param $token
     *
     * @throws \Nette\Application\AbortException
     */
    public function actionPasswordUpdate($token)
    {
        if ($token === null) {
            $this->redirect('passwordRequest');
        }

        $userEntity = $this->userModel->findOneBy([
            'passwordToken' => $token,
        ]);

        if (!$userEntity) {
            $this->flashMessage('Password token is not valid');
            $this->redirect('passwordRequest');
        }

        /** @var PasswordUpdateForm $passwordUpdateFrom */
        $passwordUpdateFrom             = $this->getComponent('passwordUpdateForm');
        $passwordUpdateFrom->userEntity = $userEntity;
    }

    /************************************************************************************************************z*v***/
    /********** COMPONENTS **********/

    /**
     * @return PasswordRequestForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentPasswordRequestForm()
    {
        /** @var PasswordRequestForm $control */
        $control = $this->passwordFormFactory->createPasswordRequest();

        $control->onRequest[] = function () {
            $this->flashMessage('Password request sent');
            $this->redirect('this');
        };

        return $control;
    }

    /**
     * @return PasswordUpdateForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentPasswordUpdateForm()
    {
        /** @var PasswordUpdateForm $control */
        $control = $this->passwordFormFactory->createPasswordUpdate();

        $control->onChange[] = function () {
            $this->flashMessage('Password updated');
            $this->redirect('Sign:in');
        };

        return $control;
    }

    /**
     * @return PasswordChangeForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentPasswordChangeForm()
    {
        /** @var PasswordChangeForm $control */
        $control             = $this->passwordFormFactory->createPasswordChange();
        $control->userEntity = $this->userEntity;

        $control->onChange[] = function () {
            $this->flashMessage('Password updated');
            $this->redirect('this');
        };

        return $control;
    }
}
