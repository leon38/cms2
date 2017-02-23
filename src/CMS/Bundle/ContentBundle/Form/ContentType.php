<?php

namespace CMS\Bundle\ContentBundle\Form;

use CMS\Bundle\ContentBundle\Form\EventListener\AddEditFieldsSubscriber;
use CMS\Bundle\ContentBundle\Form\EventListener\AddEditMetasSubscriber;
use CMS\Bundle\ContentBundle\Form\EventListener\AddNewFieldsSubscriber;
use CMS\Bundle\CoreBundle\Form\Type\EntityHiddenType;
use CMS\Bundle\CoreBundle\Form\Type\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Doctrine\ORM\EntityRepository;

class ContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'cms.content.title'))
            ->add('url', TextType::class, array(
                    'label' => 'cms.content.alias',
                    'attr' => array('class' => 'url', 'data-target' => 'content_title'),
                )
            )
            ->add('description', null, array(
                    'label' => 'cms.content.description',
                    'attr' => array('class' => 'summernote')
                )
            )
            ->add('chapo', TextareaType::class, array('label' => 'cms.content.chapo', 'attr' => array('class' => 'count_chars'), 'help' => ''))
            ->add('published', ChoiceType::class, array(
                    'label' => 'cms.content.status.status',
                    'choices' => array(
                        'cms.content.status.draft' => 0,
                        'cms.content.status.published' => 1,
                        'cms.content.status.pending' => 2,
                    ),
                    'expanded' => false,
                    'multiple' => false,
                    'data' => 1,
                )
            )
            ->add('language', null, array('label' => 'cms.content.languages'))
            ->add('taxonomy', EntityHiddenType::class, array(
                    'class' => 'CMS\Bundle\ContentBundle\Entity\ContentTaxonomy',
                    'attr' => array('readonly' => 'readonly'),
                )
            )
            ->add('categories', EntityType::class, array(
                    'label' => 'cms.content.categories',
                    'class' => 'ContentBundle:Category',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where('c.level > 0')
                            ->andWhere('c.published = 1')
                            ->orderBy('c.lft', 'ASC');
                    },
                    'multiple' => true,
                    'expanded' => true,
                    'choice_label' => 'toStringLevelSpace',
                )
            )
            ->add('thumbnail', ImageType::class, array(
                    'class' => 'CMS\Bundle\MediaBundle\Entity\Media',
                    'image_path' => 'webPath',
                    'image_size' => 'col-md-12',
                    'compound' => false
                )
            )
            ->add('featured')
            ->add('fieldValuesTemp', null, array('compound' => true, 'label' => ' '))
            ->add('metaValuesTemp', null, array('compound' => true, 'label' => ' '));

        $fields = $options['fields'];
        $fieldvalues = $options['fieldvalues'];

        // edit
        if (!empty($fieldvalues)) {
            $builder->addEventSubscriber(new AddEditFieldsSubscriber($fieldvalues, $fields));
        } else { // new content
            $builder->addEventSubscriber(new AddNewFieldsSubscriber($fields));
        }


        $metas = $options['metas'];
        $metavalues = $options['metavalues'];

        if (!empty($metavalues)) {
            $builder->addEventSubscriber(new AddEditMetasSubscriber($metavalues, $metas));
        } else { // new content
            $builder->addEventSubscriber(new AddEditMetasSubscriber(array(), $metas));
        }


    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'CMS\Bundle\ContentBundle\Entity\Content',
                'fields' => array(),
                'user' => null,
                'fieldvalues' => array(),
                'metas' => array(),
                'metavalues' => array(),
                'content_id' => 0,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tc_bundle_contentbundle_content';
    }
}
