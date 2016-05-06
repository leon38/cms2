<?php

namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use CMS\Bundle\CoreBundle\Entity\User;

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
            function(FormEvent $event) use ($general_options) {
                $form = $event->getForm();

                foreach($general_options as $option) {
                	switch($option->getOptionName()) {
                		case 'email_admin':
                			$type = 'email';
                			break;
                		case 'timezone':
                			$type = 'timezone';
                			break;
                		case 'date_format':
                			$type = 'choice';
                			$choices = array('d F Y' => date('d F Y', time()), 'Y-m-d' => date('Y-m-d', time()), 'm/d/Y' => date('m/d/Y', time()), 'd/m/Y' => date('d/m/Y', time()));
                			break;
                		default:
                			$type = 'text';
                			break;
                	}
                	if($type == 'choice') {
                    	$form->add($option->getOptionName(), $type, array(
                    		'label' => $option->getOptionName(),
                    		'data' => $option->getOptionValue(),
                    		'required' => false,
                    		'expanded' => true,
                    		'multiple' => false,
                    		'choices' => $choices
                    	));
                    } else {
                    	$form->add($option->getOptionName(), $type, array(
                    		'label' => $option->getOptionName(),
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