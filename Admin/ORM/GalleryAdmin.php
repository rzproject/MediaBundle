<?php

namespace Rz\MediaBundle\Admin\ORM;

use Sonata\MediaBundle\Admin\GalleryAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Rz\CoreBundle\Provider\PoolInterface;

class GalleryAdmin extends Admin
{
    protected $contextManager;

    protected $collectionManager;

    protected $categoryManager;

    protected $galleryPool;

    protected $galleryHasMediaPool;

    protected $slugify;

    protected $defaultContext;

    protected $defaultCollection;

    protected $defaultLookupCategory;

    protected $defaultLookupContext;

    protected $defaultLookupHideContext;

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $provider = $this->getPoolProvider($this->galleryPool);

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


        $mediaDefaultContext = ($provider && $provider->getDefaultLookupContext()) ? $provider->getDefaultLookupContext() : $this->getDefaultLookupContext();
        $mediaHideContext = ($provider && ($provider->getDefaultLookupHideContext() !== null)) ? $provider->getDefaultLookupHideContext() : $this->getDefaultLookupHideContext();
        $mediaDefaultCategory =($provider && ($provider->getDefaultLookupCategory())) ? $provider->getDefaultLookupCategory() : $this->getDefaultLookupCategory();

        $mediaFieldOptions = array(
            'edit'              => 'inline',
            'sortable'          => 'position',
            'link_parameters'   => array('context' => $mediaDefaultContext, 'hide_context'=>$mediaHideContext, 'category'=>$mediaDefaultCategory),
            'admin_code'        => 'sonata.media.admin.gallery_has_media',
        );

        if($childProvider = $this->getPoolProvider($this->galleryHasMediaPool)) {
            $mediaFieldOptions['inline'] = 'standard';
            $mediaTabSettings = array('class' => 'col-md-8');
        } else {
            $mediaFieldOptions['inline'] = 'table';
            $mediaTabSettings = array('class' => 'col-md-12');
        }

        if($provider) {

            $formMapper
                ->tab('Details')
                    ->with('rz_gallery_settings',  array('class' => 'col-md-8'))->end()
                    ->with('rz_gallery_options',  array('class' => 'col-md-4'))->end()
                ->end()
                ->tab('Media')
                    ->with('rz_gallery_gallery',  $mediaTabSettings)->end()
                ->end()
            ;
        } else {
            $formMapper
                ->tab('Details')
                    ->with('rz_gallery_options',  array('class' => 'col-md-8'))->end()
                ->end()
                ->tab('Media')
                    ->with('rz_gallery_gallery',  $mediaTabSettings)->end()
                ->end()
            ;
        }

        $formMapper
            ->tab('Details')
                ->with('rz_gallery_options')
                    ->add('enabled', null, array('required' => false))
                    ->add('name')
                    ->add('defaultFormat', 'choice', array('choices' => $formats))
                ->end()
            ->end()
        ;


        $formMapper
            ->tab('Media')
                ->with('rz_gallery_gallery')
                    ->add('galleryHasMedias', 'sonata_type_collection', array('cascade_validation' => true), $mediaFieldOptions)
                ->end()
            ->end()
        ;

        if($provider) {
            $instance = $this->getSubject();

            if ($instance && $instance->getId()) {
                $provider->load($instance);
                $provider->buildEditForm($formMapper);
            } else {
                $provider->buildCreateForm($formMapper);
            }
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
            ->add('collection', 'doctrine_orm_model_autocomplete', array('show_filter' => false), null, array(
                'property' => 'name',
                'callback' => function ($admin, $property, $value) {
                    $datagrid = $admin->getDatagrid();
                    $queryBuilder = $datagrid->getQuery();
                    $queryBuilder->andWhere(sprintf('%s.context = :context', $queryBuilder->getRootAlias()));
                    $queryBuilder->setParameter('context', $this->getDefaultContext());
                }

            ))
            ->add('context', null, array(
                'show_filter' => $this->getPersistentParameter('hide_context') !== true,
                #'show_filter' => false,
            ), 'choice', $options)
            ->add('name')
            ->add('enabled')
        ;
    }

    /**
     * @return mixed
     */
    public function getDefaultContext()
    {
        return $this->defaultContext;
    }

    /**
     * @param mixed $defaultContext
     */
    public function setDefaultContext($defaultContext)
    {
        $this->defaultContext = $defaultContext;
    }

    /**
     * @return mixed
     */
    public function getDefaultCollection()
    {
        return $this->defaultCollection;
    }

    /**
     * @param mixed $defaultCollection
     */
    public function setDefaultCollection($defaultCollection)
    {
        $this->defaultCollection = $defaultCollection;
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
    public function getCategoryManager()
    {
        return $this->categoryManager;
    }

    /**
     * @param mixed $categoryManager
     */
    public function setCategoryManager($categoryManager)
    {
        $this->categoryManager = $categoryManager;
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

    /**
     * @return mixed
     */
    public function getGalleryHasMediaPool()
    {
        return $this->galleryHasMediaPool;
    }

    /**
     * @param mixed $galleryHasMediaPool
     */
    public function setGalleryHasMediaPool($galleryHasMediaPool)
    {
        $this->galleryHasMediaPool = $galleryHasMediaPool;
    }

    /**
     * @return mixed
     */
    public function getSlugify()
    {
        return $this->slugify;
    }

    /**
     * @param mixed $slugify
     */
    public function setSlugify($slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @return mixed
     */
    public function getDefaultLookupCategory()
    {
        return $this->defaultLookupCategory;
    }

    /**
     * @param mixed $defaultLookupCategory
     */
    public function setDefaultLookupCategory($defaultLookupCategory)
    {
        $this->defaultLookupCategory = $defaultLookupCategory;
    }

    /**
     * @return mixed
     */
    public function getDefaultLookupContext()
    {
        return $this->defaultLookupContext;
    }

    /**
     * @param mixed $defaultLookupContext
     */
    public function setDefaultLookupContext($defaultLookupContext)
    {
        $this->defaultLookupContext = $defaultLookupContext;
    }

    /**
     * @return mixed
     */
    public function getDefaultLookupHideContext()
    {
        return $this->defaultLookupHideContext;
    }

    /**
     * @param mixed $defaultLookupHideContext
     */
    public function setDefaultLookupHideContext($defaultLookupHideContext)
    {
        $this->defaultLookupHideContext = $defaultLookupHideContext;
    }

    protected function fetchCurrentCollection() {

        $collectionSlug = $this->getPersistentParameter('collection');
        $collection = null;
        if($collectionSlug) {
            $collection = $this->collectionManager->findOneBy(array('slug'=>$collectionSlug));
        } else {
            $collection = $this->collectionManager->findOneBy(array('slug'=>$this->getDefaultCollection()));
        }

        if($collection) {
            return $collection;
        } else {
            return;
        }
    }

    protected function getPoolProvider($pool) {
        $currentCollection = $this->fetchCurrentCollection();
        if ($pool->hasCollection($currentCollection->getSlug())) {
            $providerName = $pool->getProviderNameByCollection($currentCollection->getSlug());
        } else {
            $providerName = $pool->getProviderNameByCollection($pool->getDefaultCollection());
        }

        if(!$providerName) {
            return null;
        }

        $provider = $pool->getProvider($providerName);

        if($pool instanceof \Rz\MediaBundle\Provider\Gallery\GalleryPool) {
            $defaultMediaLookupContext = $pool->getMediaLookupContextByCollection($currentCollection->getSlug());
            $provider->setDefaultLookupContext($defaultMediaLookupContext);
            $defaultMediaLookupHideContext = $pool->getMediaLookupHideContextByCollection($currentCollection->getSlug());
            $provider->setDefaultLookupHideContext($defaultMediaLookupHideContext);
            $defaultMediaLookupCategory = $pool->getMediaLookupCategoryByCollection($currentCollection->getSlug());
            $category = $this->categoryManager->findOneBy(array('slug'=>$this->getSlugify()->slugify($defaultMediaLookupCategory), 'context'=>$defaultMediaLookupContext));
            if($category) {
                $provider->setDefaultLookupCategory($category->getId());
            }
        }

        return $provider;


    }

    /**
     * {@inheritdoc}
     */
    public function getPersistentParameters()
    {
        $parameters = parent::getPersistentParameters();
        $collectionSlug = $this->getSlugify()->slugify($this->getDefaultCollection());
        if(is_array($parameters)) {
            $parameters = array_merge($parameters, array(
                'collection'      => $collectionSlug,
                'hide_collection' => $this->hasRequest() ? (int) $this->getRequest()->get('hide_collection', 0) : 0,));
        } else {
            $parameters = array(
                'collection'      => $collectionSlug,
                'hide_collection' => $this->hasRequest() ? (int) $this->getRequest()->get('hide_collection', 0) : 0,);
        }

        if ($this->getSubject()) {
            $parameters['collection'] = $this->getSubject()->getCollection() ? $this->getSubject()->getCollection()->getSlug() : $collectionSlug;
            return $parameters;
        }

        if ($this->hasRequest() && $this->getRequest()->get('collection')) {
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
            $contextSlug = $this->getSlugify()->slugify($this->getRequest()->get('context'));
            $instance->setContext($contextSlug);
        }

        $galleryContext = $this->contextManager->findOneBy(array('id'=>$this->getSlugify()->slugify($this->getDefaultContext())));

        if(!$galleryContext && !$galleryContext instanceof \Sonata\ClassificationBundle\Model\ContextInterface) {
            $galleryContext = $this->getContextManager->generateDefaultContext($this->getDefaultContext());
        }

        $collectionSlug = $this->getPersistentParameter('collection') ?: $this->getSlugify()->slugify($this->getDefaultCollection());
        $collections = $this->collectionManager->findBy(array('context'=>$galleryContext));
        $collection = $this->collectionManager->findOneBy(array('slug'=>$collectionSlug, 'context'=>$galleryContext));


        if (!$collections && !$collection && !$collection instanceof \Sonata\ClassificationBundle\Model\CollectionInterface) {
            $collection = $this->collectionManager->generateDefaultCollection($galleryContext, $this->getDefaultCollection());
        }

        $instance->setCollection($collection);

        return $instance;
    }

}
