<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Widget\Type;

use App\Crm\Domain\Repository\TimesheetRepository;
use App\Crm\Transport\Widget\WidgetException;
use App\Crm\Transport\Widget\WidgetInterface;

final class DurationMonth extends AbstractCounterDuration
{
    public function __construct(
        private TimesheetRepository $repository
    ) {
    }

    /**
     * @param array<string, string|bool|int|null|array<string, mixed>> $options
     @return array<string, string|bool|int|null|array<string, mixed>>
     */
    public function getOptions(array $options = []): array
    {
        return array_merge([
            'color' => WidgetInterface::COLOR_MONTH,
        ], parent::getOptions($options));
    }

    public function getPermissions(): array
    {
        return ['view_other_timesheet'];
    }

    public function getId(): string
    {
        return 'DurationMonth';
    }

    public function getTitle(): string
    {
        return 'stats.durationMonth';
    }

    /**
     * @param array<string, string|bool|int|null|array<string, mixed>> $options
     */
    public function getData(array $options = []): mixed
    {
        try {
            return $this->repository->getDurationForTimeRange($this->createMonthStartDate(), $this->createMonthEndDate(), null);
        } catch (\Exception $ex) {
            throw new WidgetException(
                'Failed loading widget data: ' . $ex->getMessage()
            );
        }
    }
}
