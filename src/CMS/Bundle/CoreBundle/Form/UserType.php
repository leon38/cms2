<?php

namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use CMS\Bundle\CoreBundle\Entity\User;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_login', 'text', array(
                'attr' => array('data-target' => 'login'),
                'label' => 'cms.user.user_login',
                'row_attr' => array('class' => 'col-md-3'),
                ));

        $user = $options['user'];

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($user) {
                $form = $event->getForm();

                $data = $event->getData();

                foreach($data->getMetas() as $meta) {
                    switch($meta->getType()) {
                        case '':
                        case 'text':
                            $class = 'col-md-3';
                            $type  = 'text';
                            break;
                        default:
                            $class = 'col-md-6';
                            $type = $meta->getType();
                            break;
                    }
                    $form->add('metas_'.$meta->getMetaKey(), $type, array('label' => $meta->getMetaKey(), 'data' => $meta->getMetaValue(), 'required' => false, 'row_attr' => array('class' => $class)));
                }
            });

        $builder
            ->add('user_nicename', 'text', array(
                'label' => 'cms.user.user_nicename',
                'row_attr' => array('class' => 'col-md-3'),
                'attr' => array('data-target' => 'nicename'),
                ))
            ->add('user_email', 'email', array('label' => 'cms.user.email', 'row_attr' => array('class' => 'col-md-3')))
            ->add('user_url', 'url', array('label' => 'cms.user.user_url', 'required' => false, 'row_attr' => array('class' => 'col-md-3')))
            ->add('roles', null, array('expanded' => false, 'row_attr' => array('class' => 'col-md-12')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\CoreBundle\Entity\User',
            'user' => null
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
