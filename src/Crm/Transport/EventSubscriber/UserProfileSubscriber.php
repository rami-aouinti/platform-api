<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\EventSubscriber;

use App\User\Domain\Entity\User;
use App\Crm\Transport\Event\PrepareUserEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserProfileSubscriber
 *
 * @package App\Crm\Transport\EventSubscriber
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserProfileSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher, private TokenStorageInterface $storage)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['prepareUserProfile', 200]
        ];
    }

    public function prepareUserProfile(KernelEvent $event): void
    {
        // ignore sub-requests
        if (!$event->isMainRequest()) {
            return;
        }

        // ignore events like the toolbar where we do not have a token
        $token = $this->storage->getToken();
        if (null === ($token)) {
            return;
        }

        $user = $token->getUser();

        if ($user instanceof User) {
            $event = new PrepareUserEvent($user);
            $this->eventDispatcher->dispatch($event);
        }
    }
}
