<?php
namespace DontPanic\Forms;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\User;
use DontPanic\User\UserPasswordModel;
use DontPanic\User\UserPasswordNotMatchException;
use Kdyby\Translation\Translator;
use Nette\Application\UI;
use Nette\Forms\Form;
use Nette\Localization\ITranslator;

class PasswordChangeForm extends UI\Control
{

    /** @var UserPasswordModel */
    protected $userPasswordModel;

    /** @var Translator */
    protected $translator;

    public $onChange;

    /** @var bool */
    public $verifyOldPassword = true;

    /** @var User */
    public $userEntity = null;

    /**
     * PasswordChangeForm constructor.
     *
     * @param UserPasswordModel $userPasswordModel
     * @param ITranslator       $translator
     */
    public function __construct(UserPasswordModel $userPasswordModel, ITranslator $translator)
    {
        parent::__construct();
        $this->translator        = $translator;
        $this->userPasswordModel = $userPasswordModel;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/passwordChange.latte');
        $this->template->render();
    }

    public function createComponentForm()
    {
        $form = new UI\Form();

        if ($this->translator) {
            $form->setTranslator($this->translator);
        }

        if ($this->verifyOldPassword) {
            $form->addPassword('old_password', 'user.password_change.form.old_password')
                 ->setRequired('user.password_change.form.old_password')
                 ->addRule(Form::MIN_LENGTH, 'user.password_change.form.errors.old_password_min', 3);
        }

        $form->addPassword('password', 'user.password_change.form.password')
             ->setRequired('user.password_change.form.errors.choose_password')
             ->addRule(Form::MIN_LENGTH, 'user.password_change.form.errors.password_min', 3);

        $form->addPassword('passwordVerify', 'user.password_change.form.password_repeat')
             ->setRequired('user.password_change.form.errors.fill_repeat_password')
             ->addRule(Form::EQUAL, 'user.password_change.form.errors.passwords_not_match', $form['password']);

        $form->addSubmit('send', 'user.password_change.form.do_change');

        $form->onSuccess[] = function (Form $form) {
            $this->passwordChangeFormSucceeded($form, $form->getValues(true));
        };

        return $form;
    }

    protected function passwordChangeFormSucceeded(Form $form, $values)
    {
        try {
            if ($this->verifyOldPassword) {
                $this->userPasswordModel->verifyPassword($this->userEntity, $values['old_password']);
            }
            $this->userPasswordModel->changePassword($this->userEntity, $values['password']);
            $form->setValues([], true);
            $this->onChange();
        } catch (UniqueConstraintViolationException $e) {
            $form->addError($this->translator->translate('user.password_change.form.errors.cahnge_failed'));
        } catch (UserPasswordNotMatchException $e) {
            $form->addError($this->translator->translate('user.password_change.form.errors.old_password_not_match'));
        }
    }

    public function getDefaults()
    {
        return [];
    }
}