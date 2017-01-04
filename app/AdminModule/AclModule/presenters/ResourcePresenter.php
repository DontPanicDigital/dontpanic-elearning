<?php

namespace AdminModule\AclModule;

use AppModule\Exception\Http404NotFoundException;
use DontPanic\Acl\CreateAclResourceException;
use DontPanic\Entities\AclResource;
use DontPanic\Entities\AclRole;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI;

class ResourcePresenter extends BasePresenter
{

    /** @var EntityManager @inject */
    public $em;

    /** @var id */
    private $id;

    public function renderDefault()
    {
        $this->template->resources = $this->aclResourceModel->rootList();
    }

    public function actionDetail($id)
    {
        $this->id = $id;
        /** @var AclResource $resourceEntity */
        $resourceEntity = $this->aclResourceModel->find($this->id);

        if (!$resourceEntity) {
            throw new Http404NotFoundException;
        }

        $this->getComponent('resourceForm')->setDefaults([
            'name'    => $resourceEntity->getName(),
            'comment' => $resourceEntity->getComment(),
        ]);

        $this->template->resources = $this->aclResourceModel->rootList();
        $this->template->id        = $this->id;
        $this->template->parentId  = $resourceEntity->getParent() ? $resourceEntity->getParent()->getId() : null;
    }

    /**************************************************************************************************************z*v*/
    /*************** FORM ***************/

    protected function createComponentCreateResourceForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('name', 'admin.resource.form.name')
             ->setRequired('admin.resource.form.errors.fill_name');

        $form->addSubmit('submit', 'admin.resource.form.do_create');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->createFormSucceseed($form, $form->getValues(true));
        };

        return $form;
    }

    public function createFormSucceseed(UI\Form $form, array $values)
    {
        try {
            $resourceEntity = $this->aclResourceModel->create($values['name']);
            $form->setValues([], true);
            $this->redirect('detail', [ 'id' => $resourceEntity->getId() ]);
        } catch (CreateAclResourceException $e) {
            $this->flashMessage($this->translator->trans('admin.resource.form.errors.create_resource_failed'));
        }
    }

    protected function createComponentResourceForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('name', 'admin.resource.form.name')
             ->setRequired('admin.resource.form.errors.fill_name');

        $form->addTextArea('comment', 'admin.resource.form.comment');

        $form->addSubmit('submit', 'admin.resource.form.do_update');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->formSucceseed($form, $form->getValues(true));
        };

        return $form;
    }

    public function formSucceseed(UI\Form $form, array $values)
    {
        try {
            $httpData = $form->getHttpData();
            $resource = $httpData['resource'] ?? null;

            /** @var AclRole $resourceEntity */
            $resourceEntity = $this->aclResourceModel->find($this->id);
            $resourceEntity->setName($values['name']);
            $resourceEntity->setComment($values['comment']);
            $resourceEntity->setParent($this->aclResourceModel->find($resource));

            $this->aclResourceModel->save($resourceEntity);
            $this->flashMessage($this->translator->trans('admin.resource.form.success.resource_update'));
            $this->redirect('this');
        } catch (UniqueConstraintViolationException $e) {
            $this->flashMessage($this->translator->trans('admin.resource.form.errors.resource_update_failed'));
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
            $resourceEntity = $this->aclResourceModel->find($id);
            $this->aclResourceModel->delete($resourceEntity);
            $this->flashMessage($this->translator->trans('admin.resource.resource_removed'));
        } catch (UniqueConstraintViolationException $e) {
            $this->flashMessage($this->translator->trans('admin.resource.resource_remove_failed'));
        }
        $this->redirect('this');
    }
}