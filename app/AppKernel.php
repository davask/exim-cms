<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function __construct($environment, $debug)
    {
        // Please read http://symfony.com/doc/2.0/book/installation.html#configuration-and-setup
        bcscale(3);

        parent::__construct($environment, $debug);
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(

            // SYMFONY STANDARD EDITION
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),

            // DOCTRINE
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            // KNP HELPER BUNDLES
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            // SONATA FEATURE
            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Sonata\PageBundle\SonataPageBundle(), // 3.x-addBlock2Admin
            new Sonata\NewsBundle\SonataNewsBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(), // 3.x-addResponsiveness

            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),

            new Sonata\AdminBundle\SonataAdminBundle(), // 3.x-addBlock2Admin
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),

            // Disable this if you don't want the audit on entities
            new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),

            // API
            new FOS\RestBundle\FOSRestBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),

            // SONATA E-COMMERCE
            new Sonata\BasketBundle\SonataBasketBundle(),
            new Sonata\CustomerBundle\SonataCustomerBundle(),
            new Sonata\DeliveryBundle\SonataDeliveryBundle(),
            new Sonata\InvoiceBundle\SonataInvoiceBundle(),
            new Sonata\OrderBundle\SonataOrderBundle(),
            new Sonata\PaymentBundle\SonataPaymentBundle(),
            new Sonata\ProductBundle\SonataProductBundle(),
            new Sonata\PriceBundle\SonataPriceBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new FOS\CommentBundle\FOSCommentBundle(),
            new Sonata\CommentBundle\SonataCommentBundle(),

            // SONATA FOUNDATION
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(), // 3.x-addBlock2Admin
            new Sonata\SeoBundle\SonataSeoBundle(), // 2.x-exim
            new Sonata\ClassificationBundle\SonataClassificationBundle(),
            new Sonata\NotificationBundle\SonataNotificationBundle(),
            new Sonata\DatagridBundle\SonataDatagridBundle(),

            // CMF Integration
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),

            // DEMO and QA - Can be deleted
            new Sonata\Bundle\DemoBundle\SonataDemoBundle(),
            new Sonata\Bundle\QABundle\SonataQABundle(),

            // Disable this if you don't want the timeline in the admin
            new Spy\TimelineBundle\SpyTimelineBundle(),
            new Sonata\TimelineBundle\SonataTimelineBundle(),

            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),

            // EXIM DEPENDENCIES
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),

            // EXIM THEME DEPENDENCIES
            new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle(),
            new Application\Sonata\BasketBundle\ApplicationSonataBasketBundle(),
            new Application\Sonata\ClassificationBundle\ApplicationSonataClassificationBundle(),
            new Application\Sonata\CustomerBundle\ApplicationSonataCustomerBundle(),
            new Application\Sonata\InvoiceBundle\ApplicationSonataInvoiceBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle(),
            new Application\Sonata\OrderBundle\ApplicationSonataOrderBundle(),
            new Application\Sonata\PageBundle\ApplicationSonataPageBundle(),
            new Application\Sonata\PaymentBundle\ApplicationSonataPaymentBundle(),
            new Application\Sonata\ProductBundle\ApplicationSonataProductBundle(),
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Bazinga\Bundle\GeocoderBundle\BazingaGeocoderBundle(),


            // EXIM CORE
            new Dwl\Exim\CoreBundle\DwlEximCoreBundle(),
            new Dwl\Exim\ThemeBundle\DwlEximThemeBundle(),

            // APP
            // new AppBundle\AppBundle(),

            // LCDD DEPENDENCIES
            // Search Integration
            new FOS\ElasticaBundle\FOSElasticaBundle(),

            // LCDD
            new Dwl\Lcdd\SearchBundle\DwlLcddSearchBundle(),
            new Dwl\Lcdd\SpeakerBundle\DwlLcddSpeakerBundle(),

            // EXIM THEME : LCDD
            new Exim\Theme\Lcdd\FrontBundle\EximThemeLcddFrontBundle(),
            new Exim\Theme\Lcdd\AdminBundle\EximThemeLcddAdminBundle(),

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Bazinga\Bundle\FakerBundle\BazingaFakerBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
