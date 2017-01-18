<?php

namespace App\Presenters;

use App\Control;
use App\Model;
use AppModule\Exception\Http401UnauthorizedException;
use AppModule\Exception\Http403ForbiddenException;
use DontPanic\Acl\SetAclModel;
use DontPanic\Api\ApiTokenModel;
use DontPanic\Auth\PresenterAuthorizationModel;
use DontPanic\Entities;
use DontPanic\ParametersProvider;
use DontPanic\User\UserModel;
use Kdyby\Translation\Translator;
use Nette;
use Nette\Http\UserStorage;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /** @persistent */
    public $locale;

    /** @var Entities\User */
    public $userEntity;

    /** @var UserModel @inject */
    public $userModel;

    /** @var ApiTokenModel @inject */
    public $apiTokenModel;

    /** @var ParametersProvider @inject */
    public $parametersProvider;

    /** @var Translator @inject */
    public $translator;

    /** @var SetAclModel @inject */
    public $setAclModel;

    /** @var PresenterAuthorizationModel @inject */
    public $presenterAuthorizationModel;

    protected function startup()
    {
        parent::startup();
        $this->setAuthorizator();
    }

    public function createTemplate($class = null)
    {
        $template                   = parent::createTemplate($class);
        $template->isProduction     = $this->parametersProvider->isProduction();
        $template->isDevelopment    = $this->parametersProvider->isDevelopment();
        $template->versionTimeStamp = $this->parametersProvider->getVersion()['timestamp'];

        return $template;
    }

    /**************************************************************************************************************z*v*/
    /*************** USER ***************/

    /**
     * @return null|Entities\User
     * @throws Http401UnauthorizedException
     */
    protected function getUserEntity()
    {
        if ($this->getUser()->isLoggedIn()) {
            /** @var Entities\User $userEntity */
            $userEntity = $this->userModel->find($this->getUser()->getId());

            if (!$userEntity) {
                throw new Http401UnauthorizedException();
            }

            return $userEntity;
        }

        return null;
    }

    /**
     * @throws \AppModule\Exception\Http401UnauthorizedException
     */
    public function setUserEntity()
    {
        $this->userEntity = $this->getUserEntity();
    }

    /**************************************************************************************************************z*v*/
    /*************** API TOKENS ***************/

    /**
     * @param $type
     *
     * @throws \AppModule\Exception\Http401UnauthorizedException
     * @throws \DontPanic\User\UserNotFoundException
     */
    protected function setApiToken($type)
    {
        $apiToken = $this->getApiToken(ApiTokenModel::TOKEN_WEBVIEW);

        $this->template->apiToken = $apiToken ?? $apiToken->getToken($type);
    }

    /**
     * @param $type
     *
     * @return null|Entities\ApiToken
     * @throws \DontPanic\User\UserNotFoundException
     * @throws \AppModule\Exception\Http401UnauthorizedException
     */
    protected function getApiToken($type)
    {
        if ($this->getUser()->isLoggedIn()) {
            $user = $this->getUserEntity();
            /** @var Entities\ApiToken $token */
            $token = $this->apiTokenModel->findTokenByUserAndType($user, $type);
            if ($token !== null) {
                if (!$token->isExpired()) {
                    return $token;
                }
            }
            //return $this->apiTokenModel->generateToken($user, $type, (new DateTime())->add(new DateInterval('P1D')));
        }

        return null;
    }

    /**************************************************************************************************************z*v*/
    /*************** AUTHORIZATOR ***************/

    private function setAuthorizator()
    {
        $this->setAclModel->implement();
    }

    /**
     * @param string $loginPage
     *
     * @throws Http403ForbiddenException
     * @throws \InvalidArgumentException
     * @throws \Nette\Application\AbortException
     */
    protected function secured($loginPage = 'Sign:in')
    {
        if ($this->user->isLoggedIn()) {
            $this->presenterAuthorizationModel->setName($this->name);
            $this->presenterAuthorizationModel->setAction($this->action);

            $this->presenterAuthorizationModel->onAccessDenied[] = function () {
                //$this->user->logout();
                throw new Http403ForbiddenException();
            };

            $this->presenterAuthorizationModel->check();
        } else {
            if ($this->user->logoutReason === UserStorage::INACTIVITY) {
                if (!in_array($this->action, [ 'in' ], true)) {
                    $this->flashMessage($this->translator->trans('user.secured.message_after_logout_inactivity'));
                    $this->redirect($loginPage);
                    throw new Http403ForbiddenException();
                }
            }
            if (!in_array($this->action, [ 'in' ], true)) {
                if ($loginPage) {
                    $this->redirect($loginPage);
                }
                throw new Http403ForbiddenException();
            }
        }
    }
}
