<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Validator;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidationFailedException
 *
 * @package App\Crm\Application\Validator
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ValidationFailedException extends \RuntimeException
{
    public function __construct(private readonly ConstraintViolationListInterface $violations, ?string $message = null)
    {
        if ($message === null) {
            $message = 'Validation failed';
        }
        parent::__construct($message, 400);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
