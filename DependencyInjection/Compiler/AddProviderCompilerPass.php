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

            $lookupContext  = $container->getParameter('rz.media.gallery.default_media_lookup_context');
            $hideContext    = $container->getParameter('rz.media.gallery.default_media_lookup_hide_context');
            $lookupCategory = $container->getParameter('rz.media.gallery.default_media_lookup_category');

            if(array_key_exists('context', $settings['gallery']['media_lookup_settings'])) {
                $lookupContext =$settings['gallery']['media_lookup_settings']['context'];
            }

            if(array_key_exists('hide_context', $settings['gallery']['media_lookup_settings'])) {
                $hideContext =$settings['gallery']['media_lookup_settings']['hide_context'];
            }

            if(array_key_exists('category', $settings['gallery']['media_lookup_settings'])) {
                $lookupCategory =$settings['gallery']['media_lookup_settings']['category'];
            }

            $galleryPool->addMethodCall('addCollection', array($name, $settings['gallery']['provider'], $lookupContext, $hideContext, $lookupCategory));
            $galleryHasMediaPool->addMethodCall('addCollection', array($name, $settings['gallery_has_media']['provider']));
        }
    }
}
