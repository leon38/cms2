<?php
/**
 * User: DCA
 * Date: 13/07/2016
 * Time: 10:24
 *
 *
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


class MapField
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

  public function display($value)
  {

    $html = '<div class="map"></div>';
    $html .= '<script src="/bundles/content/js/map.js"></script>';
    return $html;
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