<?php
namespace Dwl\LexBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('dwl_lex')
            ->children()
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('phpcr')
                            ->addDefaultsIfNotSet()
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('basepath')->defaultValue('/cms/lex')->end()
                                // ->scalarNode('manager_registry')->defaultValue('doctrine_phpcr')->end()
                                // ->scalarNode('manager_name')->defaultNull()->end()
                                // ->scalarNode('document_class')->defaultValue('Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page')->end()
                                ->scalarNode('document_class')->defaultValue('Dwl\LexBundle\Document\Dictum')->end()

                                // ->enumNode('use_sonata_admin')
                                //     ->values(array(true, false, 'auto'))
                                //     ->defaultValue('auto')
                                // ->end()
                                // ->arrayNode('sonata_admin')
                                //     ->children()
                                //         ->enumNode('sort')
                                //             ->values(array(false, 'asc', 'desc'))
                                //             ->defaultValue(false)
                                //         ->end()
                                //     ->end()
                                // ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
