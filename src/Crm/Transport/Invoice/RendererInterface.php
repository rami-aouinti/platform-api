<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Invoice;

use App\Crm\Application\Model\InvoiceDocument;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Response;

#[AutoconfigureTag]
interface RendererInterface
{
    /**
     * Checks whether the given InvoiceDocument can be rendered.
     */
    public function supports(InvoiceDocument $document): bool;

    /**
     * Render the given InvoiceDocument with the data from the InvoiceModel.
     */
    public function render(InvoiceDocument $document, InvoiceModel $model): Response;
}
