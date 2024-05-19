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
 * A calculator that sums up the invoice item records by activity.
 */
final class ActivityInvoiceCalculator extends AbstractSumInvoiceCalculator implements CalculatorInterface
{
    public function getIdentifiers(ExportableItem $invoiceItem): array
    {
        return [
            $invoiceItem->getActivity()?->getId()
        ];
    }

    protected function mergeSumInvoiceItem(InvoiceItem $invoiceItem, ExportableItem $entry): void
    {
        if (null === $entry->getActivity()) {
            return;
        }

        if ($entry->getActivity()->getInvoiceText() !== null) {
            $invoiceItem->setDescription($entry->getActivity()->getInvoiceText());
        } else {
            $invoiceItem->setDescription($entry->getActivity()->getName());
        }
    }

    public function getId(): string
    {
        return 'activity';
    }
}
