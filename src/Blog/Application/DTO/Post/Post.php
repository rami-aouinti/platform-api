<?php

declare(strict_types=1);

namespace App\Blog\Application\DTO\Post;

use App\Blog\Domain\Entity\Post as Entity;
use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package App\Post
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Post extends RestDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $title = '';

    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->setVisited('title');
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param EntityInterface|Entity $entity
     */
    public function load(EntityInterface $entity): self
    {
        if ($entity instanceof Entity) {
            $this->id = $entity->getId();
            $this->title = $entity->getTitle();
        }

        return $this;
    }
}
