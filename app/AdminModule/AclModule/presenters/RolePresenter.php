<?php

namespace AdminModule\AclModule;

use AppModule\Exception\Http404NotFoundException;
use DontPanic\Acl\CreateAclRoleException;
use DontPanic\Entities\AclRole;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI;

class RolePresenter extends BasePresenter
{

    /** @var EntityManager @inject */
    public $em;

    /** @var id */
    private $id;

    public function renderDefault()
    {
        $this->template->roles = $this->aclRoleModel->rootList();
    }

    public function actionDetail($id)
    {
        $this->id = $id;
        /** @var AclRole $roleEntity */
        $roleEntity = $this->aclRoleModel->find($this->id);

        if (!$roleEntity) {
            throw new Http404NotFoundException;
        }

        $this->getComponent('roleForm')->setDefaults([
            'name'    => $roleEntity->getName(),
            'comment' => $roleEntity->getComment(),
        ]);

        $this->template->roles    = $this->aclRoleModel->rootList();
        $this->template->id       = $this->id;
        $this->template->parentId = $roleEntity->getParent() ? $roleEntity->getParent()->getId() : null;
    }

    /**************************************************************************************************************z*v*/
    /*************** FORM ***************/

    protected function createComponentCreateRoleForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('name', 'admin.role.form.name')
             ->setRequired('admin.role.form.errors.fill_name');

        $form->addSubmit('submit', 'admin.role.form.do_create');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->createFormSucceseed($form, $form->getValues(true));
        };

        return $form;
    }

    public function createFormSucceseed(UI\Form $form, array $values)
    {
        try {
            $roleEntity = $this->aclRoleModel->create($values['name']);
            $form->setValues([], true);
            $this->redirect('detail', [ 'id' => $roleEntity->getId() ]);
        } catch (CreateAclRoleException $e) {
            $this->flashMessage($this->translator->trans('admin.role.form.errors.create_role_failed'));
        }
    }

    protected function createComponentRoleForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('name', 'admin.role.form.name')
             ->setRequired('admin.role.form.errors.fill_name');

        $form->addTextArea('comment', 'admin.role.form.comment');

        $form->addSubmit('submit', 'admin.role.form.do_update');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->formSucceseed($form, $form->getValues(true));
        };

        return $form;
    }

    public function formSucceseed(UI\Form $form, array $values)
    {
        try {
            $httpData = $form->getHttpData();
            $role     = $httpData['role'] ?? null;

            /** @var AclRole $roleEntity */
            $roleEntity = $this->aclRoleModel->find($this->id);
            $roleEntity->setName($values['name']);
            $roleEntity->setComment($values['comment']);
            $roleEntity->setParent($this->aclRoleModel->find($role));

            $this->aclRoleModel->save($roleEntity);
            $this->flashMessage($this->translator->trans('admin.role.form.success.role_update'));
            $this->redirect('this');
        } catch (UniqueConstraintViolationException $e) {
            $this->flashMessage($this->translator->trans('admin.role.form.errors.role_update_failed'));
        }
    }

    /**************************************************************************************************************z*v*/
    /*************** HANDLE ***************/

    /**
     * @param $id
     *
     * @throws \InvalidArgumentException
     * @throws \Nette\Application\AbortException
     */
    public function handleRemove($id)
    {
        try {
            $roleEntity = $this->aclRoleModel->find($id);
            $this->aclRoleModel->delete($roleEntity);
            $this->flashMessage($this->translator->trans('admin.role.role_removed'));
        } catch (UniqueConstraintViolationException $e) {
            $this->flashMessage($this->translator->trans('admin.role.role_remove_failed'));
        }
        $this->redirect('this');
    }
}