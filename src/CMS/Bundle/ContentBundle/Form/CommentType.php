<?php

namespace CMS\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class,
                array(
                    'label' => 'Votre nom *',
                    'attr' => array(
                        'placeholder' => 'Votre nom *'
                    )
                ))
            ->add('email', EmailType::class,
                array(
                    'label' => 'Votre email *',
                    'attr' => array(
                        'placeholder' => 'adresse@email.com'
                    )
                ))
            ->add('message', TextareaType::class,
                array(
                    'label' => 'Dites-nous tout',
                    'attr' => array(
                        'placeholder' => 'Donnez votre opinion, idée ou commentaire en cliquant ici'
                    )
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\ContentBundle\Entity\Comment'
        ));
    }

    public function getName()
    {
        return 'content_bundle_comment_type';
    }
}
