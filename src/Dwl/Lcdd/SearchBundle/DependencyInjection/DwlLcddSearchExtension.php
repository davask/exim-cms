<?php

namespace Dwl\Lcdd\SearchBundle\DependencyInjection;

use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DwlLcddSearchExtension extends Extension
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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        if (isset($bundles['SonataAdminBundle'])) {
            $loader->load('admin.yml');
        }
        // if (isset($bundles['SonataMediaBundle'])) {
        //     $loader->load('search/lcdd_media.yml');
        // }
        $loader->load('services.yml');

        $this->registerParameters($config, $container);
        $this->configureClass($config, $container);
        $this->configureAdmin($config, $container);

    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function registerParameters($config, ContainerBuilder $container)
    {

        $container->setParameter('lcdd_admin.title', 'La chaine du droit');
        $container->setParameter('lcdd_admin.logo_title', '%exim.theme.path.web%/media/images/icon-full.png');

        $container->setParameter('exim.lcdd.search.resources', '%kernel.root_dir%/../src/Dwl/Lcdd/SearchBundle/Resources');
        $container->setParameter('exim.lcdd.search.styles', array(
            '%exim.lcdd.search.resources%/public/scss/block_search.scss',
        ));
        $container->setParameter('exim.lcdd.search.javascript', array(
            '%exim.lcdd.search.resources%/public/js/elasticsearch.angular.js',
            '%exim.lcdd.search.resources%/public/js/search.elasticui.js',
            '%exim.lcdd.search.resources%/public/js/search.angular.js',
        ));
        $container->setParameter('exim.lcdd.search.vendor.javascript', array(
            '//code.angularjs.org/1.2.16/angular.js',
            '//code.angularjs.org/1.2.25/angular-sanitize.js',
            '/bundles/dwllcddsearch/vendor/elastic.js/dist/elastic.min.js',
        ));

        $container->setParameter('lcdd.elastic.host', '163.172.35.42');
        $container->setParameter('lcdd.elastic.port', '9200');
        $container->setParameter('lcdd.elastic.request', 'http://%lcdd.elastic.host%:%lcdd.elastic.port%');
        $container->setParameter('lcdd.elastic.index', 'lcdd');
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function configureClass($config, ContainerBuilder $container)
    {
        $container->setParameter('lcdd.search.question.question.class', $config['class']['question']);
        $container->setParameter('lcdd.search.question.question_category.class', $config['class']['question_category']);
        $container->setParameter('lcdd.search.question.category.class', $config['class']['category']);
        $container->setParameter('lcdd.search.question.tag.class', $config['class']['tag']);

        $container->setParameter('lcdd.search.question.admin.question.entity', $config['class']['question']);
        $container->setParameter('lcdd.search.question.admin.question_category.entity', $config['class']['question_category']);
        $container->setParameter('lcdd.search.question.admin.category.entity', $config['class']['category']);
        $container->setParameter('lcdd.search.question.admin.tag.entity', $config['class']['tag']);


        // admin configuration
        $container->setParameter('lcdd.search.admin.question.entity', $config['class']['question']);

        // manager configuration
        $container->setParameter('lcdd.search.manager.question.entity', $config['class']['question']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function configureAdmin($config, ContainerBuilder $container)
    {
        $container->setParameter('lcdd.search.admin.question.class', $config['admin']['question']['class']);
        $container->setParameter('lcdd.search.admin.question.controller', $config['admin']['question']['controller']);
        $container->setParameter('lcdd.search.admin.question.translation_domain', $config['admin']['question']['translation']);

    }

}
