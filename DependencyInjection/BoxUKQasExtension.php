<?php

namespace BoxUK\QasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BoxUKQasExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->loadProWebConfig($config, $container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    protected function loadProWebConfig(array $config, ContainerBuilder $container)
    {
        $wsdlUrl = null;

        if (isset($config['proweb'])) {
            $wsdlUrl = $config['proweb']['wsdl_url'];
        }

        $container->setParameter('boxuk.qas.proweb.wsdl_url', $wsdlUrl);
    }
}
