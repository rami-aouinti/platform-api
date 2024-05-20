<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Crm\Domain\Entity\Team;
use App\Crm\Domain\Entity\TeamMember;
use App\Crm\Domain\Entity\UserPreference;
use App\General\Domain\Doctrine\DBAL\Types\Types as AppTypes;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\General\Domain\Enum\Language;
use App\General\Domain\Enum\Locale;
use App\Quiz\Domain\Entity\Category;
use App\Quiz\Domain\Entity\Group;
use App\Quiz\Domain\Entity\Question;
use App\Quiz\Domain\Entity\Quiz;
use App\Quiz\Domain\Entity\Workout;
use App\Tool\Domain\Service\Interfaces\LocalizationServiceInterface;
use App\User\Domain\Entity\Enum\SexEnum;
use App\User\Domain\Entity\Interfaces\UserGroupAwareInterface;
use App\User\Domain\Entity\Interfaces\UserInterface;
use App\User\Domain\Entity\Traits\Blameable;
use App\User\Domain\Entity\Traits\UserRelations;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

/**
 * @package App\User
 */
#[ORM\Entity]
#[ORM\Table(name: 'platform_user')]
#[ORM\UniqueConstraint(
    name: 'uq_username',
    columns: ['username'],
)]
#[ORM\UniqueConstraint(
    name: 'uq_email',
    columns: ['email'],
)]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[AssertCollection\UniqueEntity('email')]
#[AssertCollection\UniqueEntity('username')]
class User implements EntityInterface, UserInterface, UserGroupAwareInterface, \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface
{
    use Blameable;
    use Timestampable;
    use UserRelations;
    use Uuid;

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_TEAMLEAD = 'ROLE_TEAMLEAD';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public const DEFAULT_ROLE = self::ROLE_USER;
    public const DEFAULT_LANGUAGE = 'en';
    public const DEFAULT_FIRST_WEEKDAY = 'monday';

    public const AUTH_INTERNAL = 'kimai';
    public const AUTH_LDAP = 'ldap';
    public const AUTH_SAML = 'saml';

    public const WIZARDS = ['intro', 'profile'];

    final public const SET_USER_PROFILE = 'set.UserProfile';
    final public const SET_USER_BASIC = 'set.UserBasic';

    final public const PASSWORD_MIN_LENGTH = 8;

    #[ORM\Id]
    #[ORM\Column(
        name: 'id',
        type: UuidBinaryOrderedTimeType::NAME,
        unique: true,
        nullable: false,
    )]
    #[Groups([
        'User',
        'User.id',

        'LogLogin.user',
        'LogLoginFailure.user',
        'LogRequest.user',

        'UserGroup.users',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private UuidInterface $id;

    #[ORM\Column(
        name: 'username',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'User',
        'User.username',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $username = '';

    #[ORM\Column(
        name: 'first_name',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'User',
        'User.firstName',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $firstName = '';

    #[ORM\Column(
        name: 'last_name',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'User',
        'User.lastName',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $lastName = '';

    #[ORM\Column(
        name: 'email',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'User',
        'User.email',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email]
    private string $email = '';

    #[ORM\Column(
        name: 'phone',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'User',
        'User.phone',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $phone = '';

    #[ORM\Column(
        name: 'title',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'User',
        'User.title',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $title = '';

    #[ORM\Column(
        name: 'description',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'User',
        'User.description',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $description = '';

    #[ORM\Column(
        name: 'birthday',
        type: 'datetime',
        nullable: true
    )]
    #[Groups([
        'User',
        'User.birthday',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?DateTimeInterface $birthday = null;

    #[ORM\Column(
        name: 'image',
        type: 'string',
        length: 255,
        nullable: true
    )]
    #[Groups([
        'User',
        'User.image',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private ?string $image = null;

    #[ORM\Column(
        name: 'sex',
        type: 'string',
        enumType: SexEnum::class
    )]
    #[Groups([
        'User',
        'User.sex',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private SexEnum $sex;

    #[ORM\Embedded(
        class: Address::class
    )]
    #[Groups([
        'User',
        'User.address',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private Address $address;

    #[ORM\Column(
        type: 'string',
        nullable: true
    )]
    #[Groups([
        'User',
        'User.googleId',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private ?string $googleId = null;

    #[ORM\Column(
        type: 'string',
        nullable: true
    )]
    #[Groups([
        'User',
        'User.facebookId',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private ?string $facebookId = null;

    #[ORM\Column(
        type: 'string',
        nullable: true
    )]
    #[Groups([
        'User',
        'User.instagramId',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private ?string $instagramId = null;

    #[ORM\Column(
        type: 'string',
        nullable: true
    )]
    #[Groups([
        'User',
        'User.twitterId',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    private ?string $twitterId = null;

    #[ORM\Column(
        name: 'language',
        type: AppTypes::ENUM_LANGUAGE,
        nullable: false,
        options: [
            'comment' => 'User language for translations',
        ],
    )]
    #[Groups([
        'User',
        'User.language',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private Language $language;

    #[ORM\Column(
        name: 'locale',
        type: AppTypes::ENUM_LOCALE,
        nullable: false,
        options: [
            'comment' => 'User locale for number, time, date, etc. formatting.',
        ],
    )]
    #[Groups([
        'User',
        'User.locale',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private Locale $locale;

    #[ORM\Column(
        name: 'timezone',
        type: Types::STRING,
        length: 255,
        nullable: false,
        options: [
            'comment' => 'User timezone which should be used to display time, date, etc.',
            'default' => LocalizationServiceInterface::DEFAULT_TIMEZONE,
        ],
    )]
    #[Groups([
        'User',
        'User.timezone',

        self::SET_USER_PROFILE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $timezone = LocalizationServiceInterface::DEFAULT_TIMEZONE;

    #[ORM\Column(
        name: 'password',
        type: Types::STRING,
        length: 255,
        nullable: false,
        options: [
            'comment' => 'Hashed password',
        ],
    )]
    private string $password = '';

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @see UserEntityEventListener
     */
    private string $plainPassword = '';

    /**
     * User preferences
     *
     * List of preferences for this user, required ones have dedicated fields/methods
     *
     * This Collection can be null for one edge case ONLY:
     * if a currently logged-in user will be deleted and then refreshed from the session from one of the UserProvider
     * e.g. see LdapUserProvider::refreshUser() it might crash if $user->getPreferenceValue() is called
     *
     * @var Collection<UserPreference>|null
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserPreference::class, cascade: ['persist'])]
    private ?Collection $preferences = null;
    /**
     * List of all team memberships.
     *
     * @var Collection<TeamMember>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TeamMember::class, cascade: ['persist'], fetch: 'LAZY', orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Serializer\Expose]
    #[Serializer\Groups(['User_Entity'])]
    #[OA\Property(type: 'array', items: new OA\Items(ref: '#/components/schemas/TeamMembership'))]
    private Collection $memberships;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: Workout::class, orphanRemoval: true)]
    private Collection $workouts;

    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: Quiz::class, orphanRemoval: true)]
    private Collection $quizzes;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?\App\Quiz\Domain\Entity\Language $prefered_language = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $organization_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $organization_label = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $account_type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $login_type = null;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'platform_quiz_user_group')]
    private ?Collection $groups;

    #[ORM\Column(nullable: true)]
    private ?bool $toReceiveMyResultByEmail = null;

    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: Question::class)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: Category::class)]
    private Collection $categories;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $current_school_year = null;

    #[ORM\Column(nullable: true)]
    private ?int $ed_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastQuizAccess = null;

    /**
     * Constructor
     *
     * @throws Throwable
     */
    public function __construct()
    {
        $this->id = $this->createUuid();
        $this->language = Language::getDefault();
        $this->locale = Locale::getDefault();
        $this->userGroups = new ArrayCollection();
        $this->logsRequest = new ArrayCollection();
        $this->logsLogin = new ArrayCollection();
        $this->logsLoginFailure = new ArrayCollection();
        $this->workouts = new ArrayCollection();
        $this->quizzes = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }
    public function setFacebookId(?string $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }
    public function getInstagramId(): ?string
    {
        return $this->instagramId;
    }
    public function setInstagramId(?string $instagramId): self
    {
        $this->instagramId = $instagramId;

        return $this;
    }
    public function getTwitterId(): ?string
    {
        return $this->twitterId;
    }
    public function setTwitterId(?string $twitterId): self
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }
    public function setBirthday(?DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getSex(): SexEnum
    {
        return $this->sex;
    }
    public function setSex(SexEnum $sex): self
    {
        $this->sex = $sex;

        return $this;
    }
    public function getAddress(): Address
    {
        return $this->address;
    }
    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }
    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function setLocale(Locale $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(callable $encoder, string $plainPassword): self
    {
        $this->password = (string)$encoder($plainPassword);

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        if ($plainPassword !== '') {
            $this->plainPassword = $plainPassword;

            // Change some mapped values so preUpdate will get called - just blank it out
            $this->password = '';
        }

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = '';
    }

    /**
     * Read-only list of all visible user preferences.
     *
     * @internal only for API usage
     * @return UserPreference[]
     */
    #[Serializer\VirtualProperty]
    #[Serializer\SerializedName('preferences')]
    #[Serializer\Groups(['User_Entity'])]
    #[OA\Property(type: 'array', items: new OA\Items(ref: '#/components/schemas/UserPreference'))]
    public function getVisiblePreferences(): array
    {
        // hide all internal preferences, which are either available in other fields
        // or which are only used within the Kimai UI
        $skip = [
            UserPreference::TIMEZONE,
            UserPreference::LOCALE,
            UserPreference::LANGUAGE,
            UserPreference::SKIN,
            'calendar_initial_view',
            'login_initial_view',
            'update_browser_title',
            'daily_stats',
            'export_decimal',
        ];

        $all = [];
        foreach ($this->preferences as $preference) {
            if ($preference->isEnabled() && !\in_array($preference->getName(), $skip)) {
                $all[] = $preference;
            }
        }

        return $all;
    }

    /**
     * @return Collection<UserPreference>
     */
    public function getPreferences(): Collection
    {
        return $this->preferences;
    }

    /**
     * @param iterable<UserPreference> $preferences
     */
    public function setPreferences(iterable $preferences): self
    {
        $this->preferences = new ArrayCollection();

        foreach ($preferences as $preference) {
            $this->addPreference($preference);
        }

        return $this;
    }

    /**
     * @param bool|int|float|string|null $default
     */
    public function getPreferenceValue(string $name, mixed $default = null, bool $allowNull = true): bool|int|float|string|null
    {
        $preference = $this->getPreference($name);
        if ($preference === null) {
            return $default;
        }

        $value = $preference->getValue();

        return $allowNull ? $value : ($value ?? $default);
    }

    public function getPreference(string $name): ?UserPreference
    {
        if ($this->preferences === null) {
            return null;
        }

        foreach ($this->preferences as $preference) {
            if ($preference->matches($name)) {
                return $preference;
            }
        }

        return null;
    }

    public function addPreference(UserPreference $preference): self
    {
        if ($this->preferences === null) {
            $this->preferences = new ArrayCollection();
        }

        $this->preferences->add($preference);
        $preference->setUser($this);

        return $this;
    }

    public function addMembership(TeamMember $member): void
    {
        if ($this->memberships->contains($member)) {
            return;
        }

        if ($member->getUser() === null) {
            $member->setUser($this);
        }

        if ($member->getUser() !== $this) {
            throw new \InvalidArgumentException('Cannot set foreign user membership');
        }

        // when using the API an invalid Team ID triggers the validation too late
        $team = $member->getTeam();
        if (($team) === null) {
            return;
        }

        if ($this->findMemberByTeam($team) !== null) {
            return;
        }

        $this->memberships->add($member);
        $team->addMember($member);
    }

    public function removeMembership(TeamMember $member): void
    {
        if (!$this->memberships->contains($member)) {
            return;
        }

        $this->memberships->removeElement($member);
        if ($member->getTeam() !== null) {
            $member->getTeam()->removeMember($member);
        }
        $member->setUser(null);
        $member->setTeam(null);
    }

    /**
     * @return Collection<TeamMember>
     */
    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function hasMembership(TeamMember $member): bool
    {
        return $this->memberships->contains($member);
    }

    public function getWorkouts(): Collection
    {
        return $this->workouts;
    }

    public function setWorkouts(Collection $workouts): void
    {
        $this->workouts = $workouts;
    }

    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function setQuizzes(Collection $quizzes): void
    {
        $this->quizzes = $quizzes;
    }

    public function getPreferedLanguage(): ?\App\Quiz\Domain\Entity\Language
    {
        return $this->prefered_language;
    }

    public function setPreferedLanguage(?\App\Quiz\Domain\Entity\Language $prefered_language): void
    {
        $this->prefered_language = $prefered_language;
    }

    public function getOrganizationCode(): ?string
    {
        return $this->organization_code;
    }

    public function setOrganizationCode(?string $organization_code): void
    {
        $this->organization_code = $organization_code;
    }

    public function getOrganizationLabel(): ?string
    {
        return $this->organization_label;
    }

    public function setOrganizationLabel(?string $organization_label): void
    {
        $this->organization_label = $organization_label;
    }

    public function getAccountType(): ?string
    {
        return $this->account_type;
    }

    public function setAccountType(?string $account_type): void
    {
        $this->account_type = $account_type;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getGroups(): ?Collection
    {
        return $this->groups;
    }

    public function setGroups(?Collection $groups): void
    {
        $this->groups = $groups;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function setQuestions(Collection $questions): void
    {
        $this->questions = $questions;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }

    public function getCurrentSchoolYear(): ?string
    {
        return $this->current_school_year;
    }

    public function setCurrentSchoolYear(?string $current_school_year): void
    {
        $this->current_school_year = $current_school_year;
    }

    public function getEdId(): ?int
    {
        return $this->ed_id;
    }

    public function setEdId(?int $ed_id): void
    {
        $this->ed_id = $ed_id;
    }

    public function getLastQuizAccess(): ?DateTimeInterface
    {
        return $this->lastQuizAccess;
    }

    public function setLastQuizAccess(?DateTimeInterface $lastQuizAccess): void
    {
        $this->lastQuizAccess = $lastQuizAccess;
    }

    private function findMemberByTeam(Team $team): ?TeamMember
    {
        foreach ($this->memberships as $member) {
            if ($member->getTeam() === $team) {
                return $member;
            }
        }

        return null;
    }
}
