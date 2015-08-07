<?php

namespace Seegno\TestBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Get config tree builder.
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('seegno_test');

        $rootNode
            ->children()
                ->arrayNode('database')
                    ->children()
                        ->enumNode('driver')
                            ->isRequired()
                            ->values(array('ODM', 'ORM'))
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
