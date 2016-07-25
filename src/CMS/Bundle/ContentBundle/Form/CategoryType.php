<?php

namespace CMS\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $id = $options['id'];

        $builder
            ->add('title')
            ->add('description', null, array(
                'attr' => array('class' => 'summernote')
            ))
            ->add('published', 'choice', array(
                'label' => 'cms.content.status.status',
                'choices' => array(0 => 'cms.content.status.draft', 1 => 'cms.content.status.published', 2 => 'cms.content.status.pending'),
                'expanded' => false,
                'multiple' => false,
                'data'     => 1
            ))
            ->add('language')
            ->add('parent')
            ->add('ordre', 'entity', array(
                    'class' => 'CMS\Bundle\ContentBundle\Entity\Category',
                    'query_builder' => function(EntityRepository $er) use ($id) {
                        return $er->getSiblings($id);
                    },
                    'empty_value' => 'Après l\'élément',
                    'required'    => false
                ))
          ->add('banner', 'image', array('class' => 'CMS\Bundle\MediaBundle\Entity\Media', 'image_path' => 'webPath', 'image_size' => 'col-md-12'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\ContentBundle\Entity\Category',
            'id' => 0
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tc_bundle_contentbundle_category';
    }
}
