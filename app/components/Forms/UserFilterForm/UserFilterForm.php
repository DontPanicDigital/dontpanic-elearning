<?php
namespace DontPanic\Forms;

use DontPanic\Acl\AclRoleModel;
use Kdyby\Translation\ITranslator;
use DontPanic\User\UserModel;
use Kdyby\Doctrine\EntityManager;

class UserFilterFormFactory
{

    /** @var UserModel */
    protected $userModel;

    /** @var AclRoleModel */
    protected $aclRoleModel;

    /** @var ITranslator */
    protected $translator;

    /** @var EntityManager */
    protected $em;

    /**
     * UserFilterFormFactory constructor.
     *
     * @param UserModel     $userModel
     * @param AclRoleModel  $aclRoleModel
     * @param ITranslator   $translator
     * @param EntityManager $em
     */
    public function __construct(UserModel $userModel, AclRoleModel $aclRoleModel, ITranslator $translator, EntityManager $em)
    {
        $this->userModel    = $userModel;
        $this->aclRoleModel = $aclRoleModel;
        $this->translator   = $translator;
        $this->em           = $em;
    }

    /**
     * @return UserSystemFilterForm
     */
    public function createUserSystemFilterForm()
    {
        return new UserSystemFilterForm($this->userModel, $this->aclRoleModel, $this->translator);
    }
}