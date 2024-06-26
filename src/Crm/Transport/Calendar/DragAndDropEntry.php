<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Calendar;

interface DragAndDropEntry
{
    /**
     * Data to be passed to the API call.
     *
     * @return array<string, mixed>
     */
    public function getData(): array;

    /**
     * Returns the title for this entry.
     */
    public function getTitle(): string;

    /**
     * Returns the color for this entry.
     */
    public function getColor(): string;

    /**
     * The block to use for rendering the entry.
     */
    public function getBlockName(): ?string;
}
