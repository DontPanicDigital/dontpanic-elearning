<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\BranchOffice;
use DontPanic\Entities\TestOption;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class DeleteTestOptionModel extends DoctrineModel
{

    /** @var TestOption */
    private $testOption;

    /** @var array|Event */
    public $onDelete;

    /** @var array|Event */
    public $onError;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(TestOption::class);
    }

    public function do()
    {
        if ($this->testOption->isDeleted()) {
            $this->onDelete();
        } else {
            try {
                $this->testOption->delete();
                $this->save($this->testOption);
                $this->onDelete();
            } catch (UniqueConstraintViolationException $e) {
                throw new DeleteException($e->getMessage());
            }
        }
    }

    /**
     * @param TestOption $testOption
     */
    public function setTestOption(TestOption $testOption)
    {
        $this->testOption = $testOption;
    }
}