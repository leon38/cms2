<?php
namespace CMS\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormTypeExtension
 * @package Acme\FrontendBundle\Form\Extension
 */
class FormRowTypeExtension extends AbstractTypeExtension
{
    /**
     * Extends the form type which all other types extend
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
    
    /**
     * Ajoute l'option image_path
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('row_attr'));
    }

    /**
     * Add the extra row_attr option
     *
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'row_attr' => array('class' => 'form-group')
        ));
    }

    /**
     * Pass the set row_attr options to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    	if (isset($options['row_attr']['class']) && !preg_match('/form-group/', $options['row_attr']['class'])) {
    		$options['row_attr']['class'] = 'form-group '.$options['row_attr']['class'];
    	}
    	if (isset($options['row_attr'])) {
            $view->vars['row_attr'] = $options['row_attr'];
        } else {
            $view->vars['row_attr'] = array();
        }
    }
}