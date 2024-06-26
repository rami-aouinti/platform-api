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
 * This event can be used, to dynamically add meta fields to activities
 */
final class ActivityMetaDefinitionEvent extends Event
{
    public function __construct(
        private Activity $entity
    ) {
    }

    public function getEntity(): Activity
    {
        return $this->entity;
    }
}
