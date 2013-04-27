<?php
/**
 * User: matteo
 * Date: 27/04/13
 * Time: 23.21
 * Just for fun...
 */


namespace Cypress\GitElephantHostBundle\Command;

use Cypress\GitElephantHostBundle\Command\Base\CommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoveNotImported
 */
class CleanCommand extends CommandBase
{
    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('gitelephant:repository:clean')
            ->setDescription('Clean not imported repositories');
    }

    /**
     * run
     *
     * @param InputInterface  $input  input
     * @param OutputInterface $output output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $since = new \DateTime();
        $since->sub(new \DateInterval('PT1H'));
        $repos = $this->getRepoRepository()->getToBeCleaned($since);
        if (!$repos) {
            $output->writeln('No old repositories to clean up');

            return;
        }
        $output->writeln(sprintf('cleaning <comment>%s</comment> repos not imported since <info>%s</info>', count($repos), $since->format('F d Y H:i:s')));
        foreach ($repos as $repo) {
            $this->getEM()->remove($repo);
        }
        $this->getEM()->flush();
    }
}