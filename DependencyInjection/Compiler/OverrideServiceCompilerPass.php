<?php

namespace Rz\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        //$rz_definition = $container->getDefinition('rz_user.admin.user');

        //override Media Admin
        $definition = $container->getDefinition('sonata.media.admin.media');
        $definition->setClass($container->getParameter('rz_media.admin.media.class'));

        $definition = $container->getDefinition('sonata.media.admin.gallery');
        $definition->setClass($container->getParameter('rz_media.admin.gallery.class'));

        $definition = $container->getDefinition('sonata.media.admin.gallery_has_media');
        $definition->setClass($container->getParameter('rz_media.admin.gallery_has_media.class'));

        $definition = $container->getDefinition('sonata.media.provider.file');
        $definition->setClass($container->getParameter('rz_media.provider.file.class'));

        $definition = $container->getDefinition('sonata.media.provider.image');
        $definition->setClass($container->getParameter('rz_media.provider.image.class'));

        $definition = $container->getDefinition('sonata.media.admin.media');
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.media.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

        $definition = $container->getDefinition('sonata.media.admin.gallery');
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.gallery.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

        $definition = $container->getDefinition('sonata.media.admin.gallery_has_media');
        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_media.configuration.gallery_has_media.templates'));
        $definition->addMethodCall('setTemplates', array($definedTemplates));

    }
}
