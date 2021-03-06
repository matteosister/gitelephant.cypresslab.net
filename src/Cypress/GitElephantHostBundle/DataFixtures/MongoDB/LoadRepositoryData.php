<?php
/**
 * User: matteo
 * Date: 22/11/12
 * Time: 22.47
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\DataFixtures\MongoDB;

use Cypress\GitElephantHostBundle\DataFixtures\BaseFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cypress\GitElephantHostBundle\Document\Repository;

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

        $sf = new Repository();
        $sf->setName('Symfony2');
        $sf->setPath('/home/matteo/libraries/symfony');
        $manager->persist($sf);

        $rb = new Repository();
        $rb->setName('Ruby sample app');
        $rb->setPath('/home/matteo/internet/ruby/tutorial/sample_app');
        $manager->persist($rb);

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
