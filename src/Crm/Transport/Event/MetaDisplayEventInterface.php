<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\Crm\Domain\Entity\MetaTableTypeInterface;
use App\Crm\Domain\Repository\Query\BaseQuery;

interface MetaDisplayEventInterface
{
    /**
     * If you want to filter where your meta-field will be displayed, use the query settings.
     */
    public function getQuery(): BaseQuery;

    /**
     * If you want to filter where your meta-field will be displayed, check the current location.
     */
    public function getLocation(): string;

    /**
     * @return MetaTableTypeInterface[]
     */
    public function getFields(): array;

    /**
     * Adds a field that should be displayed.
     */
    public function addField(MetaTableTypeInterface $meta): void;
}
