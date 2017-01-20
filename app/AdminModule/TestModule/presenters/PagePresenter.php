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
use DontPanic\Forms\TestOptionFormFactory;
use DontPanic\Forms\TestQuestionFormFactory;
use DontPanic\Forms\UpdateTestForm;
use DontPanic\Forms\UpdateTestOptionForm;
use DontPanic\Forms\UpdateTestQuestionForm;
use DontPanic\Test\DeleteTestModel;
use DontPanic\Test\ListingTestModel;
use DontPanic\Test\PermissionTestModel;
use DontPanic\Test\TestModel;
use DontPanic\Test\TestQuestionModel;
use Nette\Application\UI\Multiplier;

class PagePresenter extends BasePresenter
{

    /** @var TestModel @inject */
    public $testModel;

    /** @var TestQuestionModel @inject */
    public $testQuestionModel;

    /** @var TestFormFactory @inject */
    public $testFormFactory;

    /** @var TestQuestionFormFactory @inject */
    public $testQuestionFormFactory;

    /** @var TestOptionFormFactory @inject */
    public $testOptionFormFactory;

    /** @var ListingTestModel @inject */
    public $listingTestModel;

    /** @var DeleteTestModel @inject */
    public $deleteTestModel;

    /** @var PermissionTestModel @inject */
    public $permissionTestModel;

    /** @var Test */
    private $test;

    public function startup()
    {
        parent::startup();
        $this->test = $this->testModel->findOneBy([ 'token' => $this->getParameter('id') ]);
    }

    public function renderDefault()
    {
        $this->listingTestModel->setCompany($this->company);
        $this->template->testsList = $this->listingTestModel->getList()->getQuery()->getResult();
    }

    public function renderDetail($id)
    {
        /** @var Test $test */
        $this->test = $this->testModel->findOneBy([ 'token' => $id ]);

        if (!$this->test instanceof Test) {
            throw new Http404NotFoundException;
        }

        try {
            $this->permissionTestModel->setTest($this->test);
            $this->permissionTestModel->setUser($this->userEntity);
            $this->permissionTestModel->isAssigned();
        } catch (PermissionException $e) {
            throw new Http401UnauthorizedException;
        }

        $this->template->test = $this->test;
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
        $control->test       = $this->test;
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
        $control->test       = $this->test;
        $control->onCreate[] = function (TestQuestion $testQuestion) {
            $this->redirect('this');
        };

        return $control;
    }

    /**
     * @return Multiplier
     * @throws \Nette\InvalidStateException
     */
    public function createComponentUpdateTestQuestionForm()
    {
        $control = new Multiplier(function ($name) {
            /** @var TestQuestion $testQuestion */
            $testQuestion = $this->testQuestionModel->find($name);
            /** @var UpdateTestQuestionForm $component */
            $component               = $this->testQuestionFormFactory->updateQuestion();
            $component->testQuestion = $testQuestion;
            $component->user         = $this->userEntity;

            $component->addComponent($this->testOptionFormFactory->updateOptions(), 'updateTestOptionForm');

            /** @var UpdateTestOptionForm $updateTestOptionForm */
            $updateTestOptionForm               = $component->getComponent('updateTestOptionForm');
            $updateTestOptionForm->testQuestion = $testQuestion;

            return $component;
        });

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
            $this->flashMessage($this->translator->trans('test.delete.errors.not_found'));
        } catch (DeleteException $e) {
            $this->flashMessage($this->translator->trans('test.delete.errors.error'));
        } catch (PermissionException $e) {
            $this->flashMessage($this->translator->trans('test.delete.errors.no_permission'));
        }
        if ($this->isAjax()) {
            $this->redrawControl('testListing');
            $this->redrawControl('flashMessages');
        } else {
            $this->redirect('this');
        }
    }
}