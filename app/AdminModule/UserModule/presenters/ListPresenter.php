<?php

namespace AdminModule\UserModule;

use DontPanic\Exception\System\DeleteException;
use DontPanic\Forms\UserFilterFormFactory;
use DontPanic\Forms\UserSystemFilterForm;
use DontPanic\User\UserDeleteException;
use DontPanic\User\UserFilterModel;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ListPresenter extends BasePresenter
{

    /** @var UserFilterModel @inject */
    public $userFilterModel;

    /** @var UserFilterFormFactory @inject */
    public $userFilterFormFactory;

    /** @var string */
    private $filterSesction = 'user.system.filter';

    /** @var int */
    private $perPage = 20;

    public function renderDefault()
    {
        $filter = $this->getFilter();

        if ($filter !== null) {
            /** @var UserSystemFilterForm $filterForm */
            $filterForm           = $this->getComponent('userSystemFilterForm');
            $filterForm->defaults = $filter;
            $this->userFilterModel->setRoles($filter['roles']);
            $this->userFilterModel->setSearch($filter['search']);
        }

        $userList = $this->userFilterModel->getUsers();
        /** @var \IPub\VisualPaginator\Components\Control $visualPaginator */
        $visualPaginator         = $this->getComponent('vp');
        $paginator               = $visualPaginator->getPaginator();
        $paginator->itemsPerPage = $this->perPage;

        $userList->setFirstResult($paginator->offset);
        $userList->setMaxResults($this->perPage);

        $userList             = new Paginator($userList, true);
        $paginator->itemCount = count($userList);

        $this->template->users = $userList;
    }

    private function getFilter($filter = null)
    {
        if ($filter !== null) {
            $this->session->getSection($this->filterSesction)->filter = $filter;
        }

        return $this->session->getSection($this->filterSesction)->filter;
    }

    /**************************************************************************************************************z*v*/
    /*************** COMPONENTS ***************/

    /**
     * @return UserSystemFilterForm
     * @throws \Nette\Application\AbortException
     * @throws \Nette\InvalidArgumentException
     */
    public function createComponentUserSystemFilterForm()
    {
        /** @var UserSystemFilterForm $control */
        $control = $this->userFilterFormFactory->createUserSystemFilterForm();

        $control->onFiltered[] = function (array $filter) {
            $this->getFilter($filter);
            $this->redirect('this');
        };

        return $control;
    }

    public function createComponentVp()
    {
        $control = new \IPub\VisualPaginator\Components\Control();
        $control->disableAjax();

        return $control;
    }

    /**************************************************************************************************************z*v*/
    /*************** HANDLE ***************/

    /**
     * @param int $userId
     *
     * @throws \Nette\Application\AbortException
     */
    public function handleRemove($userId)
    {
        try {
            $this->userModel->markAsDeleted(null, $userId);
            $this->flashMessage('Uživatel smazán');
        } catch (DeleteException $e) {
            $this->flashMessage('Uživatele se nepodařilo smazat');
        }
        if ($this->isAjax()) {
            $this->redrawControl('userList');
            $this->redrawControl('flashMessages');
        } else {
            $this->rediect('this');
        }
    }

}