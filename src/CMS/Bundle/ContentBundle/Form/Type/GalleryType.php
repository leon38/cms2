<?php
/**
 * User: DCA
 * Date: 19/08/2016
 * Time: 09:08
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GalleryType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('gallery', HiddenType::class, array('empty_data' => null));
    }
    
    public function getParent()
    {
        return 'form';
    }
    
    public function getName()
    {
        return 'gallery';
    }
}