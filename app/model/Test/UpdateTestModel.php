<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\Test;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\UpdateException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class UpdateTestModel extends DoctrineModel
{

    /** @var Test */
    private $test;

    /** @var array|Event */
    public $onUpdate;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(Test::class);
    }

    /**
     * @throws CreateException
     */
    public function update()
    {
        try {
            $this->save($this->test);
            $this->onUpdate($this->test);
        } catch (UniqueConstraintViolationException $e) {
            throw new UpdateException($e->getMessage());
        }
    }

    public function prepareFromArray($data)
    {
        if (array_key_exists('name', $data)) {
            $this->test->setName($data['name']);
        }

        if (array_key_exists('description', $data)) {
            $this->test->setDescription($data['description']);
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
