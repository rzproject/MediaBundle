<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\MediaBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\MediaBundle\Model\GalleryManagerInterface;
use Sonata\MediaBundle\Model\GalleryInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;
use Sonata\CoreBundle\Model\ManagerInterface;



class MediaBxSliderBlockService extends BaseBlockService
{

    protected $galleryAdmin;

    protected $galleryManager;

    /**
     * @param string $name
     * @param EngineInterface $templating
     * @param ContainerInterface $container
     * @param \Sonata\CoreBundle\Model\ManagerInterface|\Sonata\MediaBundle\Model\GalleryManagerInterface $galleryManager
     */
    public function __construct($name, EngineInterface $templating, ContainerInterface $container, ManagerInterface $galleryManager)
    {
        parent::__construct($name, $templating);

        $this->galleryManager = $galleryManager;
        $this->container      = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Media BxSlider';
    }

    /**
     * @return \Sonata\MediaBundle\Provider\Pool
     */
    public function getMediaPool()
    {
        return $this->container->get('sonata.media.pool');
    }

    /**
     * @return \Sonata\AdminBundle\Admin\AdminInterface
     */
    public function getGalleryAdmin()
    {
        if (!$this->galleryAdmin) {
            $this->galleryAdmin = $this->container->get('sonata.media.admin.gallery');
        }

        return $this->galleryAdmin;
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                                   'gallery'   => false,
                                   'title'     => false,
                                   'context'   => false,
                                   'format'    => false,
                                   'mode' => 'horizontal',
                                   'speed' => 500,
                                   'slideMargin' => 0,
                                   'startSlide' => 0,
                                   'randomStart' => false,
                                   'slideSelector' => '',
                                   'infiniteLoop' => true,
                                   'hideControlOnEnd' => false,
                                   'easing' => 'ease',
                                   'captions' => false,
                                   'ticker' => false,
                                   'tickerHover' => false,
                                   'adaptiveHeight' => true,
                                   'adaptiveHeightSpeed' => 500,
                                   'video' => true,
                                   'responsive' => true,
                                   'useCSS' => true,
                                   'preloadImages' => 'visible',
                                   'touchEnabled' => true,
                                   'swipeThreshold' => 50,
                                   'oneToOneTouch' => true,
                                   'preventDefaultSwipeX' =>true,
                                   'preventDefaultSwipeY' => false,
                                   'pager' => true,
                                   'pagerType' => 'full',
                                   'pagerShortSeparator' => '-',
                                   'pagerSelector' => '',
                                   'pagerCustom' => null,
                                   'buildPager' => null,
                                   'controls' => true,
                                   'nextText' => 'Next',
                                   'prevText' => 'Prev',
                                   'nextSelector' => null,
                                   'prevSelector' => null,
                                   'autoControls' => false,
                                   'startText' => 'Start',
                                   'stopText' => 'Stop',
                                   'autoControlsCombine' => false,
                                   'autoControlsSelector' => false,
                                   'auto' => false,
                                   'pause' => 4000,
                                   'autoStart' => true,
                                   'autoDirection' => 'next',
                                   'autoHover' => false,
                                   'autoDelay' => 0,
                                   'minSlides' => 1,
                                   'maxSlides' => 1,
                                   'moveSlides' => 0,
                                   'slideWidth' => 0,
                                   'template'  => 'RzMediaBundle:Block:block_media_bxslider.html.twig',
                                   'galleryId'    => false
                               ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $contextChoices = array();

        foreach ($this->getMediaPool()->getContexts() as $name => $context) {
            $contextChoices[$name] = $name;
        }

        $gallery = $block->getSetting('galleryId');

        $formatChoices = array();

        if ($gallery instanceof GalleryInterface) {

            $formats = $this->getMediaPool()->getFormatNamesByContext($gallery->getContext());
            foreach ($formats as $code => $format) {
                $formatChoices[$code] = ucwords(preg_replace('/default_/', '', strtolower($code)));
            }
            $formatChoices = array_merge($formatChoices, array('reference'=>'Original Size'));
        }

        // simulate an association ...
        $fieldDescription = $this->getGalleryAdmin()->getModelManager()->getNewFieldDescriptionInstance($this->getGalleryAdmin()->getClass(), 'media' );
        $fieldDescription->setAssociationAdmin($this->getGalleryAdmin());
        $fieldDescription->setAdmin($formMapper->getAdmin());
        $fieldDescription->setOption('edit', 'list');
        $fieldDescription->setAssociationMapping(array('fieldName' => 'gallery', 'type' => \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE));

        // TODO: add label on config
        $builder = $formMapper->create('galleryId', 'sonata_type_model', array(
                                                      'sonata_field_description' => $fieldDescription,
                                                      'class'             => $this->getGalleryAdmin()->getClass(),
                                                      'model_manager'     => $this->getGalleryAdmin()->getModelManager(),
                                                      'label'             => 'Gallery'
                                                  ));

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
                                       'keys' => array(
                                           array('title', 'text', array('required' => false, 'attr'=>array('class'=>'span8'))),
                                           array('context', 'choice', array('required' => true, 'choices' => $contextChoices, 'attr'=>array('class'=>'span8'))),
                                           array('format', 'choice', array('required' => count($formatChoices) > 0, 'choices' => $formatChoices, 'attr'=>array('class'=>'span8'))),
                                           array($builder, null, array('attr'=>array('class'=>'span8'))),
                                           array('mode', 'choice', array(
                                               'choices'   => array('horizontal' => 'horizontal', 'vertical' => 'vertical')
                                           )),
                                           array('slideMargin', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('startSlide', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('randomStart', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('slideSelector', 'text', array('attr'=>array('class'=>'span8'))),
                                           array('infiniteLoop', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('hideControlOnEnd', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('infiniteLoop', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('easing', 'choice', array(
                                               'choices'   => array('linear' => 'linear',
                                                                    'ease' => 'ease',
                                                                    'ease-in' => 'ease-in',
                                                                    'ease-out' => 'ease-out',
                                                                    'ease-in-out' => 'ease-in-out'

                                               ))),
                                           array('infiniteLoop', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('easing', 'choice', array(
                                               'choices'   => array('hover' => 'Hover', 'click' => 'Click')
                                           )),
                                           array('captions', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('ticker', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('tickerHover', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('adaptiveHeight', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('adaptiveHeightSpeed', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('video', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('responsive', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('useCSS', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('preloadImages', 'choice', array(
                                               'choices'   => array('all' => 'all', 'visible' => 'visible')
                                           )),
                                           array('touchEnabled', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('swipeThreshold', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('oneToOneTouch', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('preventDefaultSwipeX', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('preventDefaultSwipeY', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('pager', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('pagerType', 'choice', array(
                                               'choices'   => array('full' => 'full', 'short' => 'short')

                                           )),
                                           array('pagerShortSeparator', 'choice', array(
                                               'choices'   => array('/' => '/')
                                           )),
                                           array('pagerSelector', 'text', array('attr'=>array('class'=>'span8'))),
                                           //array('pagerCustom', 'text', array('attr'=>array('class'=>'span8'))),
                                           //array('buildPager', 'text', array('attr'=>array('class'=>'span8'))),
                                           array('controls', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('nextText', 'text', array('attr'=>array('class'=>'span8'))),
                                           array('prevText', 'text', array('attr'=>array('class'=>'span8'))),
                                           array('nextSelector', 'text', array('attr'=>array('class'=>'span8'))),
                                           array('prevSelector', 'text', array('attr'=>array('class'=>'span8'))),
                                           array('autoControls', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('startText', 'text', array('attr'=>array('class'=>'span8'))),
                                           array('stopText', 'text', array('attr'=>array('class'=>'span8'))),
                                           array('autoControlsCombine', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('autoControlsSelector', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('auto', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('pause', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('autoStart', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('autoDirection', 'text', array('attr'=>array('class'=>'span3'))),
                                           array('autoHover', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('autoDelay', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('minSlides', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('maxSlides', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('moveSlides', 'number', array('attr'=>array('class'=>'span3'))),
                                           array('slideWidth', 'number', array('attr'=>array('class'=>'span3'))),
                                       )
                                   ));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $gallery = $blockContext->getBlock()->getSetting('galleryId');

        return $this->renderResponse($blockContext->getTemplate(), array(
                                                                     'gallery'   => $gallery,
                                                                     'block'     => $blockContext->getBlock(),
                                                                     'settings'  => $blockContext->getSettings(),
                                                                     'elements'  => $gallery ? $this->buildElements($gallery) : array(),
                                                                 ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
        $gallery = $block->getSetting('galleryId');

        if ($gallery) {
            $gallery = $this->galleryManager->findOneBy(array('id' => $gallery));
        }

        $block->setSetting('galleryId', $gallery);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(BlockInterface $block)
    {
        $block->setSetting('galleryId', is_object($block->getSetting('galleryId')) ? $block->getSetting('galleryId')->getId() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(BlockInterface $block)
    {
        $block->setSetting('galleryId', is_object($block->getSetting('galleryId')) ? $block->getSetting('galleryId')->getId() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function getStylesheets($media)
    {
        return array(
            '/bundles/rmzamorajquery/jquery-plugins/bxslider/jquery.bxslider.css',
            '/bundles/rzmedia/css/block/rzmedia-bxslider.css'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getJavascripts($media)
    {
        return array(
            '/bundles/rmzamorajquery/jquery-plugins/bxslider/jquery.bxslider.js',
            '/bundles/rzmedia/js/block/rzmedia-bxslider.js'

        );
    }

    /**
     * @param \Sonata\MediaBundle\Model\GalleryInterface $gallery
     *
     * @return array
     */
    private function buildElements(GalleryInterface $gallery)
    {
        $elements = array();
        foreach ($gallery->getGalleryHasMedias() as $galleryHasMedia) {
            if (!$galleryHasMedia->getEnabled()) {
                continue;
            }

            $type = $this->getMediaType($galleryHasMedia->getMedia());

            if (!$type) {
                continue;
            }

            $elements[] = array(
                'title'     => $galleryHasMedia->getMedia()->getName(),
                'caption'   => $galleryHasMedia->getMedia()->getDescription(),
                'type'      => $type,
                'media'     => $galleryHasMedia->getMedia(),
            );
        }

        return $elements;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return false|string
     */
    private function getMediaType(MediaInterface $media)
    {
        if ($media->getContentType() == 'video/x-flv') {
            return 'video';
        } elseif (substr($media->getContentType(), 0, 5) == 'image') {
            return 'image';
        }

        return false;
    }
}
