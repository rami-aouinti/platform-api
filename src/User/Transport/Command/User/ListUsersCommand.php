<?php

declare(strict_types=1);

namespace App\User\Transport\Command\User;

use App\General\Transport\Command\Traits\SymfonyStyleTrait;
use App\Role\Application\Security\RolesService;
use App\User\Application\Resource\UserResource;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserGroup;
use Closure;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function array_map;
use function implode;
use function sprintf;

/**
 * @package App\User
 */
#[AsCommand(
    name: self::NAME,
    description: 'Console command to list users',
)]
class ListUsersCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'user:list';

    /**
     * Constructor
     *
     * @throws LogicException
     */
    public function __construct(
        private readonly UserResource $userResource,
        private readonly RolesService $roles,
    ) {
        parent::__construct();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * {@inheritdoc}
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);
        $headers = [
            'Id',
            'Username',
            'Email',
            'Full name',
            'Roles (inherited)',
            'Groups',
        ];
        $io->title('Current users');
        $io->table($headers, $this->getRows());

        return 0;
    }

    /**
     * Getter method for formatted user rows for console table.
     *
     * @throws Throwable
     *
     * @return array<int, string>
     */
    private function getRows(): array
    {
        return array_map($this->getFormatterUser(), $this->userResource->find(orderBy: [
            'username' => 'ASC',
        ]));
    }

    /**
     * Getter method for user formatter closure. This closure will format single User entity for console table.
     */
    private function getFormatterUser(): Closure
    {
        $userGroupFormatter = static fn (UserGroup $userGroup): string => sprintf(
            '%s (%s)',
            $userGroup->getName(),
            $userGroup->getRole()->getId(),
        );

        return fn (User $user): array => [
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getFirstName() . ' ' . $user->getLastName(),
            implode(",\n", $this->roles->getInheritedRoles($user->getRoles())),
            implode(",\n", $user->getUserGroups()->map($userGroupFormatter)->toArray()),
        ];
    }
}
