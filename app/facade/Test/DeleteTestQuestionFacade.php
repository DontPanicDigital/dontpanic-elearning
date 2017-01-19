<?php

namespace DontPanic\Test;

use DontPanic\Entities\User;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Exception\System\NotFoundException;
use DontPanic\Exception\System\PermissionException;

class DeleteTestQuestionFacade extends \Nette\Object
{

    /** @var TestQuestionModel */
    private $testQuestionModel;

    /** @var DeleteTestQuestionModel */
    private $deleteTestQuestionModel;

    /** @var PermissionTestQuestionModel */
    private $permissionTestQuestionModel;

    /** @var User */
    private $user;

    public function __construct(
        TestQuestionModel $testQuestionModel,
        DeleteTestQuestionModel $deleteTestQuestionModel,
        PermissionTestQuestionModel $permissionTestQuestionModel
    )
    {
        $this->testQuestionModel           = $testQuestionModel;
        $this->deleteTestQuestionModel     = $deleteTestQuestionModel;
        $this->permissionTestQuestionModel = $permissionTestQuestionModel;
    }

    public function remove($testQuestionToken)
    {
        try {
            /** @var Test $testQuestion */
            $testQuestion = $this->testQuestionModel->findOneBy([ 'token' => $testQuestionToken ]);
            if (!$testQuestion) {
                throw new NotFoundException('Test question for delete');
            }

            $this->permissionTestQuestionModel->setTestQuestion($testQuestion);
            $this->permissionTestQuestionModel->setUser($this->user);
            $this->permissionTestQuestionModel->isAssigned();

            $this->deleteTestQuestionModel->setTeytQuestion($testQuestion);
            $this->deleteTestQuestionModel->do();
        } catch (NotFoundException $e) {
            throw new DeleteException($e->getMessage());
        } catch (DeleteException $e) {
            throw new DeleteException($e->getMessage());
        } catch (PermissionException $e) {
            throw new DeleteException($e->getMessage());
        }
    }

    /**
     * @param User $user
     *
     * @return DeleteTestQuestionFacade
     */
    public function setUser(User $user): DeleteTestQuestionFacade
    {
        $this->user = $user;

        return $this;
    }
}
