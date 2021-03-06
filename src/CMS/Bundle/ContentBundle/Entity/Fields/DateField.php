<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\Dateield
 *
 */
class DateField extends Fields
{

    public function __construct()
    {
        $this->type = $this->getTypeField();
    }

    public function getTypeField()
    {
        return DateType::class;
    }

    public function getName()
    {
        return 'Date';
    }

    public function getClassName()
    {
        return 'DateField';
    }

    // faire une classe avec les display des differents types: checkbox, select, input etc.
    public function getOptions()
    {
        $this->options['Format de la date'] = array('type' => TextType::class, 'name' => 'format', 'value' => $this->getParamsValue($this->params, 'format'));
        $this->options['attr'] = array('class' => 'datetimepicker');
        $this->options['Required'] = array('type' => ChoiceType::class, 'name' => 'required','choices' => array('No' => 0, 'Yes' => 1), 'value' => $this->getParamsValue($this->params, 'required'));

        return $this->options;
    }

}