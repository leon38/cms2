<?php

namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
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
                            $form->add($option->getOptionName(), $type, array(
                                'label' => ucfirst(str_replace('_', ' ', $option->getOptionName())),
                                'data' => $option->getOptionValue(),
                                'required' => false,
                                'expanded' => true,
                                'multiple' => false,
                                'choices' => $choices,
                            ));
                            break;
                        case 'image':
                            $form->add($option->getOptionName(), $type, array(
                                'class' => 'CMS\Bundle\MediaBundle\Entity\Media',
                                'image_path' => 'path',
                                'image_size' => 'col-md-3',
                                'data_class' => null,
                                'data' => $option->getOptionValue(),
                            ));
                            break;
                        case 'editor':
                            $form->add($option->getOptionName(), 'textarea', array(
                                'label' => ucfirst(str_replace('_', ' ', $option->getOptionName())),
                                'data' => $option->getOptionValue(),
                                'required' => false,
                                'attr' => array('class' => 'summernote')
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