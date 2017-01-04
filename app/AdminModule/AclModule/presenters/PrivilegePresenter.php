<?php

namespace AdminModule\AclModule;

use AppModule\Exception\Http404NotFoundException;
use DontPanic\Acl\CreateAclPrivilegeException;
use DontPanic\Entities\AclPrivilege;
use DontPanic\Entities\AclRole;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI;

class PrivilegePresenter extends BasePresenter
{

    /** @var EntityManager @inject */
    public $em;

    /** @var id */
    private $id;

    public function renderDefault()
    {
        $this->template->privileges = $this->aclPrivilegesModel->list();
    }

    public function actionDetail($id)
    {
        $this->id = $id;
        /** @var AclPrivilege $privilegeEntity */
        $privilegeEntity = $this->aclPrivilegesModel->find($this->id);

        if (!$privilegeEntity) {
            throw new Http404NotFoundException;
        }

        $this->getComponent('privilegeForm')->setDefaults([
            'name'    => $privilegeEntity->getName(),
            'comment' => $privilegeEntity->getComment(),
        ]);

        $this->template->resources  = $this->aclResourceModel->rootList();
        $this->template->id         = $this->id;
        $this->template->resourceId = $privilegeEntity->getResource() ? $privilegeEntity->getResource()->getId() : null;
    }

    /**************************************************************************************************************z*v*/
    /*************** FORM ***************/

    protected function createComponentCreatePrivilegeForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('name', 'admin.privilege.form.name')
             ->setRequired('admin.privilege.form.errors.fill_name');

        $form->addSubmit('submit', 'admin.privilege.form.do_create');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->createFormSucceseed($form, $form->getValues(true));
        };

        return $form;
    }

    public function createFormSucceseed(UI\Form $form, array $values)
    {
        try {
            $privilegeEntity = $this->aclPrivilegesModel->create($values['name']);
            $form->setValues([], true);
            $this->redirect('detail', [ 'id' => $privilegeEntity->getId() ]);
        } catch (CreateAclPrivilegeException $e) {
            $this->flashMessage($this->translator->trans('admin.privilege.form.errors.create_privilege_failed'));
        }
    }

    protected function createComponentPrivilegeForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('name', 'admin.privilege.form.name')
             ->setRequired('admin.privilege.form.errors.fill_name');

        $form->addTextArea('comment', 'admin.privilege.form.comment');

        $form->addSubmit('submit', 'admin.privilege.form.do_update');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->formSucceseed($form, $form->getValues(true));
        };

        return $form;
    }

    public function formSucceseed(UI\Form $form, array $values)
    {
        try {
            $httpData = $form->getHttpData();
            $resource = $httpData['resources'] ?? null;

            /** @var AclPrivilege $privilegeEntity */
            $privilegeEntity = $this->aclPrivilegesModel->find($this->id);
            $privilegeEntity->setName($values['name']);
            $privilegeEntity->setComment($values['comment']);
            $privilegeEntity->setResource($this->aclResourceModel->find($resource));

            $this->aclPrivilegesModel->save($privilegeEntity);
            $this->flashMessage($this->translator->trans('admin.privilege.form.success.privilege_update'));
            $this->redirect('this');
        } catch (UniqueConstraintViolationException $e) {
            $this->flashMessage($this->translator->trans('admin.privilege.form.errors.privilege_update_failed'));
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
            $privilegeEntity = $this->aclPrivilegesModel->find($id);
            $this->aclPrivilegesModel->delete($privilegeEntity);
            $this->flashMessage($this->translator->trans('admin.privilege.privilege_removed'));
        } catch (UniqueConstraintViolationException $e) {
            $this->flashMessage($this->translator->trans('admin.privilege.privilege_remove_failed'));
        }
        $this->redirect('this');
    }
}