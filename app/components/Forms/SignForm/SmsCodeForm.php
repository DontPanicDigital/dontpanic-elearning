<?php
namespace DontPanic\Forms;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\Entities\SmsCode;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\SmsCode\SmsCodeModel;
use DontPanic\User\UserModel;
use Nette\Application\UI;
use Nette\Forms\Form;
use Nette\Localization\ITranslator;
use zvitek\Component\Form\FormComponent;
use zvitek\Validator\Regex;

class SmsCodeForm extends FormComponent
{

    /** @var SmsCodeModel */
    protected $smsCodeModel;

    /** @var UserModel */
    protected $userModel;

    /** @var Test */
    public $test;

    /** @var User */
    public $user;

    public $onCheck;

    /**
     * SmsCodeForm constructor.
     *
     * @param SmsCodeModel $smsCodeModel
     * @param UserModel    $userModel
     * @param ITranslator  $translator
     */
    public function __construct(SmsCodeModel $smsCodeModel, UserModel $userModel, ITranslator $translator)
    {
        parent::__construct($translator);
        $this->smsCodeModel = $smsCodeModel;
        $this->userModel    = $userModel;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/smsCodeForm.latte');
        $this->template->render();
    }

    /**
     * @return UI\Form
     */
    public function setup()
    {
        $form = $this->form;

        $form->addText('code', 'user.sms_code.form.code')
             ->setRequired('user.sms_code.form.errors.code_fill')
             ->addRule(UI\Form::PATTERN, 'user.sms_code.form.errors.code_valid', Regex::SMS_CODE);

        $form->addSubmit('send', 'user.sms_code.form.do_change');

        $form->onValidate[] = function (Form $form, array $values) {
            $this->smsCodeValidation($form, $values);
        };

        $form->onSuccess[] = function (Form $form, array $values) {
            $this->smsCodeSucceeded($form, $values);
        };

        return $form;
    }

    public function smsCodeValidation(Form $form, array $values)
    {
        if (!$this->smsCodeModel->getCodeByUserAndTest($this->user, $this->test, $values['code'])) {
            $form->addError($this->translator->translate('user.sms_code.form.errors.error'));
        }
    }

    protected function smsCodeSucceeded(Form $form, array $values)
    {
        try {
            /** @var SmsCode $smsCode */
            $smsCode = $this->smsCodeModel->getCodeByUserAndTest($this->user, $this->test, $values['code']);
            $smsCode->setUsed(1);

            $this->user->setPhoneVerification(1);

            $this->smsCodeModel->save($smsCode);
            $this->userModel->save($this->user);

            $this->getPresenter()->redirect('Test:default');
        } catch (UniqueConstraintViolationException $e) {
            $form->addError('user.sms_code.form.errors.error_update');
        }
    }

    public function getDefaults()
    {
        return [];
    }
}