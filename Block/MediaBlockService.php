<?php

namespace Rz\MediaBundle\Block;

use Sonata\MediaBundle\Block\MediaBlockService as BaseMediaBlockService;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\MediaBundle\Admin\BaseMediaAdmin;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Templating\EngineInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Validator\Constraints\NotBlank;

class MediaBlockService extends BaseMediaBlockService
{
    protected $templates;

    /**
     * @return mixed
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param mixed $templates
     */
    public function setTemplates($templates = array())
    {
        $this->templates = $templates;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rz_media_block';
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        if (!$block->getSetting('mediaId') instanceof MediaInterface) {
            $this->load($block);
        }

        $formatChoices = $this->getFormatChoices($block->getSetting('mediaId'));

        $keys[] = array('title', 'text', array('required' => false));
        $keys[] = array($this->getMediaBuilder($formMapper), null, array());
        $keys[] = array('format', 'choice', array('required' => count($formatChoices) > 0, 'choices' => $formatChoices));
        if ($this->getTemplates()) {
            $keys[] = array('template', 'choice', array('choices'=>$this->getTemplates()));
        }

        $formMapper->add('settings', 'sonata_type_immutable_array', array('keys' => $keys, 'attr'=>array('class'=>'rz-immutable-container')));
    }

    /**
     * @param ErrorElement   $errorElement
     * @param BlockInterface $block
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings[mediaId]')
                ->addConstraint(new NotBlank())
            ->end();
    }
}
