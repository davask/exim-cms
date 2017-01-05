<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\DependencyInjection;

use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ApplicationSonataUserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('kernel.bundles');

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

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
