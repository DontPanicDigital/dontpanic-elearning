<?php

namespace AdminModule\AclModule;

use DontPanic\Entities\Acl;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI;
use Nette\Database\UniqueConstraintViolationException;

class PermissionPresenter extends BasePresenter
{

    /** @var EntityManager @inject */
    public $em;

    public function actionDefault()
    {
        $this->template->roles      = $this->aclRoleModel->rootList();
        $this->template->resources  = $this->aclResourceModel->rootList();
        $this->template->privileges = $this->aclPrivilegesModel->list();
    }

    /**************************************************************************************************************z*v*/
    /*************** FORM ***************/

    protected function createComponentPermissionForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addSubmit('submit', 'admin.permission.form.do_create');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->permissionFormSucceseed($form, $form->getValues(true));
        };

        return $form;
    }

    public function permissionFormSucceseed(UI\Form $form, array $values)
    {
        $httpData = $form->getHttpData();

        $roles      = (array) $httpData['roles'] ?: [];
        $resources  = (array) $httpData['resources'] ?: [];
        $privileges = (array) $httpData['privileges'] ?: [];
        $access     = (integer) $httpData['access'] ?: 0;

        $this->em->beginTransaction();
        try {
            foreach ($privileges as $privilege) {
                foreach ($resources as $resource) {
                    foreach ($roles as $role) {
                        if ($resource === '0') {
                            $resource = null;
                        }
                        if ($privilege === '0') {
                            $privilege = null;
                        }

                        /** @var Acl $aclEntity */
                        $isEntity = $this->aclModel->checkPermission($role, $privilege, $resource);

                        if ($isEntity) {
                            $this->aclModel->delete($isEntity, false);
                        }

                        $aclEntity = new Acl();
                        $aclEntity->setRole($this->aclRoleModel->find($role));
                        $aclEntity->setPrivilege($this->aclPrivilegesModel->find($privilege));
                        $aclEntity->setResource($this->aclResourceModel->find($resource));
                        $aclEntity->setAccess($access);
                        $this->aclModel->persist($aclEntity);

                        $this->aclModel->flush();
                    }
                }
            }
            $this->em->commit();
            $this->flashMessage($this->translator->trans('admin.permission.form.errors.permission_success'));
        } catch (UniqueConstraintViolationException $e) {
            $this->em->rollback();
            $this->flashMessage($this->translator->trans('admin.permission.form.errors.permission_failed'));
        }
    }
}