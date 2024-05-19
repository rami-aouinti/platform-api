<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\EventSubscriber;

use App\Crm\Application\Twig\LocaleFormatExtensions;
use App\User\Domain\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @package App\Crm\Transport\EventSubscriber
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class UserEnvironmentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private AuthorizationCheckerInterface $auth,
        private LocaleFormatExtensions $localeFormatExtensions
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['prepareEnvironment', -100],
        ];
    }

    public function prepareEnvironment(RequestEvent $event): void
    {
        // ignore sub-requests
        if (!$event->isMainRequest()) {
            return;
        }

        $locale = $event->getRequest()->getLocale();

        // events like the toolbar might not have a token
        if (null !== ($token = $this->tokenStorage->getToken())) {
            $user = $token->getUser();

            if ($user instanceof User) {
                $locale = $user->getLocale();
                date_default_timezone_set($user->getTimezone());
                $user->initCanSeeAllData($this->auth->isGranted('view_all_data'));
            }
        }

        // the locale is primarily used for formatting values, so we depend on the user locale if available
        \Locale::setDefault($locale);
        $this->localeFormatExtensions->setLocale($locale);
    }
}
