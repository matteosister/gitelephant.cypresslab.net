<?php
/**
 * User: matteo
 * Date: 15/04/13
 * Time: 22.01
 * Just for fun...
 */


namespace Cypress\GitElephantHostBundle\Jobs;


use BCC\ResqueBundle\ContainerAwareJob;
use Cypress\GitElephantHostBundle\Entity\Repository;
use Doctrine\ORM\EntityManager;
use GitElephant\Repository as Git;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ImportRepo
 *
 * @package Cypress\GitElephantHostBundle\Jobs
 */
class ImportRepo extends ContainerAwareJob
{
    /**
     * run
     *
     * @param array $args args
     */
    public function run($args)
    {
        $repository = $this->getRepository($args);
        $repositoriesDir = realpath($this->getContainer()->getParameter('cypress_git_elephant_host.repositories_dir'));
        $dirName = sprintf('%s_%s', substr(sha1(uniqid()), 0, 8), $repository->getSlug());
        $path = $repositoriesDir.'/'.$dirName;
        $this->getContainer()->get('filesystem')->mkdir($path);
        $git = Git::createFromRemote($repository->getGitUrl(), $path, $this->getContainer()->get('cypress_git_elephant.git_binary'));
        $repository->setPath($git->getPath());
        $repository->setImported(true);
        $this->getEM()->persist($repository);
        $this->getEM()->flush();
        $this->mailConferma($repository);
    }

    private function mailConferma(Repository $r)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Repo importata su gitelephant.cypresslab.net')
            ->setFrom('matteog@gmail.com')
            ->setTo('matteog@gmail.com')
            ->setBody(sprintf('importata repository <a href="%s">%s</a>', $r->getGitUrl(), $r->getGitUrl()));
        $this->getMailer()->send($message);
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        return $this->getContainer()->get('mailer');
    }

    /**
     * @param array $args
     *
     * @return Repository
     */
    private function getRepository($args)
    {
        return $this->getEM()
            ->getRepository('Cypress\GitElephantHostBundle\Entity\Repository')
            ->find($args['id']);
    }

    /**
     * @return EntityManager
     */
    private function getEM()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}