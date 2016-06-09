<?php
namespace CMS\Bundle\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', 'dropzone', array('attr' => array('class' => 'dropzone col-md-12', 'data-url' => '/admin/media/upload-media', 'data-type' => 'dropzone'), 'image_path' => 'webPath', 'class_thumb' => 'row'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\MediaBundle\Entity\Media',
        ));
    }
}