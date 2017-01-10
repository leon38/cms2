<?php

namespace CMS\Bundle\MenuBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('status', null, array('label' => 'cms.entry.published', 'attr' => array('data-toggle' => 'checkbox')))
            ->add('icon_class', TextType::class, array('label' => 'Icone', 'required' => false))
            ->add('external_url', null, array('label' => 'cms.entry.external'))
            ->add('content', null, array('label' => 'cms.entry.content'))
            ->add('category', null, array('label' => 'cms.entry.category'))
            ->add('taxonomy', null, array('label' => 'cms.entry.taxonomy'))
            ->add('parent', EntityType::class, array(
                'label' => 'cms.entry.parent',
                'class' => 'CMS\Bundle\MenuBundle\Entity\Entry',
                'query_builder' => function(EntityRepository $er) use ($entry) {
                    return $er->getAllEntriesMenu($entry->getMenuTaxonomy());
                },
                'placeholder' => '--',
                'required'    => false))
            ->add('ordre', EntityType::class, array(
                    'class' => 'CMS\Bundle\MenuBundle\Entity\Entry',
                    'query_builder' => function(EntityRepository $er) use ($entry) {
                        return $er->getSiblings($entry);
                    },
                    'placeholder' => 'Après l\'élément',
                    'required'    => false
                ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
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
