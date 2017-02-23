<?php

namespace CMS\Bundle\ContentBundle\Form;

use CMS\Bundle\ContentBundle\Form\EventListener\AddEditMetasSubscriber;
use CMS\Bundle\CoreBundle\Form\Type\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('title', TextType::class,
                array(
                    'label' => 'cms.content.title',
                    'error_bubbling' => false,
                    'invalid_message' => "The title is not valid"
                )
            )
            ->add('url', TextType::class,
                array(
                    'label' => 'cms.content.alias',
                    'attr' => array('class' => 'url', 'data-target' => 'category_title'),
                    'error_bubbling' => false,
                    'invalid_message' => "The url is not valid"
                )
            )
            ->add('description', null, array(
                'attr' => array('class' => 'summernote'),
                'error_bubbling' => false,
                'invalid_message' => "The description is not valid"
            ))
            ->add('published', ChoiceType::class, array(
                'label' => 'cms.content.status.status',
                'choices' => array('cms.content.status.draft' => 0, 'cms.content.status.published' => 1, 'cms.content.status.pending' => 2),
                'expanded' => false,
                'multiple' => false,
                'data' => 1,
                'error_bubbling' => false,
                'invalid_message' => "The published is not valid"
            ))
            ->add('language', null,
                array(
                    'error_bubbling' => false,
                    'invalid_message' => "The language is not valid"
                )
            )
            ->add('parent', EntityType::class,
                array(
                    'class' => 'CMS\Bundle\ContentBundle\Entity\Category',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.lft', 'asc');
                    },
                    'error_bubbling' => false,
                    'invalid_message' => "The parent is not valid",
                    'choice_label' => 'toStringLevelList'
                )
            )
            ->add('ordre', EntityType::class, array(
                'class' => 'CMS\Bundle\ContentBundle\Entity\Category',
                'query_builder' => function (EntityRepository $er) use ($id) {
                    return $er->getSiblings($id);
                },
                'empty_data' => 'Après l\'élément',
                'required' => false,
                'error_bubbling' => false,
                'invalid_message' => "The order is not valid",
                'compound' => false
            ))
            ->add('banner', ImageType::class,
                array(
                    'class' => 'CMS\Bundle\MediaBundle\Entity\Media',
                    'image_path' => 'webPath',
                    'image_size' => 'col-md-12',
                    'invalid_message' => "The banner is not valid",
                    'compound' => false
                )
            )
            ->add('metaValuesTemp', null, array('compound' => true, 'label' => ' ', 'error_bubbling' => false, 'invalid_message' => "The metavalues is not valid"))
        ;
        $metas = $options['metas'];
        $metavalues = $options['metavalues'];

        if (!empty($metavalues)) {
            $builder->addEventSubscriber(new AddEditMetasSubscriber($metavalues, $metas));
        } else { // new content
            $builder->addEventSubscriber(new AddEditMetasSubscriber(array(), $metas));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\ContentBundle\Entity\Category',
            'id' => 0,
            'metas' => array(),
            'metavalues' => array()
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'category';
    }
}
