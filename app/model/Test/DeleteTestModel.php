<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\BranchOffice;
use DontPanic\Entities\Test;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class DeleteTestModel extends DoctrineModel
{

    /** @var Test */
    private $test;

    /** @var array|Event */
    public $onDelete;

    /** @var array|Event */
    public $onError;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(Test::class);
    }

    public function do()
    {
        if ($this->test->isDeleted()) {
            $this->onDelete();
        } else {
            try {
                $this->test->delete();
                $this->save($this->test);
                $this->onDelete();
            } catch (UniqueConstraintViolationException $e) {
                throw new DeleteException($e->getMessage());
            }
        }
    }

    /**
     * @param Test $test
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
    }
}