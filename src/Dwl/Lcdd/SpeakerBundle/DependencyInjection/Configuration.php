<?php

namespace Dwl\Lcdd\SpeakerBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('dwl_lcdd_speaker');

        $rootNode
            ->children()
                ->arrayNode('profile')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('default_avatar')->defaultValue('bundles/sonatauser/default_avatar.png')->end()
                        ->arrayNode('dashboard')
                            ->addDefaultsIfNotSet()
                            ->fixXmlConfig('block')
                            ->children()
                                ->arrayNode('blocks')
                                    ->defaultValue(array(array('position' => 'left', 'settings' => array('content' => '<h2>Welcome!</h2> This is a sample speaker profile dashboard, feel free to override it in the configuration!'), 'type' => 'sonata.block.service.text')))
                                    ->prototype('array')
                                        ->fixXmlConfig('setting')
                                        ->children()
                                            ->scalarNode('type')->cannotBeEmpty()->end()
                                            ->arrayNode('settings')
                                                ->useAttributeAsKey('id')
                                                ->prototype('variable')->defaultValue(array())->end()
                                            ->end()
                                            ->scalarNode('position')->defaultValue('right')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('menu')
                            ->prototype('array')
                                ->addDefaultsIfNotSet()
                                ->cannotBeEmpty()
                                ->children()
                                    ->scalarNode('route')->cannotBeEmpty()->end()
                                    ->arrayNode('route_parameters')
                                        ->defaultValue(array())
                                        ->prototype('array')->end()
                                    ->end()
                                    ->scalarNode('label')->cannotBeEmpty()->end()
                                    ->scalarNode('domain')->defaultValue('messages')->end()
                                ->end()
                            ->end()
                            ->defaultValue($this->getProfileMenuDefaultValues())
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Returns default values for profile menu (to avoid BC Break).
     *
     * @return array
     */
    protected function getProfileMenuDefaultValues()
    {
        return array(
            array(
                'route' => 'lcdd_speaker_all',
                'label' => 'speaker_profile_all',
                'domain' => 'ApplicationSonataUserBundle',
                'route_parameters' => array(),
            ),
            array(
                'route' => 'lcdd_speaker_get',
                'label' => 'sonata_profile_title',
                'domain' => 'SonataUserBundle',
                'route_parameters' => array(
                ),
            ),
        );
    }

}
