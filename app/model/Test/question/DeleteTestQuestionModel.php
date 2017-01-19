<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\BranchOffice;
use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class DeleteTestQuestionModel extends DoctrineModel
{

    /** @var TestQuestion */
    private $testQuestion;

    /** @var array|Event */
    public $onDelete;

    /** @var array|Event */
    public $onError;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestQuestion::class);
    }

    public function do()
    {
        if ($this->testQuestion->isDeleted()) {
            $this->onDelete();
        } else {
            try {
                $this->testQuestion->delete();
                $this->save($this->testQuestion);
                $this->onDelete();
            } catch (UniqueConstraintViolationException $e) {
                throw new DeleteException($e->getMessage());
            }
        }
    }

    /**
     * @param TestQuestion $testQuestion
     */
    public function setTeytQuestion(TestQuestion $testQuestion)
    {
        $this->testQuestion = $testQuestion;
    }
}