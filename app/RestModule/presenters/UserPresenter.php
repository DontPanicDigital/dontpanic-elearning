<?php
namespace RestModule;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DontPanic\RestModule\Errors;
use App\Model;
use DontPanic\User\UserFacade;
use Nette\Http\IResponse;
use RestModule\Exception\Http401UnauthorizedException;

class UserPresenter extends BasePresenter
{

    /** @var UserFacade @inject */
    public $userFacade;

    public function startup()
    {
        parent::startup();
        parent::secured();
    }

    public function actionDetail()
    {
        $this->sendDataAsResponse($this->userEntity, 'apiDetail');
    }

    public function actionUpdate()
    {
        try {
            $user = $this->userFacade->update($this->userEntity, $this->body);
        } catch (UniqueConstraintViolationException $e) {
            throw new Http401UnauthorizedException(Errors::USER_UNAUTHORIZED);
        }
        $this->getHttpResponse()->setCode(IResponse::S200_OK);
        $this->sendDataAsResponse($user, 'apiDetail');
    }
}