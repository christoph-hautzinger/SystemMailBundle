<?php

namespace Hautzi\SystemMailBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hautzi_system_mail');

        $rootNode
            ->children()
                ->arrayNode('defaults')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('subject')->defaultNull()->end()
                        ->scalarNode('replyTo')->defaultNull()->end()
                        ->arrayNode('from')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('email')->defaultNull()->end()
                                ->scalarNode('name')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('to')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('email')->defaultNull()->end()
                                ->scalarNode('name')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('cc')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('email')->defaultNull()->end()
                                ->scalarNode('name')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('bcc')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('email')->defaultNull()->end()
                                ->scalarNode('name')->defaultNull()->end()
                            ->end()
                        ->end()
                     ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
