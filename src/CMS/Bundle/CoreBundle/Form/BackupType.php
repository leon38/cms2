<?php
/**
 * User: DCA
 * Date: 07/10/2016
 * Time: 14:30
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class BackupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tables = $options['tables'];
        $builder
            ->add('tables', ChoiceType::class, array('choices' => $tables, 'multiple' => true, 'expanded' => true))
            ->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-fill btn-info btn-wd')))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\Bundle\CoreBundle\Classes\Backup',
            'tables' => null
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'backup';
    }
}