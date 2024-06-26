<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export\Spreadsheet;

use App\Crm\Application\Export\Spreadsheet\Extractor\AnnotationExtractor;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

final class AnnotatedObjectExporter
{
    public function __construct(
        private SpreadsheetExporter $spreadsheetExporter,
        private AnnotationExtractor $annotationExtractor
    ) {
    }

    public function export(string $class, array $entries): Spreadsheet
    {
        $columns = $this->annotationExtractor->extract($class);

        return $this->spreadsheetExporter->export($columns, $entries);
    }
}
