<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export;

use App\Crm\Domain\Entity\ExportableItem;
use App\Crm\Domain\Repository\Query\TimesheetQuery;
use Symfony\Component\HttpFoundation\Response;

interface ExportRendererInterface
{
    /**
     * @param ExportableItem[] $exportItems
     */
    public function render(array $exportItems, TimesheetQuery $query): Response;

    public function getId(): string;

    public function getIcon(): string;

    public function getTitle(): string;
}
