<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Command;

use App\User\Application\Service\UserService;
use App\User\Domain\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @package App\Crm\Transport\Command
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsCommand(name: 'kimai:user:promote')]
final class PromoteUserCommand extends AbstractRoleCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDescription('Promotes a user by adding a role')
            ->setHelp(
                <<<'EOT'
                    The <info>kimai:user:promote</info> command promotes a user by adding a role

                      <info>php %command.full_name% susan_super ROLE_TEAMLEAD</info>
                      <info>php %command.full_name% --super susan_super</info>
                    EOT
            );
    }

    protected function executeRoleCommand(UserService $manipulator, SymfonyStyle $output, User $user, bool $super, $role): void
    {
        $username = $user->getUserIdentifier();
        if ($super) {
            if (!$user->isSuperAdmin()) {
                $user->setSuperAdmin(true);
                $manipulator->saveUser($user);
                $output->success(sprintf('User "%s" has been promoted as a super administrator.', $username));
            } else {
                $output->warning(sprintf('User "%s" does already have the super administrator role.', $username));
            }
        } else {
            if (!$user->hasRole($role)) {
                $user->addRole($role);
                $manipulator->saveUser($user);
                $output->success(sprintf('Role "%s" has been added to user "%s".', $role, $username));
            } else {
                $output->warning(sprintf('User "%s" did already have "%s" role.', $username, $role));
            }
        }
    }
}
