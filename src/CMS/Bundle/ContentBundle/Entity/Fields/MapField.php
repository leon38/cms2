<?php
/**
 * User: DCA
 * Date: 13/07/2016
 * Time: 10:24
 *
 *
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Templating\EngineInterface;


class MapField extends Fields
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
    return 'map';
  }

  public function getName()
  {
    return 'Champ carte google maps';
  }

  public function getClassName()
  {
    return 'MapField';
  }

  public function display()
  {
    return $this->templating->render('ContentBundle:Fields:map.html.twig', array('params' => $this->params));
  }

  public function setParams($params)
  {
    $this->params = $params;
    return $this;
  }

  public function getOptions()
  {
    return array();
  }

  public function getParams()
  {
    return $this->params;
  }

}