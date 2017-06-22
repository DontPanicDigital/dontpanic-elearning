<?php

namespace WebModule;

use App\Model;
use AppModule\Exception\Http404NotFoundException;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Entities\UserTestScore;
use DontPanic\Exception\System\EmailException;
use DontPanic\Forms\DisplayTestForm;
use DontPanic\Forms\TestFormFactory;
use DontPanic\Test\TestModel;
use DontPanic\Test\UserTestScoreModel;
use DontPanic\User\EndTestNoticeEmailService;
use DontPanic\User\UserTestDoneEmailService;
use Nette\Application\LinkGenerator;

class TestPresenter extends BasePresenter
{

    /** @var UserTestScoreModel @inject */
    public $userTestScoreModel;

    /** @var TestFormFactory @inject */
    public $testFormFactory;

    /** @var LinkGenerator @inject */
    public $linkGenerator;

    /** @var array */
    public $senderConfig = [];

    public function startup()
    {
        parent::startup();

        if (!$this->user->isLoggedIn()) {
            $this->redirect('Sign:testIn');
        }
        if (!$this->userEntity instanceof User) {
            $this->redirect('Sign:testIn');
        }
        $this->prepareEmailConfig();
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
        $this->sendEmailPassTest();
        $this->sendEmailEndTestNotice();
        $this->user->logout(true);
    }

    public function renderCompleted()
    {
        $this->user->logout(true);
    }

    /************************************************************************************************************z*v***/
    /********** EMAILS **********/

    public function sendEmailPassTest()
    {
        try {
            $userTestDoneEmail = new UserTestDoneEmailService($this->userEntity, $this->test, $this->senderConfig, $this->translator, $this->linkGenerator);
            $userTestDoneEmail->addTo($this->userEntity->getEmail(), $this->userEntity->getFullName());
            $userTestDoneEmail->setSubject('Absolvování kurzu - ' . $this->test->getName());
            $userTestDoneEmail->send();
        } catch (EmailException $e) {
        }
    }

    public function sendEmailEndTestNotice()
    {
        if (null === $this->test->getNotifyEmail()) {
            return;
        }
        try {
            $endTestNoticeEmail = new EndTestNoticeEmailService($this->userEntity, $this->test, $this->senderConfig, $this->translator, $this->linkGenerator);
            $endTestNoticeEmail->addTo($this->test->getNotifyEmail());
            $endTestNoticeEmail->setSubject('Absolvování kurzu - ' . $this->test->getName());
            $endTestNoticeEmail->send();
        } catch (EmailException $e) {
        }
    }

    private function prepareEmailConfig()
    {
        $this->senderConfig = [
            'from_email' => 'elearning@dntp.cz',
            'from_name'  => 'Absolvování kurzu ',
        ];
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