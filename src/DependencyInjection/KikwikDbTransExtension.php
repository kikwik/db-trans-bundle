<?php

namespace Kikwik\DbTransBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class KikwikDbTransExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $importMessagesCommand = $container->getDefinition('kikwik_db_trans.command.import_messages_command');
        $importMessagesCommand->setArgument('$domainPrefix', $config['domainPrefix']);
        $importMessagesCommand->setArgument('$locales', $config['locales']);

        $transExtension = $container->getDefinition('kikwik_db_trans.twig.trans_extension');
        $transExtension->setArgument('$domainPrefix', $config['domainPrefix']);

        $transController = $container->getDefinition('kikwik_db_trans.controller.trans_controller');
        $transController->setArgument('$locales', $config['locales']);
    }

}