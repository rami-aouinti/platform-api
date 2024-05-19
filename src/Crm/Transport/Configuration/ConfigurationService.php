<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Configuration;

use App\Crm\Domain\Entity\Configuration;
use App\Crm\Transport\Form\Model\SystemConfiguration;
use App\Crm\Domain\Repository\ConfigurationRepository;
use Doctrine\ORM\Exception\ORMException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Class ConfigurationService
 *
 * @package App\Crm\Transport\Configuration
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ConfigurationService implements ConfigLoaderInterface
{
    /**
     * @var array<string, string|null>
     */
    private static array $cacheAll = [];
    private static bool $initialized = false;

    public function __construct(
        private readonly ConfigurationRepository $configurationRepository,
        private readonly CacheInterface $cache
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     * @return array<string, string|null>
     */
    public function getConfigurations(): array
    {
        if (self::$initialized === true) {
            return self::$cacheAll;
        }

        self::$cacheAll = $this->cache->get('configurations', function (ItemInterface $item) {
            $item->expiresAfter(86400); // one day

            return $this->configurationRepository->getConfigurations();
        });

        self::$initialized = true;

        return self::$cacheAll;
    }

    public function getConfiguration(string $name): ?Configuration
    {
        return $this->configurationRepository->findOneBy(['name' => $name]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function clearCache(): void
    {
        $this->cache->delete('configurations');
        self::$initialized = false;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function saveConfiguration(Configuration $configuration): void
    {
        $this->configurationRepository->saveConfiguration($configuration);
        $this->clearCache();
    }

    /**
     * @param SystemConfiguration $model
     *
     * @throws InvalidArgumentException
     * @throws ORMException
     */
    public function saveSystemConfiguration(SystemConfiguration $model): void
    {
        $this->configurationRepository->saveSystemConfiguration($model);
        $this->clearCache();
    }
}
