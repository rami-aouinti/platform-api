<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export\Renderer;

use App\Crm\Application\Export\Base\CsvRenderer as BaseCsvRenderer;
use App\Crm\Application\Export\RendererInterface;

final class CsvRenderer extends BaseCsvRenderer implements RendererInterface
{
    public function getIcon(): string
    {
        return 'csv';
    }

    public function getTitle(): string
    {
        return 'csv';
    }
}
