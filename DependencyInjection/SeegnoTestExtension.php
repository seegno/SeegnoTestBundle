<?php

namespace Seegno\TestBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * SeegnoTestExtension.
 */
class SeegnoTestExtension extends Extension
{
    /**
     * Load.
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Get `database.driver` configuration.
        $dbDriver = !empty($config['database']) && !empty($config['database']['driver']) ? $config['database']['driver'] : null;

        // Set container parameters.
        $container->setParameter('seegno_test.database.driver', $dbDriver);
    }
}
