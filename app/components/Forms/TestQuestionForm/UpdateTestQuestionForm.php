<?php
namespace DontPanic\Forms;

use DontPanic\Entities\TestQuestion;
use DontPanic\Entities\User;
use DontPanic\Exception\System\CreateException;
use DontPanic\Exception\System\DeleteException;
use DontPanic\Exception\System\UpdateException;
use DontPanic\Test\CreateTestOptionModel;
use DontPanic\Test\DeleteTestOptionFacade;
use DontPanic\Test\DeleteTestQuestionFacade;
use DontPanic\Test\UpdateTestQuestionModel;
use DontPanic\User\UserUpdateException;
use Kdyby\Events\Event;
use Kdyby\Translation\Translator;
use Nette\Application\UI;
use Nette\Localization\ITranslator;

class UpdateTestQuestionForm extends UI\Control
{

    /** @var DeleteTestQuestionFacade */
    private $deleteTestQuestionFacade;

    /** @var UpdateTestQuestionModel */
    private $updateTestQuestionModel;

    /** @var CreateTestOptionModel */
    private $createTestOptionModel;

    /** @var DeleteTestOptionFacade */
    private $deleteTestOptionFacade;

    /** @var Translator */
    private $translator;

    /** @var TestQuestion */
    public $testQuestion;

    /** @var User */
    public $user;

    /** @var array|Event */
    public $onUpdate;

    /**
     * UpdateTestQuestionForm constructor.
     *
     * @param DeleteTestQuestionFacade $deleteTestQuestionFacade
     * @param UpdateTestQuestionModel  $updateTestQuestionModel
     * @param CreateTestOptionModel    $createTestOptionModel
     * @param DeleteTestOptionFacade   $deleteTestOptionFacade
     * @param ITranslator              $translator
     */
    public function __construct(
        DeleteTestQuestionFacade $deleteTestQuestionFacade,
        UpdateTestQuestionModel $updateTestQuestionModel,
        CreateTestOptionModel $createTestOptionModel,
        DeleteTestOptionFacade $deleteTestOptionFacade,
        ITranslator $translator
    )
    {
        parent::__construct();
        $this->deleteTestQuestionFacade = $deleteTestQuestionFacade;
        $this->updateTestQuestionModel  = $updateTestQuestionModel;
        $this->createTestOptionModel    = $createTestOptionModel;
        $this->deleteTestOptionFacade  = $deleteTestOptionFacade;
        $this->translator               = $translator;
    }

    public function render()
    {
        $this->getComponent('form')->setDefaults($this->getDefault());
        $this->template->setFile(__DIR__ . '/templates/updateTestQuestionForm.latte');
        $this->template->testQuestion = $this->testQuestion;
        $this->template->render();
    }

    public function createComponentForm()
    {
        $form = new UI\Form();

        $form->getElementPrototype()->class = 'ajax';
        $form->setTranslator($this->translator);

        $form->addText('question', 'test.update_question.form.question')
             ->setRequired('test.update_question.form.errors.fill_question');

        $form->addTextArea('description', 'test.update_question.form.description');

        $form->addSubmit('submit', 'test.update_question.form.enter');

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
            $this->getPresenter()->flashMessage($this->translator->translate('test.update_question.form.success.question'));
        } catch (UpdateException $e) {
            $form->addError($this->translator->translate('test.update_question.form.errors.error'));
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

    /**************************************************************************************************************z*v*/
    /*************** HANDLE ***************/

    /**
     * @param string $testQuestionToken
     *
     * @throws \InvalidArgumentException
     * @throws \Nette\Application\AbortException
     */
    public function handleRemoveTestQuestion($testQuestionToken)
    {
        try {
            $this->deleteTestQuestionFacade->setUser($this->user);
            $this->deleteTestQuestionFacade->remove($testQuestionToken);
            $this->getPresenter()->flashMessage($this->translator->trans('test.delete_question.success'));
        } catch (DeleteException $e) {
            $this->getPresenter()->flashMessage($this->translator->trans('test.delete_question.errors.error'));
        }
        if ($this->getPresenter()->isAjax()) {
            $this->redrawControl('testQuestion');
            $this->getPresenter()->redrawControl('flashMessages');
        } else {
            $this->getPresenter()->redirect('this');
        }
    }

    /**
     *
     * @throws \InvalidArgumentException
     * @throws \Nette\Application\AbortException
     */
    public function handleCreateTestOption()
    {
        try {
            $this->createTestOptionModel->setTestQuestion($this->testQuestion);
            $this->createTestOptionModel->create();
            $this->getPresenter()->flashMessage($this->translator->trans('test.create_option.success'));
        } catch (CreateException $e) {
            $this->getPresenter()->flashMessage($this->translator->trans('test.create_option.errors.error'));
        }
        if ($this->getPresenter()->isAjax()) {
            $this->redrawControl('testQuestion');
            $this->getPresenter()->redrawControl('flashMessages');
        } else {
            $this->getPresenter()->redirect('this');
        }
    }

    /**
     * @param string $testOptionToken
     *
     * @throws \InvalidArgumentException
     * @throws \Nette\Application\AbortException
     */
    public function handleRemoveTestOption($testOptionToken)
    {
        try {
            $this->deleteTestOptionFacade->setUser($this->user);
            $this->deleteTestOptionFacade->remove($testOptionToken);
            $this->getPresenter()->flashMessage($this->translator->trans('test.delete_option.success'));
        } catch (DeleteException $e) {
            $this->getPresenter()->flashMessage($this->translator->trans('test.delete_option.errors.error'));
        }
        if ($this->getPresenter()->isAjax()) {
            $this->redrawControl('testQuestion');
            $this->getPresenter()->redrawControl('flashMessages');
        } else {
            $this->getPresenter()->redirect('this');
        }
    }
}