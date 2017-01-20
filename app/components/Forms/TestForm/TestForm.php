<?php
namespace DontPanic\Forms;

use DontPanic\Test\CreateTestModel;
use DontPanic\Test\TestModel;
use DontPanic\Test\UpdateTestModel;
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

    /** @var ITranslator */
    protected $translator;

    /** @var EntityManager */
    protected $em;

    /**
     * TestFormFactory constructor.
     *
     * @param TestModel       $testModel
     * @param CreateTestModel $createTestModel
     * @param UpdateTestModel $updateTestModel
     * @param ITranslator     $translator
     * @param EntityManager   $em
     */
    public function __construct(
        TestModel $testModel,
        CreateTestModel $createTestModel,
        UpdateTestModel $updateTestModel,
        ITranslator $translator,
        EntityManager $em
    )
    {
        $this->testModel       = $testModel;
        $this->createTestModel = $createTestModel;
        $this->updateTestModel = $updateTestModel;
        $this->translator      = $translator;
        $this->em              = $em;
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
        return new DisplayTestForm($this->translator);
    }
}