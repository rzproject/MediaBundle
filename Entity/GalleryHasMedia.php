<?php

namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGalleryHasMedia;

abstract class GalleryHasMedia extends BaseGalleryHasMedia
{
    public function __construct()
    {
        $this->position = 0;
        $this->enabled  = true;
    }
}
