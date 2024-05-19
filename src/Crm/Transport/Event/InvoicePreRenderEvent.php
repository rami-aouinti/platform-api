<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\Crm\Transport\Invoice\InvoiceModel;
use App\Crm\Transport\Invoice\RendererInterface;
use App\Crm\Application\Model\InvoiceDocument;
use Symfony\Contracts\EventDispatcher\Event;

final class InvoicePreRenderEvent extends Event
{
    public function __construct(private InvoiceModel $model, private InvoiceDocument $document, private RendererInterface $renderer)
    {
    }

    public function getModel(): InvoiceModel
    {
        return $this->model;
    }

    public function getDocument(): InvoiceDocument
    {
        return $this->document;
    }

    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }
}
