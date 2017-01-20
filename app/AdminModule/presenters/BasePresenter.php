<?php

namespace AdminModule;

use DontPanic\Entities\Company;
use DontPanic\User\SwitchCompanyException;
use DontPanic\User\UserCompanyControlModel;

abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    /** @var UserCompanyControlModel @inject */
    public $userCompanyControlModel;

    /** @var Company */
    public $company;

    public function startup()
    {
        parent::startup();
        parent::setUserEntity();
        parent::secured(':Admin:Sign:in');

        if ($this->user->isLoggedIn()) {
            $this->initializeUserCompanyControl();
        }
    }

    public function createTemplate($class = null)
    {
        $template                   = parent::createTemplate($class);
        $template->companiesControl = $this->userCompanyControlModel->getCompanies();
        $template->activeCompany    = $this->company;
        $template->userEntity       = $this->userEntity;

        return $template;
    }

    /**
     * @throws \Nette\Application\AbortException
     * @throws \Nette\InvalidArgumentException
     */
    private function initializeUserCompanyControl()
    {
        $this->userCompanyControlModel->setUser($this->userEntity);

        /** @var Company $company */
        $this->company = $this->userCompanyControlModel->getActiveCompany();

        if ($this->action !== 'noCompanies' && (!count($this->userCompanyControlModel->getCompanies()) || $this->company === null)) {
            $this->redirect(':Admin:Company:Information:noCompanies');
        }
    }

    /**
     * @param $companyToken
     *
     * @throws \Nette\InvalidArgumentException
     * @throws \Nette\Application\AbortException
     * @throws \DontPanic\User\SwitchBranchOfficeException
     */
    public function handleSetActiveCompany($companyToken)
    {
        try {
            $this->userCompanyControlModel->setActiveCompany($companyToken);
        } catch (SwitchCompanyException $e) {
            $this->flashMessage(
                $this->translator->translate('test.switch.error')
            );
        }
        $this->redirect('this');
    }
}
