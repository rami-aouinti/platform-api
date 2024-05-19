<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Invoice\Calculator;

use App\Crm\Domain\Entity\ExportableItem;
use App\Crm\Transport\Invoice\CalculatorInterface;
use App\Crm\Transport\Invoice\InvoiceItem;

/**
 * A calculator that sums up the invoice item records by project.
 */
final class ProjectInvoiceCalculator extends AbstractSumInvoiceCalculator implements CalculatorInterface
{
    public function getIdentifiers(ExportableItem $invoiceItem): array
    {
        if ($invoiceItem->getProject() === null) {
            throw new \Exception('Cannot handle invoice items without project');
        }

        if ($invoiceItem->getProject()->getId() === null) {
            throw new \Exception('Cannot handle un-persisted projects');
        }

        return [
            $invoiceItem->getProject()->getId(),
        ];
    }

    public function getId(): string
    {
        return 'project';
    }

    protected function mergeSumInvoiceItem(InvoiceItem $invoiceItem, ExportableItem $entry): void
    {
        if ($entry->getProject() === null) {
            return;
        }

        if ($entry->getProject()->getInvoiceText() !== null) {
            $invoiceItem->setDescription($entry->getProject()->getInvoiceText());
        } else {
            $invoiceItem->setDescription($entry->getProject()->getName());
        }
    }
}
