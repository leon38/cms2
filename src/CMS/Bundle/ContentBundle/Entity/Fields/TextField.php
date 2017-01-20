<?php
namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\TextField
 *
 */
class TextField extends Fields
{

  public function getTypeField()
  {
    return TextType::class;
  }

  public function getName()
  {
    return 'Texte';
  }

  public function getClassName()
  {
    return 'TextField';
  }


  // faire une classe avec les display des differents types: checkbox, select, input etc.
  public function getOptions()
  {
    $this->options['Size'] = array('type' => TextType::class, 'name' => 'size', 'value' => $this->getParamsValue($this->params, 'size'));
    $this->options['Default Value'] = array('type' => TextType::class, 'name' => 'defaultvalue', 'value' => $this->getParamsValue($this->params, 'size'));
    $this->options['Required'] = array('type' => ChoiceType::class, 'name' => 'required', 'choices' => array('No' => 0, 'Yes' => 1), 'value' => $this->getParamsValue($this->params, 'required'));

    return $this->options;
  }


}