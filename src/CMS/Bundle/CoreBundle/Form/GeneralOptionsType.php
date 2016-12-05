<?php

namespace CMS\Bundle\CoreBundle\Form;

use CMS\Bundle\CoreBundle\Form\Type\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;



class GeneralOptionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $general_options = $options['general_options'];
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($general_options) {
                $form = $event->getForm();
                
                foreach ($general_options as $option) {
                    $type = $option->getType();
                    switch ($type) {
                        case 'choice':
                            $choices = array('d F Y' => date('d F Y', time()), 'Y-m-d' => date('Y-m-d', time()), 'm/d/Y' => date('m/d/Y', time()), 'd/m/Y' => date('d/m/Y', time()));
                            $form->add($option->getOptionName(), ChoiceType::class, array(
                                'label' => ucfirst(str_replace('_', ' ', $option->getOptionName())),
                                'data' => $option->getOptionValue(),
                                'required' => false,
                                'expanded' => true,
                                'multiple' => false,
                                'choices' => $choices,
                            ));
                            break;
                        case 'image':
                            $form->add($option->getOptionName(), ImageType::class, array(
                                'class' => 'CMS\Bundle\MediaBundle\Entity\Media',
                                'image_path' => 'path',
                                'image_size' => 'col-md-3',
                                'data_class' => null,
                                'data' => $option->getOptionValue(),
                            ));
                            break;
                        case 'editor':
                            $form->add($option->getOptionName(), TextareaType::class, array(
                                'label' => ucfirst(str_replace('_', ' ', $option->getOptionName())),
                                'data' => $option->getOptionValue(),
                                'required' => false,
                                'attr' => array('class' => 'summernote')
                            ));
                            break;
                        case 'text':
                            $form->add($option->getOptionName(), TextType::class, array(
                                'label' => ucfirst(str_replace('_', ' ', $option->getOptionName())),
                                'data' => $option->getOptionValue(),
                                'required' => false
                            ));
                            break;
                        case 'email':
                            $form->add($option->getOptionName(), EmailType::class, array(
                                'label' => ucfirst(str_replace('_', ' ', $option->getOptionName())),
                                'data' => $option->getOptionValue(),
                                'required' => false
                            ));
                            break;
                        case 'timezone':
                            $form->add($option->getOptionName(), TimezoneType::class, array(
                                'label' => ucfirst(str_replace('_', ' ', $option->getOptionName())),
                                'data' => $option->getOptionValue(),
                                'required' => false
                            ));
                            break;
                        default:
                            $form->add($option->getOptionName(), $type, array(
                                'label' => ucfirst(str_replace('_', ' ', $option->getOptionName())),
                                'data' => $option->getOptionValue(),
                                'required' => false
                            ));
                    }
                }
            });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\CoreBundle\Classes\GeneralOptions',
            'general_options' => array()
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tc_bundle_corebundle_user';
    }
}