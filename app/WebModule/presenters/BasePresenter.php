<?php

namespace WebModule;

use App\Model;
use DontPanic\Entities\Company;
use DontPanic\Entities\Test;
use DontPanic\Test\TestModel;
use Nette\Http\Response;
use Nette\Application\BadRequestException;

abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    /** @var TestModel @inject */
    public $testModel;

    /** @var @persistent */
    public $token;

    /** @var Test */
    public $test;

    /** @var Company */
    public $company;

    public function startup()
    {
        parent::startup();
        parent::setUserEntity();

        $this->initTest();
        $this->initCompany();
    }

    public function createTemplate($class = null)
    {
        $template          = parent::createTemplate($class);
        $template->test    = $this->test;
        $template->company = $this->company;

        return $template;
    }

    /************************************************************************************************************z*v***/
    /********** VERIFY **********/

    public function verifyUserPhone()
    {
        if (!$this->test->isSmsVerification()) {
            return;
        }
        if (!$this->userEntity->getPhone(false)) {
            $this->user->logout(true);
            $this->redirect('Sign:testIn');
        }
        if (!$this->userEntity->isPhoneVerification()) {
            $this->redirect('Sign:authCode');
        }
    }

    /************************************************************************************************************z*v***/
    /********** INIT **********/

    /**
     * @throws BadRequestException
     */
    private function initTest()
    {
        $token      = $this->getParameter('token');
        $this->test = $this->testModel->findOneBy([ 'token' => $token ]);

        if (!$this->test instanceof Test) {
            $this->error(Response::S404_NOT_FOUND);
        }
    }

    private function initCompany()
    {
        /** @var Company $company */
        $this->company = $this->test->getCompany();

        if (!$this->company instanceof Company) {
            $this->error(Response::S404_NOT_FOUND);
        }
    }
}
