<?php

namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('lang', LocaleType::class, array(
                'label' => 'cms.language.language',
                'mapped' => false,
                'attr' => array('onchange' => 'getLanguageInfo($(this))'),
                'data' => $options['language']
            ))
            ->add('name', TextType::class, array(
                'label' => 'cms.language.name',
                'attr' => array('class' => 'name')
            ))
            ->add('code_local', TextType::class, array(
                'label' => 'cms.language.code_local',
                'attr' => array('class' => 'code')
            ))
            ->add('code_lang', TextType::class, array(
                'label' => 'cms.language.code_lang',
                'attr' => array('class' => 'code')
            ))
            ->add('sens_ecriture', ChoiceType::class, array(
                'choices' => array('cms.language.sens.left_right' => 'ltr', 'cms.language.sens.right_left' => 'rtl'),
                'label' => 'cms.language.sens_ecriture',
                'multiple' => false,
                'expanded' => true
            ))
            ->add('ordre', TextType::class, array('label' => 'cms.language.ordre'))
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
