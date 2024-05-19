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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @package App\Crm\Transport\Command
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
abstract class AbstractRoleCommand extends Command
{
    public function __construct(
        private UserService $userService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('role', InputArgument::OPTIONAL, 'The role'),
                new InputOption('super', null, InputOption::VALUE_NONE, 'Instead specifying role, use this to quickly add the super administrator role'),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');
        $super = ($input->getOption('super') === true);

        if ($role !== null && $super) {
            throw new \InvalidArgumentException('You can pass either the role or the --super option (but not both simultaneously).');
        }

        if ($role === null && !$super) {
            throw new \RuntimeException('Not enough arguments, pass a role or use --super.');
        }

        $user = $this->userService->findUserByUsernameOrThrowException($username);

        $this->executeRoleCommand($this->userService, new SymfonyStyle($input, $output), $user, $super, $role);

        return Command::SUCCESS;
    }

    abstract protected function executeRoleCommand(UserService $manipulator, SymfonyStyle $output, User $user, bool $super, $role): void;
}
