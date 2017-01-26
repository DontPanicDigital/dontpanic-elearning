<?php
namespace DontPanic\Forms;

use DontPanic\Entities\Test;
use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\CreateException;
use DontPanic\Test\CreateTestQuestionModel;
use DontPanic\Test\TestQuestionModel;
use DontPanic\User\UserUpdateException;
use Kdyby\Events\Event;
use Kdyby\Translation\Translator;
use Nette\Application\UI;
use Nette\Localization\ITranslator;

class CreateTestQuestionForm extends UI\Control
{

    /** @var CreateTestQuestionModel */
    private $createTestQuestionModel;

    /** @var Translator */
    private $translator;

    /** @var Test */
    public $test;

    /** @var array|Event */
    public $onCreate;

    /**
     * CreateTestQuestionForm constructor.
     *
     * @param CreateTestQuestionModel $createTestQuestionModel
     * @param ITranslator             $translator
     */
    public function __construct(CreateTestQuestionModel $createTestQuestionModel, ITranslator $translator)
    {
        parent::__construct();
        $this->createTestQuestionModel = $createTestQuestionModel;
        $this->translator              = $translator;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/templates/createTestQuestionForm.latte');
        $this->template->render();
    }

    public function createComponentForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('question', 'test.create_question.form.question')
             ->setRequired('test.create_question.form.errors.fill_question');

        $form->addTextArea('description', 'test.create_question.form.description');

        $form->addRadioList('type', 'test.create_question.form.type', [
            TestQuestionModel::TYPE_CHECKBOXLIST => 'test.create_question.form.type_options.checkbox',
            TestQuestionModel::TYPE_RADIOLIST    => 'test.create_question.form.type_options.radio',
            TestQuestionModel::TYPE_SORT         => 'test.create_question.form.type_options.sort',
        ])->setRequired('test.create_question.form.errors.fill_type');

        $form->addSubmit('submit', 'test.create_question.form.enter');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->processForm($form, $form->getValues(true));
        };

        return $form;
    }

    protected function processForm(UI\Form $form, array $values)
    {
        try {
            $this->createTestQuestionModel->setTest($this->test);
            $this->createTestQuestionModel->setQuestion($values['question']);
            $this->createTestQuestionModel->setDescription($values['description']);
            $this->createTestQuestionModel->setType($values['type']);
            $this->createTestQuestionModel->onCreate[] = function (TestQuestion $testQuestion) {
                $this->getPresenter()->flashMessage($this->translator->translate('test.create_question.form.success.was_created'));
                $this->onCreate($testQuestion);
            };
            $this->createTestQuestionModel->create();
        } catch (CreateException $e) {
            $form->addError($this->translator->translate('test.create_question.form.errors.error'));
        }
    }
}