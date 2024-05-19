<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Invoice;

use App\Crm\Domain\Entity\ExportableItem;
use App\Crm\Domain\Repository\Query\InvoiceQuery;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface InvoiceItemRepositoryInterface
{
    /**
     * @param ExportableItem[] $invoiceItems
     * @return void
     */
    public function setExported(array $invoiceItems) /* : void */;

    /**
     * @param InvoiceQuery $query
     * @return ExportableItem[]
     */
    public function getInvoiceItemsForQuery(InvoiceQuery $query): iterable;
}
