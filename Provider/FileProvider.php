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

use Sonata\MediaBundle\Provider\FileProvider as BaseProvider;
use Sonata\AdminBundle\Form\FormMapper;

class FileProvider extends BaseProvider
{

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
    {
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
        $formMapper->add('binaryContent', 'file', array('required' => false));
    }

}
