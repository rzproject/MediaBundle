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


    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->resetViewTransformers()
        ->addViewTransformer(new ModelToIdTransformer($options['model_manager'], $options['class']));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($view->vars['sonata_admin'])) {
            // set the correct edit mode
            $view->vars['sonata_admin']['edit'] = 'list';
        }
    }


    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'text';
    }

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
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rz_type_media_image_list';
    }
}
