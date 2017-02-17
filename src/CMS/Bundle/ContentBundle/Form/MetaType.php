<?php

namespace CMS\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('published')
            ->add('value', null, array('label' => 'balise'))
            ->add('default_value', ChoiceType::class,
                array(
                    'label' => 'Default value',
                    'choices' =>
                        array(
                            '' => '',
                            'Title' => 'Title',
                            'Chapo' => 'Chapo',
                            'URL' => 'URL',
                            'Thumbnail' => 'Thumbnail',
                            'Username twitter' => 'Username twitter',
                            'Username Facebook' => 'Username Facebook'
                        ),
                    'required' => false
                )
            )
            ->add('type')
            ->add('submit', SubmitType::class, array('attr' => array('class' => 'btn btn-info btn-fill pull-right')));
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\ContentBundle\Entity\Meta'
        ));
    }
}
