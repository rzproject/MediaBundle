<?php

namespace Rz\MediaBundle\Provider\Gallery;

use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\MediaBundle\Model\GalleryHasMediaInterface;

abstract class BaseGalleryHasMediaProvider implements ProviderInterface
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
    public function postPersist(GalleryHasMediaInterface $object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(GalleryHasMediaInterface $object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, GalleryHasMediaInterface $object)
    {
    }

    public function load(GalleryHasMediaInterface $object) {
    }
}
