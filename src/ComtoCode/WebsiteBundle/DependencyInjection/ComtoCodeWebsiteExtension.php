<?php

namespace ComtoCode\WebsiteBundle\DependencyInjection;


use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ComtoCodeWebsiteExtension extends Extension implements PrependExtensionInterface
{



    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load( 'services.yml' );
        // Website file
        $loader->load( 'comtocode.yml' );

    }

    /**
     * Loads DemoBundle configuration.
     *
     * @param ContainerBuilder $container
     */
    public function prepend( ContainerBuilder $container )
    {
        $legacyConfigFile = __DIR__ . '/../Resources/config/legacy_settings.yml';
        $config = Yaml::parse( file_get_contents( $legacyConfigFile ) );
        $container->prependExtensionConfig( 'ez_publish_legacy', $config );
        $container->addResource( new FileResource( $legacyConfigFile ) );

        $configFile = __DIR__ . '/../Resources/config/design.yml';
        $config = Yaml::parse( file_get_contents( $configFile ) );
        $container->prependExtensionConfig( 'assetic', $config );
        $container->addResource( new FileResource( $configFile ) );

        $configFile = __DIR__ . '/../Resources/config/override.yml';
        $config = Yaml::parse( file_get_contents( $configFile ) );
        $container->prependExtensionConfig( 'ezpublish', $config );
        $container->addResource( new FileResource( $configFile ) );

        $configFile = __DIR__ . '/../Resources/config/image_variations.yml';
        $config = Yaml::parse( file_get_contents( $configFile ) );
        $container->prependExtensionConfig( 'ezpublish', $config );
        $container->addResource( new FileResource( $configFile ) );


    }
}
