<?php
namespace DontPanic\Forms;

use DontPanic\Entities\Test;
use DontPanic\Exception\System\UpdateException;
use DontPanic\Test\UpdateTestModel;
use DontPanic\User\UserUpdateException;
use Kdyby\Events\Event;
use Kdyby\Translation\Translator;
use Nette\Application\UI;
use Nette\Localization\ITranslator;

class UpdateTestForm extends UI\Control
{

    /** @var UpdateTestModel */
    private $updateTestModel;

    /** @var Translator */
    private $translator;

    /** @var Test */
    public $test;

    /** @var array|Event */
    public $onUpdate;

    /**
     * CreateTestForm constructor.
     *
     * @param UpdateTestModel $updateTestModel
     * @param ITranslator     $translator
     */
    public function __construct(UpdateTestModel $updateTestModel, ITranslator $translator)
    {
        parent::__construct();
        $this->updateTestModel = $updateTestModel;
        $this->translator      = $translator;
    }

    public function render()
    {
        $this->getComponent('form')->setDefaults($this->getDefaults());
        $this->template->setFile(__DIR__ . '/templates/updateTestForm.latte');
        $this->template->render();
    }

    public function createComponentForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('name', 'company.update.form.name')
             ->setRequired('company.update.form.errors.fill_name');

        $form->addTextArea('description', 'company.update.form.name');

        $form->addSubmit('submit', 'company.update.form.enter');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->processForm($form, $form->getValues(true));
        };

        return $form;
    }

    protected function processForm(UI\Form $form, array $values)
    {
        try {
            $this->updateTestModel->setTest($this->test);
            $this->updateTestModel->prepareFromArray($values);
            $this->updateTestModel->onUpdate[] = function (Test $test) {
                $this->onUpdate($test);
            };
            $this->updateTestModel->update();
        } catch (UpdateException $e) {
            $form->addError($this->translator->translate('company.update.form.errors.error'));
        }
    }

    private function getDefaults()
    {
        if ($this->test instanceof Test) {
            return [
                'name'        => $this->test->getName(),
                'description' => $this->test->getDescription(),
            ];
        }

        return [];
    }
}