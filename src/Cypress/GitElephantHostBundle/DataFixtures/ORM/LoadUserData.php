<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 9.44
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\DataFixtures\ORM;

use Cypress\GitElephantHostBundle\DataFixtures\BaseFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cypress\GitElephantHostBundle\Entity\User;

class LoadUserData extends BaseFixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('pippo');

        $manager->persist($user);
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
