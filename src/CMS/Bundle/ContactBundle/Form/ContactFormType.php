<?php

namespace CMS\Bundle\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('alias')
            ->add('htmlForm', null, array('attr' => array('rows' => 20)))
            ->add('receiver')
            ->add('subject')
            ->add('htmlMessage', null, array('attr' => array('rows' => 10)))
            ->add(
                $builder->create('translations', FormType::class, array('label' => 'Traductions', 'by_reference' => true))
                    ->add('message_mail_sent_ok', TextType::class, array(
                        'label' => 'cms.contactform.message.sent_ok',
                        'data' => "Votre message a bien été envoyé"
                    ))
                    ->add('message_mail_sent_nok', TextType::class, array(
                        'label' => 'cms.contactform.message.sent_nok',
                        'data' => "Il y a eu une erreur lors de l'envoi de votre message"
                    ))
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\ContactBundle\Entity\ContactForm'
        ));
    }
}
