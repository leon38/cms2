<?php
/**
 * User: DCA
 * Date: 13/07/2016
 * Time: 10:39
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MapType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('address', TextareaType::class)
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class);
  }



  public function getParent()
  {
    return 'form';
  }

  public function getName()
  {
    return 'map';
  }
}