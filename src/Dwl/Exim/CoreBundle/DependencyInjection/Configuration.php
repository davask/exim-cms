<?php

namespace Dwl\Exim\CoreBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('exim_theme');

        $rootNode
            ->children()
                ->booleanNode('debug')
                    ->defaultValue(true)
                ->end()
                ->arrayNode('theme')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('exim')->end()
                        ->arrayNode('path')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('web')->defaultValue($theme_path_web)->end()
                                ->scalarNode('root')->defaultValue($theme_path_root)->end()
                                ->scalarNode('public')->defaultValue($theme_path_public)->end()
                                // ->scalarNode('internal')->defaultValue($theme_path_internal)->end()
                                ->scalarNode('private')->defaultValue($theme_path_private)->end()
                                ->scalarNode('views')->defaultValue($theme_path_root . '/views')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('assets')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('version')->defaultValue('%theme.assets.version%')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
