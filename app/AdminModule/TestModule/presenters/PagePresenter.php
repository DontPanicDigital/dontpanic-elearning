<?php

namespace AdminModule\TestModule;

use DontPanic\Entities\Test;
use DontPanic\Forms\CreateTestForm;
use DontPanic\Forms\TestFormFactory;

class PagePresenter extends BasePresenter
{

    /** @var TestFormFactory @inject */
    public $testFormFactory;

    /**************************************************************************************************************z*v*/
    /*************** COMPONENTS ***************/

    /**
     * @return CreateTestForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentCreateTestForm()
    {
        /** @var CreateTestForm $control */
        $control             = $this->testFormFactory->createTest();
        $control->company    = $this->company;
        $control->user       = $this->userEntity;
        $control->onCreate[] = function (Test $test) {
            $this->redirect('detail', [ 'id' => $test->getToken() ]);
        };

        return $control;
    }
}