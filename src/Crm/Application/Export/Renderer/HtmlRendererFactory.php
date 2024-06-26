<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Export\Renderer;

use App\Crm\Transport\Activity\ActivityStatisticService;
use App\Crm\Transport\Project\ProjectStatisticService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

final class HtmlRendererFactory
{
    public function __construct(
        private Environment $twig,
        private EventDispatcherInterface $dispatcher,
        private ProjectStatisticService $projectStatisticService,
        private ActivityStatisticService $activityStatisticService
    ) {
    }

    public function create(string $id, string $template): HtmlRenderer
    {
        $renderer = new HtmlRenderer($this->twig, $this->dispatcher, $this->projectStatisticService, $this->activityStatisticService);
        $renderer->setId($id);
        $renderer->setTemplate($template);

        return $renderer;
    }
}
