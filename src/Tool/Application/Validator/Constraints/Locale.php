<?php

declare(strict_types=1);

namespace App\Tool\Application\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * Usage example;
 *  App\Tool\Application\Validator\Constraints\Locale()
 *
 * Just add that to your property as an annotation and you're good to go.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @package App\Tool
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Locale extends Constraint
{
    final public const INVALID_LOCALE = '44e3862f-2d38-46d4-b1ae-632990814af6';
    final public const MESSAGE = 'This locale "{{ locale }}" is not valid.';

    /**
     * {@inheritdoc}
     *
     * @psalm-var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::INVALID_LOCALE => 'INVALID_LOCALE',
    ];
}
