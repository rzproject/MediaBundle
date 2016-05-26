<?php

namespace Rz\MediaBundle\Admin\ORM;

use Sonata\MediaBundle\Admin\GalleryHasMediaAdmin as Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class GalleryHasMediaAdmin extends Admin
{
    protected $collectionManager;

    protected $contextManager;

    protected $galleryHasMediaPool;

    const GALLERY_HAS_MEDIA_DEFAULT_COLLECTION = 'default';

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $link_parameters = array();

        if ($this->hasParentFieldDescription()) {
            $link_parameters = $this->getParentFieldDescription()->getOption('link_parameters', array());
        }

        if ($this->hasRequest()) {
            $context = $this->getRequest()->get('context', null);

            if (null !== $context) {
                $link_parameters['context'] = $context;
            }
        }

        // define group zoning
        $formMapper
            ->tab('Media')
                ->with('rz_gallery_has_media_media',  array('class' => 'col-md-12'))->end()
            ->end()
            ->tab('Settings')
                ->with('rz_gallery_has_media_settings',  array('class' => 'col-md-6'))->end()
            ->end()
        ;

        $formMapper
            ->tab('Media')
                ->with('rz_gallery_has_media_media',  array('class' => 'col-md-12'))
                    ->add('media', 'sonata_type_model_list', array('required' => false), array(
                        'link_parameters' => $link_parameters,
                    ))
                    ->add('enabled', null, array('required' => false))
                    ->add('position', 'hidden')
                ->end()
            ->end()
        ;

        $provider = $this->getGalleryHasMediaPoolProvider();
        $instance = $this->getSubject();

        if ($instance && $instance->getId()) {
            $provider->load($instance);
            $provider->buildEditForm($formMapper);
        } else {
            $provider->buildCreateForm($formMapper);
        }
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('media')
            ->add('gallery')
            ->add('position')
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

    protected function fetchCurrentCollection() {

        $collectionSlug = $this->getPersistentParameter('collection');
        $collection = null;
        if($collectionSlug) {
            $collection = $this->collectionManager->findOneBy(array('slug'=>$collectionSlug));
        } else {
            $collection = $this->collectionManager->findOneBy(array('slug'=>self::GALLERY_HAS_MEDIA_DEFAULT_COLLECTION));
        }

        if($collection) {
            return $collection;
        } else {
            return;
        }
    }

    protected function getGalleryHasMediaPoolProvider() {
        $currentCollection = $this->fetchCurrentCollection();
        if ($this->galleryHasMediaPool->hasCollection($currentCollection->getSlug())) {
            $providerName = $this->galleryHasMediaPool->getProviderNameByCollection($currentCollection->getSlug());
        } else {
            $providerName = $this->galleryHasMediaPool->getProviderNameByCollection($this->galleryHasMediaPool->getDefaultCollection());
        }

        return $this->galleryHasMediaPool->getProvider($providerName);
    }
}
