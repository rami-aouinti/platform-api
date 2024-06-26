<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export\Spreadsheet\CellFormatter;

use App\Crm\Application\Utils\StringHelper;
use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class StringFormatter implements CellFormatterInterface
{
    public function setFormattedValue(Worksheet $sheet, int $column, int $row, $value): void
    {
        if ($value === null) {
            $sheet->setCellValue(CellAddress::fromColumnAndRow($column, $row), '');

            return;
        }

        if (!\is_string($value)) {
            throw new \InvalidArgumentException('Unsupported value given, only string is supported');
        }

        $sheet->setCellValue(CellAddress::fromColumnAndRow($column, $row), StringHelper::sanitizeDDE($value));
    }
}
