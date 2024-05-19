<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Command;

use App\Crm\Constants;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class VersionCommand
 *
 * @package App\Crm\Transport\Command
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsCommand(name: 'kimai:version')]
final class VersionCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Receive version information')
            ->setHelp('This command allows you to fetch various version information about Kimai.')
            ->addOption('short', null, InputOption::VALUE_NONE, 'Display the version only')
            ->addOption('number', null, InputOption::VALUE_NONE, 'Display the version identifier only only')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('short')) {
            $io->writeln(Constants::VERSION);

            return Command::SUCCESS;
        }

        if ($input->getOption('number')) {
            $io->writeln((string) Constants::VERSION_ID);

            return Command::SUCCESS;
        }

        $io->writeln(sprintf('%s <info>%s</info> by Kevin Papst.', Constants::SOFTWARE, Constants::VERSION));

        return Command::SUCCESS;
    }
}
