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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class InvoicePostRenderEvent
 *
 * @package App\Crm\Transport\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class InvoicePostRenderEvent extends Event
{
    public function __construct(private InvoiceModel $model, private InvoiceDocument $document, private RendererInterface $renderer, private Response $response)
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

    public function getResponse(): Response
    {
        return $this->response;
    }
}
