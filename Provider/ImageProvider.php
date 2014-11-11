<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\MediaBundle\Provider;

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\AdminBundle\Form\FormMapper;

use Imagine\Image\ImagineInterface;
use Gaufrette\Filesystem;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use Sonata\AdminBundle\Validator\ErrorElement;

class ImageProvider extends FileProvider
{
    protected $imagineAdapter;

    /**
     * @param string                                                $name
     * @param \Gaufrette\Filesystem                                 $filesystem
     * @param \Sonata\MediaBundle\CDN\CDNInterface                  $cdn
     * @param \Sonata\MediaBundle\Generator\GeneratorInterface      $pathGenerator
     * @param \Sonata\MediaBundle\Thumbnail\ThumbnailInterface      $thumbnail
     * @param array                                                 $allowedExtensions
     * @param array                                                 $allowedMimeTypes
     * @param \Imagine\Image\ImagineInterface                       $adapter
     * @param \Sonata\MediaBundle\Metadata\MetadataBuilderInterface $metadata
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions = array(), array $allowedMimeTypes = array(), ImagineInterface $adapter, MetadataBuilderInterface $metadata = null)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes, $metadata);

        $this->imagineAdapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderMetadata()
    {
        return new Metadata($this->getName(), $this->getName().".description", null, "SonataMediaBundle", array('class' => 'fa fa-picture-o'));
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        if ($format == 'reference') {
            $box = $media->getBox();
        } else {
            $resizerFormat = $this->getFormat($format);
            if ($resizerFormat === false) {
                throw new \RuntimeException(sprintf('The image format "%s" is not defined.
                        Is the format registered in your ``sonata_media`` configuration?', $format));
            }

            $box = $this->resizer->getBox($media, $resizerFormat);
        }

        return array_merge(array(
            'alt'      => $media->getName(),
            'title'    => $media->getName(),
            'src'      => $this->generatePublicUrl($media, $format),
            'width'    => $box->getWidth(),
            'height'   => $box->getHeight()
        ), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceImage(MediaInterface $media)
    {
        return sprintf('%s/%s',
            $this->generatePath($media),
            $media->getProviderReference()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        parent::doTransform($media);

        if (!is_object($media->getBinaryContent()) && !$media->getBinaryContent()) {
            return;
        }
        try {
            $image = $this->imagineAdapter->open($media->getBinaryContent()->getPathname());
        } catch (\Exception $e) {
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);
            return;
        }

        $size  = $image->getSize();

        $media->setWidth($size->getWidth());
        $media->setHeight($size->getHeight());

        $media->setProviderStatus(MediaInterface::STATUS_OK);
    }


    /**
     * {@inheritdoc}
     */
    public function updateMetadata(MediaInterface $media, $force = true)
    {
        try {
            // this is now optimized at all!!!
            $path       = tempnam(sys_get_temp_dir(), 'sonata_update_metadata');
            $fileObject = new \SplFileObject($path, 'w');
            $fileObject->fwrite($this->getReferenceFile($media)->getContent());

            $image = $this->imagineAdapter->open($fileObject->getPathname());
            $size  = $image->getSize();

            $media->setSize($fileObject->getSize());
            $media->setWidth($size->getWidth());
            $media->setHeight($size->getHeight());
        } catch (\LogicException $e) {
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            $media->setSize(0);
            $media->setWidth(0);
            $media->setHeight(0);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = $this->thumbnail->generatePublicUrl($this, $media, $format);
        }

        return $this->getCdn()->getPath($path, $media->getCdnIsFlushable());
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        return $this->thumbnail->generatePrivateUrl($this, $media, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(MediaInterface $media)
    {
        $path = $this->getReferenceImage($media);

        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }

        $this->thumbnail->delete($this, $media);
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
    {
        $subject = $formMapper->getAdmin()->getSubject();
        $formMapper->add('name');
        $formMapper->add('enabled', null, array('required' => false));
        $formMapper->add('authorName');
        $formMapper->add('cdnIsFlushable');
        $formMapper->add('description');
        $formMapper->add('content', 'sonata_formatter_type', array(
            'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
            'format_field'   => 'contentFormatter',
            'source_field'   => 'rawContent',
            'ckeditor_context' => 'news',
            'source_field_options'      => array(
                'attr' => array('class' => 'span12', 'rows' => 20)
            ),
            'target_field'   => 'content',
            'listener'       => true,
        ));
        $formMapper->add('copyright');
        $formMapper->add('binaryContent', 'file', array('required' => false,
                                                        'thumbnail_enabled'=>true,
                                                        'current_subject'=>$subject));
    }

    /**
     * {@inheritdoc}
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper->add('binaryContent', 'file', array('required' => false, 'thumbnail_enabled'=>true));

        $formMapper->add('binaryContent', 'file', array(
            'thumbnail_enabled'=>true,
            'constraints' => array(
                new NotBlank(),
                new NotNull()
            )
        ));
    }
}
