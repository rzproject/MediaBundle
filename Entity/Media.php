<?php

namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia;

abstract class Media extends BaseMedia
{
     protected $category;

    /**
     * @param mixed $category
     */
    public function setCategory ($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategory ()
    {
        return $this->category;
    }
}
