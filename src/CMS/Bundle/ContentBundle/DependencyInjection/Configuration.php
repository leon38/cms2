<?php

namespace CMS\Bundle\ContentBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('content');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('consumer_key')->cannotBeEmpty()->end()
                ->scalarNode('consumer_secret')->cannotBeEmpty()->end()
                ->scalarNode('access_token')->cannotBeEmpty()->end()
                ->scalarNode('access_token_secret')->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
