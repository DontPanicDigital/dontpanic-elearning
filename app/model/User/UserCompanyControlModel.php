<?php

namespace DontPanic\User;

use Doctrine\Common\Collections\ArrayCollection;
use DontPanic\Company\CompanyModel;
use DontPanic\Entities\BranchOffice;
use DontPanic\Entities\Company;
use DontPanic\Entities\User;
use DontPanic\Model\DoctrineModel;
use Kdyby\Doctrine\EntityManager;
use Nette\Http\Session;

class UserCompanyControlModel extends DoctrineModel
{

    /** @var Company */
    private $companyModel;

    /** @var Session */
    private $session;

    /** @var User */
    private $user;

    /**
     * UserCompanyControlModel constructor.
     *
     * @param EntityManager $em
     * @param CompanyModel  $companyModel
     * @param Session       $session
     */
    public function __construct(EntityManager $em, CompanyModel $companyModel, Session $session)
    {
        $this->em           = $em;
        $this->session      = $session;
        $this->companyModel = $companyModel;
        $this->er           = $this->em->getRepository(User::class);
    }

    /**
     * @return array|\Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection|static
     */
    public function getCompanies()
    {
        if ($this->user instanceof User) {
            return $this->user->getCompanies();
        }

        return [];
    }

    /**
     * @param $companyOfficeToken
     *
     * @throws SwitchCompanyException
     * @throws \Nette\InvalidArgumentException
     */
    public function setActiveCompany($companyOfficeToken)
    {
        /** @var Company $company */
        $company = $this->companyModel->findOneBy([ 'token' => $companyOfficeToken ]);
        if ($company) {
            $this->getSection()->id = $company->getId();
        } else {
            throw new SwitchCompanyException('Company for switch beetwen companies');
        }
    }

    /**
     * @return BranchOffice|null
     * @throws \Nette\InvalidArgumentException
     */
    public function getActiveCompany()
    {
        $companyId = $this->getSection()->id;
        if ($companyId !== null) {
            /** @var Company $company */
            $company = $this->companyModel->find($companyId);
            if ($company instanceof Company) {
                if (!$company->hasUser($this->user)) {
                    $companyId = null;
                }
            }
        }

        if ($companyId === null) {
            /** @var Company $company */
            $company = $this->getCompanies()->first();
            if ($company instanceof Company) {
                $companyId = $company->getId();
            }
        }

        if ($companyId) {
            /** @var Company $company */
            $company = $this->companyModel->find($companyId);
            if ($company) {
                return $company;
            }
        }

        return null;
    }

    /**
     * @return \Nette\Http\SessionSection
     * @throws \Nette\InvalidArgumentException
     */
    private function getSection()
    {
        return $this->session->getSection('COMPANY_CONTROL');
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
