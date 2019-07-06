<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('shopware');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->append($this->createFilesystemSection())
                ->append($this->createCdnSection())
                ->append($this->createApiSection())
                ->append($this->createStoreSection())
                ->append($this->createAdminWorkerSection())
                ->append($this->createCacheSection())
            ->end()
        ;

        return $treeBuilder;
    }

    private function createFilesystemSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('filesystem');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('private')
                    ->performNoDeepMerging()
                    ->children()
                        ->scalarNode('type')->end()
                        ->variableNode('config')->end()
                    ->end()
                ->end()
                ->arrayNode('public')
                    ->performNoDeepMerging()
                    ->children()
                        ->scalarNode('type')->end()
                        ->variableNode('config')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $rootNode;
    }

    private function createCdnSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('cdn');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('url')->end()
                ->scalarNode('strategy')->end()
            ->end()
            ;

        return $rootNode;
    }

    private function createApiSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('api');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
            ->arrayNode('allowed_limits')
                ->prototype('scalar')->end()
            ->end()
            ->integerNode('max_limit')->end()
            ->arrayNode('api_browser')
                ->children()
                ->booleanNode('public')
                    ->defaultFalse()
                ->end()
            ->end()
            ->end()
        ;

        return $rootNode;
    }

    private function createStoreSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('store');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
            ->scalarNode('api_url')->end()
            ->scalarNode('host')->end()
            ->end()
        ;

        return $rootNode;
    }

    private function createAdminWorkerSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('admin_worker');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('transports')
                    ->prototype('scalar')->end()
                ->end()
                ->integerNode('poll_interval')
                    ->defaultValue(30)
                ->end()
                ->booleanNode('enable_admin_worker')
                    ->defaultValue(true)
                ->end()

            ->end()
        ;

        return $rootNode;
    }

    private function createCacheSection(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('cache');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('entity_cache')
                    ->children()
                        ->integerNode('expiration_time')->min(0)->end()
                        ->booleanNode('enabled')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $rootNode;
    }
}
