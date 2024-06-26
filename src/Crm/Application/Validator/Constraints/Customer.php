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

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Customer extends Constraint
{
    public const CUSTOMER_NUMBER_EXISTING = 'kimai-customer-00';

    protected const ERROR_NAMES = [
        self::CUSTOMER_NUMBER_EXISTING => 'The number %number% is already used.',
    ];

    public string $message = 'This customer has invalid settings.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
