<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Repository\Query;

use App\Crm\Domain\Entity\Project;

/**
 * Can be used for advanced queries with the: ActivityRepository
 */
class ActivityQuery extends ProjectQuery
{
    public const ACTIVITY_ORDER_ALLOWED = [
        'name',
        'description' => 'comment',
        'activity_number' => 'number',
        'customer',
        'project',
        'budget',
        'timeBudget',
        'visible',
    ];

    /**
     * @var array<Project>
     */
    private array $projects = [];
    private bool $globalsOnly = false;
    private bool $excludeGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setDefaults([
            'orderBy' => 'name',
            'projects' => [],
            'globalsOnly' => false,
            'excludeGlobals' => false,
        ]);
    }

    public function isGlobalsOnly(): bool
    {
        return $this->globalsOnly;
    }

    public function setGlobalsOnly(bool $globalsOnly): self
    {
        $this->globalsOnly = $globalsOnly;

        return $this;
    }

    public function isExcludeGlobals(): bool
    {
        return $this->excludeGlobals;
    }

    public function setExcludeGlobals(bool $excludeGlobals): self
    {
        $this->excludeGlobals = $excludeGlobals;

        return $this;
    }

    public function addProject(Project $project): self
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * @param array<Project> $projects
     * @return $this
     */
    public function setProjects(array $projects): self
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * @return array<Project>
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * @return array<int>
     */
    public function getProjectIds(): array
    {
        return array_values(array_filter(array_unique(array_map(function (Project $project) {
            return $project->getId();
        }, $this->projects)), function ($id) {
            return $id !== null;
        }));
    }

    public function hasProjects(): bool
    {
        return !empty($this->projects);
    }
}
