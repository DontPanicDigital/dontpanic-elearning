<?php

namespace DontPanic\Acl;

use DontPanic\Entities\Acl;
use DontPanic\Entities\AclPrivilege;
use DontPanic\Entities\AclResource;
use DontPanic\Entities\AclRole;
use Nette\Security\Permission;

class PermissionModel extends Permission
{

    public function __construct(AclModel $aclModel, AclRoleModel $aclRoleModel, AclResourceModel $aclResourceModel)
    {
        $roles     = $aclRoleModel->getRoles();
        $resources = $aclResourceModel->getResources();

        foreach ($roles as $role) {
            $this->addRole($role['key_name'], $role['parent_key']);
        }

        foreach ($resources as $resource) {
            $this->addResource($resource['key_name'], $resource['parent_key']);
        }

        $rules = $aclModel->getRules();

        /** @var Acl $rule */
        foreach ($rules as $rule) {
            /** @var AclRole $role */
            $role = $rule->getRole();
            /** @var AclResource $resource */
            $resource = $rule->getResource();
            /** @var AclPrivilege $privilege */
            $privilege = $rule->getPrivilege();
            $this->{$rule->getAccess() ? 'allow' : 'deny'}(
                $role ? $role->getKeyName() : null,
                $resource ? $resource->getKeyName() : null,
                $privilege ? $privilege->getKeyName() : null
            );
        }
    }

}
