<?php

namespace CMS\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
                    'label' => 'cms.content.comment.pseudo',
                    'attr' => array(
                        'placeholder' => 'cms.content.comment.pseudo'
                    )
                ))
            ->add('email', EmailType::class,
                array(
                    'label' => 'cms.content.comment.email',
                    'attr' => array(
                        'placeholder' => 'cms.content.comment.email_placeholder'
                    )
                ))
            ->add('message', TextareaType::class,
                array(
                    'label' => 'cms.content.comment.message',
                    'attr' => array(
                        'placeholder' => 'cms.content.comment.message_placeholder'
                    )
                ))
            ->add('captcha', HiddenType::class, array('mapped' => false));
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
