<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Command;

use App\User\Application\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @package App\Crm\Transport\Command
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsCommand(name: 'kimai:user:deactivate')]
final class DeactivateUserCommand extends Command
{
    public function __construct(
        private readonly UserService $userService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Deactivate a user')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
            ])
            ->setHelp(
                <<<'EOT'
                    The <info>kimai:user:deactivate</info> command deactivates a user (will not be able to log in)

                      <info>php %command.full_name% susan_super</info>
                    EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $user = $this->userService->findUserByUsernameOrThrowException($username);

        $io = new SymfonyStyle($input, $output);

        if ($user->isEnabled()) {
            $user->setEnabled(false);
            $this->userService->saveUser($user);
            $io->success(sprintf('User "%s" has been deactivated.', $username));
        } else {
            $io->warning(sprintf('User "%s" is already deactivated.', $username));
        }

        return Command::SUCCESS;
    }
}
