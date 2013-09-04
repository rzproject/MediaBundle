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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\Definition\Processor;
use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;


/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RzMediaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        /**
         * TODO: create configuration file
         */
        $loader->load('admin_orm.xml');
        $loader->load('orm.xml');
        $loader->load('provider.xml');
        $loader->load('block.xml');
        $loader->load('form.xml');
        $loader->load('service.xml');


        $this->configureParameterClass($container, $config);
        $this->configureAdminClass($config, $container);
        $this->configureTranslationDomain($config, $container);
        $this->configureController($config, $container);
        $this->configureRzTemplates($config, $container);

        // merge RzFieldTypeBundle to RzAdminBundle
        $container->setParameter('twig.form.resources',
                                 array_merge(
                                     $container->getParameter('twig.form.resources'),
                                     array('RzMediaBundle:Form:rz_media_form_type.html.twig')
                                 ));

        $this->registerDoctrineMapping($config);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureAdminClass($config, ContainerBuilder $container)
    {
        $container->setParameter('sonata.media.admin.media.class', $config['admin']['media']['class']);
        $container->setParameter('sonata.media.admin.gallery.class', $config['admin']['gallery']['class']);
        $container->setParameter('sonata.media.admin.gallery_has_media.class', $config['admin']['gallery_has_media']['class']);

        $container->setParameter('rz_media.admin.category.class', $config['admin']['category']['class']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureTranslationDomain($config, ContainerBuilder $container)
    {
        $container->setParameter('sonata.media.admin.media.translation_domain', $config['admin']['media']['translation']);
        $container->setParameter('sonata.media.admin.gallery.translation_domain', $config['admin']['gallery']['translation']);
        $container->setParameter('sonata.media.admin.gallery_has_media.translation_domain', $config['admin']['gallery_has_media']['translation']);

        $container->setParameter('rz_media.admin.category.translation_domain', $config['admin']['category']['translation']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureController($config, ContainerBuilder $container)
    {
        $container->setParameter('sonata.media.admin.media.controller', $config['admin']['media']['controller']);
        $container->setParameter('sonata.media.admin.gallery.controller', $config['admin']['gallery']['controller']);
        $container->setParameter('sonata.media.admin.gallery_has_media.controller', $config['admin']['gallery_has_media']['controller']);

        $container->setParameter('rz_media.admin.category.controller', $config['admin']['category']['controller']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureRzTemplates($config, ContainerBuilder $container)
    {
        $container->setParameter('rz_media.configuration.media.templates', $config['admin']['media']['templates']);
        $container->setParameter('rz_media.configuration.gallery.templates', $config['admin']['gallery']['templates']);
        $container->setParameter('rz_media.configuration.gallery_has_media.templates', $config['admin']['gallery_has_media']['templates']);

        $container->setParameter('rz_media.configuration.category.templates', $config['admin']['category']['templates']);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     */
    public function configureParameterClass(ContainerBuilder $container, array $config)
    {
        $container->setParameter('sonata.media.admin.media.entity', $config['class']['media']);
        $container->setParameter('sonata.media.admin.gallery.entity', $config['class']['gallery']);
        $container->setParameter('sonata.media.admin.gallery_has_media.entity', $config['class']['gallery_has_media']);

        $container->setParameter('rz_media.admin.category.entity', $config['class']['category']);

        $container->setParameter('sonata.media.media.class', $config['class']['media']);
        $container->setParameter('sonata.media.gallery.class', $config['class']['gallery']);
//        $container->getDefinition('sonata.media.form.type.media')->replaceArgument(1, $config['class']['media']);

        $container->setParameter('rz_media.category.class', $config['class']['category']);
    }

    /**
     * @param array $config
     *
     * @return void
     */
    public function registerDoctrineMapping(array $config)
    {
        $collector = DoctrineCollector::getInstance();

        $collector->addAssociation($config['class']['gallery'], 'mapManyToOne', array(
            'fieldName' => 'image',
            'targetEntity' => $config['class']['media'],
            'cascade' =>
            array(
                0 => 'remove',
                1 => 'persist',
                2 => 'refresh',
                3 => 'merge',
                4 => 'detach',
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' =>
            array(
                array(
                    'name' => 'image_id',
                    'referencedColumnName' => 'id',
                ),
            ),
            'orphanRemoval' => false,
        ));

        $collector->addAssociation($config['class']['gallery'], 'mapManyToOne', array(
            'fieldName' => 'category',
            'targetEntity' => $config['class']['category'],
            'cascade' =>
            array(
                0 => 'remove',
                1 => 'persist',
                2 => 'refresh',
                3 => 'merge',
                4 => 'detach',
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' =>
            array(
                array(
                    'name' => 'category_id',
                    'referencedColumnName' => 'id',
                ),
            ),
            'orphanRemoval' => false,
        ));

        $collector->addAssociation($config['class']['media'], 'mapManyToOne', array(
            'fieldName' => 'category',
            'targetEntity' => $config['class']['category'],
            'cascade' =>
            array(
                0 => 'remove',
                1 => 'persist',
                2 => 'refresh',
                3 => 'merge',
                4 => 'detach',
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' =>
            array(
                array(
                    'name' => 'category_id',
                    'referencedColumnName' => 'id',
                ),
            ),
            'orphanRemoval' => false,
        ));
    }
}
