<?php

namespace AdminModule\TestModule;

use AppModule\Exception\Http401UnauthorizedException;
use AppModule\Exception\Http404NotFoundException;
use DontPanic\Entities\Test;
use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Exception\System\NotFoundException;
use DontPanic\Exception\System\PermissionException;
use DontPanic\Forms\CreateTestForm;
use DontPanic\Forms\CreateTestQuestionForm;
use DontPanic\Forms\TestFormFactory;
use DontPanic\Forms\TestQuestionFormFactory;
use DontPanic\Forms\UpdateTestForm;
use DontPanic\Test\DeleteTestModel;
use DontPanic\Test\ListingTestModel;
use DontPanic\Test\PermissionTestModel;
use DontPanic\Test\TestModel;

class PagePresenter extends BasePresenter
{

    /** @var TestModel @inject */
    public $testModel;

    /** @var TestFormFactory @inject */
    public $testFormFactory;

    /** @var TestQuestionFormFactory @inject */
    public $testQuestionFormFactory;

    /** @var ListingTestModel @inject */
    public $listingTestModel;

    /** @var DeleteTestModel @inject */
    public $deleteTestModel;

    /** @var PermissionTestModel @inject */
    public $permissionTestModel;

    public function renderDefault()
    {
        $this->listingTestModel->setCompany($this->company);
        $this->template->testsList = $this->listingTestModel->getList()->getQuery()->getResult();
    }

    public function actionDetail($id)
    {
        /** @var Test $test */
        $test = $this->testModel->findOneBy([ 'token' => $id ]);

        if (!$test instanceof Test) {
            throw new Http404NotFoundException;
        }

        try {
            $this->permissionTestModel->setTest($test);
            $this->permissionTestModel->setUser($this->userEntity);
            $this->permissionTestModel->isAssigned();
        } catch (PermissionException $e) {
            throw new Http401UnauthorizedException;
        }

        /** @var UpdateTestForm $updateTestForm */
        $updateTestForm       = $this->getComponent('updateTestForm');
        $updateTestForm->test = $test;

        /** @var CreateTestQuestionForm $createTestQuestionForm */
        $createTestQuestionForm       = $this->getComponent('createTestQuestionForm');
        $createTestQuestionForm->test = $test;

        $this->template->test = $test;
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

    /**
     * @return UpdateTestForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentUpdateTestForm()
    {
        /** @var UpdateTestForm $control */
        $control             = $this->testFormFactory->updateTest();
        $control->onUpdate[] = function (Test $test) {
            $this->redirect('this');
        };

        return $control;
    }

    /**
     * @return CreateTestQuestionForm
     * @throws \Nette\Application\AbortException
     */
    public function createComponentCreateTestQuestionForm()
    {
        /** @var CreateTestQuestionForm $control */
        $control             = $this->testQuestionFormFactory->createQuestion();
        $control->onCreate[] = function (TestQuestion $testQuestion) {
            $this->redirect('this');
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

            $this->permissionTestModel->setTest($test);
            $this->permissionTestModel->setUser($this->userEntity);
            $this->permissionTestModel->isAssigned();

            $this->deleteTestModel->setTest($test);
            $this->deleteTestModel->do();
        } catch (NotFoundException $e) {
            $this->flashMessage($this->translator->trans('company.delete.errors.not_found'));
        } catch (DeleteException $e) {
            $this->flashMessage($this->translator->trans('company.delete.errors.error'));
        } catch (PermissionException $e) {
            $this->flashMessage($this->translator->trans('company.delete.errors.no_permission'));
        }
        if ($this->isAjax()) {
            $this->redrawControl('testListing');
            $this->redrawControl('flashMessages');
        } else {
            $this->redirect('this');
        }
    }
}