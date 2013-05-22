<?php

namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\MediaManager as BaseMediaManager;
use Sonata\MediaBundle\Model\MediaInterface;
use Doctrine\ORM\EntityManager;
use Sonata\MediaBundle\Provider\Pool;

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
