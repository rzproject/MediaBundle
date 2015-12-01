<?php

namespace Rz\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RzMediaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('provider.xml');
        $loader->load('block.xml');

        $this->configureManagerClass($config, $container);
        $this->configureAdminClass($config, $container);
        $this->configureBlocks($config['blocks'], $container);
        $this->configureProviders($container, $config);
        $this->registerDoctrineMapping($config);

    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureManagerClass($config, ContainerBuilder $container)
    {
        $container->setParameter('rz.media.entity.manager.media.class',             $config['manager_class']['orm']['media']);
        $container->setParameter('rz.media.entity.manager.gallery.class',           $config['manager_class']['orm']['gallery']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureAdminClass($config, ContainerBuilder $container)
    {
        $container->setParameter('rz.media.admin.media.class',              $config['admin']['media']['class']);
        $container->setParameter('rz.media.admin.media.controller',         $config['admin']['media']['controller']);
        $container->setParameter('rz.media.admin.media.translation_domain', $config['admin']['media']['translation']);

        $container->setParameter('rz.media.admin.gallery.class',              $config['admin']['gallery']['class']);
        $container->setParameter('rz.media.admin.gallery.controller',         $config['admin']['gallery']['controller']);
        $container->setParameter('rz.media.admin.gallery.translation_domain', $config['admin']['gallery']['translation']);

        $container->setParameter('rz.media.admin.gallery_has_media.class',              $config['admin']['gallery_has_media']['class']);
        $container->setParameter('rz.media.admin.gallery_has_media.controller',         $config['admin']['gallery_has_media']['controller']);
        $container->setParameter('rz.media.admin.gallery_has_media.translation_domain', $config['admin']['gallery_has_media']['translation']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureBlocks($config, ContainerBuilder $container)
    {
        ####################################
        # rz.media.block.media
        ####################################

        # class
        $container->setParameter('rz.media.block.media.class', $config['media']['class']);
        # template
        if($temp = $config['media']['templates']) {
            $templates = array();
            foreach ($temp as $template) {
                $templates[$template['path']] = $template['name'];
            }
            $container->setParameter('rz.media.block.media.templates', $templates);
        }
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     */
    public function configureProviders(ContainerBuilder $container, $config)
    {
        $pool = $container->getDefinition('rz.media.gallery.pool');
        $pool->replaceArgument(0, $config['default_collection']);

        //set default collection
        $container->setParameter('rz.media.default_collection', $config['default_collection']);
        $container->setParameter('rz.media.provider.collections', $config['collections']);
    }

    /**
     * @param array $config
     */
    public function registerDoctrineMapping(array $config)
    {
        $collector = DoctrineCollector::getInstance();

        if (interface_exists('Sonata\ClassificationBundle\Model\CollectionInterface')) {
            $collector->addAssociation($config['class']['gallery'], 'mapManyToOne', array(
                'fieldName'     => 'collection',
                'targetEntity'  => $config['class']['collection'],
                'cascade'       => array(
                    'persist',
                ),
                'mappedBy'      => null,
                'inversedBy'    => null,
                'joinColumns'   => array(
                    array(
                        'name'                 => 'collection_id',
                        'referencedColumnName' => 'id',
                        'onDelete'             => 'SET NULL',
                    ),
                ),
                'orphanRemoval' => false,
            ));
        }

    }
}
