<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Invoice\NumberGenerator;

use App\Crm\Domain\Repository\InvoiceRepository;
use App\Crm\Transport\Invoice\InvoiceModel;
use App\Crm\Transport\Invoice\NumberGeneratorInterface;

/**
 * Class DateNumberGenerator generates the invoice number based on the current day.
 * It will create duplicate IDs if you create more then 99 invoices per day.
 */
final class DateNumberGenerator implements NumberGeneratorInterface
{
    private ?InvoiceModel $model = null;

    public function __construct(
        private InvoiceRepository $repository
    ) {
    }

    public function getId(): string
    {
        return 'date';
    }

    public function setModel(InvoiceModel $model): void
    {
        $this->model = $model;
    }

    public function getInvoiceNumber(): string
    {
        $loops = 0;
        $increaseBy = 0;

        $result = date('ymd', $this->model->getInvoiceDate()->getTimestamp());

        // in the case that someone configured a weird format, that should not result in an endless loop
        while ($this->repository->hasInvoice($result) && $loops++ < 99) {
            $suffix = str_pad((string)++$increaseBy, 2, '0', STR_PAD_LEFT);
            $result = date('ymd', $this->model->getInvoiceDate()->getTimestamp()) . '-' . $suffix;
        }

        return $result;
    }
}
