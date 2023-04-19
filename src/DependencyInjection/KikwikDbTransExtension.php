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


        // make the empty translation files in the default translation
        $translationDir = $container->getParameter('translator.default_path');
        foreach($config['locales'] as $locale)
        {
            $localeFile = $translationDir.'/'.$config['domain'].'.'.$locale.'.db';
            if(!is_file($localeFile))
            {
                @touch($localeFile);
            }
        }
    }

}