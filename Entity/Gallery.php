<?php

namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGallery;
use Sonata\MediaBundle\Model\MediaInterface;

class Gallery extends BaseGallery
{
    protected $abstract;
    protected $content;
    protected $image;
    protected $rawContent;
    protected $contentFormatter;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->enabled = true;

    }

    /**
     * {@inheritdoc}
     */
    public function setImage(MediaInterface $image = null)
    {
        $this->image = $image;
    }

    /**
     * {@inheritdoc}
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * {@inheritdoc}
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * {@inheritdoc}
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $contentFormatter
     */
    public function setContentFormatter($contentFormatter)
    {
        $this->contentFormatter = $contentFormatter;
    }

    /**
     * @return mixed
     */
    public function getContentFormatter()
    {
        return $this->contentFormatter;
    }

    /**
     * @param mixed $rawContent
     */
    public function setRawContent($rawContent)
    {
        $this->rawContent = $rawContent;
    }

    /**
     * @return mixed
     */
    public function getRawContent()
    {
        return $this->rawContent;
    }
}
