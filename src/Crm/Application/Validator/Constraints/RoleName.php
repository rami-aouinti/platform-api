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
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class RoleName extends Constraint
{
    public const ROLE_NAME_ERROR = 'xd5hffg-dsfef3-426a-83d7-1f2d33hs5d85';

    protected const ERROR_NAMES = [
        self::ROLE_NAME_ERROR => 'ROLE_NAME_ERROR',
    ];

    public string $message = 'This value is not a valid role name.';
}
