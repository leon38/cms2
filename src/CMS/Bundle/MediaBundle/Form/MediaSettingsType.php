<?php
namespace CMS\Bundle\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaSettingsType extends AbstractType
{
  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('settings', CollectionType::class, array(
        'entry_type'   => MediaSizeType::class,
        'allow_add'    => true,
        'allow_delete' => true,
        'label'        => ' ',
        'entry_options'  => array('label' => ' ')))
      ->add('submit', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn btn-info btn-fill pull-right')))
    ;
  }

  /**
   * @param OptionsResolver $resolver
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => null,
    ));
  }
}