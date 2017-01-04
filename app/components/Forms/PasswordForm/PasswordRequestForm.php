<?php
namespace DontPanic\Forms;

use Nette\Localization\ITranslator;
use DontPanic\User\UserModel;
use DontPanic\User\UserNotFoundException;
use DontPanic\User\UserPasswordModel;
use Nette\Application\UI;
use Nette\Forms\Form;
use zvitek\Component\Form\FormComponent;

class PasswordRequestForm extends FormComponent
{

    /**  @var UserPasswordModel */
    protected $userPasswordModel;

    /** @var UserModel */
    protected $userModel;

    public $onRequest;

    /**
     * PasswordRequestForm constructor.
     *
     * @param UserPasswordModel $userPasswordModel
     * @param UserModel         $userModel
     * @param ITranslator       $translator
     */
    public function __construct(UserPasswordModel $userPasswordModel, UserModel $userModel, ITranslator $translator)
    {
        parent::__construct($translator);
        $this->userPasswordModel = $userPasswordModel;
        $this->userModel         = $userModel;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/passwordRequest.latte');
        $this->template->render();
    }

    /**
     * @return UI\Form
     */
    public function setup()
    {
        $form = $this->form;

        $form->addText('email', 'user.password_request.form.email')
             ->setRequired('user.password_request.form.errors.fill_email')
             ->addRule(Form::EMAIL, 'user.password_request.form.errors.fill_valid_email');

        $form->addSubmit('send', 'user.password_request.form.do_request');

        $form->onSuccess[] = function (Form $form, array $values) {
            $this->requestPasswordFormSucceeded($form, $values);
        };

        return $form;
    }

    protected function requestPasswordFormSucceeded(Form $form, array $values)
    {
        try {
            $this->userPasswordModel->requestPassword($values['email']);
            $form->setValues([], true);
            $this->onRequest();
        } catch (UserNotFoundException $e) {
            $form->addError('user.password_request.form.errors.email_not_found');
        }
    }

    public function getDefaults()
    {
        return [];
    }
}