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
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Feature Media';
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formatChoices = $this->getFormatChoices($block->getSetting('mediaId'));

        $translator = $this->container->get('translator');

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title', 'text', array('required' => false)),
                array('content', 'ckeditor', array('config_name'=>'minimal_editor', 'attr'=>array('class'=>'span8'))),
                array('orientation', 'choice', array('choices' => array(
                    'left'  => $translator->trans('feature_left_choice', array(), 'SonataMediaBundle'),
                    'right' => $translator->trans('feature_right_choice', array(), 'SonataMediaBundle')
                ))),
                array($this->getMediaBuilder($formMapper), null, array()),
                array('format', 'choice', array('required' => count($formatChoices) > 0, 'choices' => $formatChoices)),
            )
        ));
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
