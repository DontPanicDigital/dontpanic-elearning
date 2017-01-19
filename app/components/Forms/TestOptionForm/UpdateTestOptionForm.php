<?php
namespace DontPanic\Forms;

use DontPanic\Entities\TestOption;
use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\UpdateException;
use DontPanic\Test\TestQuestionModel;
use DontPanic\Test\UpdateTestOptionModel;
use DontPanic\User\UserUpdateException;
use Kdyby\Events\Event;
use Kdyby\Translation\Translator;
use Nette\Application\UI;
use Nette\Localization\ITranslator;

class UpdateTestOptionForm extends UI\Control
{

    /** @var UpdateTestOptionModel */
    protected $updateTestOptionModel;

    /** @var Translator */
    private $translator;

    /** @var TestQuestion */
    public $testQuestion;

    /** @var array|Event */
    public $onUpdate;

    /**
     * UpdateTestOptionForm constructor.
     *
     * @param UpdateTestOptionModel $updateTestOptionModel
     * @param ITranslator           $translator
     */
    public function __construct(
        UpdateTestOptionModel $updateTestOptionModel,
        ITranslator $translator
    )
    {
        parent::__construct();
        $this->updateTestOptionModel = $updateTestOptionModel;
        $this->translator            = $translator;
    }

    public function render()
    {
        $this->getComponent('form')->setDefaults($this->getDefault());
        $this->template->setFile(__DIR__ . '/templates/updateTestOptionForm.latte');
        $this->template->testQuestion = $this->testQuestion;
        $this->template->render();
    }

    public function createComponentForm()
    {
        $form = new UI\Form();

        $form->getElementPrototype()->class = 'ajax';
        $form->setTranslator($this->translator);

        $questionType = $this->testQuestion->getType();
        $optionsList  = [];

        if (count($this->testQuestion->getOptions())) {
            /** @var TestOption $option */
            foreach ($this->testQuestion->getOptions() as $option) {
                if (!$option->isDeleted()) {
                    $form->addText($option->getToken() . '_option', 'company.update_option.form.option')
                         ->setRequired('company.update_option.form.errors.fill_option');

                    $form->addTextArea($option->getToken() . '_description', 'company.update_option.form.description');
                    $form->addTextArea($option->getToken() . '_annotation', 'company.update_option.form.annotation');

                    if ($questionType === TestQuestionModel::TYPE_SORT) {
                        $form->addText($option->getToken() . '_sort', 'company.update_option.form.sort')
                             ->setType('number');
                    }

                    if (in_array($questionType, [ TestQuestionModel::TYPE_RADIOLIST, TestQuestionModel::TYPE_CHECKBOXLIST ], true)) {
                        $optionsList[$option->getToken()] = 'company.update_option.form.correct';
                    }
                }
            }
            if ($questionType === TestQuestionModel::TYPE_RADIOLIST) {
                $form->addRadioList('correct', 'company.update_option.form.correct', $optionsList)
                     ->setRequired('company.update_option.form.choose');
            }
            if ($questionType === TestQuestionModel::TYPE_CHECKBOXLIST) {
                $form->addCheckboxList('correct', 'company.update_option.form.correct', $optionsList);
            }
        }

        $form->addSubmit('submit', 'company.update_option.form.enter');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->processForm($form, $form->getValues(true));
        };

        return $form;
    }

    protected function processForm(UI\Form $form, array $values)
    {
        try {
            $this->updateTestOptionModel->setTestQuestion($this->testQuestion);
            $this->updateTestOptionModel->prepareFromArray($values);
            $this->updateTestOptionModel->update();
            $this->getPresenter()->flashMessage($this->translator->translate('company.update_option.form.success.option'));
        } catch (UpdateException $e) {
            $form->addError($this->translator->translate('company.update_option.form.errors.error'));
        }
        if ($this->getPresenter()->isAjax()) {
            $this->redrawControl('testOption');
            $this->getPresenter()->redrawControl('flashMessages');
        }
    }

    private function getDefault()
    {
        if ($this->testQuestion instanceof TestQuestion) {
            $defaults     = [];
            $options      = $this->testQuestion->getOptions();
            $questionType = $this->testQuestion->getType();
            $checkboxList = [];
            $radioList    = null;
            if (count($options)) {
                /** @var TestOption $option */
                foreach ($options as $option) {
                    if (!$option->isDeleted()) {
                        $defaults[$option->getToken() . '_option']      = $option->getOption();
                        $defaults[$option->getToken() . '_description'] = $option->getDescription();
                        $defaults[$option->getToken() . '_annotation']  = $option->getAnnotation();

                        if ($questionType === TestQuestionModel::TYPE_CHECKBOXLIST) {
                            if ($option->getCorrect()) {
                                $checkboxList[] = $option->getToken();
                            }
                        }

                        if ($questionType === TestQuestionModel::TYPE_RADIOLIST) {
                            if ($option->getCorrect()) {
                                $radioList = $option->getToken();
                            }
                        }

                        if ($questionType === TestQuestionModel::TYPE_SORT) {
                            $defaults[$option->getToken() . '_sort'] = $option->getSort();
                        }
                    }
                }

                if (count($checkboxList)) {
                    $defaults['correct'] = $checkboxList;
                }

                if ($radioList) {
                    $defaults['correct'] = $radioList;
                }
            }

            return $defaults;
        }

        return [];
    }

    /**************************************************************************************************************z*v*/
    /*************** HANDLE ***************/

    /**
     * @param $testOptionToken
     */
    public function handleRemoveTestOption($testOptionToken)
    {
        $this->getParent()->handleRemoveTestOption($testOptionToken);
    }
}