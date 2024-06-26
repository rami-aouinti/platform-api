<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Entity;

use Doctrine\Common\Collections\Collection;

interface EntityWithMetaFields
{
    /**
     * @return Collection|MetaTableTypeInterface[]
     */
    public function getMetaFields(): Collection;

    public function getMetaField(string $name): ?MetaTableTypeInterface;

    public function setMetaField(MetaTableTypeInterface $meta): self;
}
