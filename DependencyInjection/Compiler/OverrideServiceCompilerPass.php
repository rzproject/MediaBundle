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
        //override Media Admin
        $definition = $container->getDefinition('sonata.media.admin.media');
        $definition->setClass($container->getParameter('rz_media.admin.media.class'));
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.media.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

        //override Gallery Admin
        $definition = $container->getDefinition('sonata.media.admin.gallery');
        $definition->setClass($container->getParameter('rz_media.admin.gallery.class'));
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.gallery.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

        //override GalleryHasMedia Admin
        $definition = $container->getDefinition('sonata.media.admin.gallery_has_media');
        $definition->setClass($container->getParameter('rz_media.admin.gallery_has_media.class'));
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.gallery_has_media.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

        //override File Provider
        $definition = $container->getDefinition('sonata.media.provider.file');
        $definition->setClass($container->getParameter('rz_media.provider.file.class'));

        //override Image Provider
        $definition = $container->getDefinition('sonata.media.provider.image');
        $definition->setClass($container->getParameter('rz_media.provider.image.class'));

        //override Media Block Class
        $definition = $container->getDefinition('sonata.media.block.media');
        $definition->setClass($container->getParameter('rz_media.block.media.class'));

        //override Feature Media Block Class
        $definition = $container->getDefinition('sonata.media.block.feature_media');
        $definition->setClass($container->getParameter('rz_media.block.feature_media.class'));

        //override Gallery Block Class
        $definition = $container->getDefinition('sonata.media.block.gallery');
        $definition->setClass($container->getParameter('rz_media.block.gallery.class'));
    }
}
