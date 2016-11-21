<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('rz_media');
        $this->addBundleSettings($node);
        $this->addModelSection($node);
        $this->addManagerSection($node);
        $this->addBlockSettings($node);
        $this->addProviderSection($node);

        $node
            ->children()
                ->scalarNode('db_driver')->defaultValue('doctrine_orm')->end()
                ->scalarNode('default_context')->defaultValue('default')->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addBundleSettings(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('layout')->defaultValue('RzMediaBundle:MediaBrowser:layout.html.twig')->cannotBeEmpty()->end()
                        ->scalarNode('browser')->defaultValue('RzMediaBundle:MediaAdmin:browser.html.twig')->cannotBeEmpty()->end()
                        ->scalarNode('browser_inner_list_row')->defaultValue('RzMediaBundle:MediaAdmin:browser_masonry_item.html.twig')->cannotBeEmpty()->end()
                        ->scalarNode('upload')->defaultValue('RzMediaBundle:MediaAdmin:upload.html.twig')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('media')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Admin\\ORM\\MediaAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('RzMediaBundle:MediaAdmin')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('RzMediaBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('user_block')->defaultValue('SonataAdminBundle:Core:user_block.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('add_block')->defaultValue('SonataAdminBundle:Core:add_block.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('layout')->defaultValue('SonataAdminBundle::standard_layout.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('ajax')->defaultValue('SonataAdminBundle::ajax_layout.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('dashboard')->defaultValue('SonataAdminBundle:Core:dashboard.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('search')->defaultValue('SonataAdminBundle:Core:search.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('list')->defaultValue('RzMediaBundle:MediaAdmin:list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('filter')->defaultValue('SonataAdminBundle:Form:filter_admin_fields.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('show')->defaultValue('SonataAdminBundle:CRUD:show.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('show_compare')->defaultValue('SonataAdminBundle:CRUD:show_compare.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('edit')->defaultValue('RzMediaBundle:CRUD:edit.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('preview')->defaultValue('SonataAdminBundle:CRUD:preview.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history')->defaultValue('SonataAdminBundle:CRUD:history.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('acl')->defaultValue('SonataAdminBundle:CRUD:acl.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history_revision_timestamp')->defaultValue('SonataAdminBundle:CRUD:history_revision_timestamp.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('action')->defaultValue('SonataAdminBundle:CRUD:action.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('select')->defaultValue('SonataAdminBundle:CRUD:list__select.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('list_block')->defaultValue('SonataAdminBundle:Block:block_admin_list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('search_result_block')->defaultValue('SonataAdminBundle:Block:block_search_result.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('short_object_description')->defaultValue('RzMediaBundle:Helper:short-object-description.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('delete')->defaultValue('SonataAdminBundle:CRUD:delete.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('batch')->defaultValue('SonataAdminBundle:CRUD:list__batch.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('batch_confirmation')->defaultValue('SonataAdminBundle:CRUD:batch_confirmation.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('inner_list_row')->defaultValue('RzMediaBundle:MediaAdmin:masonry_item.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_mosaic')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_mosaic.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_list')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_tree')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_tree.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('base_list_field')->defaultValue('SonataAdminBundle:CRUD:base_list_field.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('pager_links')->defaultValue('SonataAdminBundle:Pager:links.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('pager_results')->defaultValue('SonataAdminBundle:Pager:results.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('tab_menu_template')->defaultValue('SonataAdminBundle:Core:tab_menu_template.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('knp_menu_template')->defaultValue('SonataAdminBundle:Menu:sonata_menu.html.twig')->cannotBeEmpty()->end()
                                        /*********************************
                                         * rzAdmin Added Templates
                                         *********************************/
                                        //** table items */
                                        ->scalarNode('rz_base_list_inner_row_header')->defaultValue('RzAdminBundle:CRUD:base_list_inner_row_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_inner_row_header')->defaultValue('RzAdminBundle:CRUD:list_inner_row_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_field_header')->defaultValue('RzAdminBundle:CRUD:base_list_field_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_field_header')->defaultValue('RzAdminBundle:CRUD:list_field_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_batch_header')->defaultValue('RzAdminBundle:CRUD:base_list_batch_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_batch_header')->defaultValue('RzAdminBundle:CRUD:list_batch_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_select_header')->defaultValue('RzAdminBundle:CRUD:base_list_select_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_select_header')->defaultValue('RzAdminBundle:CRUD:list_select_header.html.twig')->cannotBeEmpty()->end()
                                        // table actions and other components
                                        ->scalarNode('rz_list_table_footer')->defaultValue('RzAdminBundle:CRUD:list_table_footer.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_batch')->defaultValue('RzAdminBundle:CRUD:list_table_batch.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_download')->defaultValue('RzAdminBundle:CRUD:list_table_download.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_pager')->defaultValue('RzAdminBundle:CRUD:list_table_pager.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_per_page')->defaultValue('RzAdminBundle:CRUD:list_table_per_page.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_media_browser_list_table_footer')->defaultValue('RzMediaBundle:MediaAdmin:list_table_browser_footer.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('gallery')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Admin\\GalleryAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('RzMediaBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('user_block')->defaultValue('SonataAdminBundle:Core:user_block.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('add_block')->defaultValue('SonataAdminBundle:Core:add_block.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('layout')->defaultValue('SonataAdminBundle::standard_layout.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('ajax')->defaultValue('SonataAdminBundle::ajax_layout.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('dashboard')->defaultValue('SonataAdminBundle:Core:dashboard.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('search')->defaultValue('SonataAdminBundle:Core:search.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('list')->defaultValue('SonataAdminBundle:CRUD:list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('filter')->defaultValue('SonataAdminBundle:Form:filter_admin_fields.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('show')->defaultValue('SonataAdminBundle:CRUD:show.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('show_compare')->defaultValue('SonataAdminBundle:CRUD:show_compare.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('edit')->defaultValue('RzMediaBundle:CRUD:edit.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('preview')->defaultValue('SonataAdminBundle:CRUD:preview.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history')->defaultValue('SonataAdminBundle:CRUD:history.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('acl')->defaultValue('SonataAdminBundle:CRUD:acl.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history_revision_timestamp')->defaultValue('SonataAdminBundle:CRUD:history_revision_timestamp.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('action')->defaultValue('SonataAdminBundle:CRUD:action.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('select')->defaultValue('SonataAdminBundle:CRUD:list__select.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('list_block')->defaultValue('SonataAdminBundle:Block:block_admin_list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('search_result_block')->defaultValue('SonataAdminBundle:Block:block_search_result.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('short_object_description')->defaultValue('SonataAdminBundle:Helper:short-object-description.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('delete')->defaultValue('SonataAdminBundle:CRUD:delete.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('batch')->defaultValue('SonataAdminBundle:CRUD:list__batch.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('batch_confirmation')->defaultValue('SonataAdminBundle:CRUD:batch_confirmation.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('inner_list_row')->defaultValue('SonataAdminBundle:CRUD:list_inner_row.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_mosaic')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_mosaic.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_list')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_tree')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_tree.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('base_list_field')->defaultValue('SonataAdminBundle:CRUD:base_list_field.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('pager_links')->defaultValue('SonataAdminBundle:Pager:links.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('pager_results')->defaultValue('SonataAdminBundle:Pager:results.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('tab_menu_template')->defaultValue('SonataAdminBundle:Core:tab_menu_template.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('knp_menu_template')->defaultValue('SonataAdminBundle:Menu:sonata_menu.html.twig')->cannotBeEmpty()->end()
                                        /*********************************
                                         * rzAdmin Added Templates
                                         *********************************/
                                        //** table items */
                                        ->scalarNode('rz_base_list_inner_row_header')->defaultValue('RzAdminBundle:CRUD:base_list_inner_row_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_inner_row_header')->defaultValue('RzAdminBundle:CRUD:list_inner_row_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_field_header')->defaultValue('RzAdminBundle:CRUD:base_list_field_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_field_header')->defaultValue('RzAdminBundle:CRUD:list_field_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_batch_header')->defaultValue('RzAdminBundle:CRUD:base_list_batch_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_batch_header')->defaultValue('RzAdminBundle:CRUD:list_batch_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_select_header')->defaultValue('RzAdminBundle:CRUD:base_list_select_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_select_header')->defaultValue('RzAdminBundle:CRUD:list_select_header.html.twig')->cannotBeEmpty()->end()
                                        // table actions and other components
                                        ->scalarNode('rz_list_table_footer')->defaultValue('RzAdminBundle:CRUD:list_table_footer.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_batch')->defaultValue('RzAdminBundle:CRUD:list_table_batch.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_download')->defaultValue('RzAdminBundle:CRUD:list_table_download.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_pager')->defaultValue('RzAdminBundle:CRUD:list_table_pager.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_per_page')->defaultValue('RzAdminBundle:CRUD:list_table_per_page.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('gallery_has_media')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Admin\\GalleryHasMediaAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('RzMediaBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('user_block')->defaultValue('SonataAdminBundle:Core:user_block.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('add_block')->defaultValue('SonataAdminBundle:Core:add_block.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('layout')->defaultValue('SonataAdminBundle::standard_layout.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('ajax')->defaultValue('SonataAdminBundle::ajax_layout.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('dashboard')->defaultValue('SonataAdminBundle:Core:dashboard.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('search')->defaultValue('SonataAdminBundle:Core:search.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('list')->defaultValue('SonataAdminBundle:CRUD:list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('filter')->defaultValue('SonataAdminBundle:Form:filter_admin_fields.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('show')->defaultValue('SonataAdminBundle:CRUD:show.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('show_compare')->defaultValue('SonataAdminBundle:CRUD:show_compare.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('edit')->defaultValue('SonataAdminBundle:CRUD:edit.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('preview')->defaultValue('SonataAdminBundle:CRUD:preview.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history')->defaultValue('SonataAdminBundle:CRUD:history.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('acl')->defaultValue('SonataAdminBundle:CRUD:acl.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history_revision_timestamp')->defaultValue('SonataAdminBundle:CRUD:history_revision_timestamp.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('action')->defaultValue('SonataAdminBundle:CRUD:action.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('select')->defaultValue('SonataAdminBundle:CRUD:list__select.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('list_block')->defaultValue('SonataAdminBundle:Block:block_admin_list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('search_result_block')->defaultValue('SonataAdminBundle:Block:block_search_result.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('short_object_description')->defaultValue('SonataAdminBundle:Helper:short-object-description.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('delete')->defaultValue('SonataAdminBundle:CRUD:delete.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('batch')->defaultValue('SonataAdminBundle:CRUD:list__batch.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('batch_confirmation')->defaultValue('SonataAdminBundle:CRUD:batch_confirmation.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('inner_list_row')->defaultValue('SonataAdminBundle:CRUD:list_inner_row.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_mosaic')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_mosaic.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_list')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('outer_list_rows_tree')->defaultValue('SonataAdminBundle:CRUD:list_outer_rows_tree.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('base_list_field')->defaultValue('SonataAdminBundle:CRUD:base_list_field.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('pager_links')->defaultValue('SonataAdminBundle:Pager:links.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('pager_results')->defaultValue('SonataAdminBundle:Pager:results.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('tab_menu_template')->defaultValue('SonataAdminBundle:Core:tab_menu_template.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('knp_menu_template')->defaultValue('SonataAdminBundle:Menu:sonata_menu.html.twig')->cannotBeEmpty()->end()

                                        /*********************************
                                         * rzAdmin Added Templates
                                         *********************************/
                                        //** table items */
                                        ->scalarNode('rz_base_list_inner_row_header')->defaultValue('RzAdminBundle:CRUD:base_list_inner_row_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_inner_row_header')->defaultValue('RzAdminBundle:CRUD:list_inner_row_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_field_header')->defaultValue('RzAdminBundle:CRUD:base_list_field_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_field_header')->defaultValue('RzAdminBundle:CRUD:list_field_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_batch_header')->defaultValue('RzAdminBundle:CRUD:base_list_batch_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_batch_header')->defaultValue('RzAdminBundle:CRUD:list_batch_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_base_list_select_header')->defaultValue('RzAdminBundle:CRUD:base_list_select_header.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_select_header')->defaultValue('RzAdminBundle:CRUD:list_select_header.html.twig')->cannotBeEmpty()->end()
                                        // table actions and other components
                                        ->scalarNode('rz_list_table_footer')->defaultValue('RzAdminBundle:CRUD:list_table_footer.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_batch')->defaultValue('RzAdminBundle:CRUD:list_table_batch.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_download')->defaultValue('RzAdminBundle:CRUD:list_table_download.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_pager')->defaultValue('RzAdminBundle:CRUD:list_table_pager.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('rz_list_table_per_page')->defaultValue('RzAdminBundle:CRUD:list_table_per_page.html.twig')->cannotBeEmpty()->end()
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
    private function addModelSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('media')->defaultValue('Application\\Sonata\\MediaBundle\\Entity\\Media')->end()
                        ->scalarNode('gallery')->defaultValue('Application\\Sonata\\MediaBundle\\Entity\\Gallery')->end()
                        ->scalarNode('gallery_has_media')->defaultValue('Application\\Sonata\\MediaBundle\\Entity\\GalleryHasMedia')->end()
                    ->end()
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
                ->arrayNode('class_manager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('media')->defaultValue('Rz\\MediaBundle\\Entity\\MediaManager')->end()
                        ->scalarNode('gallery')->defaultValue('Rz\\MediaBundle\\Entity\\GalleryManager')->end()
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
                        ->arrayNode('feature_media')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Block\\FeatureMediaBlockService')->end()
                                ->arrayNode('templates')
                                    ->useAttributeAsKey('id')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('name')->defaultValue('default')->end()
                                            ->scalarNode('path')->defaultValue('RzMediaBundle:Block:block_feature_media.html.twig')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('gallery')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Block\\GalleryBlockService')->end()
                                ->arrayNode('templates')
                                    ->useAttributeAsKey('id')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('name')->defaultValue('default')->end()
                                            ->scalarNode('path')->defaultValue('RzMediaBundle:Block:block_gallery.html.twig')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('breadcrumb_media')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Block\\Breadcrumb\\MediaViewBreadcrumbBlockService')->end()
                                ->arrayNode('templates')
                                    ->useAttributeAsKey('id')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('name')->defaultValue('default')->end()
                                            ->scalarNode('path')->defaultValue('RzMediaBundle:Block:block_breadcrumb_media.html.twig')->end()
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
                        ->arrayNode('class')
                            ->addDefaultsIfNotSet()
                            ->children() 
								->scalarNode('youtube')->cannotBeEmpty()->defaultValue('Rz\\MediaBundle\\Provider\\YouTubeProvider')->end()     
                            ->end()
                        ->end()
					->end()
				->end()
			->end();
	}
}
