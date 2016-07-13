<?php
namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProfileType extends AbstractType
{


  public function __construct(SecurityContext $securityContext)
  {
    $this->securityContext = $securityContext;
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $builder
      ->add('user_login', 'text', array(
        'attr' => array('readonly' => 'readonly'),
        'label' => 'cms.user.user_login',
        'row_attr' => array('class' => 'col-md-3')
      ));
    $user = $this->securityContext->getToken()->getUser();
    if (!$user) {
      throw new \LogicException(
        'Le profil ne peut pas être utilisé sans utilisateur connecté!'
      );
    }

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
              $type = 'text';
              break;
            default:
              $class = 'col-md-6';
              $type = $meta->getType();
              break;
          }
          $form->add('metas_' . $meta->getMetaKey(), $type, array('label' => $meta->getMetaKey(), 'data' => $meta->getMetaValue(), 'required' => false, 'row_attr' => array('class' => $class), 'attr' => array('data-target' => $meta->getMetaKey())));
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
      ->add('avatar', 'dropzone', array('attr' => array('class' => 'dropzone', 'data-url' => '/admin/upload-avatar/' . $options['user_id'], 'data-type' => 'dropzone'), 'image_path' => 'webPath', 'row_attr' => array('class' => 'col-md-3')));

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