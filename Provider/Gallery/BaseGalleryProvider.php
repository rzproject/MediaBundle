<?php

namespace Rz\MediaBundle\Provider\Gallery;

use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\MediaBundle\Model\GalleryInterface;

abstract class BaseGalleryProvider extends BaseProvider
{
    public function postPersist(GalleryInterface $object){}

    public function postUpdate(GalleryInterface $object){}

    public function validate(ErrorElement $errorElement, GalleryInterface $object){}

    public function load(GalleryInterface $object){}
}
