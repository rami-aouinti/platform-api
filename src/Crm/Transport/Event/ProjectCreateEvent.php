<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

/**
 * Triggered for new project instances, which might or might not be saved.
 */
final class ProjectCreateEvent extends AbstractProjectEvent
{
}
