<?php

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
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Sonata\CoreBundle\Model\ManagerInterface;

class FeaturedGalleriesBlockService extends BaseBlockService
{
    protected $galleryAdmin;

    protected $galleryManager;

    /**
     * @param string                  $name
     * @param EngineInterface         $templating
     * @param ContainerInterface      $container
     * @param GalleryManagerInterface $galleryManager
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
        return 'Featured Gallery';
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
                                   'galleries'   => false,
                                   'title'     => false,
                                   'fill' => true,
                                   'verticalAlign' => 'center',
                                   'horizontalAlign' => 'center',
                                   'template'     => 'RzMediaBundle:Block:block_featured_galleries.html.twig',
                                   'galleryId'    => array()
                               ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        // simulate an association ...
        $fieldDescription = $this->getGalleryAdmin()->getModelManager()->getNewFieldDescriptionInstance($this->getGalleryAdmin()->getClass(), 'media' );
        $fieldDescription->setAssociationAdmin($this->getGalleryAdmin());
        $fieldDescription->setAdmin($formMapper->getAdmin());
        $fieldDescription->setOption('edit', 'list');
        $fieldDescription->setAssociationMapping(array('fieldName' => 'galleries',
                                                       'type' => \Doctrine\ORM\Mapping\ClassMetadataInfo::ONE_TO_MANY,
                                                       'targetEntity' => $this->getGalleryAdmin()->getClass(),
                                                       'cascade'       => array(
                                                               0 => 'persist',
                                                       )));

        // TODO: add label on config
        $builder = $formMapper->create('galleryId', 'sonata_type_model', array(
                                                     'sonata_field_description' => $fieldDescription,
                                                     'class'             => $this->getGalleryAdmin()->getClass(),
                                                     'model_manager'     => $this->getGalleryAdmin()->getModelManager(),
                                                     'label'             => 'Galleries',
                                                     'by_reference' => false,
                                                     'multiple' => true,
                                                     'select2'=>true,
                                                     'btn_add' => false
                                                  ));

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
                                       'keys' => array(
                                           array('title', 'text', array('required' => false, 'attr'=>array('class'=>'span8'))),
                                           array($builder, null, array('attr'=>array('class'=>'span8'))),
                                           array('fill', 'checkbox', array('attr'=>array('class'=>'span3'))),
                                           array('verticalAlign', 'choice', array(
                                               'choices'   => array('center' => 'center',
                                                                    'top' => 'top',
                                                                    'bottom' => 'bottom',
                                                                    '50%' => '50%',
                                                                    '10%' => '10%'

                                               ))),
                                           array('horizontalAlign', 'choice', array(
                                               'choices'   => array('center' => 'center',
                                                                    'top' => 'top',
                                                                    'bottom' => 'bottom',
                                                                    '50%' => '50%',
                                                                    '10%' => '10%'

                                               ))),
                                       )
                                   ));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $galleries = $blockContext->getBlock()->getSetting('galleryId');

        return $this->renderResponse($blockContext->getTemplate(), array(
                                                                     'galleries'   => $galleries,
                                                                     'block'     => $blockContext->getBlock(),
                                                                     'settings'  => $blockContext->getSettings(),
                                                                 ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
        $galleries = $block->getSetting('galleryId');

        if ($galleries) {
            $gal = new ArrayCollection();

            foreach($galleries as $gallery) {
                if ($gallery) {
                    $gal->add($this->galleryManager->findOneBy(array('id' => $gallery)));
                }
            }

            $block->setSetting('galleryId', $gal);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(BlockInterface $block)
    {
        if ($block->getSetting('galleryId') instanceof ArrayCollection) {
            $gal = array();
            foreach($block->getSetting('galleryId') as $gallery) {
                array_push($gal, $gallery->getId());
            }
            $block->setSetting('galleryId', $gal);
        } else {
            $block->setSetting('galleryId', null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(BlockInterface $block)
    {
        if ($block->getSetting('galleryId') instanceof ArrayCollection) {
            $gal = array();
            foreach($block->getSetting('galleryId') as $gallery) {
                array_push($gal, $gallery->getId());
            }
            $block->setSetting('galleryId', $gal);
        } else {
            $block->setSetting('galleryId', null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStylesheets($media)
    {
        return array(
            '/bundles/rzmedia/css/block/rzmedia-featured-gallery.css'
        );
    }

    /**
     * {@inheritdoc}
     */
//    public function getJavascripts($media)
//    {
//        return array(
//            '/bundles/rmzamorajquery/jquery-plugins/imgLiquid/js/imgLiquid.js',
//            '/bundles/rzmedia/js/block/rzmedia-featured-gallery.js');
//    }
}
