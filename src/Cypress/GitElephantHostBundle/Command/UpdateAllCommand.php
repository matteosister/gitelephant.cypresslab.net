<?php
/**
 * User: matteo
 * Date: 04/01/13
 * Time: 22.19
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Command;

use Cypress\GitElephantHostBundle\Command\Base\CommandBase;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * update all repositories
 */
class UpdateAllCommand extends CommandBase
{
    protected function configure()
    {
        $this
            ->setName('gitelephant:repository:update-all')
            ->setDescription('Update all the repositories')
            ->addArgument('repository_id', InputArgument::OPTIONAL, 'the repository id', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repos = $this->getRepoRepository()->findAll();
        foreach ($repos as $repo) {
            $output->writeln(sprintf('Updating <info>%s</info> repository', $repo->getName()));
            $repo->getGit()->updateAllBranches();
        }
    }
}
