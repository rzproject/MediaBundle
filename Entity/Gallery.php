<?php

namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGallery;

/**
 * Bundle\MediaBundle\Entity\BaseGallery
 */
abstract class Gallery extends BaseGallery
{
    protected $image;
    protected $abstract;
    protected $content;

    /**
     * @param mixed $image
     */
    public function setImage ($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage ()
    {
        return $this->image;
    }

    /**
     * @param mixed $abstract
     */
    public function setAbstract ($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * @return mixed
     */
    public function getAbstract ()
    {
        return $this->abstract;
    }

    /**
     * @param mixed $content
     */
    public function setContent ($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent ()
    {
        return $this->content;
    }
}
