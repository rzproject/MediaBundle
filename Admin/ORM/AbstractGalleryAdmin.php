<?php

namespace Rz\MediaBundle\Admin\ORM;

use Sonata\MediaBundle\Admin\GalleryAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Rz\CoreBundle\Provider\PoolInterface;
use Sonata\MediaBundle\Provider\Pool;

class AbstractGalleryAdmin extends Admin
{
    protected $contextManager;
    protected $collectionManager;
    protected $categoryManager;
    protected $galleryPool;
    protected $childGalleryPool;
    protected $galleryProvider;
    protected $childGalleryProvider;
    protected $slugify;
    protected $defaultContext;
    protected $defaultCollection;
    protected $settings;

    /**
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param Pool   $pool
     */
    public function __construct($code, $class, $baseControllerName, Pool $pool)
    {
        parent::__construct($code, $class, $baseControllerName, $pool);
        $this->settings = [];
        $this->galleryProvider = null;
        $this->childGalleryProvider = null;
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
    public function getGalleryPool()
    {
        return $this->galleryPool;
    }

    /**
     * @param mixed $galleryPool
     */
    public function setGalleryPool($galleryPool)
    {
        $this->galleryPool = $galleryPool;
    }

    /**
     * @return mixed
     */
    public function getChildGalleryPool()
    {
        return $this->childGalleryPool;
    }

    /**
     * @param mixed $childGalleryPool
     */
    public function setChildGalleryPool($childGalleryPool)
    {
        $this->childGalleryPool = $childGalleryPool;
    }

    /**
     * @return null
     */
    public function getGalleryProvider()
    {
        return $this->galleryProvider;
    }

    /**
     * @param null $galleryProvider
     */
    public function setGalleryProvider($galleryProvider)
    {
        $this->galleryProvider = $galleryProvider;
    }

    /**
     * @return null
     */
    public function getChildGalleryProvider()
    {
        return $this->childGalleryProvider;
    }

    /**
     * @return mixed
     */
    public function hasChildGalleryProvider($interface = null)
    {
        if(!$interface) {
            return isset($this->childGalleryProvider);
        }

        if($this->childGalleryProvider instanceof $interface) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function hasGalleryProvider($interface = null)
    {
        if(!$interface) {
            return isset($this->galleryProvider);
        }

        if($this->galleryProvider instanceof $interface) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param mixed $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * {@inheritDoc}
     */
    public function getSetting($name, $default = null)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;
    }
}
