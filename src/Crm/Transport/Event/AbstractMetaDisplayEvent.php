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
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractMetaDisplayEvent extends Event implements MetaDisplayEventInterface
{
    /**
     * @var MetaTableTypeInterface[]
     */
    private array $fields = [];

    public function __construct(
        private BaseQuery $query,
        private string $location
    ) {
    }

    /**
     * To filter where your meta-field will be displayed, use the query settings.
     */
    public function getQuery(): BaseQuery
    {
        return $this->query;
    }

    /**
     * If you want to filter where your meta-field will be displayed, check the current location.
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Add a new meta field that should be included.
     */
    public function addField(MetaTableTypeInterface $meta): void
    {
        $this->fields[] = $meta;
    }

    /**
     * Returns all meta-fields to be included.
     *
     * @return MetaTableTypeInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
