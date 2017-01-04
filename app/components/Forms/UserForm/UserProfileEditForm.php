<?php
namespace DontPanic\Forms;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\User;
use DontPanic\User\UserFacade;
use DontPanic\User\UserModel;
use DontPanic\User\UserUpdateException;
use Nette\Application\UI;
use Nette\Localization\ITranslator;

class UserProfileEditForm extends UserEditForm
{

    /**
     * UserProfileEditForm constructor.
     *
     * @param UserModel   $userModel
     * @param UserFacade  $userFacade
     * @param ITranslator $translator
     * @param User|null   $userEntity
     */
    public function __construct(UserModel $userModel, UserFacade $userFacade, ITranslator $translator, User $userEntity = null)
    {
        $this->userModel  = $userModel;
        $this->userFacade = $userFacade;

        parent::__construct($userModel, $userFacade, $translator, $userEntity);
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/UserProfileEditForm.latte');
        parent::render();
    }

    public function setup()
    {
        parent::setup();
    }

    protected function processForm(UI\Form $form, array $values)
    {
        try {
            parent::processForm($form, $values);
            $this->userFacade->update($this->userEntity, $values);
            $this->onUpdate();
        } catch (UniqueConstraintViolationException $e) {
            $form->addError($this->translator->translate('user.update_profile.form.errors.user_update_address_failed'));
        }
    }

    protected function getDefaults()
    {
        $defaults = parent::getDefaults();

        $user = $this->userEntity;
        if ($user) {
            $defaults = array_merge($defaults, []);
        }

        return $defaults;
    }
}