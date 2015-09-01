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
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\MediaBundle\Block\FeatureMediaBlockService as BaseFeatureMediaBlockService;
use Sonata\MediaBundle\Model\MediaInterface;

/**
 * PageExtension
 *
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class FeatureMediaBlockService extends BaseFeatureMediaBlockService
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
        return 'Media - (Feature Media)';
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formatChoices = $this->getFormatChoices($block->getSetting('mediaId'));
        $keys[] = array('title', 'text', array('required' => false));
        $keys[] = array($this->getMediaBuilder($formMapper), null, array());
        $keys[] = array('content', 'ckeditor', array('config_name'=>'minimal_editor', 'attr'=>array('class'=>'span8')));
        $keys[] = array('format', 'choice', array('required' => count($formatChoices) > 0, 'choices' => $formatChoices));
        if($this->getTemplates()) {
            $keys[] = array('template', 'choice', array('choices'=>$this->getTemplates()));
        }

        $formMapper->add('settings', 'sonata_type_immutable_array', array('keys' => $keys));
    }

    /**
     * @param null|\Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return array
     */
    protected function getFormatChoices(MediaInterface $media = null)
    {
        $formatChoices = array('reference'=>'Original Size');
        if (!$media instanceof MediaInterface) {
            return $formatChoices;
        }
        $formats = $this->getMediaPool()->getFormatNamesByContext($media->getContext());
        foreach ($formats as $code => $format) {
            $formatChoices[$code] = $code;
        }
        return $formatChoices;
    }
}
