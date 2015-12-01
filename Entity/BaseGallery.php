<?php


namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGallery as Gallery;
use Sonata\ClassificationBundle\Model\CollectionInterface;

abstract class BaseGallery extends Gallery
{
    protected $collection;

    protected $settings;

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

    /**
     * {@inheritDoc}
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * {@inheritDoc}
     */
    public function getSetting($name, $default = null)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }
}
