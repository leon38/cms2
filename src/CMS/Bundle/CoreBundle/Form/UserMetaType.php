<?php
namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use CMS\Bundle\CoreBundle\Entity\UserMeta;

class UserMetaType extends AbstractType
{

	/**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
    	    ->add('meta_key', 'hidden')
    		->add('meta_value', 'text', array('label' => $options['user_meta']->getMetaKey()));
	}

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\CoreBundle\Entity\UserMeta',
            'user_meta' => new UserMeta()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_meta';
    }
}