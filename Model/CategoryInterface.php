<?php

namespace Rz\MediaBundle\Model;

interface CategoryInterface
{
    /**
     * @param $name
     *
     * @return mixed
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName();

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled);

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled();

    /**
     * Set slug
     *
     * @param integer $slug
     */
    public function setSlug($slug);

    /**
     * Get slug
     *
     * @return integer $slug
     */
    public function getSlug();

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription();

    /**
     * Set count
     *
     * @param integer $count
     */
    public function setCount($count);

    /**
     * Get count
     *
     * @return integer $count
     */
    public function getCount();
}
