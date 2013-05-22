<?php

namespace Rz\MediaBundle\Provider;

use Sonata\MediaBundle\Provider\FileProvider as BaseProvider;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormBuilder;

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
        $formMapper->add('description', 'rz_ckeditor', array('config_name'=>'simple_editor'));
        $formMapper->add('copyright');
        $formMapper->add('binaryContent', 'file', array('required' => false));
    }

}
