<?php

namespace Kikwik\DbTransBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('kikwik_db_trans');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->end()
        ;

        return $treeBuilder;
    }
}