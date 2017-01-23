<?php

namespace WebModule;

use App\Model;
use AppModule\Exception\Http404NotFoundException;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Entities\UserTestScore;
use DontPanic\Forms\DisplayTestForm;
use DontPanic\Forms\TestFormFactory;
use DontPanic\Test\TestModel;

class TestPresenter extends BasePresenter
{

    /** @var TestModel @inject */
    public $testModel;

    /** @var TestFormFactory @inject */
    public $testFormFactory;

    /** @var Test */
    private $test;

    public function startup()
    {
        parent::startup();
        $this->test = $this->testModel->findOneBy([ 'token' => $this->getParameter('token') ]);

        if (!$this->user->isLoggedIn()) {
            $this->redirect('Sign:testIn', [ 'backlink' => $this->storeRequest() ]);
        }
        if (!$this->userEntity instanceof User) {
            $this->redirect('Sign:testIn', [ 'backlink' => $this->storeRequest() ]);
        }
        if (!$this->userEntity->isPhoneVerification()) {
            $this->redirect('Sign:authCode', [
                'backlink' => $this->storeRequest(),
                'token'    => $this->getParameter('token'),
            ]);
        }
    }

    /**
     * @param $token
     *
     * @throws Http404NotFoundException
     */
    public function actionDefault($token)
    {

        if (!$this->test instanceof Test) {
            throw new Http404NotFoundException;
        }

        $this->template->test = $this->test;
    }

    /************************************************************************************************************z*v***/
    /********** COMPONENTS **********/

    /**
     * @return DisplayTestForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentDisplayForm()
    {
        /** @var DisplayTestForm $control */
        $control             = $this->testFormFactory->displayTest();
        $control->test       = $this->test;
        $control->user       = $this->userEntity;
        $control->onCreate[] = function (UserTestScore $userTestScore) {
            $this->redirect('this');
        };

        return $control;
    }
}