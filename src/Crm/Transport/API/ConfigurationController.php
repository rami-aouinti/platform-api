<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\API;

use App\Crm\Transport\API\Model\TimesheetConfig;
use App\Crm\Transport\Configuration\SystemConfiguration;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('API')]
#[OA\Tag(name: 'Default')]
final class ConfigurationController extends BaseApiController
{
    public function __construct(
        private readonly ViewHandlerInterface $viewHandler
    ) {
    }

    /**
     * Returns the timesheet configuration
     */
    #[OA\Response(response: 200, description: 'Returns the instance specific timesheet configuration', content: new OA\JsonContent(ref: new Model(type: TimesheetConfig::class)))]
    #[Route(methods: ['GET'], path: '/config/timesheet')]
    public function timesheetConfigAction(SystemConfiguration $configuration): Response
    {
        $model = new TimesheetConfig();
        $model->setTrackingMode($configuration->getTimesheetTrackingMode());
        $model->setDefaultBeginTime($configuration->getTimesheetDefaultBeginTime());
        $model->setActiveEntriesHardLimit($configuration->getTimesheetActiveEntriesHardLimit());
        $model->setIsAllowFutureTimes($configuration->isTimesheetAllowFutureTimes());
        $model->setIsAllowOverlapping($configuration->isTimesheetAllowOverlappingRecords());

        $view = new View($model, 200);
        $view->getContext()->setGroups(['Default', 'Config']);

        return $this->viewHandler->handle($view);
    }
}
