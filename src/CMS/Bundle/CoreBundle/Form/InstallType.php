<?php
namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InstallType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
    		->add('option_value', TextType::class, array('label' => 'cms.option.site_title'))
    		->add('option_name', HiddenType::class, array('data' => 'sitename'))
    		->add('user_login', TextType::class, array('label' => 'cms.user.user_login'))
    		->add('user_pass', RepeatedType::class, array(
    			'type' => PasswordType::class,
    			'first_options' => array('label' => 'cms.user.user_pass'),
    			'second_options' => array('label' => 'cms.user.user_pass_confirm', 'help' => 'cms.user.user_pass_help')
     			))
    		->add('user_email', EmailType::class, array('label' => 'cms.user.user_email', 'help' => 'cms.user.user_email_help'))
    		->add('robots', CheckboxType::class, array('label' => 'cms.install.robots_txt', 'required' => false, 'attr' => array('data-toggle' => 'checkbox', 'class' => 'checkbox-inline'), 'label_attr' => array('class' => 'checkbox')))
            ->add('submit', SubmitType::class, array('label' => 'Installer', 'attr' => array('class' => 'btn btn-fill btn-info btn-wd')))
    		;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\CoreBundle\Classes\Install',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'install';
    }
}