<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Reporting;

/**
 * @package App\Crm\Application\Reporting
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class Report implements ReportInterface
{
    public function __construct(
        private string $id,
        private string $route,
        private string $label,
        private string $reportIcon,
        private string $translationDomain = 'reporting'
    ) {
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getReportIcon(): string
    {
        return $this->reportIcon;
    }

    public function getTranslationDomain(): string
    {
        return $this->translationDomain;
    }
}
