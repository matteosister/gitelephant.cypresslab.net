<?php

namespace Cypress\GitElephantHostBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cypress_git_elephant_host');

        $rootNode
            ->children()
                ->scalarNode('repositories_dir')->isRequired()->end()
                ->scalarNode('login_url')->end()
                ->scalarNode('access_token_url')->end()
                ->scalarNode('client_id')->end()
                ->scalarNode('client_secret')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
