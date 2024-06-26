<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Utils;

use Pagerfanta\View\Template\TemplateInterface;
use Pagerfanta\View\TwitterBootstrap5View;

final class PaginationView extends TwitterBootstrap5View
{
    protected function getDefaultProximity(): int
    {
        return 2;
    }

    protected function createDefaultTemplate(): TemplateInterface
    {
        return new PaginationTemplate();
    }
}
