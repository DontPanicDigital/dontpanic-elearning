<?php
namespace DontPanic\Forms;

use Nette\Localization\ITranslator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\User;
use DontPanic\User\UserPasswordModel;
use Nette\Forms\Form;
use zvitek\Component\Form\FormComponent;

class PasswordUpdateForm extends FormComponent
{

    /** @var UserPasswordModel */
    protected $userPasswordModel;

    public $onChange;

    /** @var User */
    public $userEntity = null;

    /**
     * PasswordUpdateForm constructor.
     *
     * @param UserPasswordModel $userPasswordModel
     * @param ITranslator       $translator
     */
    public function __construct(UserPasswordModel $userPasswordModel, ITranslator $translator)
    {
        parent::__construct($translator);
        $this->userPasswordModel = $userPasswordModel;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/passwordUpdate.latte');
        $this->template->render();
    }

    public function setup()
    {
        $form = $this->form;

        $form->addPassword('password', 'user.password_update.form.password')
             ->setRequired('user.password_update.form.errors.choose_password')
             ->addRule(Form::MIN_LENGTH, 'user.password_update.form.errors.password_min', 3);

        $form->addPassword('passwordVerify', 'user.password_update.form.password_repeat')
             ->setRequired('user.password_update.form.errors.fill_repeat_password')
             ->addRule(Form::EQUAL, 'user.password_update.form.errors.passwords_not_match', $form['password']);

        $form->addSubmit('send', 'user.password_update.form.do_change');

        $form->onSuccess[] = function (Form $form, array $values) {
            $this->passwordUpdateFormSucceeded($form, $values);
        };

        return $form;
    }

    protected function passwordUpdateFormSucceeded(Form $form, $values)
    {
        try {
            $this->userPasswordModel->changePassword($this->userEntity, $values['password']);
            $form->setValues([], true);
            $this->onChange();
        } catch (UniqueConstraintViolationException $e) {
            $form->addError('user.password_update.form.errors.password_change_failed');
        }
    }

    public function getDefaults()
    {
        return [];
    }
}