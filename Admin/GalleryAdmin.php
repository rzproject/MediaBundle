<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\MediaBundle\Admin;

use Sonata\MediaBundle\Admin\GalleryAdmin as BaseGalleryAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\AdminBundle\Show\ShowMapper;

class GalleryAdmin extends BaseGalleryAdmin
{
    protected $formOptions = array('validation_groups'=>array('admin'), 'cascade_validation'=>true);

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $context = $this->getPersistentParameter('context');

        if (!$context) {
            $context = $this->pool->getDefaultContext();
        }

        $formats = array();
        foreach ((array) $this->pool->getFormatNamesByContext($context) as $name => $options) {
            $formats[$name] = $name;
        }

        $contexts = array();
        foreach ((array) $this->pool->getContexts() as $contextItem => $format) {
            $contexts[$contextItem] = $contextItem;
        }

        $formMapper
            ->with('Settings')
                ->add('context', 'sonata_type_translatable_choice', array(
                    'choices' => $contexts,
                    'catalogue' => 'SonataMediaBundle'
                ))
                ->add('enabled', null, array('required' => false))
                ->add('defaultFormat', 'choice', array('choices' => $formats))
                ->add('name')
            ->end()
            ->with('Details')
                ->add('image', 'sonata_type_model_list',array('required' => false, 'attr'=>array('class'=>'span8')))
                ->add('abstract')

                ->add('content', 'sonata_formatter_type', array(
                    'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                    'format_field'   => 'contentFormatter',
                    'source_field'   => 'rawContent',
                    'ckeditor_context' => 'news',
                    'source_field_options'      => array(
                        'attr' => array('class' => 'span12', 'rows' => 20)
                    ),
                    'target_field'   => 'content',
                    'listener'       => true,
                ))
            ->end()
            ->with('Assets')
                ->add('galleryHasMedias', 'sonata_type_collection', array(
                        'cascade_validation' => true,
                        'error_bubbling' => false,
                        //'attr' => array('class'=>'span6'),
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        //'inline' => 'standard',
                        'sortable'  => 'position',
                        'link_parameters' => array('context' => $context),
                        'admin_code' => 'sonata.media.admin.gallery_has_media'
                    )
                )
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('enabled')
            ->add('context', null ,array('operator_options'=>array('selectpicker_dropup' => true)))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, array('footable'=>array('attr'=>array('data_toggle'=>true))))
            ->add('enabled', 'boolean', array('editable' => true, 'footable'=>array('attr'=>array('data_hide'=>'phone'))))
            ->add('context', 'trans', array('catalogue' => 'SonataMediaBundle', 'footable'=>array('attr'=>array('data_hide'=>'phone,tablet'))))
            ->add('defaultFormat', 'trans', array('catalogue' => 'SonataMediaBundle', 'footable'=>array('attr'=>array('data_hide'=>'phone,tablet'))))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'Show' => array('template' => 'SonataAdminBundle:CRUD:list__action_show.html.twig'),
                    'Edit' => array('template' => 'SonataAdminBundle:CRUD:list__action_edit.html.twig'),
                    'Delete' => array('template' => 'SonataAdminBundle:CRUD:list__action_delete.html.twig')),
                'footable'=>array('attr'=>array('data_hide'=>'phone,tablet')),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Settings')
                ->add('context')
                ->add('enabled')
                ->add('defaultFormat')
                ->add('name')
            ->end()
            ->with('Details')
                ->add('image')
                ->add('abstract')
                ->add('contentFormatter')
                ->add('content')
            ->end()
            ->with('Assets')
                ->add('galleryHasMedias')
            ->end()
        ;
    }
}
