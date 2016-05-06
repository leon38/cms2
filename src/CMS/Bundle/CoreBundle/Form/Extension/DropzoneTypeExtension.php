<?php
namespace CMS\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DropzoneTypeExtension extends AbstractTypeExtension
{
    /**
     * Retourne le nom du type de champ qui est étendu
     *
     * @return string Le nom du type qui est étendu
     */
    public function getExtendedType()
    {
        return 'dropzone';
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
        if (array_key_exists('image_path', $options)) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
               $accessor = PropertyAccess::createPropertyAccessor();
               $imageUrl = $accessor->getValue($parentData, $options['image_path']);
               $imageSize = (isset($options['image_size'])) ? $options['image_size'] : '';
               $classThumb = (isset($options['class_thumb'])) ? $options['class_thumb'] : '';
            } else {
                $imageUrl = null;
                $imageSize = null;
            }


            // définit une variable "image_url" qui sera disponible à l'affichage du champ
            $view->vars['image_url'] = $imageUrl;
            $view->vars['image_size'] = $imageSize;
            $view->vars['class_thumb'] = $classThumb;
        }
    }
}