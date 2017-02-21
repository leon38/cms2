<?php
namespace CMS\Bundle\CoreBundle\Form;

use CMS\Bundle\CoreBundle\Form\Type\DropzoneType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProfileType extends AbstractType
{


  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $builder
      ->add('user_login', TextType::class, array(
        'attr' => array('readonly' => 'readonly'),
        'label' => 'cms.user.user_login',
        'row_attr' => array('class' => 'col-md-3')
      ));
    $user = $options['user'];
    $builder->addEventListener(
      FormEvents::PRE_SET_DATA,
      function (FormEvent $event) use ($user) {
        $form = $event->getForm();

        $data = $event->getData();

        foreach ($data->getMetas() as $meta) {
          switch ($meta->getType()) {
            case '':
            case 'text':
              $class = 'col-md-3';
              $type = TextType::class;
              break;

            default:
              $class = 'col-md-6';
              $type = TextareaType::class;
              break;
          }
          $form->add('metas_' . $meta->getMetaKey(), $type, array('label' => $meta->getMetaKey(), 'data' => $meta->getMetaValue(), 'required' => false, 'row_attr' => array('class' => $class), 'attr' => array('data-target' => $meta->getMetaKey())));
        }
      });

    $builder
      ->add('user_nicename', TextType::class, array(
        'label' => 'cms.user.user_nicename',
        'row_attr' => array('class' => 'col-md-3'),
        'attr' => array('data-target' => 'nicename'),
      ))
      ->add('user_email', EmailType::class, array('label' => 'cms.user.email', 'row_attr' => array('class' => 'col-md-3')))
      ->add('user_url', UrlType::class, array('label' => 'cms.user.user_url', 'required' => false, 'row_attr' => array('class' => 'col-md-3')))
      ->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class, 'first_options' => array('label' => 'cms.core.user_pass', 'row_attr' => array('class' => 'col-md-6')), 'second_options' => array('label' => 'cms.core.confirm_pass', 'row_attr' => array('class' => 'col-md-6'))))
      ->add('avatar', DropzoneType::class, array('attr' => array('class' => 'dropzone', 'data-url' => '/admin/upload-avatar/' . $options['user_id'], 'data-type' => 'dropzone'), 'image_path' => 'webPath', 'row_attr' => array('class' => 'col-md-3')));

  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'CMS\Bundle\CoreBundle\Entity\User',
      'user_id' => 0,
      'user' => null,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return 'profile';
  }
}