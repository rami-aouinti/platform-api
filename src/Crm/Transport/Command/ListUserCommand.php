<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Command;

use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ListUserCommand
 *
 * @package App\Crm\Transport\Command
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsCommand(name: 'kimai:user:list', description: 'List all users')]
final class ListUserCommand extends Command
{
    public function __construct(private readonly UserRepository $repository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output = new SymfonyStyle($input, $output);
        $users = $this->repository->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                $user->getUserIdentifier(),
                $user->getEmail(),
                implode(', ', $user->getRoles()),
                $user->isEnabled() ? 'X' : '',
                $user->getPasswordRequestedAt()?->format('Y-m-d H:i:s')
            ];
        }

        $header = ['Username', 'Email', 'Roles', 'Active', 'PW Reset'];

        $output->table($header, $data);

        return Command::SUCCESS;
    }
}
