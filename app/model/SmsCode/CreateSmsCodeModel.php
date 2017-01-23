<?php

namespace DontPanic\SmsCode;

use DontPanic\Entities\SmsCode;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\EntityException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class CreateSmsCodeModel extends DoctrineModel
{

    /** @var SmsCodeGeneratorModel */
    private $smsCodeGeneratorModel;

    /** @var User */
    private $user;

    /** @var Test */
    private $test;

    /** @var SmsCode */
    private $smsCode;

    /** @var array|Event */
    public $onCreate;

    public function __construct(EntityManager $em, SmsCodeGeneratorModel $smsCodeGeneratorModel)
    {
        $this->em                    = $em;
        $this->smsCodeGeneratorModel = $smsCodeGeneratorModel;
        $this->er                    = $this->em->getRepository(SmsCode::class);

        $this->smsCode = new SmsCode();
    }

    /**
     * @throws CreateException
     */
    public function create()
    {
        try {
            $this->prepareData();
            $this->save($this->smsCode);
            $this->onCreate($this->smsCode);
        } catch (EntityException $e) {
            throw new CreateException($e->getMessage());
        }
    }

    /**
     * @throws EntityException
     */
    private function prepareData()
    {
        if (!$this->user instanceof User) {
            throw new EntityException('User entity for SMS code not found');
        }

        $this->smsCode->setUser($this->user);
        $this->smsCode->setTest($this->test);
        $this->smsCode->setCode($this->smsCodeGeneratorModel->getCode());
        $this->smsCode->setToken();
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        $this->smsCodeGeneratorModel->setUser($this->user);
    }

    /**
     * @param Test $test
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
    }
}
