<?php

use Tracy\ILogger;

class ErrorPresenter extends \App\Presenters\BasePresenter
{

    /** @var ILogger */
    private $logger;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param  Exception
     *
     * @return void
     * @throws \Nette\Application\AbortException
     */
    public function renderDefault($exception)
    {
        if ($exception instanceof Nette\Application\BadRequestException) {
            $code = $exception->getCode();
            // load template 403.latte or 404.latte or ... 4xx.latte
            $this->setView(in_array($code, [ 403, 404, 405, 410, 500 ], false) ? $code : '4xx');
            // log to access.log
            $this->logger->log("HTTP code $code: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}", 'access');
        } else {
            $this->setView('500'); // load template 500.latte
            $this->logger->log($exception, ILogger::EXCEPTION); // and log exceptions
        }

        if ($this->isAjax()) { // AJAX request? Note this error in payload.
            $this->payload->error = true;
            $this->terminate();
        }
    }

}
