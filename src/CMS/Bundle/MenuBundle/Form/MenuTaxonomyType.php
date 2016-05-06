<?php

namespace CMS\Bundle\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('slug', 'text', array('label' => 'cms.menu.alias', 'attr' => array('class' => 'url', 'data-target' => 'cms_bundle_menubundle_menutaxonomy_title')))
            ->add('language')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\MenuBundle\Entity\MenuTaxonomy'
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
