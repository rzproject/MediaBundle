<?php

namespace Rz\MediaBundle\Admin;

use Sonata\MediaBundle\Admin\GalleryHasMediaAdmin as BaseGalleryHasMediaAdmin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class GalleryHasMediaAdmin extends BaseGalleryHasMediaAdmin
{
    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->hasRequest()) {
            $link_parameters = array('context' => $this->getRequest()->get('context'));
        } else {
            $link_parameters = array();
        }

        $formMapper
            ->add('media', 'sonata_type_model_list', array('required' => false, 'attr'=>array('class'=>'span12')), array(
                'link_parameters' => $link_parameters
            ))
            ->add('enabled', null, array('required' => false))
            ->add('position', 'hidden')
        ;
    }
}
