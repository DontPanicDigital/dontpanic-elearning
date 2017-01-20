<?php
namespace DontPanic\Forms;

use DontPanic\Entities\Test;
use DontPanic\Entities\TestOption;
use DontPanic\Entities\TestQuestion;
use DontPanic\Test\TestQuestionModel;
use DontPanic\User\UserUpdateException;
use Kdyby\Translation\Translator;
use Nette\Application\UI;
use Nette\Localization\ITranslator;

class DisplayTestForm extends UI\Control
{

    /** @var Translator */
    private $translator;

    /** @var Test */
    public $test;

    /**
     * DisplayTestForm constructor.
     *
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        parent::__construct();
        $this->translator = $translator;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/templates/displayTestForm.latte');
        $this->template->test = $this->test;
        $this->template->render();
    }

    public function createComponentForm()
    {
        $form = new UI\Form();

        if (count($this->test->getQuestions())) {
            /** @var TestQuestion $question */
            foreach ($this->test->getQuestions() as $question) {
                if (!$question->isDeleted()) {
                    $questionType = $question->getType();
                    if (count($question->getOptions())) {
                        /** @var TestOption $option */
                        $bufferOptions = [];
                        foreach ($question->getOptions() as $option) {
                            if (!$option->isDeleted()) {
                                if ($questionType === TestQuestionModel::TYPE_RADIOLIST) {
                                    $bufferOptions[$option->getToken()] = $option->getOption();
                                }
                                if ($questionType === TestQuestionModel::TYPE_CHECKBOXLIST) {
                                    $bufferOptions[$option->getToken()] = $option->getOption();
                                }
                                if ($questionType === TestQuestionModel::TYPE_SORT) {
                                    $form->addText($option->getToken() . '_sort', $option->getOption());
                                }
                            }
                        }

                        if ($questionType === TestQuestionModel::TYPE_RADIOLIST && count($bufferOptions)) {
                            $form->addRadioList($question->getToken(), $question->getQuestion(), $bufferOptions);
                        }
                        if ($questionType === TestQuestionModel::TYPE_CHECKBOXLIST && count($bufferOptions)) {
                            $form->addCheckboxList($question->getToken(), $question->getQuestion(), $bufferOptions);
                        }
                    }
                }
            }
        }

        $form->addSubmit('submit', 'Odeslat');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->processForm($form, $form->getValues(true));
        };

        return $form;
    }

    protected function processForm(UI\Form $form, array $values)
    {
    }
}