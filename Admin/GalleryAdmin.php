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

class GalleryAdmin extends BaseGalleryAdmin
{

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
                ->add('content', 'rz_ckeditor', array('required' => false))
            ->end()
            ->with('Assets')
                ->add('galleryHasMedias', 'sonata_type_collection', array(
                        'cascade_validation' => true,
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
            ->addIdentifier('name', null, array('footable'=>array('attr'=>array('data_toggle'=>true))))
            ->add('enabled', 'boolean', array('editable' => true, 'footable'=>array('attr'=>array('data_hide'=>'phone'))))
            ->add('context', 'trans', array('catalogue' => 'SonataMediaBundle', 'footable'=>array('attr'=>array('data_hide'=>'phone,tablet'))))
            ->add('defaultFormat', 'trans', array('catalogue' => 'SonataMediaBundle', 'footable'=>array('attr'=>array('data_hide'=>'phone,tablet'))))
        ;
    }
}
