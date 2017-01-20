<?php

namespace WebModule;

use App\Model;
use AppModule\Exception\Http404NotFoundException;
use DontPanic\Entities\Test;
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
     */
    public function createComponentDisplayForm()
    {
        /** @var DisplayTestForm $control */
        $control       = $this->testFormFactory->displayTest();
        $control->test = $this->test;

        return $control;
    }
}