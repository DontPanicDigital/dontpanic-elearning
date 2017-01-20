<?php

namespace DontPanic\User;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\TestOption;
use DontPanic\Entities\User;
use DontPanic\Entities\UserTestAnswer;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\ValueException;
use DontPanic\Model\DoctrineModel;
use DontPanic\Test\TestOptionModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class UserTestAnswerModel extends DoctrineModel
{

    /** @var TestOptionModel */
    private $testOptionModel;

    /** @var User */
    private $user;

    /** @var array|Event */
    public $onCreate;

    /**
     * UserTestAnswerModel constructor.
     *
     * @param TestOptionModel $testOptionModel
     * @param EntityManager   $em
     */
    public function __construct(TestOptionModel $testOptionModel, EntityManager $em)
    {
        $this->em              = $em;
        $this->testOptionModel = $testOptionModel;
        $this->er              = $this->em->getRepository(UserTestAnswer::class);
    }

    public function do()
    {
        try {
            $this->flush();
            $this->onCreate();
        } catch (ValueException $e) {
            throw new CreateException($e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            throw new CreateException($e->getMessage());
        }
    }

    public function prepereFromArray(array $data)
    {
        $this->distributeAnswers($data);
    }

    private function distributeAnswers(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->prepareCheckboxlist($value);
            } else {
                if (strpos($key, '_sort') === false) {
                    $this->prepareRadiolist($value);
                } else {
                    $this->prepareSort($key, $value);
                }
            }
        }
    }

    private function prepareRadiolist($optionToken)
    {
        $testOption = $this->findOption($optionToken);
        /** @var UserTestAnswer $userTestAnswer */
        $userTestAnswer = new UserTestAnswer();
        $userTestAnswer->setUser($this->user);
        $userTestAnswer->setOption($testOption);
        $userTestAnswer->setCorrect(1);
        $this->persist($userTestAnswer);
    }

    private function prepareSort($optionKey, $value)
    {
        $optionKeySplit = explode('_', $optionKey);
        $optionToken    = $optionKeySplit[0];

        if (strlen($optionToken) !== 30) {
            throw new ValueException('Option sort token not valid');
        }

        /** @var TestOption $testOption */
        $testOption = $this->findOption($optionToken);

        /** @var UserTestAnswer $userTestAnswer */
        $userTestAnswer = new UserTestAnswer();
        $userTestAnswer->setUser($this->user);
        $userTestAnswer->setOption($testOption);
        $userTestAnswer->setCorrect($value);
        $this->persist($userTestAnswer);
    }

    private function prepareCheckboxlist(array $answers)
    {
        /** @var $userTestAnswerTemp $userTestAnswer */
        $userTestAnswerTemp = new UserTestAnswer();

        foreach ($answers as $answer) {
            /** @var TestOption $testOption */
            $testOption = $this->findOption($answer);

            $userTestAnswer = clone $userTestAnswerTemp;
            $userTestAnswer->setUser($this->user);
            $userTestAnswer->setOption($testOption);
            $userTestAnswer->setCorrect(1);
            $this->persist($userTestAnswer);
        }
    }

    /**
     * @param $optionToken
     *
     * @return TestOption
     * @throws ValueException
     */
    private function findOption($optionToken)
    {
        /** @var TestOption $testOption */
        $testOption = $this->testOptionModel->findOneBy([ 'token' => $optionToken ]);

        if (!$testOption instanceof TestOption) {
            throw new ValueException("Can not found option for save answers ({$optionToken})");
        }

        return $testOption;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
