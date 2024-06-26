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
use App\Crm\Transport\Configuration\SystemConfiguration;
use App\Crm\Transport\Widget\WidgetException;
use App\Crm\Transport\Widget\WidgetInterface;

final class ActiveUsersYear extends AbstractCounterYear
{
    public function __construct(
        private TimesheetRepository $repository,
        SystemConfiguration $systemConfiguration
    ) {
        parent::__construct($systemConfiguration);
    }

    /**
     * @param array<string, string|bool|int|null|array<string, mixed>> $options
     @return array<string, string|bool|int|null|array<string, mixed>>
     */
    public function getOptions(array $options = []): array
    {
        return array_merge([
            'icon' => 'users',
            'color' => WidgetInterface::COLOR_YEAR,
        ], parent::getOptions($options));
    }

    public function getPermissions(): array
    {
        return ['ROLE_TEAMLEAD'];
    }

    public function getId(): string
    {
        return 'activeUsersYear';
    }

    /**
     * @param array<string, string|bool|int|null|array<string, mixed>> $options
     */
    protected function getYearData(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): mixed
    {
        try {
            return $this->repository->countActiveUsers($begin, $end, null);
        } catch (\Exception $ex) {
            throw new WidgetException(
                'Failed loading widget data: ' . $ex->getMessage()
            );
        }
    }

    protected function getFinancialYearTitle(): string
    {
        return 'stats.activeUsersFinancialYear';
    }
}
