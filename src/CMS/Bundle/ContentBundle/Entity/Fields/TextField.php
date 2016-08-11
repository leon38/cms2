<?php
namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\TextField
 *
 */
class TextField extends Fields
{
  /**
   * @var string Type du champ
   */
  private $type;

  /**
   * @var array paramètres du champs
   */
  private $params;

  public function __construct()
  {
    $this->type = $this->getTypeField();
    $this->params = array();
  }

  public function getTypeField()
  {
    return 'text';
  }

  public function getName()
  {
    return 'Texte';
  }

  public function getClassName()
  {
    return 'TextField';
  }

  public function display($value)
  {
    $start = null;
    $end = null;
    switch ($this->params['displaytype']) {
      case 'div':
        $start = '<div>';
        $end = '</div>';
        break;
      case 'span':
        $start = '<span>';
        $end = '</span>';
        break;
      case 'p':
        $start = '<p>';
        $end = '</p>';
        break;
      default:
        $start = '';
        $end = '';
        break;
    }
    $html = $start . ' ' . $value . ' ' . $end;
    return $html;
  }

  public function setParams($params)
  {
    $this->params = $params;
    return $this;
  }

  // faire une classe avec les display des differents types: checkbox, select, input etc.
  public function getOptions()
  {
    $options['Size'] = array('type' => 'text', 'name' => 'size', 'value' => $this->getParamsValue($this->params, 'size'));
    $options['Default Value'] = array('type' => 'text', 'name' => 'defaultvalue', 'value' => $this->getParamsValue($this->params, 'size'));
    $options['Required'] = array('type' => 'choice', 'name' => 'required', 'choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));

    return $options;
  }

  public function getParams()
  {
    return $this->params;
  }


}