<?php

namespace Rz\MediaBundle\Admin\ORM;

use Sonata\MediaBundle\Admin\ORM\MediaAdmin as BaseMediaAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class MediaAdmin extends BaseMediaAdmin
{
    /**
     * @param  \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('providerReference')
            ->add('enabled')
            ->add('context')
        ;

        $providers = array();

        $providerNames = (array) $this->pool->getProviderNamesByContext($this->getPersistentParameter('context', $this->pool->getDefaultContext()));
        foreach ($providerNames as $name) {
            $providers[$name] = $name;
        }

        $datagridMapper->add('providerName', 'doctrine_orm_choice', array(
            'field_options'=> array(
                'choices' => $providers,
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'expanded' => false,
                'selectpicker_dropup' => true,
            ),
            'field_type'=> 'choice',
            'operator_type'=>'choice',
            'operator_options'=>array('selectpicker_dropup' => true),
        ));
    }
}
