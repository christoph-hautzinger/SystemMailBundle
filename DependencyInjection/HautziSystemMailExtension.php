<?php

namespace Hautzi\SystemMailBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HautziSystemMailExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('config.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // inject default configuration
        $container
            ->getDefinition('hautzi_system_mail.system_mailer')
            ->replaceArgument(4, $config['defaults']);
    }
}
