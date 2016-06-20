<?php
/**
 * Created by PhpStorm.
 * User: DCA
 * Date: 20/06/2016
 * Time: 08:40
 */

namespace CMS\Bundle\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MediaInfoType  extends AbstractType {

  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('metas')
      ->add('id', 'hidden', array('data' => $options['id']))
      ->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary pull-right')));
  }

  /**
   * @param OptionsResolver $resolver
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'CMS\Bundle\MediaBundle\Entity\Media',
      'id' => 0,
    ));
  }
}