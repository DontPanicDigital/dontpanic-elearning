<?php
namespace DontPanic\Forms;

use DontPanic\Test\CreateTestQuestionModel;
use DontPanic\Test\UpdateTestModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Translation\ITranslator;

class TestQuestionFormFactory
{

    /** @var CreateTestQuestionModel */
    protected $createTestQuestionModel;

    /** @var ITranslator */
    protected $translator;

    /** @var EntityManager */
    protected $em;

    /**
     * TestQuestionFormFactory constructor.
     *
     * @param CreateTestQuestionModel $createTestQuestionModel
     * @param UpdateTestModel         $updateTestModel
     * @param ITranslator             $translator
     * @param EntityManager           $em
     */
    public function __construct(
        CreateTestQuestionModel $createTestQuestionModel,
        UpdateTestModel $updateTestModel,
        ITranslator $translator,
        EntityManager $em
    )
    {
        $this->createTestQuestionModel = $createTestQuestionModel;
        $this->updateTestModel         = $updateTestModel;
        $this->translator              = $translator;
        $this->em                      = $em;
    }

    public function createQuestion()
    {
        return new CreateTestQuestionForm($this->createTestQuestionModel, $this->translator);
    }

}