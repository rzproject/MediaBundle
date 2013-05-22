<?php

namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\MediaManager as BaseMediaManager;

class MediaManager extends BaseMediaManager
{
    /**
     * {@inheritdoc}
     */
    public function fetchMedia()
    {
        return $this->getRepository()->findAll();
    }
}
