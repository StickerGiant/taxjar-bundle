<?php

namespace LAShowroom\TaxJarBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('la_showroom_tax_jar');

        $rootNode
            ->children()
                ->scalarNode('api_token')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('This is the API tokent that you generate in your dashboard')
                ->end()
                ->scalarNode('cache')
                    ->info('The service of the cache adapter to use')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
