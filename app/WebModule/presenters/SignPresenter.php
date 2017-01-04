<?php
namespace WebModule;

use DontPanic\Forms\SignFormFactory;

class SignPresenter extends BasePresenter
{

    /** @var SignFormFactory @inject */
    public $signFormFactory;

    public function actionOut()
    {
        $this->getUser()->logout(true);
        $this->flashMessage('Logout is ok');
        $this->redirect('in');
    }

    /************************************************************************************************************z*v***/
    /********** COMPONENTS **********/

    /**
     * @return \DontPanic\Forms\SignInForm
     * @throws \Nette\Application\AbortException
     */
    protected function createComponentSignInForm()
    {
        $control = $this->signFormFactory->createSignIn();

        $control->onSignIn[] = function () {
            $this->flashMessage('Login is ok');
            $this->redirect('this');
        };

        return $control;
    }

    /**
     * @return \DontPanic\Forms\SignUpForm
     * @throws \Nette\Application\AbortException
     */
    protected function createComponentSignUpForm()
    {
        $control = $this->signFormFactory->createSignUp();

        $control->onSignUp[] = function () {
            $this->flashMessage('Registration is ok');
            $this->redirect('this');
        };

        return $control;
    }

}