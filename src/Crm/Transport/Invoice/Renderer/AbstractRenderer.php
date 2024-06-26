<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Invoice\Renderer;

use App\Crm\Application\Model\InvoiceDocument;
use App\Crm\Transport\Invoice\InvoiceFilename;
use App\Crm\Transport\Invoice\InvoiceModel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @internal
 */
abstract class AbstractRenderer
{
    public function supports(InvoiceDocument $document): bool
    {
        foreach ($this->getFileExtensions() as $extension) {
            if (stripos($document->getFilename(), $extension) !== false) {
                return true;
            }
        }

        return false;
    }
    /**
     * @return string[]
     */
    abstract protected function getFileExtensions(): array;

    abstract protected function getContentType(): string;

    protected function buildFilename(InvoiceModel $model): string
    {
        return (string)new InvoiceFilename($model);
    }

    protected function getFileResponse(mixed $file, string $filename): BinaryFileResponse
    {
        $response = new BinaryFileResponse($file);
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);

        $response->headers->set('Content-Type', $this->getContentType());
        $response->headers->set('Content-Disposition', $disposition);
        $response->deleteFileAfterSend(true);

        return $response;
    }
}
