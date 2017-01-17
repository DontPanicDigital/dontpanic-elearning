<?php

namespace AdminModule;

use Nette\Http\UserStorage;

abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    public function startup()
    {
        parent::startup();
        parent::secured(':Admin:Sign:in');
        parent::setUserEntity();
    }

    public function getUser()
    {
        $user = parent::getUser();
        // @TODO: set namespace for admin
        /** @var UserStorage $storage */
        //$storage = $user->getStorage();
        //$storage->setNamespace('Admin');

        return $user;
    }
}
