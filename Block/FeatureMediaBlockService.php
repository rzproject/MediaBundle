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
