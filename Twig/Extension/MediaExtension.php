<?php

namespace Rz\MediaBundle\Twig\Extension;

use Sonata\MediaBundle\Twig\TokenParser\MediaTokenParser;
use Sonata\MediaBundle\Twig\TokenParser\ThumbnailTokenParser;
use Sonata\MediaBundle\Twig\TokenParser\PathTokenParser;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\MediaBundle\Provider\Pool;
use Gaufrette\Adapter;


class MediaExtension extends \Twig_Extension
{

    /**
     * @param Pool             $mediaService
     * @param ManagerInterface $mediaManager
     */
    public function __construct(Pool $mediaService, ManagerInterface $mediaManager)
    {
        $this->mediaService = $mediaService;
        $this->mediaManager = $mediaManager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('rzmedia_file_exist', array($this, 'mediaFileExist')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function mediaFileExist($media, $format='reference')
    {
        $media = $this->getMedia($media);

        if (!$media) {
            return '';
        }

        $provider = $this->getMediaService()->getProvider($media->getProviderName());
        if (!$provider->getFilesystem()->getAdapter() instanceof Adapter) {
            return false;
        }
        //will only check reference image
        $path = $provider->getReferenceImage($media);
        $baseDir = $provider->getFilesystem()->getAdapter()->getDirectory();
        $fullPath = sprintf('%s/%s', $baseDir, $path);
        return file_exists($fullPath);
    }

    public function getName()
    {
        return 'rzmedia_file_exist';
    }

    /**
     * @return \Sonata\MediaBundle\Provider\Pool
     */
    public function getMediaService()
    {
        return $this->mediaService;
    }


    /**
     * @param mixed $media
     *
     * @return null|\Sonata\MediaBundle\Model\MediaInterface
     */
    private function getMedia($media)
    {
        if (!$media instanceof MediaInterface && strlen($media) > 0) {
            $media = $this->mediaManager->findOneBy(array(
                'id' => $media
            ));
        }

        if (!$media instanceof MediaInterface) {
            return false;
        }

        if ($media->getProviderStatus() !== MediaInterface::STATUS_OK) {
            return false;
        }

        return $media;
    }

}
