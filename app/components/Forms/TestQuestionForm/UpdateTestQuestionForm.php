<?php
namespace DontPanic\Forms;

use DontPanic\Entities\TestQuestion;
use DontPanic\Exception\System\UpdateException;
use DontPanic\Test\UpdateTestQuestionModel;
use DontPanic\User\UserUpdateException;
use Kdyby\Events\Event;
use Kdyby\Translation\Translator;
use Nette\Application\UI;
use Nette\Localization\ITranslator;

class UpdateTestQuestionForm extends UI\Control
{

    /** @var UpdateTestQuestionModel */
    private $updateTestQuestionModel;

    /** @var Translator */
    private $translator;

    /** @var TestQuestion */
    public $testQuestion;

    /** @var array|Event */
    public $onUpdate;

    /**
     * CreateTestQuestionForm constructor.
     *
     * @param UpdateTestQuestionModel $updateTestQuestionModel
     * @param ITranslator             $translator
     */
    public function __construct(UpdateTestQuestionModel $updateTestQuestionModel, ITranslator $translator)
    {
        parent::__construct();
        $this->updateTestQuestionModel = $updateTestQuestionModel;
        $this->translator              = $translator;
    }

    public function render()
    {
        $this->getComponent('form')->setDefaults($this->getDefault());
        $this->template->setFile(__DIR__ . '/templates/updateTestQuestionForm.latte');
        $this->template->render();
    }

    public function createComponentForm()
    {
        $form = new UI\Form();

        $form->getElementPrototype()->class = 'ajax';
        $form->setTranslator($this->translator);

        $form->addText('question', 'company.update_question.form.question')
             ->setRequired('company.update_question.form.errors.fill_question');

        $form->addTextArea('description', 'company.update_question.form.description');

        $form->addSubmit('submit', 'company.update_question.form.enter');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->processForm($form, $form->getValues(true));
        };

        return $form;
    }

    protected function processForm(UI\Form $form, array $values)
    {
        try {
            $this->updateTestQuestionModel->setTestQuestion($this->testQuestion);
            $this->updateTestQuestionModel->prepareFromArray($values);
            $this->updateTestQuestionModel->onUpdate[] = function (TestQuestion $testQuestion) {
                $this->onUpdate($testQuestion);
            };
            $this->updateTestQuestionModel->update();
            $this->getPresenter()->flashMessage($this->translator->translate('company.update_question.form.success.question'));
        } catch (UpdateException $e) {
            $form->addError($this->translator->translate('company.update_question.form.errors.error'));
        }
        if ($this->getPresenter()->isAjax()) {
            $this->redrawControl('testQuestion');
            $this->getPresenter()->redrawControl('flashMessages');
        }
    }

    private function getDefault()
    {
        if ($this->testQuestion instanceof TestQuestion) {
            return [
                'question'    => $this->testQuestion->getQuestion(),
                'description' => $this->testQuestion->getDescription(),
            ];
        }

        return [];
    }
}