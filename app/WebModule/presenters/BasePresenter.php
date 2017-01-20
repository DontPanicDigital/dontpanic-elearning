<?php

namespace WebModule;

use App\Model;

abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    public function startup()
    {
        parent::startup();
        parent::setUserEntity();
    }
}
