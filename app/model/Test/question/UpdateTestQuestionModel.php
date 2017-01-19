<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\EntityException;
use DontPanic\Exception\System\UpdateException;
use DontPanic\Exception\System\ValueException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class UpdateTestQuestionModel extends DoctrineModel
{

    /** @var TestQuestion */
    private $testQuestion;

    /** @var array|Event */
    public $onUpdate;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestQuestion::class);
    }

    /**
     * @throws UpdateException
     */
    public function update()
    {
        try {
            $this->save($this->testQuestion);
            $this->onUpdate($this->testQuestion);
        } catch (UniqueConstraintViolationException $e) {
            throw new UpdateException($e->getMessage());
        }
    }

    /**
     * @throws EntityException
     * @throws ValueException
     */
    public function prepareFromArray($data)
    {
        if (array_key_exists('question', $data)) {
            $this->testQuestion->setQuestion($data['question']);
        }

        if (array_key_exists('description', $data)) {
            $this->testQuestion->setDescription($data['description']);
        }

        if (array_key_exists('sort', $data)) {
            $this->testQuestion->setSort($data['sort']);
        }
    }

    /**
     * @param TestQuestion $testQuestion
     */
    public function setTestQuestion(TestQuestion $testQuestion)
    {
        $this->testQuestion = $testQuestion;
    }
}
