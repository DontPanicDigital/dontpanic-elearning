<?php
namespace DontPanic\Forms;

use Nette\Localization\ITranslator;
use DontPanic\User\UserModel;
use DontPanic\User\UserRegisterModel;
use DontPanic\User\UserRegistrationException;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI;
use Nette\Forms\Form;
use Nette\Utils\Strings;
use zvitek\Component\Form\FormComponent;

class SignUpForm extends FormComponent
{

    /** @var UserRegisterModel */
    protected $userRegisterModel;

    /** @var UserModel */
    protected $userModel;

    /** @var EntityManager */
    protected $em;

    public $onSignUp;

    /**
     * SignUpForm constructor.
     *
     * @param UserRegisterModel $userRegisterModel
     * @param UserModel         $userModel
     * @param ITranslator       $translator
     * @param EntityManager     $em
     */
    public function __construct(UserRegisterModel $userRegisterModel, UserModel $userModel, ITranslator $translator, EntityManager $em)
    {
        parent::__construct($translator);
        $this->userRegisterModel = $userRegisterModel;
        $this->userModel         = $userModel;
        $this->em                = $em;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/signUp.latte');
        $this->template->render();
    }

    /**
     * @return UI\Form
     */
    public function setup()
    {
        $form = $this->form;
        $form->getElementPrototype()->addAttributes([ 'role' => 'form' ]);

        $form->addText('name', 'user.registration.form.full_name')
             ->setRequired('user.registration.form.errors.fill_full_name')
             ->addRule(UI\Form::PATTERN, 'user.registration.form.errors.fill_full_name', '\S{2,}( \S{2,})+')
             ->addRule(UI\Form::PATTERN, 'user.registration.form.errors.full_name_pattern', '[a-zA-ZěščřžýáíéďťňúůĚŠČŘŽÝÁÍÉĎŤŇÚŮ ]+');

        $form->addText('email', 'user.registration.form.email')
             ->setRequired('user.registration.form.errors.fill_email')
             ->addRule(UI\Form::EMAIL, 'user.registration.form.errors.fill_valid_email');

        $form->addPassword('password', 'user.registration.form.password')
             ->addRule(UI\Form::FILLED, 'user.registration.form.errors.fill_password')
             ->getControlPrototype()->addAttributes([ 'placeholder' => 'user.registration.form.errors.fill_password' ]);

        $form->addCheckbox('rules', 'user.registration.form.rules')
             ->setRequired('user.registration.form.errors.check_rules');

        $form->addSubmit('send', 'user.registration.form.do_registration');

        $form->onValidate[] = function (Form $form) {
            $this->signUpFormValidation($form, $form->getValues(true));
        };

        $form->onSuccess[] = function (Form $form, array $values) {
            $this->signUpFormSucceeded($form, $values);
        };

        return $form;
    }

    public function signUpFormValidation(Form $form, $values)
    {
        if ($this->userModel->findByEmail(Strings::lower($values['email']))) {
            $form->addError('user.registration.form.errors.email_taken');
        }
    }

    protected function signUpFormSucceeded(Form $form, array $values)
    {
        $this->em->beginTransaction();

        try {
            $this->userRegisterModel->registerUser($values);
            $this->em->commit();
            $this->onSignUp();
        } catch (UserRegistrationException $e) {
            $form->addError('user.registration.form.errors.registration_failed');
            $this->em->rollback();
        }
    }

    public function getDefaults()
    {
        return [];
    }
}