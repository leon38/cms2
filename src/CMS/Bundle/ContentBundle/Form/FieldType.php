<?php

namespace CMS\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class FieldType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fieldclass = $options['fieldclass'];
        $fieldtype = $options['fieldtype'];
        $builder
            ->add('title')
            ->add('name', null, array('attr' => array('class' => 'url', 'data-target' => 'tc_bundle_contentbundle_field_title')))
            ->add('published', 'choice', array(
                'choices'=> array('1'=>'Oui', '0'=>'Non'),
                'expanded' => false,
                'multiple' => false,
                'label'=>'Published'
            ))
            ->add('contentTaxonomy')
            ->add('fieldtype', 'hidden', array('data' => $fieldtype))
            ->add('params', null, array('compound' => true))
        ;


        if (is_object($fieldclass)) {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($fieldclass) {
                    $form = $event->getForm();
                    $params = $form->get('params');
                    foreach($fieldclass->getOptions() as $label => $infos) {
                        switch($infos['type']) {
                            case 'choice':
                                $params->add($infos['name'], $infos['type'], array('label' => $label, 'choices' => $infos['choices'], 'data' => $infos['value']));
                                break;
                            default:
                                $params->add($infos['name'], $infos['type'], array('label' => $label, 'data' => $infos['value']));
                                break;
                        }
                    }
                    $form->add('submit', 'submit', array('attr' => array('class' => 'btn btn-primary pull-right')));
                }
            );
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\ContentBundle\Entity\Field',
            'fieldclass' => '',
            'fieldtype'  => ''
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tc_bundle_contentbundle_field';
    }
}
