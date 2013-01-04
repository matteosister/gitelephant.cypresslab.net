<?php
/**
 * User: matteo
 * Date: 03/01/13
 * Time: 17.56
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Command;

use Cypress\GitElephantHostBundle\Entity\Repository\RepositoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use GitElephant\Repository as Git;

/**
 * import a repository
 */
class ImportRepositoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('gitelephant:repository:import')
            ->setDescription('Import the repositories')
            ->addArgument('repository_id', InputArgument::OPTIONAL, 'the repository id', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (null !== $id = $input->getArgument('repository_id')) {
            $repository = $this->getRepoRepository()->findOneBy(array('imported' => false, 'id' => $id));
        } else {
            $repository = $this->getRepoRepository()->findOneBy(array('imported' => false));
        }
        if (null === $repository) {
            $output->writeln('<error>Unable to find a repository to process</error>');
            die;
        }
        $repositoriesDir = realpath($this->getContainer()->getParameter('cypress_git_elephant_host.repositories_dir'));
        $dirName = sprintf('%s_%s', substr(sha1(uniqid()), 0, 8), $repository->getSlug());
        $path = $repositoriesDir.'/'.$dirName;
        $fs = new Filesystem();
        $fs->mkdir($path);
        try {
            Git::createFromRemote($repository->getGitUrl(), $path);
        } catch (\Exception $e) {
            $output->writeln('Error during clone, git reports: '.$e->getMessage());

            return;
        }
        $repository->setPath($path);
        $repository->setImported(true);
        $this->getEM()->persist($repository);
        $this->getEM()->flush();
    }

    /**
     * @return RepositoryRepository
     */
    private function getRepoRepository()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('Cypress\GitElephantHostBundle\Entity\Repository');
    }

    /**
     * @return EntityManager
     */
    private function getEM()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
