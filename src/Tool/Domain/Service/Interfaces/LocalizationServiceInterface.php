<?php

declare(strict_types=1);

namespace App\Tool\Domain\Service\Interfaces;

use Throwable;

/**
 * Interface LocalizationServiceInterface
 *
 * @package App\Tool
 */
interface LocalizationServiceInterface
{
    final public const DEFAULT_TIMEZONE = 'Europe/Kyiv';

    /**
     * @return array<int, string>
     */
    public function getLanguages(): array;

    /**
     * @return array<int, string>
     */
    public function getLocales(): array;

    /**
     * @return array<int, array{timezone: string, identifier: string,  offset: string, value: string}>
     */
    public function getTimezones(): array;

    /**
     * @return array<int, array{timezone: string, identifier: non-empty-string,  offset: string, value: string}>
     *
     * @throws Throwable
     */
    public function getFormattedTimezones(): array;
}
