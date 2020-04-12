<?php

namespace CodeBuds\MattermostPublicationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("mattermost_publication");
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode("webhook_url")->defaultValue("http://{your-mattermost-site}/hooks/xxx-generatedkey-xxx")->end()
                ->scalarNode("username")->defaultNull()->end()
                ->scalarNode("icon_url")->defaultNull()->end()
                ->scalarNode("channel")->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}