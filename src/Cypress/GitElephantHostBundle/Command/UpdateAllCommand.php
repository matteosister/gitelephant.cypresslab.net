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
use Symfony\Component\Process\Process;

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
        $repos = $this->getRepoRepository()->getActive();
        foreach ($repos as $repo) {
            $output->write(sprintf('Updating <comment>%s</comment> repository (%s) ...', $repo->getName(), $repo->getPath()));
            $p = new Process('git pull', $repo->getPath());
            $p->run();
            if ($p->isSuccessful()) {
                $output->write("<info>done</info>\n");
            } else {
                $output->writeln(sprintf('<error>%s</error>', $p->getErrorOutput()));
            }
        }
    }
}
