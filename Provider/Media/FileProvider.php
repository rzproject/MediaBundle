<?php

namespace Rz\MediaBundle\Provider\Media;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\MediaBundle\Provider\FileProvider as BaseFileProvider;

class FileProvider extends BaseFileProvider
{
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
        $formMapper->add('copyright');
        $formMapper->add('binaryContent', 'file', array('required' => false, 'file_path'=>$this->generatePublicUrl($subject, 'admin')));
    }
}
