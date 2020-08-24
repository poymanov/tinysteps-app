<?php

declare(strict_types=1);

namespace App\Event\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SetFromListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private string $fromEmail;

    /**
     * @var string
     */
    private string $fromName;

    /**
     * @param string $fromEmail
     * @param string $fromName
     */
    public function __construct(string $fromEmail, string $fromName)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName  = $fromName;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => 'onMessage',
        ];
    }

    public function onMessage(MessageEvent $event): void
    {
        $email = $event->getMessage();

        if (!$email instanceof Email) {
            return;
        }

        $email->from(new Address($this->fromEmail, $this->fromName));
    }
}
