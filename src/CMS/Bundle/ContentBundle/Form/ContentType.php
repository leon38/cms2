<?php

namespace CMS\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add('url', 'text', array('label' => 'cms.content.alias', 'attr' => array('class' => 'url', 'data-target' => 'tc_bundle_contentbundle_content_title')))
            ->add('description', null, array('label' => 'cms.content.description','attr' => array('class' => 'summernote')))
            ->add('published', 'choice', array(
                'label' => 'cms.content.status.status',
                'choices' => array(0 => 'cms.content.status.draft', 1 => 'cms.content.status.published', 2 => 'cms.content.status.pending'),
                'expanded' => false,
                'multiple' => false,
                'data'     => 1
            ))
            ->add('language', null, array('label' => 'cms.content.languages'))
            ->add('taxonomy', 'entity_hidden', array(
                'class' => 'CMS\Bundle\ContentBundle\Entity\ContentTaxonomy',
                'attr' => array('readonly' => 'readonly')))
            ->add('categories', 'entity', array(
                'label' => 'cms.content.categories',
                'class' => 'ContentBundle:Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                              ->where('c.level > 0')
                              ->orderBy('c.lft', 'ASC');
                },
                'multiple' => true,
                'expanded' => true,
            ))
            //->add('author', 'entity_hidden', array('class' => 'CMS\Bundle\CoreBundle\Entity\User'))
            ->add('thumbnail', 'image', array('class' => 'CMS\Bundle\MediaBundle\Entity\Media', 'image_path' => 'webPath', 'image_size' => 'col-md-12'))
            ->add('fieldValuesTemp', null, array('compound' => true, 'label' => ' '))
        ;

        $fields = $options['fields'];
        $fieldvalues = $options['fieldvalues'];

        // edit
        if ( !empty($fieldvalues) ) {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($fieldvalues, $fields) {
                    $form = $event->getForm();
                    $fieldvaluesTemp = $form->get('fieldValuesTemp');
                    $names = array();
                    foreach($fieldvalues as $fieldvalue) {
                        $field = $fieldvalue->getField();
                        $names[] = $field->getName();
                        $params = $field->getField()->getParams();
                        $required = (isset($params['required'])) ? $params['required'] : false;
                        $input = (isset($params['format'])) ? $params['format'] : '';
                        $options = array( 'label' => $field->getTitle(), 'data' => $fieldvalue->getValue(), 'required' => $required);
                        $type = $field->getField()->getTypeField();
                        if (isset($params['format'])) {
                            $options['attr'] = array('class' => 'datetimepicker');
                            $options['row_attr'] = array('class' => 'row col-md-12');
                            $type = 'text';
                        }

                        if (isset($params['editor']) && $params['editor'] == true) {
                            $options['attr'] = array('class' => 'summernote');
                        }

                      if (isset($params['options']) && $params['options'] != '') {
                        $tmp_choices = explode('%%', $params['options']);
                        $choices = array();
                        foreach($tmp_choices as $choice) {
                          $choices[] = explode('::', $choice);
                        }
                        $options['choices'] = $choices;
                        dump($params); die;
                      }

                        $fieldvaluesTemp->add( $field->getName(), strtolower( $type ), $options );
                    }
                    $diff = array_diff(array_keys($fields), $names);
                    foreach($diff as $name) {
                        $field = $fields[$name];
                        $params = $field->getField()->getParams();
                        $required = (isset($params['required'])) ? $params['required'] : false;
                        $options = array( 'label' => $field->getTitle(), 'required' => $required);
                        $type = $field->getField()->getTypeField();
                        if (isset($params['format'])) {
                            $options['attr'] = array('class' => 'datetimepicker') ;
                            $type = 'text';
                        }
                        if (isset($params['editor']) && $params['editor'] == true) {
                            $options['attr'] = array('class' => 'summernote');
                        }

                      if (isset($params['options']) && $params['options'] != '') {
                        $tmp_choices = explode('%%', $params['options']);
                        $choices = array();
                        foreach($tmp_choices as $choice) {
                          list($value, $label) = explode('::', $choice);
                          $choices[$value] = $label;
                        }
                        $options['choices'] = $choices;
                        $options['expanded'] = true;
                        dump($options); die;
                      }

                        $fieldvaluesTemp->add($field->getName(), strtolower( $type ), $options);
                    }


                }
            );
        } else { // new
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($fields) {
                    $form = $event->getForm();
                    $fieldvaluesTemp = $form->get('fieldValuesTemp');
                    foreach($fields as $field) {
                        $params = $field->getField()->getParams();
                        $required = (isset($params['required'])) ? $params['required'] : false;
                        $options = array( 'label' => $field->getTitle(), 'required' => $required);
                        $type = $field->getField()->getTypeField();
                        if (isset($params['format'])) {
                            $options['attr'] = array('class' => 'datetimepicker') ;
                            $type = 'text';
                        }
                        if (isset($params['editor']) && $params['editor'] == true) {
                            $options['attr'] = array('class' => 'summernote');
                        }

                      if (isset($params['options']) && $params['options'] != '') {
                        $tmp_choices = explode('%%', $params['options']);
                        $choices = array();
                        foreach($tmp_choices as $choice) {
                          $choices[] = explode('::', $choice);
                        }
                        $options['choices'] = $choices;
                      }
                      $fieldvaluesTemp->add($field->getName(), strtolower( $type ), $options);
                    }

                }
            );
        }


        $metas = $options['metas'];
        $metavalues = $options['metavalues'];
        if ( !empty($metavalues) ) {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($metavalues) {
                    $form = $event->getForm();
                    foreach($metavalues as $key => $metas_temp) {
                        foreach($metas_temp as $meta_meta) {
                            if ($key == 'metavalue') {
                                $meta = $meta_meta->getMeta();
                                $metavalue = $meta_meta;
                                $form->add( $meta->getName(), strtolower( $meta->getType() ), array( 'label' => $meta->getName(), 'data' => $metavalue->getValue(), 'required' => false ) );
                            } else {
                                $meta = $meta_meta;
                                $form->add($meta->getName(), strtolower( $meta->getType() ), array('label' => $meta->getName(), 'required' => false));
                            }
                        }

                    }

                }
            );
        } else {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event) use ($metas) {
                    $form = $event->getForm();
                    foreach($metas as $meta) {
                        $form->add($meta->getName(), strtolower( $meta->getType() ), array('label' => $meta->getName(), 'required' => false));
                    }

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
            'data_class'  => 'CMS\Bundle\ContentBundle\Entity\Content',
            'fields'      => array(),
            'user'        => null,
            'fieldvalues' => array(),
            'metas' => array(),
            'metavalues' => array(),
            'content_id'  => 0,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tc_bundle_contentbundle_content';
    }
}
