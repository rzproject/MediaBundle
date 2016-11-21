<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        #######################
        # Override Media Admin
        #######################
        $definition = $container->getDefinition('sonata.media.admin.media');
        $definition->setClass($container->getParameter('rz_media.admin.media.class'));
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.media.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

        #######################
        # Override Gallery Admin
        #######################
        $definition = $container->getDefinition('sonata.media.admin.gallery');
        $definition->setClass($container->getParameter('rz_media.admin.gallery.class'));
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.gallery.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

        #######################
        # Override GalleryHasMedia Admin
        #######################
        $definition = $container->getDefinition('sonata.media.admin.gallery_has_media');
        $definition->setClass($container->getParameter('rz_media.admin.gallery_has_media.class'));
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.gallery_has_media.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

        ##############################
        # Override File Provider Class
        ##############################
        $definition = $container->getDefinition('sonata.media.provider.file');
        $definition->setClass($container->getParameter('rz_media.provider.file.class'));

        ##############################
        # Override Image Provider Class
        ##############################
        $definition = $container->getDefinition('sonata.media.provider.image');
        $definition->setClass($container->getParameter('rz_media.provider.image.class'));


        #################################
        # Override Media Block
        #################################
        $definition = $container->getDefinition('sonata.media.block.media');
        $definition->setClass($container->getParameter('rz_media.block.media.class'));
        if($container->hasParameter('rz_media.block.media.templates')) {
            $definition->addMethodCall('setTemplates', array($container->getParameter('rz_media.block.media.templates')));
        }

        #################################
        # Override Feature Media Block
        #################################
        $definition = $container->getDefinition('sonata.media.block.feature_media');
        $definition->setClass($container->getParameter('rz_block.block.feature_media.class'));
        if($container->hasParameter('rz_block.block.feature_media.templates')) {
            $definition->addMethodCall('setTemplates', array($container->getParameter('rz_block.block.feature_media.templates')));
        }

        #################################
        # Override Gallery Block
        #################################
        $definition = $container->getDefinition('sonata.media.block.gallery');
        $definition->setClass($container->getParameter('rz_media.block.gallery.class'));
        if($container->hasParameter('rz_media.block.gallery.templates')) {
            $definition->addMethodCall('setTemplates', array($container->getParameter('rz_media.block.gallery.templates')));
        }

        #################################
        # Override Breadcrumb Media Block
        #################################
        $definition = $container->getDefinition('sonata.media.block.breadcrumb_view_media');
        $definition->setClass($container->getParameter('rz_media.block.breadcrumb_media.class'));
        if($container->hasParameter('rz_media.block.breadcrumb_media.templates')) {
            $definition->addMethodCall('setTemplates', array($container->getParameter('rz_media.block.breadcrumb_media.templates')));
        }
		
	//Youtube Provider override 				
        $definition = $container->getDefinition('sonata.media.provider.youtube');		
        if($container->hasParameter('mosaic.media.provider.class.youtube')) { 
            $definition->setClass($container->getParameter('mosaic.media.provider.class.youtube'));
        } 
    }
}
