<?php

namespace DontPanic\User;

use DontPanic\Email\EmailService;
use DontPanic\Entities\Task;
use DontPanic\Entities\Test;
use DontPanic\Entities\User;
use DontPanic\Exception\System\EmailException;
use Kdyby\Translation\Translator;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\InvalidLinkException;

class EndTestNoticeEmailService
{

    /** @var Translator */
    private $translator;

    /** @var User */
    private $user;

    /** @var Test */
    private $test;

    /** @var EmailService */
    private $mail;

    /** @var LinkGenerator */
    protected $linkGenerator;

    public function __construct(User $user, Test $test, array $emailSender, Translator $translator, LinkGenerator $linkGenerator)
    {
        $this->translator    = $translator;
        $this->user          = $user;
        $this->test          = $test;
        $this->linkGenerator = $linkGenerator;

        $this->mail = new EmailService($emailSender, $this->translator);
    }

    /**
     * @throws EmailException
     */
    public function send()
    {
        try {
            $this->prepareEmailHeaders();
            $this->mail->send();
        } catch (InvalidLinkException $e) {
            throw new EmailException($e->getMessage());
        }
    }

    /**
     * @param      $email
     * @param null $name
     */
    public function addTo($email, $name = null)
    {
        $this->mail->addTo($email, $name);
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->mail->setSubject($subject);
    }

    /**
     * @throws InvalidLinkException
     */
    private function prepareEmailHeaders()
    {
        $this->mail->setTemplate('user/endTestNotice', [
            'test'    => $this->test,
            'user'    => $this->user,
            'rootUrl' => $this->linkGenerator->link('Web:Page:default'),
        ]);
    }
}