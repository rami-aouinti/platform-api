<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\Crm\Domain\Repository\Query\InvoiceArchiveQuery;

/**
 * Dynamically find possible meta fields for an invoice-archive query.
 *
 * @method InvoiceArchiveQuery getQuery()
 */
final class InvoiceMetaDisplayEvent extends AbstractMetaDisplayEvent
{
    public const INVOICE = 'invoice';

    public function __construct(InvoiceArchiveQuery $query, string $location)
    {
        parent::__construct($query, $location);
    }
}
