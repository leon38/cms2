<?php

namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //var_dump($options['language']); die;
        $builder
            ->add('lang', 'locale', array(
                'label' => 'cms.language.language',
                'mapped' => false,
                'attr' => array('onchange' => 'getLanguageInfo($(this))'),
                'data' => $options['language']
            ))
            ->add('name', null, array(
                'label' => 'cms.language.name',
                'attr' => array('class' => 'name')
            ))
            ->add('code_local', null, array(
                'label' => 'cms.language.code_local',
                'attr' => array('class' => 'code')
            ))
            ->add('code_lang', null, array(
                'label' => 'cms.language.code_lang',
                'attr' => array('class' => 'code')
            ))
            ->add('sens_ecriture', 'choice', array(
                'choices' => array('ltr' => 'cms.language.sens.left_right', 'rtl' => 'cms.language.sens.right_left'),
                'label' => 'cms.language.sens_ecriture',
                'multiple' => false,
                'expanded' => true
            ))
            ->add('ordre', null, array('label' => 'cms.language.ordre'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\CoreBundle\Entity\Language',
            'language'   => ''
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tc_bundle_corebundle_language';
    }
}
