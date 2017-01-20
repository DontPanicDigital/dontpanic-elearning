<?php
namespace DontPanic\Forms;

use DontPanic\Test\CreateTestModel;
use DontPanic\Test\TestModel;
use DontPanic\Test\UpdateTestModel;
use DontPanic\User\UserTestAnswerModel;
use DontPanic\User\UserTestScoreModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Translation\ITranslator;

class TestFormFactory
{

    /** @var TestModel */
    protected $testModel;

    /** @var CreateTestModel */
    protected $createTestModel;

    /** @var UpdateTestModel */
    protected $updateTestModel;

    /** @var UserTestAnswerModel */
    protected $userTestAnswerModel;

    /** @var UserTestScoreModel */
    protected $userTestScoreModel;

    /** @var ITranslator */
    protected $translator;

    /** @var EntityManager */
    protected $em;

    /**
     * TestFormFactory constructor.
     *
     * @param TestModel           $testModel
     * @param CreateTestModel     $createTestModel
     * @param UpdateTestModel     $updateTestModel
     * @param UserTestAnswerModel $userTestAnswerModel
     * @param UserTestScoreModel  $userTestScoreModel
     * @param ITranslator         $translator
     * @param EntityManager       $em
     */
    public function __construct(
        TestModel $testModel,
        CreateTestModel $createTestModel,
        UpdateTestModel $updateTestModel,
        UserTestAnswerModel $userTestAnswerModel,
        UserTestScoreModel $userTestScoreModel,
        ITranslator $translator,
        EntityManager $em
    )
    {
        $this->testModel           = $testModel;
        $this->createTestModel     = $createTestModel;
        $this->updateTestModel     = $updateTestModel;
        $this->userTestAnswerModel = $userTestAnswerModel;
        $this->userTestScoreModel  = $userTestScoreModel;
        $this->translator          = $translator;
        $this->em                  = $em;
    }

    /**
     * @return CreateTestForm
     */
    public function createTest()
    {
        return new CreateTestForm($this->createTestModel, $this->translator);
    }

    /**
     * @return UpdateTestForm
     */
    public function updateTest()
    {
        return new UpdateTestForm($this->updateTestModel, $this->translator);
    }

    /**
     * @return DisplayTestForm
     */
    public function displayTest()
    {
        return new DisplayTestForm($this->userTestAnswerModel, $this->userTestScoreModel, $this->translator);
    }
}