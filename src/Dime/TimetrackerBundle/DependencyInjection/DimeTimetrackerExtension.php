<?php

namespace Dime\TimetrackerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DimeTimetrackerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // set pagination offset
        // default: 0
        $container->setParameter('dime_timetracker.pagination.offset',
            (isset($config['parameters']['pagination']['offset'])) ? $config['parameters']['pagination']['offset'] : 0
        );

        // set pagination limit
        // default: 10
        $container->setParameter('dime_timetracker.pagination.limit',
            (isset($config['parameters']['pagination']['limit'])) ? $config['parameters']['pagination']['limit'] : 10
        );

    }
}
