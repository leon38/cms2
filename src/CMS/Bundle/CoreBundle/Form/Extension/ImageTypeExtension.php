<?php
/**
 * Created by PhpStorm.
 * User: DCA
 * Date: 30/06/2016
 * Time: 16:16
 */

namespace CMS\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageTypeExtension extends AbstractTypeExtension
{
  /**
   * Retourne le nom du type de champ qui est étendu
   *
   * @return string Le nom du type qui est étendu
   */
  public function getExtendedType()
  {
    return 'image';
  }

  /**
   * Ajoute l'option image_path
   *
   * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setOptional(array('image_path'));
    $resolver->setOptional(array('image_size'));
    $resolver->setOptional(array('class_thumb'));
  }

  /**
   * Passe l'url de l'image à la vue
   *
   * @param \Symfony\Component\Form\FormView $view
   * @param \Symfony\Component\Form\FormInterface $form
   * @param array $options
   */
  public function buildView(FormView $view, FormInterface $form, array $options)
  {
    $imageUrl = null;
    $imageSize = 'col-md-3';
    $classThumb = null;
    if (array_key_exists('image_path', $options)) {
      $parentData = $form->getParent()->getData();
      if (null !== $parentData) {
        $accessor = PropertyAccess::createPropertyAccessor();

        if (preg_match('/^\[/', $options['image_path'])) {
          if (isset($parentData['image'])) {
            $imageUrl = $parentData['image']->getWebPath();
          }
        } else if (get_class($parentData) == 'CMS\Bundle\CoreBundle\Classes\GeneralOptions') {
          $tmp_options = $parentData->getOptions();

          foreach($tmp_options as $option) {
              dump($option);
            if ($option->getType() == 'image' && is_object($option->getOptionValue()) && is_object($options['data']) && $option->getOptionValue()->getId() == $options['data']->getId()) {
              $imageUrl = $option->getOptionValue()->getWebPath();
              break;
            }
          }
        } else {
          $imageUrl = $accessor->getValue($parentData, $options['image_path']);
        }
        $imageSize = (isset($options['image_size'])) ? $options['image_size'] : '';
        $classThumb = (isset($options['class_thumb'])) ? $options['class_thumb'] : '';
      } else {
        $imageUrl = null;
        $imageSize = 'col-md-3';
        $classThumb = null;
      }

      // définit une variable "image_url" qui sera disponible à l'affichage du champ
      $view->vars['image_url'] = $imageUrl;
      $view->vars['image_size'] = $imageSize;
      $view->vars['class_thumb'] = $classThumb;
    }
  }
}