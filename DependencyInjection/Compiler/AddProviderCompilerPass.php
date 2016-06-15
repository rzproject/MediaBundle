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
        #set slugify service
        $serviceId = $container->getParameter('rz.media.slugify_service');

        $galleryPool = $container->getDefinition('rz.media.gallery.pool');
        $galleryPool->addMethodCall('setSlugify', array(new Reference($serviceId)));

        $galleryHasMediaPool = $container->getDefinition('rz.media.gallery_has_media.pool');
        $galleryHasMediaPool->addMethodCall('setSlugify', array(new Reference($serviceId)));


        foreach ($container->findTaggedServiceIds('rz.media.gallery_provider') as $id => $attributes) {
            $galleryPool->addMethodCall('addProvider', array($id, new Reference($id)));
        }

        foreach ($container->findTaggedServiceIds('rz.media.gallery_has_media_provider') as $id => $attributes) {
            $galleryHasMediaPool->addMethodCall('addProvider', array($id, new Reference($id)));
        }

        $collections = $container->getParameter('rz.media.gallery.provider.collections');

        foreach ($collections as $name => $settings) {

            if($settings['gallery']['provider']) {
                $galleryPool->addMethodCall('addCollection', array($name, $settings['gallery']['provider'], array()));
                if($container->hasDefinition($settings['gallery']['provider'])) {
                    $provider = $container->getDefinition($settings['gallery']['provider']);
                    $provider->addMethodCall('setSlugify', array(new Reference($serviceId)));
                }
            }

            if($settings['gallery_has_media']['provider']) {
                $galleryHasMediaPool->addMethodCall('addCollection', array($name, $settings['gallery_has_media']['provider'], $settings['gallery_has_media']['settings']));
                if($container->hasDefinition($settings['gallery_has_media']['provider'])) {
                    $provider =$container->getDefinition($settings['gallery_has_media']['provider']);
                    $provider->addMethodCall('setSlugify', array(new Reference($serviceId)));
                    $provider->addMethodCall('setCategoryManager', array(new Reference('sonata.classification.manager.category')));
                }
            }
        }
    }
}
