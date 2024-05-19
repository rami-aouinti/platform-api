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

/**
 * A calculator that sums up the invoice item records for each day and user.
 */
final class DateUserInvoiceCalculator extends AbstractSumInvoiceCalculator implements CalculatorInterface
{
    public function getIdentifiers(ExportableItem $invoiceItem): array
    {
        if ($invoiceItem->getBegin() === null) {
            throw new \Exception('Cannot handle invoice items without start date');
        }

        if ($invoiceItem->getUser()?->getId() === null) {
            throw new \Exception('Cannot handle un-persisted users');
        }

        return [
            $invoiceItem->getBegin()->format('Y-m-d'),
            $invoiceItem->getUser()->getId(),
        ];
    }

    public function getId(): string
    {
        return 'date_user';
    }
}
