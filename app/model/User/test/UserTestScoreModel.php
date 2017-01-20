<?php

namespace DontPanic\User;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Entities\UserTestScore;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\EntityException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class UserTestScoreModel extends DoctrineModel
{

    /** @var User */
    private $user;

    /** @var Test */
    private $test;

    /** @var UserTestScore */
    private $userTestScore;

    /** @var array|Event */
    public $onCreate;

    /**
     * UserTestAnswerModel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(UserTestScore::class);

        $this->userTestScore = new UserTestScore();
    }

    public function do()
    {
        try {
            $this->prepareData();
            $this->save($this->userTestScore);
            $this->onCreate($this->userTestScore);
        } catch (EntityException $e) {
            throw new CreateException($e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            throw new CreateException($e->getMessage());
        }
    }

    private function prepareData()
    {
        if (!$this->user instanceof User) {
            throw new EntityException('User for save test score not found');
        }

        if (!$this->test instanceof Test) {
            throw new EntityException('Test for save test score not found');
        }

        $this->userTestScore->setUser($this->user);
        $this->userTestScore->setTest($this->test);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Test $test
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
    }
}
