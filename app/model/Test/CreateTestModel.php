<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\Company;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\EntityException;
use DontPanic\Exception\System\ValueException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class CreateTestModel extends DoctrineModel
{

    /** @var string */
    private $name;

    /** @var Company */
    private $company;

    /** @var User */
    private $user;

    /** @var Test */
    private $test;

    /** @var array|Event */
    public $onCreate;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->er = $this->em->getRepository(Test::class);

        $this->test = new Test();
    }

    /**
     * @throws CreateException
     */
    public function create()
    {
        try {
            $this->prepareData();
            $this->save($this->test);
            $this->onCreate($this->test);
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
        if (empty($this->name)) {
            throw new ValueException('Test name is not valid');
        }

        if (!$this->company instanceof Company) {
            throw new EntityException('Company entity for new test not found');
        }

        if (!$this->user instanceof User) {
            throw new EntityException('User entity for new test not found');
        }

        $this->test->setName($this->name);
        $this->test->setCompany($this->company);
        $this->test->setUser($this->user);
        $this->test->setToken();
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
