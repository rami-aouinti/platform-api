<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Calendar;

/**
 * Class Google
 *
 * @package App\Crm\Transport\Calendar
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class Google
{
    /**
     * @param string $apiKey
     * @param GoogleSource[] $sources
     */
    public function __construct(private string $apiKey, private array $sources = [])
    {
    }

    /**
     * @return GoogleSource[]
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
