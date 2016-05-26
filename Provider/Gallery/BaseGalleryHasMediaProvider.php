<?php

namespace Rz\MediaBundle\Provider\Gallery;

use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\MediaBundle\Model\GalleryHasMediaInterface;

abstract class BaseGalleryHasMediaProvider extends BaseProvider
{
    public function postPersist(GalleryHasMediaInterface $object){}

    public function postUpdate(GalleryHasMediaInterface $object){}

    public function validate(ErrorElement $errorElement, GalleryHasMediaInterface $object){}

    public function load(GalleryHasMediaInterface $object) {}
}
