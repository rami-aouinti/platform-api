<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\DependencyInjection\Compiler;

use App\Crm\Transport\Widget\WidgetInterface;
use App\Crm\Transport\Widget\WidgetService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Dynamically adds all widgets to the WidgetRepository.
 */
final class WidgetCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(WidgetService::class);

        $taggedRenderer = $container->findTaggedServiceIds(WidgetInterface::class);
        foreach ($taggedRenderer as $id => $tags) {
            $definition->addMethodCall('registerWidget', [new Reference($id)]);
        }
    }
}
