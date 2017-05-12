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
use DontPanic\Test\UserTestScoreModel;

class TestPresenter extends BasePresenter
{

    /** @var UserTestScoreModel @inject */
    public $userTestScoreModel;

    /** @var TestFormFactory @inject */
    public $testFormFactory;

    public function startup()
    {
        parent::startup();

        if (!$this->user->isLoggedIn()) {
            $this->redirect('Sign:testIn');
        }
        if (!$this->userEntity instanceof User) {
            $this->redirect('Sign:testIn');
        }
    }

    /**
     * @param $token
     *
     * @throws Http404NotFoundException
     * @throws \Nette\Application\AbortException
     */
    public function actionDefault($token)
    {
        /** @var UserTestScore $userScore */
        $userScore = $this->userTestScoreModel->getScore($this->userEntity, $this->test);

        if ($userScore && $userScore->isDone()) {
            $this->redirect('completed');
        }
    }

    public function renderDone()
    {
        $this->user->logout(true);
    }

    public function renderCompleted()
    {
        $this->user->logout(true);
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
            $this->redirect('done');
        };

        return $control;
    }
}