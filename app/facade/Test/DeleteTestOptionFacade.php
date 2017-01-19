<?php

namespace DontPanic\Test;

use DontPanic\Entities\TestOption;
use DontPanic\Entities\User;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Exception\System\NotFoundException;
use DontPanic\Exception\System\PermissionException;

class DeleteTestOptionFacade extends \Nette\Object
{

    /** @var TestOptionModel */
    private $testOptionModel;

    /** @var DeleteTestOptionModel */
    private $deleteTestOptionModel;

    /** @var PermissionTestOptionModel */
    private $permissionTestOptionModel;

    /** @var User */
    private $user;

    public function __construct(
        TestOptionModel $testOptionModel,
        DeleteTestOptionModel $deleteTestOptionModel,
        PermissionTestOptionModel $permissionTestOptionModel
    )
    {
        $this->testOptionModel           = $testOptionModel;
        $this->deleteTestOptionModel     = $deleteTestOptionModel;
        $this->permissionTestOptionModel = $permissionTestOptionModel;
    }

    public function remove($testOptionToken)
    {
        try {
            /** @var TestOption $testOption */
            $testOption = $this->testOptionModel->findOneBy([ 'token' => $testOptionToken ]);
            if (!$testOption) {
                throw new NotFoundException('Test option for delete');
            }

            $this->permissionTestOptionModel->setTestOption($testOption);
            $this->permissionTestOptionModel->setUser($this->user);
            $this->permissionTestOptionModel->isAssigned();

            $this->deleteTestOptionModel->setTestOption($testOption);
            $this->deleteTestOptionModel->do();
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
    public function setUser(User $user): DeleteTestOptionFacade
    {
        $this->user = $user;

        return $this;
    }
}
