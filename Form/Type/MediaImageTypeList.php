<?php

namespace Rz\MediaBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\AdminBundle\Form\DataTransformer\ModelToIdTransformer;

class MediaImageTypeList extends AbstractType
{
    protected $modelManager;
    protected $class;

    public function __construct($modelManager, $class) {
        $this->modelManager = $modelManager;
        $this->class = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                           'model_manager'     => $this->modelManager,
                           'class'             => $this->class,
                           'parent'            => 'text',
        ));
    }


    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'sonata_type_model_list';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rz_type_media_image_list';
    }
}
