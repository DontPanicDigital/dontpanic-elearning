<?php

namespace WebModule;

use AppModule\Exception\Http403ForbiddenException;
use AppModule\Exception\Http404NotFoundException;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Exception\System\CreateException;
use DontPanic\Forms\SignFormFactory;
use DontPanic\Forms\SmsCodeForm;
use DontPanic\Forms\TestSignUpForm;
use DontPanic\SmsCode\CreateSmsCodeModel;
use DontPanic\SmsCode\SmsCodeModel;
use DontPanic\Test\TestModel;
use Nette\Security\Identity;
use Nette\Application\AbortException;
use Nette\Security\AuthenticationException;

class SignPresenter extends BasePresenter
{

    /** @var SignFormFactory @inject */
    public $signFormFactory;

    /** @var CreateSmsCodeModel @inject */
    public $createSmsCodeModel;

    /** @var SmsCodeModel @inject */
    public $smsCodeModel;

    /** @var TestModel @inject */
    public $testModel;

    /** @persistent */
    public $backlink = '';

    public function actionAuthCode($token)
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect('testIn');
        }

        /** @var Test $test */
        $test = $this->testModel->findOneBy([ 'token' => $token ]);
        if (!$test instanceof Test) {
            throw new Http404NotFoundException;
        }

        try {
            if (!$this->smsCodeModel->getCodeByUserAndTest($this->userEntity, $test)) {
                $this->createSmsCodeModel->setUser($this->userEntity);
                $this->createSmsCodeModel->setTest($test);
                $this->createSmsCodeModel->create();
            }
        } catch (CreateException $e) {
            throw new Http403ForbiddenException;
        }

        /** @var SmsCodeForm $smsCodeForm */
        $smsCodeForm       = $this->getComponent('smsCodeForm');
        $smsCodeForm->test = $test;

        $this->template->test = $test;
    }

    public function actionOut()
    {
        $this->getUser()->logout(true);
        $this->redirect('in');
    }

    /************************************************************************************************************z*v***/
    /********** COMPONENTS **********/

    /**
     * @return TestSignUpForm
     * @throws AbortException
     * @throws AuthenticationException
     */
    protected function createComponentTestSignUpForm()
    {
        /** @var TestSignUpForm $control */
        $control = $this->signFormFactory->createTestSignUp();

        $form = $control->getComponent('form');
        if (!$this->test->isSmsVerification()) {
            unset($form['phone']);
        }

        $control->onSignUp[] = function (User $user) {
            /** @var Identity $identity */
            $identity = new Identity($user->getId(), [], null);
            $this->user->login($identity);
            $this->redirect('Test:default');
        };

        return $control;
    }

    /**
     * @return \DontPanic\Forms\SignInForm
     * @throws \Nette\Application\AbortException
     */
    protected function createComponentSignInForm()
    {
        $control = $this->signFormFactory->createSignIn();

        $control->onSignIn[] = function () {
            $this->restoreRequest($this->backlink);
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
            $this->restoreRequest($this->backlink);
            $this->redirect('this');
        };

        return $control;
    }

    /**
     * @return SmsCodeForm
     *
     * @throws \Nette\Application\AbortException
     */
    public function createComponentSmsCodeForm()
    {
        /** @var SmsCodeForm $control */
        $control       = $this->signFormFactory->createSmsCode();
        $control->user = $this->userEntity;

        return $control;
    }

}