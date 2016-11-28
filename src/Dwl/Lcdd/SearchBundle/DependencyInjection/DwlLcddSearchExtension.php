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
        $loader->load('services.yml');

        $this->registerParameters($config, $container);
        $this->registerDoctrineMapping($config, $container);
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

        $container->setParameter('exim.lcdd.search.resources', '%kernel.root_dir%/../src/Dwl/Lcdd/SearchBundle/Resources/public');
        $container->setParameter('exim.lcdd.search.styles', array(
            '%exim.lcdd.search.resources%/scss/block_search.scss',
        ));
        $container->setParameter('exim.lcdd.search.javascript', array(
            '%exim.lcdd.search.resources%/js/elasticsearch.angular.js',
            '%exim.lcdd.search.resources%/js/search.elasticui.js',
            '%exim.lcdd.search.resources%/js/search.angular.js',
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

    /**
     * @param array $config
     */
    public function registerDoctrineMapping(array $config)
    {
        $collector = DoctrineCollector::getInstance();

        foreach ($config['class'] as $type => $class) {
            if (!class_exists($class)) {
                return;
            }
        }

        // $collector->addAssociation($config['class']['question'], 'mapManyToOne', array(
        //     'fieldName' => 'image',
        //     'targetEntity' => $config['class']['media'],
        //     'cascade' => array(
        //             0 => 'remove',
        //             1 => 'persist',
        //             2 => 'refresh',
        //             3 => 'merge',
        //             4 => 'detach',
        //         ),
        //     'mappedBy' => null,
        //     'inversedBy' => null,
        //     'joinColumns' => array(
        //             array(
        //                 'name' => 'image_id',
        //                 'referencedColumnName' => 'id',
        //             ),
        //         ),
        //     'orphanRemoval' => false,
        // ));

        // $collector->addAssociation($config['class']['question'], 'mapManyToOne', array(
        //     'fieldName' => 'author',
        //     'targetEntity' => $config['class']['user'],
        //     'cascade' => array(
        //             1 => 'persist',
        //         ),
        //     'mappedBy' => null,
        //     'inversedBy' => null,
        //     'joinColumns' => array(
        //             array(
        //                 'name' => 'author_id',
        //                 'referencedColumnName' => 'id',
        //             ),
        //         ),
        //     'orphanRemoval' => false,
        // ));

        // $collector->addAssociation($config['class']['question'], 'mapManyToOne', array(
        //     'fieldName' => 'collection',
        //     'targetEntity' => $config['class']['collection'],
        //     'cascade' => array(
        //             1 => 'persist',
        //         ),
        //     'mappedBy' => null,
        //     'inversedBy' => null,
        //     'joinColumns' => array(
        //             array(
        //                 'name' => 'collection_id',
        //                 'referencedColumnName' => 'id',
        //             ),
        //         ),
        //     'orphanRemoval' => false,
        // ));

        $collector->addAssociation($config['class']['question'], 'mapManyToMany', array(
            'fieldName' => 'legalTags',
            'targetEntity' => $config['class']['tag'],
            'cascade' => array(
                    1 => 'persist',
                ),
            'joinTable' => array(
                    'name' => $config['table']['question_legaltag'],
                    'joinColumns' => array(
                            array(
                                'name' => 'question_id',
                                'referencedColumnName' => 'id',
                            ),
                        ),
                    'inverseJoinColumns' => array(
                            array(
                                'name' => 'tag_id',
                                'referencedColumnName' => 'id',
                            ),
                        ),
                ),
        ));

        $collector->addAssociation($config['class']['question'], 'mapManyToMany', array(
            'fieldName' => 'civilTags',
            'targetEntity' => $config['class']['tag'],
            'cascade' => array(
                    1 => 'persist',
                ),
            'joinTable' => array(
                    'name' => $config['table']['question_civiltag'],
                    'joinColumns' => array(
                            array(
                                'name' => 'question_id',
                                'referencedColumnName' => 'id',
                            ),
                        ),
                    'inverseJoinColumns' => array(
                            array(
                                'name' => 'tag_id',
                                'referencedColumnName' => 'id',
                            ),
                        ),
                ),
        ));

        /*
         * QUESTION CATEGORY
         */
        $collector->addAssociation($config['class']['question'], 'mapManyToMany', array(
            'fieldName' => 'questionCategories',
            'targetEntity' => $config['class']['category'],
            'cascade' => array(
                    1 => 'persist',
                ),
            'joinTable' => array(
                    'name' => $config['table']['question_category'],
                    'joinColumns' => array(
                            array(
                                'name' => 'question_id',
                                'referencedColumnName' => 'id',
                            ),
                        ),
                    'inverseJoinColumns' => array(
                            array(
                                'name' => 'category_id',
                                'referencedColumnName' => 'id',
                            ),
                        ),
                ),
        ));

    }

}
