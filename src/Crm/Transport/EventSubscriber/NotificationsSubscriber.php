<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\EventSubscriber;

use KevinPapst\TablerBundle\Event\NotificationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @package App\Crm\Transport\EventSubscriber
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class NotificationsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            NotificationEvent::class => ['onNotificationEvent', 100],
        ];
    }

    public function onNotificationEvent(NotificationEvent $event): void
    {
        $event->setShowBadgeTotal(false);
    }
}
