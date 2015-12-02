<?php

namespace Rz\MediaBundle\Provider\Gallery;

use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\MediaBundle\Model\GalleryInterface;

abstract class BaseGalleryProvider implements ProviderInterface
{
    /**
     * @param string                                           $name
     */
    public function __construct($name)
    {
        $this->name          = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(GalleryInterface $object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(GalleryInterface $object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, GalleryInterface $object)
    {
    }

    public function load(GalleryInterface $object) {
    }
}
