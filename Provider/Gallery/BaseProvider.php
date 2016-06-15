<?php

namespace Rz\MediaBundle\Provider\Gallery;

use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\MediaBundle\Model\GalleryInterface;
use Rz\MediaBundle\Provider\BaseProvider as Provider;

abstract class BaseProvider extends Provider
{
    protected $slugify;

    /**
     * @param string                                           $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * @param mixed $rawSettings
     */
    public function setRawSettings($rawSettings)
    {
        parent::setRawSettings($rawSettings);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(GalleryInterface $object){}

    /**
     * {@inheritdoc}
     */
    public function preUpdate(GalleryInterface $object){}

    /**
     * {@inheritdoc}
     */
    public function postPersist(GalleryInterface $object){}

    /**
     * {@inheritdoc}
     */
    public function postUpdate(GalleryInterface $object){}

    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, GalleryInterface $object){}

    public function load(GalleryInterface $object) {}

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
}
