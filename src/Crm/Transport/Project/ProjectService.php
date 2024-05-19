<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Project;

use App\Crm\Transport\Configuration\SystemConfiguration;
use App\Crm\Domain\Entity\Customer;
use App\Crm\Domain\Entity\Project;
use App\Crm\Transport\Event\ProjectCreateEvent;
use App\Crm\Transport\Event\ProjectCreatePostEvent;
use App\Crm\Transport\Event\ProjectCreatePreEvent;
use App\Crm\Transport\Event\ProjectMetaDefinitionEvent;
use App\Crm\Transport\Event\ProjectUpdatePostEvent;
use App\Crm\Transport\Event\ProjectUpdatePreEvent;
use App\Crm\Domain\Repository\ProjectRepository;
use App\Crm\Application\Utils\Context;
use App\Crm\Application\Utils\NumberGenerator;
use App\Crm\Application\Validator\ValidationFailedException;
use InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @final
 */
final class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $repository,
        private readonly SystemConfiguration $configuration,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly ValidatorInterface $validator
    )
    {
    }

    public function createNewProject(?Customer $customer = null): Project
    {
        $project = new Project();
        $project->setNumber($this->calculateNextProjectNumber());

        if ($customer !== null) {
            $project->setCustomer($customer);
        }

        $this->dispatcher->dispatch(new ProjectMetaDefinitionEvent($project));
        $this->dispatcher->dispatch(new ProjectCreateEvent($project));

        return $project;
    }

    public function saveNewProject(Project $project, ?Context $context = null): Project
    {
        if (null !== $project->getId()) {
            throw new InvalidArgumentException('Cannot create project, already persisted');
        }

        $this->validateProject($project);

        if ($context !== null && $this->configuration->isProjectCopyTeamsOnCreate()) {
            foreach ($context->getUser()->getTeams() as $team) {
                $project->addTeam($team);
                $team->addProject($project);
            }
        }

        $this->dispatcher->dispatch(new ProjectCreatePreEvent($project));
        $this->repository->saveProject($project);
        $this->dispatcher->dispatch(new ProjectCreatePostEvent($project));

        return $project;
    }

    /**
     * @param Project $project
     * @param string[] $groups
     * @throws ValidationFailedException
     */
    private function validateProject(Project $project, array $groups = []): void
    {
        $errors = $this->validator->validate($project, null, $groups);

        if ($errors->count() > 0) {
            throw new ValidationFailedException($errors, 'Validation Failed');
        }
    }

    public function updateProject(Project $project): Project
    {
        $this->validateProject($project);

        $this->dispatcher->dispatch(new ProjectUpdatePreEvent($project));
        $this->repository->saveProject($project);
        $this->dispatcher->dispatch(new ProjectUpdatePostEvent($project));

        return $project;
    }

    public function findProjectByName(string $name): ?Project
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function findProjectByNumber(string $number): ?Project
    {
        return $this->repository->findOneBy(['number' => $number]);
    }

    private function calculateNextProjectNumber(): ?string
    {
        $format = $this->configuration->find('project.number_format');
        if (empty($format) || !\is_string($format)) {
            return null;
        }

        // we cannot use max(number) because a varchar column returns unexpected results
        $start = $this->repository->countProject();
        $i = 0;

        do {
            $start++;

            $numberGenerator = new NumberGenerator($format, function (string $originalFormat, string $format, int $increaseBy) use ($start): string|int {
                return match ($format) {
                    'pc' => $start + $increaseBy,
                    default => $originalFormat,
                };
            });

            $number = $numberGenerator->getNumber();
            $project = $this->findProjectByNumber($number);
        } while ($project !== null && $i++ < 100);

        if ($project !== null) {
            return null;
        }

        return $number;
    }
}
