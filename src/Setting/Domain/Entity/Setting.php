<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Setting\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Setting\Domain\Repository\SettingRepository;
use App\User\Domain\Entity\Traits\Blameable;
use App\User\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

/**
 * Defines the properties of the Post entity to represent the blog posts.
 *
 * See https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See https://symfony.com/doc/current/doctrine/reverse_engineering.html
 *
 * @author Rami Aouinti <rami.aouinti@gmail.com>
 */
#[ORM\Entity(repositoryClass: SettingRepository::class)]
#[ORM\Table(name: 'platform_setting')]
class Setting
{
    use Blameable;
    use Timestampable;
    use Uuid;

    final public const SET_SETTING = 'set.Setting';

    #[ORM\Id]
    #[ORM\Column(
        name: 'id',
        type: UuidBinaryOrderedTimeType::NAME,
        unique: true,
        nullable: false,
    )]
    #[Groups([
        'Setting',
        'Setting.id',

        self::SET_SETTING,
    ])]
    private UuidInterface $id;

    #[ORM\Column(
        name: 'title',
        type: Types::STRING
    )]
    #[Assert\NotBlank]
    #[Groups([
        'Setting',
        'Setting.title',

        self::SET_SETTING,
    ])]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups([
        'Setting',
        'Setting.logo',

        self::SET_SETTING,
    ])]
    private ?string $logo = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\Length(max: 255)]
    #[Groups([
        'Setting',
        'Setting.drawer',

        self::SET_SETTING,
    ])]
    private ?string $drawer = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\Length(max: 255)]
     #[Groups([
        'Setting',
        'Setting.sidebarColor',

        self::SET_SETTING,
    ])]
    private ?string $sidebarColor = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\Length(max: 255)]
    #[Groups([
        'Setting',
        'Setting.sidebarTheme',

        self::SET_SETTING,
    ])]
    private ?string $sidebarTheme = null;

    #[ORM\Column]
    #[Groups([
        'Setting',
        'Setting.publishedAt',

        self::SET_SETTING,
    ])]
    private DateTimeImmutable $publishedAt;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * @throws Throwable
     */
    public function __construct()
    {
        $this->id = $this->createUuid();
        $this->publishedAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public  function getLogo(): ?string
    {
        return $this->logo;
    }

    public  function setLogo(?string $logo):void
    {
        $this->logo = $logo;
    }

    public  function getDrawer(): ?string
    {
        return $this->drawer;
    }

    public  function setDrawer(?string $drawer):void
    {
        $this->drawer = $drawer;
    }

    public  function getSidebarColor(): ?string
    {
        return $this->sidebarColor;
    }

    public  function setSidebarColor(?string $sidebarColor):void
    {
        $this->sidebarColor = $sidebarColor;
    }

    public  function getSidebarTheme(): ?string
    {
        return $this->sidebarTheme;
    }

    public  function setSidebarTheme(?string $sidebarTheme):void
    {
        $this->sidebarTheme = $sidebarTheme;
    }

    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }
}
