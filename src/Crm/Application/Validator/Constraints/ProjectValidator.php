<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Validator\Constraints;

use App\Crm\Application\Configuration\SystemConfiguration;
use App\Crm\Application\Validator\Constraints\Project as ProjectEntityConstraint;
use App\Crm\Domain\Entity\Project as ProjectEntity;
use App\Crm\Domain\Repository\ProjectRepository;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ProjectValidator extends ConstraintValidator
{
    /**
     * @param ProjectConstraint[] $constraints
     */
    public function __construct(
        private readonly SystemConfiguration $systemConfiguration,
        private readonly ProjectRepository $projectRepository,
        #[TaggedIterator(ProjectConstraint::class)]
        private iterable $constraints = []
    ) {
    }

    /**
     * @param Project|mixed $value
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!($constraint instanceof ProjectEntityConstraint)) {
            throw new UnexpectedTypeException($constraint, ProjectEntityConstraint::class);
        }

        if (!\is_object($value) || !($value instanceof ProjectEntity)) {
            return;
        }

        if ($value->getStart() !== null && $value->getEnd() !== null && $value->getStart()->getTimestamp() > $value->getEnd()->getTimestamp()) {
            $this->context->buildViolation(ProjectEntityConstraint::getErrorName(ProjectEntityConstraint::END_BEFORE_BEGIN_ERROR))
                ->atPath('end')
                ->setTranslationDomain('validators')
                ->setCode(ProjectEntityConstraint::END_BEFORE_BEGIN_ERROR)
                ->addViolation();
        }

        if ((bool)$this->systemConfiguration->find('project.allow_duplicate_number') === false && (($number = $value->getNumber()) !== null)) {
            foreach (
                $this->projectRepository->findBy([
                    'number' => $number,
                ]) as $tmp
            ) {
                if ($tmp->getId() !== $value->getId()) {
                    $this->context->buildViolation(Project::getErrorName(Project::PROJECT_NUMBER_EXISTING))
                        ->setParameter('%number%', $number)
                        ->atPath('number')
                        ->setTranslationDomain('validators')
                        ->setCode(Project::PROJECT_NUMBER_EXISTING)
                        ->addViolation();
                    break;
                }
            }
        }

        foreach ($this->constraints as $innerConstraint) {
            $this->context
                ->getValidator()
                ->inContext($this->context)
                ->validate($value, $innerConstraint, [Constraint::DEFAULT_GROUP]);
        }
    }
}
