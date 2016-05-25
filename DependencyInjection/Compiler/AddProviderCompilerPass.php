<?php

namespace Rz\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AddProviderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->attachProviders($container);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function attachProviders(ContainerBuilder $container)
    {
        $galleryPool = $container->getDefinition('rz.media.gallery.pool');
        $galleryHasMediaPool = $container->getDefinition('rz.media.gallery_has_media.pool');

        foreach ($container->findTaggedServiceIds('rz.media.gallery_provider') as $id => $attributes) {
            $galleryPool->addMethodCall('addProvider', array($id, new Reference($id)));
        }

        foreach ($container->findTaggedServiceIds('rz.media.gallery_has_media_provider') as $id => $attributes) {
            $galleryHasMediaPool->addMethodCall('addProvider', array($id, new Reference($id)));
        }

        $collections = $container->getParameter('rz.media.gallery.provider.collections');

        foreach ($collections as $name => $settings) {
            $galleryPool->addMethodCall('addCollection', array($name, $settings['gallery_provider']));
            $galleryHasMediaPool->addMethodCall('addCollection', array($name, $settings['gallery_has_media_provider']));
        }
    }
}
