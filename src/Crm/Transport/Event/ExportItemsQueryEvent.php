<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\Crm\Domain\Repository\Query\ExportQuery;
use Symfony\Contracts\EventDispatcher\Event;

final class ExportItemsQueryEvent extends Event
{
    public function __construct(
        private ExportQuery $query
    ) {
    }

    public function getExportQuery(): ExportQuery
    {
        return $this->query;
    }
}
