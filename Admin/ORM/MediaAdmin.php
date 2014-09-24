<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        $contexts = array();

        foreach ($this->pool->getContexts() as $name => $context) {
            $contexts[$name] = $name;
        }


        $datagridMapper
            ->add('name')
            ->add('providerReference')
            ->add('enabled')
            ->add('context', null, array(), 'choice', array(
                'choices' => $contexts
            ))
            ->add('category', null, array('show_filter' => false))
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
                'selectpicker_dropup' => true,
            ),
            'field_type'=> 'choice',
            'operator_type'=>'choice',
            'operator_options'=>array('selectpicker_dropup' => true),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistentParameters()
    {

        $parameters = array();

        foreach ($this->getExtensions() as $extension) {
            $params = $extension->getPersistentParameters($this);

            if (!is_array($params)) {
                throw new \RuntimeException(sprintf('The %s::getPersistentParameters must return an array', get_class($extension)));
            }

            $parameters = array_merge($parameters, $params);
        }

        if (!$this->hasRequest()) {
            return $parameters;
        }

        if ($filter = $this->getRequest()->get('filter')) {
            $context = $filter['context']['value'];
        } else {
            $context   = $this->getRequest()->get('context', $this->pool->getDefaultContext());
        }

        $providers = $this->pool->getProvidersByContext($context);
        $provider  = $this->getRequest()->get('provider');

        // if the context has only one provider, set it into the request
        // so the intermediate provider selection is skipped
        if (count($providers) == 1 && null === $provider) {
            $provider = array_shift($providers)->getName();
            $this->getRequest()->query->set('provider', $provider);
        }

        return array_merge($parameters,array(
            'provider' => $provider,
            'context'  => $context,
            'category' => $this->getRequest()->get('category'),
        ));
    }
}
