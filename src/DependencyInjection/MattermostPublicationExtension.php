<?php

namespace CodeBuds\MattermostPublicationBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MattermostPublicationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        try {
            $loader->load('services.xml');
        } catch (Exception $exception) {
            var_dump($exception);
        }

        $configuration = $this->getConfiguration($configs, $container);

        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition("codebuds_mattermost_publication.mattermost_publication");
        $definition->setArgument(0, $config['webhook_url']);
        $definition->setArgument(1, $config['username']);
        $definition->setArgument(2, $config['icon_url']);
        $definition->setArgument(3, $config['channel']);
    }
}
