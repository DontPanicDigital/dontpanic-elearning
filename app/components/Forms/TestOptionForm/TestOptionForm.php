<?php
namespace DontPanic\Forms;

use DontPanic\Test\UpdateTestOptionModel;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Translation\ITranslator;

class TestOptionFormFactory
{

    /** @var UpdateTestOptionModel */
    protected $updateTestOptionModel;

    /** @var ITranslator */
    protected $translator;

    /** @var EntityManager */
    protected $em;

    /**
     * TestOptionFormFactory constructor.
     *
     * @param UpdateTestOptionModel $updateTestOptionModel
     * @param ITranslator           $translator
     * @param EntityManager         $em
     */
    public function __construct(
        UpdateTestOptionModel $updateTestOptionModel,
        ITranslator $translator,
        EntityManager $em
    )
    {
        $this->updateTestOptionModel = $updateTestOptionModel;
        $this->translator            = $translator;
        $this->em                    = $em;
    }

    public function updateOptions()
    {
        return new UpdateTestOptionForm(
            $this->updateTestOptionModel,
            $this->translator
        );
    }
}