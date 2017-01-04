<?php
namespace DontPanic\Forms;

use Nette\Localization\ITranslator;
use DontPanic\User\UserLoginModel;
use Nette\Application\UI;
use Nette\Forms\Form;
use Nette\Security\AuthenticationException;
use zvitek\Component\Form\FormComponent;

class SignInForm extends FormComponent
{

    /** @var UserLoginModel */
    protected $userLoginModel;

    public $onSignIn;

    /**
     * SignInForm constructor.
     *
     * @param UserLoginModel $userLoginModel
     * @param ITranslator    $translator
     */
    public function __construct(UserLoginModel $userLoginModel, ITranslator $translator)
    {
        parent::__construct($translator);
        $this->userLoginModel = $userLoginModel;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/signIn.latte');
        $this->template->render();
    }

    /**
     * @return UI\Form
     */
    public function setup()
    {
        $form = $this->form;

        $form->addText('email', 'user.login.form.email')
             ->setRequired('user.login.form.errors.fill_email')
             ->addRule(Form::EMAIL, 'user.login.form.errors.fill_valid_email');

        $form->addPassword('password', 'user.login.form.password')
             ->setRequired('user.login.form.errors.fill_password');

        $form->addCheckbox('remember', 'user.login.form.stay_logged')
             ->setDefaultValue(1);

        $form->addSubmit('send', 'user.login.form.enter');

        $form->onSuccess[] = function (Form $form, array $values) {
            $this->signInFormSucceeded($form, $values);
        };

        return $form;
    }

    protected function signInFormSucceeded(Form $form, array $values)
    {
        try {
            $user = $this->getPresenter()->getUser();
            $this->userLoginModel->login($values['email'], $values['password'], $values['remember']);
            $this->onSignIn($user);
        } catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

    public function getDefaults()
    {
        return [];
    }
}