<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\TextAreaField
 *
 */
class TextAreaField extends Fields
{

    public function getTypeField()
    {
        return TextareaType::class;
    }

    public function getName()
    {
        return 'Zone de texte';
    }

    public function getClassName()
    {
        return 'TextAreaField';
    }

    // faire une classe avec les display des differents types: checkbox, select, input etc.
    public function getOptions()
    {
        $this->options['Colonne'] = array('type' => TextType::class, 'name' => 'cols', 'value' => $this->getParamsValue($this->params, 'cols'));
        $this->options['Ligne'] = array('type' => TextType::class, 'name' => 'rows', 'value' => $this->getParamsValue($this->params, 'rows'));
        $this->options['Default Value'] = array('type' => TextType::class, 'name' => 'defaultvalue', 'value' => $this->getParamsValue($this->params, 'cols'));
        $this->options['Required'] = array('type' => ChoiceType::class, 'name' => 'required','choices' => array('No' => 0, 'Yes' => 1), 'value' => $this->getParamsValue($this->params, 'required'));
        $this->options['Editeur'] = array('type' => ChoiceType::class, 'name' => 'editor','choices' => array('No' => 0, 'Yes' => 1), 'value' => $this->getParamsValue($this->params, 'required'));

        return $this->options;
    }
}