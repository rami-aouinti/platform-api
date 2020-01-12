<?php
declare(strict_types = 1);
/**
 * /src/App/Validator/Constraints/EntityReferenceExistsValidator.php
 */

namespace App\Validator\Constraints;

use App\Entity\Interfaces\EntityInterface;
use App\Utils\Traits\LoggerAware;
use Closure;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class EntityReferenceExistsValidator
 *
 * @package App\Validator\Constraints
 */
class EntityReferenceExistsValidator extends ConstraintValidator
{
    // Traits
    use LoggerAware;

    /**
     * Checks if the passed value is valid.
     *
     * @param EntityInterface|array|mixed $value The value that should be validated
     * @param Constraint                  $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityReferenceExists) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\EntityReferenceExists');
        }

        /** @var array<int, EntityInterface> $values */
        $values = $this->normalize($constraint->entityClass, $value);
        $this->check($values);
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param string                      $target Target class to check
     * @param EntityInterface|array|mixed $input  The value that should be validated
     *
     * @return array
     */
    private function normalize(string $target, $input): array
    {
        $values = is_array($input) ? $input : [$input];

        foreach ($values as $value) {
            if (!$value instanceof $target) {
                throw new UnexpectedValueException($value, $target);
            }

            if (!$value instanceof EntityInterface) {
                throw new UnexpectedValueException($value, EntityInterface::class);
            }
        }

        return $values;
    }

    /**
     * @param array $entities
     */
    private function check(array $entities): void
    {
        $invalidIds = $this->getInvalidValues($entities);

        if (count($invalidIds) > 0) {
            $message = count($invalidIds) === 1
                ? EntityReferenceExists::MESSAGE_SINGLE
                : EntityReferenceExists::MESSAGE_MULTIPLE;
            $entity = get_class($entities[0]);
            $parameterEntity = str_replace('Proxies\\__CG__\\', '', $entity);
            $parameterId = count($invalidIds) > 1 ? implode('", "', $invalidIds) : (string)$invalidIds[0];
            $this->context
                ->buildViolation($message)
                ->setParameter('{{ entity }}', $parameterEntity)
                ->setParameter('{{ id }}', $parameterId)
                ->setCode(EntityReferenceExists::ENTITY_REFERENCE_EXISTS_ERROR)
                ->addViolation();
        }
    }

    /**
     * @param array<int, EntityInterface> $entities
     *
     * @return array<int, string>
     */
    private function getInvalidValues(array $entities): array
    {
        $iterator = static function (EntityInterface $entity): string {
            return $entity->getId();
        };

        return array_map($iterator, array_filter($entities, $this->getFilterClosure()));
    }

    /**
     * Method to return used filter closure.
     *
     * @return Closure
     */
    private function getFilterClosure(): Closure
    {
        return function (EntityInterface $entity): bool {
            $output = false;

            try {
                $entity->getCreatedAt();
            } catch (EntityNotFoundException $exception) {
                $this->logger->error($exception->getMessage());
                $output = true;
            }

            return $output;
        };
    }
}
