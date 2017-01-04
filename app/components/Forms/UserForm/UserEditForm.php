<?php
namespace DontPanic\Forms;

use DontPanic\User\UserFacade;
use DontPanic\User\UserUpdateException;
use zvitek\Component\Form\FormComponent;
use DontPanic\User\UserModel;
use DontPanic\Entities\User;
use Nette\Localization\ITranslator;
use Nette\Application\UI;
use zvitek\Validator;

class UserEditForm extends FormComponent
{

    /** @var UserModel */
    protected $userModel;

    /** @var UserFacade */
    protected $userFacade;

    /** @var User */
    public $userEntity;

    public $onUpdate;

    /**
     * UserEditForm constructor.
     *
     * @param UserModel   $userModel
     * @param UserFacade  $userFacade
     * @param ITranslator $translator
     * @param User|null   $userEntity
     */
    public function __construct(UserModel $userModel, UserFacade $userFacade, ITranslator $translator, User $userEntity = null)
    {
        $this->userModel  = $userModel;
        $this->userEntity = $userEntity;
        $this->userFacade = $userFacade;

        parent::__construct($translator);
    }

    public function render()
    {
        $this->template->render();
    }

    public function setup()
    {
        $form = $this->form;

        $form->addText('name', 'user.update_profile.form.name')
             ->setRequired('user.update_profile.form.errors.fill_full_name')
             ->addRule(UI\Form::PATTERN, 'user.update_profile.form.errors.fill_full_name', Validator\Form::INPUT_NAME_AND_SURNAME_PATTERN)
             ->addRule(UI\Form::PATTERN, 'user.update_profile.form.errors.full_name_pattern', Validator\Form::INPUT_DIACRITICS_CHARTS_PATTERN);

        $form->addText('email', 'user.update_profile.form.email')
             ->setRequired('user.update_profile.form.errors.fill_email')
             ->addRule(UI\Form::EMAIL, 'user.update_profile.form.errors.fill_valid_email');

        $form->addText('phone', 'user.update_profile.form.phone')
             ->setRequired('user.update_profile.form.errors.fill_phone')
             ->addRule(UI\Form::PATTERN, 'user.update_profile.form.errors.fill_valid_phone', Validator\Form::INPUT_PHONE_PATTERN)
             ->setAttribute('placeholder', 'user.update_profile.form.placeholder.phone');

        $form->addSubmit('save', 'user.update_profile.form.do_save');

        $form->onSuccess[] = function (UI\Form $form, array $values) {
            $this->processForm($form, $values);
        };

        $form->setDefaults($this->getDefaults());

        return $form;
    }

    /**
     * @param UI\Form $form
     * @param array   $values
     */
    protected function processForm(UI\Form $form, array $values)
    {
        try {
            $this->userFacade->update($this->userEntity, $values);
            $this->onUpdate();
        } catch (UserUpdateException $e) {
            $form->addError($this->translator->translate('user.update_profile.form.errors.user_update_failed'));
        }
    }

    protected function getDefaults()
    {
        $defaults = [];
        $user     = $this->userEntity;

        if ($user) {
            $defaults = array_merge($defaults, [
                'name'  => $user->getFullName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(false),
            ]);
        }

        return $defaults;
    }
}
