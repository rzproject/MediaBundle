<?php

namespace Rz\MediaBundle\Provider\Gallery;

use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\MediaBundle\Model\GalleryInterface;

abstract class BaseGalleryProvider extends BaseProvider
{
    protected $defaultLookupCategory;

    protected $defaultLookupContext;

    protected $defaultLookupHideContext;

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

    public function postPersist(GalleryInterface $object){}

    public function postUpdate(GalleryInterface $object){}

    public function validate(ErrorElement $errorElement, GalleryInterface $object){}

    public function load(GalleryInterface $object){}
}
