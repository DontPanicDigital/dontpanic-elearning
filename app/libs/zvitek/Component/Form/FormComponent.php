<?php

namespace zvitek\Component\Form;

use Nette\Application\UI;
use Nette\Forms\Form as BaseForm;
use Nette\Localization\ITranslator;

/**
 * @method onSuccess(BaseForm $form, array $values)
 */
abstract class FormComponent extends UI\Control
{
    /** @var ITranslator */
    protected $translator;

    /** @var UI\Form */
    protected $form;

    /** @var bool */
    protected $secured = false;

    /** @var callable[] */
    public $onSuccess = [];

    /** @var callable[] */
    public $onSubmit = [];

    /** @var callable[] */
    public $onError = [];

    public function __construct(ITranslator $translator = null) {
        parent::__construct();
        $this->form = new UI\Form();
        $this->translator = $translator;
        $this->initForm();
    }

    protected function createComponentForm() {
        return $this->form;
    }

    /**
     * Initialize form
     */
    private function initForm() {
        $this->setup();

        if ($this->translator) {
            $this->form->setTranslator($this->translator);
        }

        $this->form->onSuccess[] = function (BaseForm $form, array $values) {
            $this->onSuccess($form, $values);
        };

        $this->form->setDefaults($this->getDefaults());

        if ($this->secured) {
            $this->form->addProtection('base.forms.protection_timeout');
        }
    }

    public function addSuccessHandler(callable $handler) {
        $this->form->onSuccess[] = $handler;
    }

    public function addValidateHandler(callable $handler) {
        $this->form->onValidate[] = $handler;
    }

    public function addErrorHandler(callable $handler) {
        $this->form->onError[] = $handler;
    }

    public function getErrors() {
        return $this->form->getErrors();
    }

    /**
     * @return mixed
     */
    abstract protected function setup();

    /**
     * Get default values of the form
     *
     * @return array
     */
    abstract protected function getDefaults();
}