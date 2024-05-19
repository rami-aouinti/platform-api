<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\EventSubscriber;

use App\Crm\Transport\Event\EmailEvent;
use App\Crm\Transport\Mail\KimaiMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber to handle emails.
 */
final readonly class EmailSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private KimaiMailer $mailer
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvent::class => ['onMailEvent', 100],
        ];
    }

    public function onMailEvent(EmailEvent $event): void
    {
        $this->mailer->send($event->getEmail());
    }
}
