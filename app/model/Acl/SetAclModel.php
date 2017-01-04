<?php

namespace DontPanic\Acl;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Security\User;
use DontPanic\Model\DoctrineModel;
use DontPanic\ParametersProvider;

class SetAclModel extends DoctrineModel
{

    /** @var AclModel */
    public $aclModel;

    /** @var AclRoleModel */
    public $aclRoleModel;

    /** @var AclResourceModel */
    public $aclResourceModel;

    /** @var ParametersProvider */
    public $parametersProvider;

    /** @var User */
    public $user;

    /** @var Cache */
    private $cache;

    /** @var array */
    private $config;

    /**
     * SetAclModel constructor.
     *
     * @param AclModel           $aclModel
     * @param AclRoleModel       $aclRoleModel
     * @param AclResourceModel   $aclResourceModel
     * @param ParametersProvider $parametersProvider
     * @param User               $user
     */
    public function __construct(
        AclModel $aclModel,
        AclRoleModel $aclRoleModel,
        AclResourceModel $aclResourceModel,
        ParametersProvider $parametersProvider,
        User $user
    )
    {
        $this->aclModel           = $aclModel;
        $this->aclRoleModel       = $aclRoleModel;
        $this->aclResourceModel   = $aclResourceModel;
        $this->parametersProvider = $parametersProvider;
        $this->user               = $user;

        $this->config = $this->parametersProvider->getAcl();
        $this->cache  = new Cache(new FileStorage(CACHE_DIR), $this->config['namespace']);
    }

    public function implement()
    {
        $acl = $this->cache->load($this->config['key']);
        $this->user->setAuthorizator($acl ?? $this->generate());
    }

    public function generate()
    {
        $acl = new PermissionModel($this->aclModel, $this->aclRoleModel, $this->aclResourceModel);
        $this->cache->save($this->config['key'], $acl);

        return $acl;
    }
}
