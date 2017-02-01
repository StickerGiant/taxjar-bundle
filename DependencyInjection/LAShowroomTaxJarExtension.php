<?php

namespace LAShowroom\TaxJarBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class LAShowroomTaxJarExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('la_showroom_tax_jar.api_token', $config['api_token']);

        if (!empty($config['cache'])) {
            $container
                ->getDefinition('la_showroom_tax_jar.client')
                ->addMethodCall('setCacheItemPool', [
                    new Reference($config['cache'])
                ])
            ;
        }
    }
}
