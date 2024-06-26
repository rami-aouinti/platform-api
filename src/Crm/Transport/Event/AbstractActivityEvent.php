<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\Crm\Domain\Entity\Activity;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Base event class to used with activity manipulations.
 */
abstract class AbstractActivityEvent extends Event
{
    public function __construct(
        private Activity $activity
    ) {
    }

    public function getActivity(): Activity
    {
        return $this->activity;
    }
}
