<?php

namespace CMS\Bundle\MenuBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuTaxonomyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug', TextType::class, array('label' => 'cms.menu.alias', 'attr' => array('class' => 'url', 'data-target' => 'cms_bundle_menubundle_menutaxonomy_title')))
            ->add('position', ChoiceType::class, array('choices' => $options['positions'], 'expanded' => false, 'multiple' => false))
            ->add('language')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\MenuBundle\Entity\MenuTaxonomy',
            'positions'  => array()
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_bundle_menubundle_menutaxonomy';
    }
}
