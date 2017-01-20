<?php

namespace AdminModule;

class PagePresenter extends BasePresenter
{

    public function actionDefault()
    {
        if ($this->user->isLoggedIn()) {
            $this->redirect('Test:Page:default');
        }
    }
}