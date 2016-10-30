<?php

namespace Dwl\Exim\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DwlEximCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();

        $params = array(
            'name' => $container->hasParameter('theme.name') ? $container->getParameter('theme.name') : 'exim',
        );
        $container->setParameter("theme.name", $params['name']);
        $params = array_merge($params, array(
            'path' => array(
                'public' => $container->hasParameter('theme.path.public') ? $container->getParameter('theme.path.public') : 'bundles/dwleximcore/themes/%theme.name%',
                'private' => $container->hasParameter('theme.path.private') ? $container->getParameter('theme.path.private') : 'DwlEximCoreBundle:themes/%theme.name%',
            ),
            'assets' => array(
                'version' => $container->hasParameter('theme.assets.version') ? $container->getParameter('theme.assets.version') : '1.0',
            ),
        ));

        $configuration = new Configuration($params);
        $config = $processor->processConfiguration($configuration, $configs);

        // $container->setParameter('exim.theme', $config["theme"]);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        // $loader->load('theme.yml');

    }

}
