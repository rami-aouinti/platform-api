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
 * @package App\Crm\Transport\Calendar
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class CalendarSource
{
    /**
     * @var array<string, string|bool|int>
     */
    private array $options = [];

    public function __construct(
        private readonly CalendarSourceType $type,
        private readonly string $id,
        private readonly string $uri,
        private readonly ?string $color = null
    ) {
    }

    public function getType(): CalendarSourceType
    {
        return $this->type;
    }

    public function getTypeName(): string
    {
        return $this->type->value;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function addOption(string $name, int|bool|string $value): void
    {
        $this->options[$name] = $value;
    }

    /**
     * @return array<string, bool|int|string>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
