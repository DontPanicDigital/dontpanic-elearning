<?php

namespace WebModule;

use App\Model;

abstract class BasePresenter extends \App\Presenters\BasePresenter
{

    /** @var @persistent */
    public $token;

    public function startup()
    {
        parent::startup();
        parent::setUserEntity();
    }
}
