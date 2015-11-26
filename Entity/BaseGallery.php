<?php


namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGallery as Gallery;
use Sonata\ClassificationBundle\Model\CollectionInterface;

abstract class BaseGallery extends Gallery
{
    protected $collection;

    /**
     * @return mixed
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param mixed $collection
     */
    public function setCollection(CollectionInterface $collection)
    {
        $this->collection = $collection;
    }
}
