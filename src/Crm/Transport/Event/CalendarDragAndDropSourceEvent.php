<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\Calendar\DragAndDropSource;
use App\User\Domain\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

final class CalendarDragAndDropSourceEvent extends Event
{
    /**
     * @var DragAndDropSource[]
     */
    private array $sources = [];

    public function __construct(
        private User $user,
        private int $maxEntries
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMaxEntries(): int
    {
        return $this->maxEntries;
    }

    public function addSource(DragAndDropSource $source): self
    {
        $this->sources[] = $source;

        return $this;
    }

    public function removeSource(DragAndDropSource $source): bool
    {
        $key = array_search($source, $this->sources, true);
        if ($key === false) {
            return false;
        }

        unset($this->sources[$key]);

        return true;
    }

    /**
     * @return DragAndDropSource[]
     */
    public function getSources(): array
    {
        return array_values($this->sources);
    }
}
