<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\DependencyInjection\Compiler;

use App\Crm\Application\Export\ExportRepositoryInterface;
use App\Crm\Application\Export\RendererInterface;
use App\Crm\Application\Export\ServiceExport;
use App\Crm\Application\Export\TimesheetExportInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Dynamically adds all dependencies to the ExportService.
 */
final class ExportServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ServiceExport::class);

        $taggedRenderer = $container->findTaggedServiceIds(RendererInterface::class);
        foreach ($taggedRenderer as $id => $tags) {
            $definition->addMethodCall('addRenderer', [new Reference($id)]);
        }

        $taggedExporter = $container->findTaggedServiceIds(TimesheetExportInterface::class);
        foreach ($taggedExporter as $id => $tags) {
            $definition->addMethodCall('addTimesheetExporter', [new Reference($id)]);
        }

        $taggedRepository = $container->findTaggedServiceIds(ExportRepositoryInterface::class);
        foreach ($taggedRepository as $id => $tags) {
            $definition->addMethodCall('addExportRepository', [new Reference($id)]);
        }

        $exportDocuments = $container->getParameter('kimai.export.documents');
        if (\is_array($exportDocuments)) {
            $path = \dirname(__DIR__, 3) . DIRECTORY_SEPARATOR;
            foreach ($exportDocuments as $exportPath) {
                if (!is_dir($path . $exportPath)) {
                    continue;
                }

                $definition->addMethodCall('addDirectory', [realpath($path . $exportPath)]);
            }
        }
    }
}
