<?php

namespace DontPanic\Email;

use DontPanic\Exception\System\EmailException;
use Kdyby\Translation\Translator;
use Latte;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class EmailService extends Message
{

    const TEMPLATE_PATH = EMAILS_DIR;

    /** @var Translator */
    private $translator;

    public function __construct(array $config, Translator $translator = null)
    {
        parent::__construct();
        $this->translator = $translator;
        $this->setFrom($config['from_email'], $config['from_name']);
    }

    public function setTemplate(string $templateName, array $parameters = [])
    {
        $latte = new Latte\Engine();
        $latte->addFilter('translate', $this->translator === null ? null : [ $this->translator, 'translate' ]);
        $template = $latte->renderToString(sprintf('%s/%s.latte', self::TEMPLATE_PATH, $templateName), $parameters);
        $this->setHtmlBody($template);
    }

    public function send()
    {
        try {
            $maile = new SendmailMailer();
            $maile->send($this);
        } catch (\Exception $e) {
            throw new EmailException($e->getMessage());
        }
    }
}