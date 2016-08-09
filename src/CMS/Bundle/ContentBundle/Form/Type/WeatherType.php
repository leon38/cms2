<?php
/**
 * User: DCA
 * Date: 04/08/2016
 * Time: 17:23
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeatherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ville', TextType::class);
    }
    
    public function getParent()
    {
        return 'form';
    }
    
    public function getName()
    {
        return 'weather';
    }
}