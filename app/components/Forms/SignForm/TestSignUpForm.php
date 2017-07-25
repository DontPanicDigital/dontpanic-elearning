<?php
namespace DontPanic\Forms;

use DontPanic\Entities\User;
use DontPanic\User\UserModel;
use DontPanic\User\UserRegisterModel;
use DontPanic\User\UserRegistrationException;
use Nette\Application\UI;
use Nette\Forms\Form;
use Nette\Localization\ITranslator;
use zvitek\Component\Form\FormComponent;

class TestSignUpForm extends FormComponent
{

    /** @var UserRegisterModel */
    protected $userRegisterModel;

    /** @var UserModel */
    protected $userModel;

    public $onSignUp;

    /**
     * SignUpForm constructor.
     *
     * @param UserRegisterModel $userRegisterModel
     * @param UserModel         $userModel
     * @param ITranslator       $translator
     */
    public function __construct(UserRegisterModel $userRegisterModel, UserModel $userModel, ITranslator $translator)
    {
        parent::__construct($translator);
        $this->userRegisterModel = $userRegisterModel;
        $this->userModel         = $userModel;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/testSignUp.latte');
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

        $form->addText('phone', 'user.update_profile.form.phone')
             ->setRequired('user.update_profile.form.errors.fill_phone')
             ->addRule(UI\Form::PATTERN, 'user.update_profile.form.errors.fill_valid_phone', \zvitek\Validator\Form::INPUT_PHONE_PATTERN)
             ->setAttribute('placeholder', 'user.update_profile.form.placeholder.phone');

        $form->addSubmit('send', 'user.registration.form.do_registration');

        $form->onSuccess[] = function (Form $form, array $values) {
            $this->signUpFormSucceeded($form, $values);
        };

        return $form;
    }

    protected function signUpFormSucceeded(Form $form, array $values)
    {
        try {
            $user = $this->userModel->findByPhone($values['phone'] ?? null);
            if ($user instanceof User) {
                $this->onSignUp($user);
            } else {
                $this->userRegisterModel->prepareFromArray($values);
                $this->userRegisterModel->onSignup[] = function (User $user) {
                    $this->onSignUp($user);
                };
                $this->userRegisterModel->create();
            }
        } catch (UserRegistrationException $e) {
            $form->addError('user.registration.form.errors.registration_failed');
        }
    }

    public function getDefaults()
    {
        return [];
    }
}