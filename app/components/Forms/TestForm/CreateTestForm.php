<?php
namespace DontPanic\Forms;

use DontPanic\Entities\Company;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Exception\System\CreateException;
use DontPanic\Test\CreateTestModel;
use DontPanic\User\UserUpdateException;
use Kdyby\Events\Event;
use Kdyby\Translation\Translator;
use Nette\Application\UI;
use Nette\Localization\ITranslator;

class CreateTestForm extends UI\Control
{

    /** @var CreateTestModel */
    private $createTestModel;

    /** @var Translator */
    private $translator;

    /** @var Company */
    public $company;

    /** @var User */
    public $user;

    /** @var array|Event */
    public $onCreate;

    /**
     * CreateTestForm constructor.
     *
     * @param CreateTestModel $createTestModel
     * @param ITranslator     $translator
     */
    public function __construct(CreateTestModel $createTestModel, ITranslator $translator)
    {
        parent::__construct();
        $this->createTestModel = $createTestModel;
        $this->translator      = $translator;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/templates/createTestForm.latte');
        $this->template->render();
    }

    public function createComponentForm()
    {
        $form = new UI\Form();
        $form->setTranslator($this->translator);

        $form->addText('name', 'company.create.form.name')
             ->setRequired('company.create.form.errors.fill_name');

        $form->addSubmit('submit', 'company.create.form.enter');

        $form->onSuccess[] = function (UI\Form $form) {
            $this->processForm($form, $form->getValues(true));
        };

        return $form;
    }

    protected function processForm(UI\Form $form, array $values)
    {
        try {
            $this->createTestModel->setCompany($this->company);
            $this->createTestModel->setUser($this->user);
            $this->createTestModel->setName($values['name']);
            $this->createTestModel->onCreate[] = function (Test $test) {
                $this->onCreate($test);
            };
            $this->createTestModel->create();
        } catch (CreateException $e) {
            $form->addError($this->translator->translate('company.create.form.errors.error'));
        }
    }
}