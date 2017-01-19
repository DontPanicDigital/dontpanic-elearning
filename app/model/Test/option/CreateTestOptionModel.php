<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\TestOption;
use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\EntityException;
use DontPanic\Exception\System\ValueException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class CreateTestOptionModel extends DoctrineModel
{

    /** @var TestQuestion */
    private $testQuestion;

    /** @var TestOption */
    private $testOption;

    /** @var array|Event */
    public $onCreate;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestOption::class);

        $this->testOption = new TestOption();
    }

    /**
     * @throws CreateException
     */
    public function create()
    {
        try {
            $this->prepareData();
            $this->save($this->testOption);
            $this->onCreate($this->testOption);
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
        if (!$this->testQuestion instanceof TestQuestion) {
            throw new EntityException('Test question entity for new test option not found');
        }

        $this->testOption->setQuestion($this->testQuestion);
        $this->testOption->setToken();
    }

    /**
     * @param TestQuestion $testQuestion
     */
    public function setTestQuestion(TestQuestion $testQuestion)
    {
        $this->testQuestion = $testQuestion;
    }
}
