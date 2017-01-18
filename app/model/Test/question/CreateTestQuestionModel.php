<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\Test;
use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\EntityException;
use DontPanic\Exception\System\ValueException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class CreateTestQuestionModel extends DoctrineModel
{

    /** @var string */
    private $question;

    /** @var string */
    private $description;

    /** @var string */
    private $type;

    /** @var Test */
    private $test;

    /** @var TestQuestion */
    private $testQuestion;

    /** @var array|Event */
    public $onCreate;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestQuestion::class);

        $this->testQuestion = new TestQuestion();
    }

    /**
     * @throws CreateException
     */
    public function create()
    {
        try {
            $this->prepareData();
            $this->save($this->testQuestion);
            $this->onCreate($this->testQuestion);
        } catch (ValueException $e) {
            throw new CreateException($e->getMessage());
        } catch (EntityException $e) {
            throw new CreateException($e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            throw new CreateException($e->getMessage());
        }
    }

    /**
     * @throws EntityException
     * @throws ValueException
     */
    private function prepareData()
    {
        if (empty($this->question)) {
            throw new ValueException('Question name is not valid');
        }

        if (!$this->test instanceof Test) {
            throw new EntityException('Test entity for new test question not found');
        }

        if (!in_array($this->type, [ TestQuestionModel::TYPE_CHECKBOXLIST, TestQuestionModel::TYPE_RADIOLIST, TestQuestionModel::TYPE_SORT ], true)) {
            throw new ValueException('Question type is not valid');
        }

        $this->testQuestion->setQuestion($this->question);
        $this->testQuestion->setDescription($this->description);
        $this->testQuestion->setType($this->type);
        $this->testQuestion->setTest($this->test);
        $this->testQuestion->setToken();
    }

    /**
     * @param Test $test
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
    }

    /**
     * @param $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
}
