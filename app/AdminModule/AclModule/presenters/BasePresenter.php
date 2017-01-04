<?php

namespace AdminModule\AclModule;

use DontPanic\Acl\AclModel;
use DontPanic\Acl\AclPrivilegesModel;
use DontPanic\Acl\AclResourceModel;
use DontPanic\Acl\AclRoleModel;

abstract class BasePresenter extends \AdminModule\BasePresenter
{

    /** @var AclModel @inject */
    public $aclModel;

    /** @var AclPrivilegesModel @inject */
    public $aclPrivilegesModel;

    /** @var AclRoleModel @inject */
    public $aclRoleModel;

    /** @var AclResourceModel @inject */
    public $aclResourceModel;
}
