<?php

namespace DontPanic\Test;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\TestOption;
use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\UpdateException;
use DontPanic\Exception\System\ValueException;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;

class UpdateTestOptionModel extends DoctrineModel
{

    /** @var TestOptionModel */
    private $testOptionModel;

    /** @var array */
    private $buffer = [];

    /** @var TestQuestion */
    public $testQuestion;

    /** @var array|Event */
    public $onUpdate;

    public function __construct(EntityManager $em, TestOptionModel $testOptionModel)
    {
        $this->em              = $em;
        $this->testOptionModel = $testOptionModel;
        $this->er              = $this->em->getRepository(TestOption::class);
    }

    /**
     * @throws UpdateException
     */
    public function update()
    {
        try {
            $this->prepareEntity();
            $this->flush();
            $this->onUpdate($this->testQuestion);
        } catch (UniqueConstraintViolationException $e) {
            throw new UpdateException($e->getMessage());
        } catch (ValueException $e) {
            throw new UpdateException($e->getMessage());
        }
    }

    private function prepareEntity()
    {
        if (count($this->buffer)) {
            foreach ($this->buffer as $item) {
                /** @var TestOption $testOption */
                $testOption = $this->testOptionModel->findOneBy([ 'token' => $item ]);
                if ($testOption instanceof TestOption) {
                    $testOption->setOption($item['option']);
                    $testOption->setDescription($item['description']);
                    $testOption->setAnnotation($item['annotation']);

                    if (in_array($this->testQuestion->getType(), [ TestQuestionModel::TYPE_RADIOLIST, TestQuestionModel::TYPE_CHECKBOXLIST ], true)) {
                        $testOption->setCorrect(isset($item['correct']) ? 1 : 0);
                    }

                    if ($this->testQuestion->getType() === TestQuestionModel::TYPE_SORT) {
                        if (isset($item['sort'])) {
                            $testOption->setSort($item['sort']);
                        }
                    }

                    $this->persist($testOption);
                }
            }
        } else {
            throw new ValueException('There are no data for update');
        }
    }

    private function prepareBuffer(array $data)
    {
        foreach ($data as $key => $value) {
            $splitKey = explode('_', $key);
            if (count($splitKey) === 2) {
                list($token, $keyName) = $splitKey;
                if (!array_key_exists($token, $this->buffer)) {
                    $this->buffer[$token] = [
                        'id' => $token,
                    ];
                }
                $this->buffer[$token][$keyName] = $value;
            }
        }

        $this->distributeCorrect($data);
    }

    private function distributeCorrect(array $data)
    {
        if (array_key_exists('correct', $data)) {
            if ($this->testQuestion->getType() === TestQuestionModel::TYPE_RADIOLIST) {
                if (array_key_exists($data['correct'], $this->buffer)) {
                    $this->buffer[$data['correct']]['correct'] = 1;
                }
            }
            if ($this->testQuestion->getType() === TestQuestionModel::TYPE_CHECKBOXLIST) {
                foreach ((array) $data['correct'] as $correct) {
                    if (array_key_exists($correct, $this->buffer)) {
                        $this->buffer[$correct]['correct'] = 1;
                    }
                }
            }
        }
    }

    public function prepareFromArray($data)
    {
        $this->prepareBuffer($data);
    }

    /**
     * @param TestQuestion $testQuestion
     *
     * @return UpdateTestOptionModel
     */
    public function setTestQuestion(TestQuestion $testQuestion): UpdateTestOptionModel
    {
        $this->testQuestion = $testQuestion;

        return $this;
    }
}
