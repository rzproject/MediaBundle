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
        //$formMapper->add('description', 'rz_ckeditor', array('config_name'=>'simple_editor'));
        $formMapper->add('description', 'ckeditor', array('config_name'=>'simple_editor'));
        $formMapper->add('copyright');
        $formMapper->add('binaryContent', 'file', array('required' => false));
    }

}
