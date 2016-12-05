<?php

namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class WidgetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $widgetclass = $options['widgetclass'];
        
        $builder
            ->add('title')
            ->add('name')
            ->add('params', null, array('compound' => true))
        ;
    
        if (is_object($widgetclass)) {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($widgetclass) {
                    $form = $event->getForm();
                    $params = $form->get('params');
                    foreach($widgetclass->getOptions() as $label => $infos) {
                        switch($infos['type']) {
                            case 'choice':
                                $params->add($infos['name'], ChoiceType::class, array('label' => $label, 'choices' => $infos['choices'], 'data' => $infos['value']));
                                break;
                            default:
                                $params->add($infos['name'], $infos['type'], array('label' => $label, 'data' => $infos['value']));
                                break;
                        }
                    }
                    $form->add('submit', SubmitType::class, array('label' => 'Valider','attr' => array('class' => 'btn btn-info btn-fill pull-right')));
                }
            );
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\CoreBundle\Entity\Widget',
            'widgetclass' => ''
        ));
    }
}
