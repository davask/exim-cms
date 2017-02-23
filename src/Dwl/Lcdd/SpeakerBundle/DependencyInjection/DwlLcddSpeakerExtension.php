<?php

namespace Dwl\Lcdd\SpeakerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DwlLcddSpeakerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $bundles = $container->getParameter('kernel.bundles');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (isset($bundles['SonataAdminBundle'])) {
            $loader->load('admin.yml');
            $loader->load('front_admin.yml');
        }

        $loader->load('block.yml');
        $loader->load('menu.yml');

        $container->setParameter('lcdd.speaker.default_avatar', $config['profile']['default_avatar']);

        $this->configureSpeaker($config, $container);
        $this->configureMenu($config, $container);

    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureSpeaker(array $config, ContainerBuilder $container)
    {
        $container->setParameter('lcdd.speaker.configuration.speaker_blocks', $config['profile']['dashboard']['blocks']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureMenu(array $config, ContainerBuilder $container)
    {
        $container->getDefinition('lcdd.speaker.profile.menu_builder')->replaceArgument(2, $config['profile']['menu']);
    }


}
