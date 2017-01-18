<?php

namespace AdminModule\TestModule;

use DontPanic\Entities\Test;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Exception\System\NotFoundException;
use DontPanic\Forms\CreateTestForm;
use DontPanic\Forms\TestFormFactory;
use DontPanic\Test\DeleteTestModel;
use DontPanic\Test\ListingTestModel;
use DontPanic\Test\TestModel;

class PagePresenter extends BasePresenter
{

    /** @var TestModel @inject */
    public $testModel;

    /** @var TestFormFactory @inject */
    public $testFormFactory;

    /** @var ListingTestModel @inject */
    public $listingTestModel;

    /** @var DeleteTestModel @inject */
    public $deleteTestModel;

    public function renderDefault()
    {
        $this->listingTestModel->setCompany($this->company);
        $this->template->testsList = $this->listingTestModel->getList()->getQuery()->getResult();
    }

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

    /**************************************************************************************************************z*v*/
    /*************** HANDLE ***************/

    /**
     * @param string $testToken
     *
     * @throws \InvalidArgumentException
     * @throws \Nette\Application\AbortException
     */
    public function handleRemoveTest($testToken)
    {
        try {
            /** @var Test $test */
            $test = $this->testModel->findOneBy([ 'token' => $testToken ]);
            if (!$test) {
                throw new NotFoundException('Test for delete');
            }

            $this->deleteTestModel->setTest($test);
            $this->deleteTestModel->do();
        } catch (NotFoundException $e) {
            $this->flashMessage($this->translator->trans('company.delete.errors.not_found'));
        } catch (DeleteException $e) {
            $this->flashMessage($this->translator->trans('company.delete.errors.error'));
        }
        if ($this->isAjax()) {
            $this->redrawControl('testListing');
            $this->redrawControl('flashMessages');
        } else {
            $this->redirect('this');
        }
    }
}