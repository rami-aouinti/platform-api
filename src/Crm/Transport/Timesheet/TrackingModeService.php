<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Timesheet;

use App\Crm\Transport\Configuration\SystemConfiguration;
use App\Crm\Transport\Timesheet\TrackingMode\TrackingModeInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

final class TrackingModeService
{
    /**
     * @param SystemConfiguration $configuration
     * @param TrackingModeInterface[] $modes
     */
    public function __construct(
        private readonly SystemConfiguration $configuration,
        #[TaggedIterator(TrackingModeInterface::class)]
        private readonly iterable $modes
    )
    {
    }

    /**
     * @return TrackingModeInterface[]
     */
    public function getModes(): iterable
    {
        return $this->modes;
    }

    public function getActiveMode(): TrackingModeInterface
    {
        $trackingMode = $this->configuration->getTimesheetTrackingMode();

        foreach ($this->getModes() as $mode) {
            if ($mode->getId() === $trackingMode) {
                return $mode;
            }
        }

        throw new ServiceNotFoundException($trackingMode);
    }
}
