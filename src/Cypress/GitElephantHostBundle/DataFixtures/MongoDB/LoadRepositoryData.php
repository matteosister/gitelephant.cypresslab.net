<?php
/**
 * User: matteo
 * Date: 22/11/12
 * Time: 22.47
 *
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\DataFixtures\MongoDB;

use Cypress\GitElephantHostBundle\DataFixtures\MongoDB\BaseFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cypress\GitElephantHostBundle\Document\Repository;

class LoadRepositoryData extends BaseFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $ge = new Repository();
        $ge->setName('first repository');
        $ge->setPath('/home/matteo/libraries/GitElephant');
        $manager->persist($ge);

        $sf = new Repository();
        $sf->setName('symfony');
        $sf->setPath('/home/matteo/libraries/symfony');
        $manager->persist($sf);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 0;
    }
}
