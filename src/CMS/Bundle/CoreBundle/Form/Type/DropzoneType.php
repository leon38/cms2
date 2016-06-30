<?php
namespace CMS\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DropzoneType extends AbstractType
{

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array('attr' => array('data-type' => 'dropzone','data-url' => '')));
	}

	public function getParent()
	{
		return 'form';
	}

	public function getName()
	{
		return 'dropzone';
	}
}