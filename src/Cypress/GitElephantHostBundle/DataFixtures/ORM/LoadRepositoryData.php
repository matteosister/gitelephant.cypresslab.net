<?php
/**
 * User: matteo
 * Date: 22/11/12
 * Time: 22.47
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\DataFixtures\ORM;

use Cypress\GitElephantHostBundle\DataFixtures\BaseFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cypress\GitElephantHostBundle\Entity\Repository;

/**
 * repository data
 */
class LoadRepositoryData extends BaseFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $ge = new Repository();
        $ge->setName('GitElephant');
        $ge->setPath('/home/matteo/libraries/GitElephant');
        $manager->persist($ge);

//        $sf = new Repository();
//        $sf->setName('Symfony2');
//        $sf->setPath('/home/matteo/libraries/symfony');
//        $manager->persist($sf);

//        $rb = new Repository();
//        $rb->setName('Ruby sample app');
//        $rb->setPath('/home/matteo/internet/ruby/tutorial/sample_app');
//        $manager->persist($rb);

//        $bower = new Repository();
//        $bower->setName('Bower');
//        $bower->setGitUrl('git://github.com/twitter/bower.git');
//        $manager->persist($bower);

        $self = new Repository();
        $self->setName('GitElephant Hosting');
        $self->setPath(realpath(__DIR__.'/../../../../../'));
        $manager->persist($self);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 0;
    }
}
