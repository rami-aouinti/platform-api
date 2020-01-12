<?php
declare(strict_types = 1);
/**
 * /src/Resource/ApiKeyResource.php
 */

namespace App\Resource;

use App\DTO\Interfaces\RestDtoInterface;
use App\Entity\ApiKey as Entity;
use App\Entity\Interfaces\EntityInterface;
use App\Rest\RestResource;
use App\Repository\ApiKeyRepository as Repository;

/**
 * Class ApiKeyResource
 *
 * @package App\Resource
 *
 * @codingStandardsIgnoreStart
 *
 * @method Entity      getReference(string $id): Entity
 * @method Repository  getRepository(): Repository
 * @method Entity[]    find(?array $criteria = null, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null): array
 * @method Entity|null findOne(string $id, ?bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null, ?bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null): EntityInterface
 * @method Entity      update(string $id, RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null): EntityInterface
 * @method Entity      patch(string $id, RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null): EntityInterface
 * @method Entity      delete(string $id, ?bool $flush = null): EntityInterface
 * @method Entity      save(EntityInterface $entity, ?bool $flush = null, ?bool $skipValidation = null): EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class ApiKeyResource extends RestResource
{
    /**
     * Constructor
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->setRepository($repository);
    }
}
