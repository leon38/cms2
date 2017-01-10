<?php
/**
 * User: DCA
 * Date: 13/10/2016
 * Time: 14:29
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class DifficultyType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('note', TextType::class, array('attr' => array('class' => 'rating', 'min' => 0, 'step' => 1)));
    }
    
    public function getParent()
    {
        return 'form';
    }
    
    public function getName()
    {
        return 'difficulty';
    }
}