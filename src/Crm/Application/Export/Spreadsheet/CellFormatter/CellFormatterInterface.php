<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export\Spreadsheet\CellFormatter;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface CellFormatterInterface
{
    /**
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function setFormattedValue(Worksheet $sheet, int $column, int $row, $value): void;
}
