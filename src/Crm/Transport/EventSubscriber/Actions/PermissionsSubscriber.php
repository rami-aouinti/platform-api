<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\EventSubscriber\Actions;

use App\Crm\Transport\Event\PageActionsEvent;

final class PermissionsSubscriber extends AbstractActionsSubscriber
{
    public static function getActionName(): string
    {
        return 'user_permissions';
    }

    public function onActions(PageActionsEvent $event): void
    {
        if ($this->isGranted('role_permissions')) {
            $event->addCreate($this->path('admin_user_roles'), !$event->isView('role'));
        }
    }
}
