<?php
namespace DontPanic\Forms;

use DontPanic\Acl\AclRoleModel;
use DontPanic\Entities\AclRole;
use DontPanic\User\UserModel;
use Nette\Localization\ITranslator;
use Nette\Application\UI;
use Nette\Forms\Form;

class UserSystemFilterForm extends UI\Control
{

    /** @var UserModel */
    protected $userModel;

    /** @var AclRoleModel */
    protected $aclRoleModel;

    /** @var array */
    public $defaults = [];

    /** @var callable[] */
    public $onFiltered;

    /**
     * UserSystemFilterForm constructor.
     *
     * @param UserModel    $userModel
     * @param AclRoleModel $aclRoleModel
     * @param ITranslator  $translator
     */
    public function __construct(UserModel $userModel, AclRoleModel $aclRoleModel, ITranslator $translator)
    {
        parent::__construct();
        $this->userModel    = $userModel;
        $this->aclRoleModel = $aclRoleModel;
    }

    public function render()
    {
        $this->getComponent('form')->setDefaults($this->getDefaults());
        $this->template->setFile(__DIR__ . '/userSystemFilterForm.latte');
        $this->template->roles = $this->aclRoleModel->rootList();
        $this->template->render();
    }

    /**
     * @return UI\Form
     */
    public function createComponentForm()
    {
        $form  = new UI\Form();
        $roles = [];

        /** @var AclRole $role */
        foreach ($this->aclRoleModel->getList() as $role) {
            $roles[$role->getId()] = $role->getName();
        }

        $form->addText('search', 'search', 'Vyhledat');
        $form->addCheckboxList('roles', 'role', $roles);

        $form->addSubmit('filter', 'fitler');

        $form->onSuccess[] = function (Form $form) {
            $this->filterFormSucceeded($form, $form->getValues(true));
        };

        $this->getDefaults();

        return $form;
    }

    protected function filterFormSucceeded(Form $form, array $values)
    {
        $filter = [
            'roles'  => $values['roles'],
            'search' => $values['search'],
        ];
        $this->onFiltered($filter);
    }

    public function getDefaults()
    {
        if (count($this->defaults)) {
            return [
                'roles'  => $this->defaults['roles'],
                'search' => $this->defaults['search'],
            ];
        }

        return [];
    }
}