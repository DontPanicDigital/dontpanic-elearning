<?php
namespace AdminModule;

use DontPanic\Forms\SignFormFactory;

class SignPresenter extends BasePresenter
{

    /** @var SignFormFactory @inject */
    public $signFormFactory;

    public function actionOut()
    {
        $this->getUser()->logout(true);
        $this->flashMessage($this->translator->trans('user.admin.message_after_logout'));
        $this->redirect('in');
    }

    /************************************************************************************************************z*v***/
    /********** COMPONENTS **********/

    /**
     * @return \DontPanic\Forms\SignInForm
     * @throws \InvalidArgumentException
     * @throws \Nette\Application\AbortException
     */
    protected function createComponentSignInForm()
    {
        $control = $this->signFormFactory->createSignIn();

        $control->onSignIn[] = function () {
            $this->user->setExpiration('60 minutes', true);
            $this->flashMessage($this->translator->trans('user.admin.message_after_login'));
            $this->redirect('Page:default');
        };

        return $control;
    }
}