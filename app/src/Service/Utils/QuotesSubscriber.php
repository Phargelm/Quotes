<?php

namespace App\Service\Utils;

use App\Service\QuotesService\QuotesRequestedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class QuotesSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $senderAddress;

    public function __construct(MailerInterface $mailer, string $senderAddress)
    {
        $this->mailer = $mailer;
        $this->senderAddress = $senderAddress;
    }

    public static function getSubscribedEvents()
    {
        return [QuotesRequestedEvent::NAME => 'onQuotesRequest'];
    }

    public function onQuotesRequest(QuotesRequestedEvent $event): void
    {
        $emailText = sprintf('From %s to %s',
            $event->getStartDate()->format('Y-m-d'),
            $event->getEndDate()->format('Y-m-d')
        );

        $email = (new Email())->from($this->senderAddress)
            ->to($event->getEmail())
            ->subject($event->getCompanyName())
            ->text($emailText);

        $this->mailer->send($email);
    }
}