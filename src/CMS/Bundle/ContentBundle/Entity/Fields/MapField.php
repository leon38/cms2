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
use CMS\Bundle\ContentBundle\Form\Type\MapType;
use Symfony\Component\Templating\EngineInterface;


class MapField extends Fields
{
  

  public function __construct()
  {

    $this->type = $this->getTypeField();
    $this->params = array();
  }

  public function getTypeField()
  {
    return MapType::class;
  }

  public function getName()
  {
    return 'Carte google maps';
  }

  public function getClassName()
  {
    return 'MapField';
  }

}