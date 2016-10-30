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

    private $params;

    public function  __construct($params)
    {
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dwl_exim_core');

        $rootNode
            ->children()
                ->booleanNode('debug')
                    ->defaultValue(true)
                ->end()
                ->arrayNode('theme')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue($this->params['name'])->end()
                        ->arrayNode('path')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('public')->defaultValue($this->params['path']['public'])->end()
                                ->scalarNode('private')->defaultValue($this->params['path']['private'])->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('assets')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('version')->defaultValue($this->params['assets']['version'])->end()
                        ->arrayNode('stylesheets')->end()
                        // ->defaultValue(array(
                        //     '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
                        //     $this->params['path']['public'] . '/css/front.css'
                        // ))->prototype('scalar')
                        ->arrayNode('javascripts')->end()
                        // ->defaultValue(array(
                        //     '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
                        //     $this->params['path']['public'] . '/css/front.js'
                        // ))->prototype('scalar')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
