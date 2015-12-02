<?php

namespace Rz\MediaBundle\Admin\ORM;

use Sonata\MediaBundle\Admin\GalleryAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Rz\MediaBundle\Provider\Gallery\PoolInterface;

class GalleryAdmin extends Admin
{
    protected $collectionManager;

    protected $contextManager;

    protected $galleryPool;

    const GALLERY_DEFAULT_COLLECTION = 'default';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // define group zoning
        $formMapper
            ->with('rz_gallery_settings',  array('class' => 'col-md-6'))->end()
            ->with($this->trans('Options'),  array('class' => 'col-md-6'))->end()
            ->with($this->trans('Gallery'),  array('class' => 'col-md-12'))->end()
        ;

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
            ->with('Options', array('class' => 'col-md-6',))
                ->add('enabled', null, array('required' => false))
                ->add('name')
                ->add('defaultFormat', 'choice', array('choices' => $formats))
            ->end()
        ;

        $formMapper
            ->with('Gallery', array('class' => 'col-md-12',))
            ->add('galleryHasMedias', 'sonata_type_collection', array(
                'cascade_validation' => true,
                    ), array(
                            'edit'              => 'inline',
                            'inline'            => 'standard',
                            'sortable'          => 'position',
                            'link_parameters'   => array('context' => $context),
                            'admin_code'        => 'sonata.media.admin.gallery_has_media',
                        )
                    )
            ->end()
        ;

        $provider = $this->getGalleryPoolProvider();
        $instance = $this->getSubject();

        if ($instance && $instance->getId()) {
            $provider->load($instance);
            $provider->buildEditForm($formMapper);
        } else {
            $provider->buildCreateForm($formMapper);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('enabled', 'boolean', array('editable' => true, 'footable'=>array('attr'=>array('data-breakpoints'=>array('xs', 'sm')))))
            ->add('context', 'trans', array('catalogue' => 'SonataMediaBundle','footable'=>array('attr'=>array('data-breakpoints'=>array('all')))))
            ->add('defaultFormat', 'trans', array('catalogue' => 'SonataMediaBundle','footable'=>array('attr'=>array('data-breakpoints'=>array('xs', 'sm')))))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $options = array(
            'choices' => array(),
        );

        foreach ($this->pool->getContexts() as $name => $context) {
            $options['choices'][$name] = $name;
        }

        $datagridMapper
            ->add('collection', 'doctrine_orm_model_autocomplete', array(), null, array(
                'property' => 'name',
                'callback' => function ($admin, $property, $value) {
                    $datagrid = $admin->getDatagrid();
                    $queryBuilder = $datagrid->getQuery();
                    $queryBuilder->andWhere(sprintf('%s.context = :context', $queryBuilder->getRootAlias()));
                    $queryBuilder->setParameter('context', 'gallery');
                }

            ))
            ->add('context', null, array(
                'show_filter' => $this->getPersistentParameter('hide_context') !== true,
            ), 'choice', $options)
            ->add('name')
            ->add('enabled')
        ;
    }

    /**
     * @return mixed
     */
    public function getCollectionManager()
    {
        return $this->collectionManager;
    }

    /**
     * @param mixed $collectionManager
     */
    public function setCollectionManager($collectionManager)
    {
        $this->collectionManager = $collectionManager;
    }

    /**
     * @return mixed
     */
    public function getContextManager()
    {
        return $this->contextManager;
    }

    /**
     * @param mixed $contextManager
     */
    public function setContextManager($contextManager)
    {
        $this->contextManager = $contextManager;
    }

    /**
     * @return mixed
     */
    public function getGalleryPool()
    {
        return $this->galleryPool;
    }

    /**
     * @param mixed $galleryPool
     */
    public function setGalleryPool(PoolInterface $galleryPool)
    {
        $this->galleryPool = $galleryPool;
    }

    protected function fetchCurrentCollection() {

        $collectionSlug = $this->getPersistentParameter('collection');
        $collection = null;
        if($collectionSlug) {
            $collection = $this->collectionManager->findOneBy(array('slug'=>$collectionSlug));
        } else {
            $collection = $this->collectionManager->findOneBy(array('slug'=>self::GALLERY_DEFAULT_COLLECTION));
        }

        if($collection) {
            return $collection;
        } else {
            return;
        }
    }

    protected function getGalleryPoolProvider() {
        $currentCollection = $this->fetchCurrentCollection();
        if ($this->galleryPool->hasCollection($currentCollection->getSlug())) {
            $providerName = $this->galleryPool->getProviderNameByCollection($currentCollection->getSlug());
        } else {
            $providerName = $this->galleryPool->getProviderNameByCollection($this->galleryPool->getDefaultCollection());
        }

        return $this->galleryPool->getProvider($providerName);
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistentParameters()
    {
        $parameters = parent::getPersistentParameters();
        if(is_array($parameters)) {
            $parameters = array_merge($parameters, array(
                'collection'      => 'default',
                'hide_collection' => $this->hasRequest() ? (int) $this->getRequest()->get('hide_collection', 0) : 0,));
        } else {
            $parameters = array(
                'collection'      => 'default',
                'hide_collection' => $this->hasRequest() ? (int) $this->getRequest()->get('hide_collection', 0) : 0,
            );
        }

        if ($this->getSubject()) {
            $parameters['collection'] = $this->getSubject()->getCollection() ? $this->getSubject()->getCollection()->getSlug() : '';

            return $parameters;
        }

        if ($this->hasRequest()) {
            $parameters['collection'] = $this->getRequest()->get('collection');

            return $parameters;
        }


        return array_merge($parameters, array(
            'context'  => $this->getRequest()->get('context', $this->pool->getDefaultContext()),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        if ($this->hasRequest()) {
            $instance->setContext($this->getRequest()->get('context'));
        }

        if ($collectionSlug = $this->getPersistentParameter('collection') ?: 'default') {
            $collection = $this->collectionManager->findOneBy(array('slug'=>$collectionSlug));

            if (!$collection) {
                //find 'news' context
                $context = $this->contextManager->find('gallery');
                if(!$context) {
                    $context = $this->contextManager->create();
                    $context->setEnabled(true);
                    $context->setId($context);
                    $context->setName($context);
                    $this->contextManager->save($context);
                }
                //create collection
                $collection = $this->collectionManager->create();
                $collection->setContext($context);
                $name = ucwords(str_replace('-', ' ',$collectionSlug));
                $collection->setName($name);
                $collection->setDescription($name);
                $this->collectionManager->save($collection);
            }

            $instance->setCollection($collection);
        }

        return $instance;
    }

}
