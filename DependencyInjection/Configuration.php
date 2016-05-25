<?php

namespace Rz\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('rz_media');
        $this->addModelSection($node);
        $this->addManagerSection($node);
        $this->addAdminSection($node);
        $this->addBlockSettings($node);
        $this->addProviderSection($node);
        $this->addSettingsSection($node);
        return $treeBuilder;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addSettingsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('slugify_service')
                    ->info('You should use: sonata.core.slugify.cocur, but for BC we keep \'sonata.core.slugify.native\' as default')
                    ->defaultValue('sonata.core.slugify.cocur')
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addManagerSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('manager_class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('orm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('media')->defaultValue('Rz\\MediaBundle\\Entity\\MediaManager')->end()
                                ->scalarNode('gallery')->defaultValue('Rz\\MediaBundle\\Entity\\GalleryManager')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

     /**
     * @param ArrayNodeDefinition $node
     */
    private function addModelSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('media')->defaultValue('AppBundle\\Entity\\Media\\Media')->end()
                        ->scalarNode('gallery')->defaultValue('AppBundle\\Entity\\Media\\Gallery')->end()
                        ->scalarNode('gallery_has_media')->defaultValue('AppBundle\\Entity\\Media\\GalleryHasMedia')->end()
                        ->scalarNode('collection')->defaultValue('AppBundle\\Entity\\Classification\\Collection')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

     private function addAdminSection(ArrayNodeDefinition $node) {
        $node
            ->children()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('media')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Admin\\ORM\\MediaAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataMediaBundle:MediaAdmin')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('SonataMediaBundle')->end()
                            ->end()
                        ->end()
                        ->arrayNode('gallery')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Admin\\GalleryAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataMediaBundle:GalleryAdmin')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('SonataMediaBundle')->end()
                            ->end()
                        ->end()
                        ->arrayNode('gallery_has_media')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Admin\\ORM\\GalleryHasMediaAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('SonataMediaBundle')->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addBlockSettings(ArrayNodeDefinition $node) {
        $node
            ->children()
                ->arrayNode('blocks')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('media')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Block\\MediaBlockService')->end()
                                ->arrayNode('templates')
                                    ->useAttributeAsKey('id')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('name')->defaultValue('default')->end()
                                            ->scalarNode('path')->defaultValue('RzMediaBundle:Block:block_media.html.twig')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addProviderSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('providers')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('gallery')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('default_context')->isRequired()->end()
                                ->scalarNode('default_collection')->isRequired()->end()
                                ->scalarNode('default_provider_collection')->isRequired()->end()
                                ->arrayNode('collections')
                                    ->useAttributeAsKey('id')
                                    ->isRequired()
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('gallery_provider')->isRequired()->end()
                                            ->scalarNode('gallery_has_media_provider')->isRequired()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
