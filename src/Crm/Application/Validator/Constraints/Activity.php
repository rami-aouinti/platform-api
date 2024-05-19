<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @package App\Crm\Application\Validator\Constraints
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Activity extends Constraint
{
    public const ACTIVITY_NUMBER_EXISTING = 'kimai-activity-00';

    protected const ERROR_NAMES = [
        self::ACTIVITY_NUMBER_EXISTING => 'The number %number% is already used.',
    ];

    public string $message = 'This activity has invalid settings.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
