<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export\Base;

use App\Crm\Application\Twig\SecurityPolicy\ExportPolicy;
use App\Crm\Domain\Entity\ExportableItem;
use App\Crm\Domain\Entity\MetaTableTypeInterface;
use App\Crm\Domain\Repository\Query\CustomerQuery;
use App\Crm\Domain\Repository\Query\TimesheetQuery;
use App\Crm\Transport\Activity\ActivityStatisticService;
use App\Crm\Transport\Event\ActivityMetaDisplayEvent;
use App\Crm\Transport\Event\CustomerMetaDisplayEvent;
use App\Crm\Transport\Event\MetaDisplayEventInterface;
use App\Crm\Transport\Event\ProjectMetaDisplayEvent;
use App\Crm\Transport\Event\TimesheetMetaDisplayEvent;
use App\Crm\Transport\Event\UserPreferenceDisplayEvent;
use App\Crm\Transport\Project\ProjectStatisticService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Extension\SandboxExtension;

class HtmlRenderer
{
    use RendererTrait;

    /**
     * @var string
     */
    private $id = 'html';
    /**
     * @var string
     */
    private $template = 'default.html.twig';

    public function __construct(
        protected Environment $twig,
        protected EventDispatcherInterface $dispatcher,
        private ProjectStatisticService $projectStatisticService,
        private ActivityStatisticService $activityStatisticService
    ) {
    }

    /**
     * @param ExportableItem[] $timesheets
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(array $timesheets, TimesheetQuery $query): Response
    {
        /** @var CustomerQuery $customerQuery */
        $customerQuery = $query->copyTo(new CustomerQuery());

        $timesheetMetaFields = $this->findMetaColumns(new TimesheetMetaDisplayEvent($query, TimesheetMetaDisplayEvent::EXPORT));
        $customerMetaFields = $this->findMetaColumns(new CustomerMetaDisplayEvent($customerQuery, CustomerMetaDisplayEvent::EXPORT));
        $projectMetaFields = $this->findMetaColumns(new ProjectMetaDisplayEvent($query, ProjectMetaDisplayEvent::EXPORT));
        $activityMetaFields = $this->findMetaColumns(new ActivityMetaDisplayEvent($query, ActivityMetaDisplayEvent::EXPORT));

        $event = new UserPreferenceDisplayEvent(UserPreferenceDisplayEvent::EXPORT);
        $this->dispatcher->dispatch($event);
        $userPreferences = $event->getPreferences();

        $summary = $this->calculateSummary($timesheets);

        // enable basic security measures
        $sandbox = new SandboxExtension(new ExportPolicy());
        $sandbox->enableSandbox();
        $this->twig->addExtension($sandbox);

        $content = $this->twig->render($this->getTemplate(), array_merge([
            'entries' => $timesheets,
            'query' => $query,
            'summaries' => $summary,
            'budgets' => $this->calculateProjectBudget($timesheets, $query, $this->projectStatisticService),
            'activity_budgets' => $this->calculateActivityBudget($timesheets, $query, $this->activityStatisticService),
            'timesheetMetaFields' => $timesheetMetaFields,
            'customerMetaFields' => $customerMetaFields,
            'projectMetaFields' => $projectMetaFields,
            'activityMetaFields' => $activityMetaFields,
            'userPreferences' => $userPreferences,
        ], $this->getOptions($query)));

        $response = new Response();
        $response->setContent($content);

        return $response;
    }

    public function setTemplate(string $filename): self
    {
        $this->template = $filename;

        return $this;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return MetaTableTypeInterface[]
     */
    protected function findMetaColumns(MetaDisplayEventInterface $event): array
    {
        $this->dispatcher->dispatch($event);

        return $event->getFields();
    }

    protected function getOptions(TimesheetQuery $query): array
    {
        $decimal = false;
        if ($query->getCurrentUser() !== null) {
            $decimal = $query->getCurrentUser()->isExportDecimal();
        } elseif ($query->getUser() !== null) {
            $decimal = $query->getUser()->isExportDecimal();
        }

        return [
            'decimal' => $decimal,
        ];
    }

    protected function getTemplate(): string
    {
        return '@export/' . $this->template;
    }
}
