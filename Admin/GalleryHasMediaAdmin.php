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

use Sonata\MediaBundle\Admin\GalleryHasMediaAdmin as BaseGalleryHasMediaAdmin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class GalleryHasMediaAdmin extends BaseGalleryHasMediaAdmin
{
    protected $formOptions = array('validation_groups'=>array('admin'), 'cascade_validation'=>true);

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
            ->add('media', 'sonata_type_model_list', array('required' => false, 'attr'=>array('class'=>'span12'), 'btn_delete' => false), array(
                'link_parameters' => $link_parameters
            ))
            ->add('enabled', null, array('required' => false))
            ->add('position', 'hidden')
        ;
    }
}
