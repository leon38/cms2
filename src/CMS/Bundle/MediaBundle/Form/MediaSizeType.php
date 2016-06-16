<?php
/**
 * Created by PhpStorm.
 * User: DCA
 * Date: 15/06/2016
 * Time: 15:30
 */

namespace CMS\Bundle\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MediaSizeType extends AbstractType
{
  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', 'text')
      ->add('width', 'number')
      ->add('height', 'number')
    ;
  }

  /**
   * @param OptionsResolver $resolver
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'CMS\Bundle\CoreBundle\Entity\Option',
    ));
  }

  public function getName()
  {
    return 'media_size';
  }
}