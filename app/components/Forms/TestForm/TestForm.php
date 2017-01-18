<?php
namespace DontPanic\Forms;

use DontPanic\Test\CreateTestModel;
use DontPanic\Test\TestModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Translation\ITranslator;

class TestFormFactory
{

    /** @var TestModel */
    protected $testModel;

    /** @var CreateTestModel */
    protected $createTestModel;

    /** @var ITranslator */
    protected $translator;

    /** @var EntityManager */
    protected $em;

    /**
     * TestFormFactory constructor.
     *
     * @param TestModel       $testModel
     * @param CreateTestModel $createTestModel
     * @param ITranslator     $translator
     * @param EntityManager   $em
     */
    public function __construct(TestModel $testModel, CreateTestModel $createTestModel, ITranslator $translator, EntityManager $em)
    {
        $this->testModel       = $testModel;
        $this->createTestModel = $createTestModel;
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
}