<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\API;

use App\Crm\Transport\API\Model\PageAction;
use App\Crm\Domain\Entity\Activity;
use App\Crm\Domain\Entity\Customer;
use App\Crm\Domain\Entity\Project;
use App\Crm\Domain\Entity\Timesheet;
use App\Crm\Transport\Event\PageActionsEvent;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ActionsController
 *
 * @package App\Crm\Transport\API
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[Route(path: '/actions')]
#[IsGranted('API')]
#[OA\Tag(name: 'Actions')]
final class ActionsController extends BaseApiController
{
    public function __construct(
        private readonly ViewHandlerInterface $viewHandler,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @param PageActionsEvent $event
     * @param string $locale
     * @return array<PageAction>
     */
    private function convertEvent(PageActionsEvent $event, string $locale): array
    {
        $event->setLocale($locale);
        $this->dispatcher->dispatch($event, $event->getEventName());

        $translator = $this->translator;

        $all = [];
        foreach ($event->getActions() as $name => $action) {
            $action = $action === null ? [] : $action;
            $domain = \array_key_exists('translation_domain', $action) ? $action['translation_domain'] : 'messages';
            if (!\array_key_exists('title', $action)) {
                $action['title'] = $translator->trans($name, [], $domain, $locale);
            } else {
                $action['title'] = $translator->trans($action['title'], [], $domain, $locale);
            }
            $all[] = new PageAction($name, $action);
        }

        return $all;
    }

    /**
     * Get all item actions for the given Timesheet [for internal use]
     */
    #[OA\Response(response: 200, description: 'Returns item actions for the timesheet', content: new OA\JsonContent(ref: new Model(type: PageAction::class)))]
    #[OA\Parameter(name: 'id', description: 'Timesheet ID to fetch', in: 'path', required: true)]
    #[OA\Parameter(name: 'view', description: 'View to display the actions at (e.g. index, custom)', in: 'path', required: true)]
    #[OA\Parameter(name: 'locale', description: 'Language to translate the action title to (e.g. de, en)', in: 'path', required: true)]
    #[Route(path: '/timesheet/{id}/{view}/{locale}', name: 'get_timesheet_actions', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getTimesheetActions(Timesheet $timesheet, string $view, string $locale): Response
    {
        $event = new PageActionsEvent($this->getUser(), ['timesheet' => $timesheet], 'timesheet', $view);
        $actions = $this->convertEvent($event, $locale);

        $view = new View($actions, 200);

        return $this->viewHandler->handle($view);
    }

    /**
     * Get all item actions for the given Activity [for internal use]
     */
    #[OA\Response(response: 200, description: 'Returns item actions for the activity', content: new OA\JsonContent(ref: new Model(type: PageAction::class)))]
    #[OA\Parameter(name: 'id', description: 'Activity ID to fetch', in: 'path', required: true)]
    #[OA\Parameter(name: 'view', description: 'View to display the actions at (e.g. index, custom)', in: 'path', required: true)]
    #[OA\Parameter(name: 'locale', description: 'Language to translate the action title to (e.g. de, en)', in: 'path', required: true)]
    #[Route(path: '/activity/{id}/{view}/{locale}', name: 'get_activity_actions', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getActivityActions(Activity $activity, string $view, string $locale): Response
    {
        $event = new PageActionsEvent($this->getUser(), ['activity' => $activity], 'activity', $view);
        $actions = $this->convertEvent($event, $locale);

        $view = new View($actions, 200);

        return $this->viewHandler->handle($view);
    }

    /**
     * Get all item actions for the given Project [for internal use]
     */
    #[OA\Response(response: 200, description: 'Returns item actions for the project', content: new OA\JsonContent(ref: new Model(type: PageAction::class)))]
    #[OA\Parameter(name: 'id', description: 'Project ID to fetch', in: 'path', required: true)]
    #[OA\Parameter(name: 'view', description: 'View to display the actions at (e.g. index, custom)', in: 'path', required: true)]
    #[OA\Parameter(name: 'locale', description: 'Language to translate the action title to (e.g. de, en)', in: 'path', required: true)]
    #[Route(path: '/project/{id}/{view}/{locale}', name: 'get_project_actions', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getProjectActions(Project $project, string $view, string $locale): Response
    {
        $event = new PageActionsEvent($this->getUser(), ['project' => $project], 'project', $view);
        $actions = $this->convertEvent($event, $locale);

        $view = new View($actions, 200);

        return $this->viewHandler->handle($view);
    }

    /**
     * Get all item actions for the given Customer [for internal use]
     */
    #[OA\Response(response: 200, description: 'Returns item actions for the customer', content: new OA\JsonContent(ref: new Model(type: PageAction::class)))]
    #[OA\Parameter(name: 'id', description: 'Customer ID to fetch', in: 'path', required: true)]
    #[OA\Parameter(name: 'view', description: 'View to display the actions at (e.g. index, custom)', in: 'path', required: true)]
    #[OA\Parameter(name: 'locale', description: 'Language to translate the action title to (e.g. de, en)', in: 'path', required: true)]
    #[Route(path: '/customer/{id}/{view}/{locale}', name: 'get_customer_actions', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getCustomerActions(Customer $customer, string $view, string $locale): Response
    {
        $event = new PageActionsEvent($this->getUser(), ['customer' => $customer], 'customer', $view);
        $actions = $this->convertEvent($event, $locale);

        $view = new View($actions, 200);

        return $this->viewHandler->handle($view);
    }
}
