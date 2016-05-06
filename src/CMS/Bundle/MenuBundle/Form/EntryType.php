<?php

namespace CMS\Bundle\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EntryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entry = $options['entry'];
        $builder
            ->add('title', null, array('label' => 'cms.entry.title'))
            ->add('status', null, array('attr' => array('data-toggle' => 'checkbox')))
            ->add('external_url', null, array('label' => 'cms.entry.external'))
            ->add('content', null, array('label' => 'cms.entry.content'))
            ->add('category', null, array('label' => 'cms.entry.category'))
            ->add('taxonomy', null, array('label' => 'cms.entry.taxonomy'))
            ->add('parent', null, array('label' => 'cms.entry.parent'))
            ->add('ordre', 'entity', array(
                    'class' => 'CMS\Bundle\MenuBundle\Entity\Entry',
                    'query_builder' => function(EntityRepository $er) use ($entry) {
                        return $er->getSiblings($entry);
                    },
                    'empty_value' => 'Après l\'élément',
                    'required'    => false
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\MenuBundle\Entity\Entry',
            'entry' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_bundle_menubundle_entry';
    }
}
