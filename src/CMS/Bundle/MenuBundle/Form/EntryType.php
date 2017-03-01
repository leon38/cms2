<?php

namespace CMS\Bundle\MenuBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
            ->add('title', TextType::class, array('label' => 'cms.entry.title'))
            ->add('status', CheckboxType::class, array('label' => 'cms.entry.status', 'attr' => array('data-toggle' => 'checkbox')))
            ->add('icon_class', TextType::class, array('label' => 'Icone', 'required' => false))
            ->add('external_url', UrlType::class, array('label' => 'cms.entry.external', 'required' => false))
            ->add('content', EntityType::class, array(
                'label' => 'cms.entry.content',
                'class' => 'CMS\Bundle\ContentBundle\Entity\Content',
                'placeholder' => '--',
                'empty_data'  => null,
                'required' => false
            ))
            ->add('category', EntityType::class, array(
                'label' => 'cms.entry.category',
                'class' => 'CMS\Bundle\ContentBundle\Entity\Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.lft', 'asc');
                },
                'placeholder' => '--',
                'empty_data'  => null,
                'choice_label' => 'toStringLevelList',
                'required' => false))
            ->add('taxonomy', EntityType::class, array(
                'label' => 'cms.entry.taxonomy',
                'class' => 'CMS\Bundle\ContentBundle\Entity\ContentTaxonomy',
                'placeholder' => '--',
                'empty_data'  => null,
                'required' => false
            ))
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
