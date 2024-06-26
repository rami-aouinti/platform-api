<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export;

use App\Crm\Domain\Entity\ExportableItem;
use App\Crm\Domain\Repository\Query\ExportQuery;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface ExportRepositoryInterface
{
    /**
     * This method will receive ALL exported items, loaded from all repositories.
     * Be careful to only handle the ones, which belong to your repository.
     *
     * @param ExportableItem[] $items
     */
    public function setExported(array $items): void;

    /**
     * @return ExportableItem[]
     */
    public function getExportItemsForQuery(ExportQuery $query): iterable;

    /**
     * Returns the type of this repository.
     * Must match the value returned by your entities via ExportableItem::getType().
     */
    public function getType(): string;
}
