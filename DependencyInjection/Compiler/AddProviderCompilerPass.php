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
        $pool = $container->getDefinition('rz.media.gallery.pool');

        foreach ($container->findTaggedServiceIds('rz.media.gallery_provider') as $id => $attributes) {
            $pool->addMethodCall('addProvider', array($id, new Reference($id)));
        }
        $collections = $container->getParameter('rz.media.provider.collections');

        foreach ($collections as $name => $settings) {
            $pool->addMethodCall('addCollection', array($name, $settings['provider']));
//            if($container->hasDefinition($settings['provider'])) {
//                $provider =$container->getDefinition($settings['provider']);
//                $provider->addMethodCall('setTemplates', array($templates));
//                $provider->addMethodCall('setPostManager', array($id, new Reference('sonata.news.manager.post')));
//                $provider->addMethodCall('setMediaAdmin', array(new Reference('sonata.media.admin.media')));
//                $provider->addMethodCall('setMediaManager', array(new Reference('sonata.media.manager.media')));
//                $provider->addMethodCall('setMetatagChoices', array($container->getParameter('rz_seo.metatags')));
//            }
        }
    }
}
