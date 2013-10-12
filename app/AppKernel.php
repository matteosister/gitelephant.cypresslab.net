<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Cypress\GitElephantHostBundle\CypressGitElephantHostBundle(),
            //new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Cypress\GitElephantBundle\CypressGitElephantBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Oryzone\Bundle\BoilerplateBundle\OryzoneBoilerplateBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Cypress\PygmentsElephantBundle\CypressPygmentsElephantBundle(),
            new Ornicar\GravatarBundle\OrnicarGravatarBundle(),
            new Cypress\CompassElephantBundle\CypressCompassElephantBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new Knp\Bundle\TimeBundle\KnpTimeBundle(),
            new BCC\ResqueBundle\BCCResqueBundle(),
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'vagrant'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        if ('test' === $this->getEnvironment()) {
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function getCacheDir()
    {
        if (in_array($this->environment, array('vagrant', 'test'))) {
            return '/dev/shm/gitelephant_host/cache/' .  $this->environment;
        }

        return parent::getCacheDir();
    }

    public function getLogDir()
    {
        if (in_array($this->environment, array('vagrant', 'test'))) {
            return '/dev/shm/gitelephant_host/log/' .  $this->environment;
        }

        return parent::getLogDir();
    }


}
