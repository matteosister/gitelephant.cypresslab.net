<?php
/**
 * User: matteo
 * Date: 03/01/13
 * Time: 17.56
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Command;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * import a repository
 */
class ImportRepositoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('gitelephant:repository:import')
            ->setDescription('Import the repositories');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repositories = $this->getRepoRepository()->findBy(array('imported' => false));
        $output->writeln(sprintf('There are <info>%s</info> repositories to import', count($repositories)));
    }

    /**
     * @return EntityRepository
     */
    private function getRepoRepository()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('Cypress\GitElephantHostBundle\Entity\Repository');
    }
}
