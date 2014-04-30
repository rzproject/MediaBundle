<?php

namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia;

abstract class Media extends BaseMedia
{
     protected $category;

    /**
     * {@inheritdoc}
     */
    public function setCategory ($category)
    {
        $this->category = $category;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory ()
    {
        return $this->category;
    }
}
