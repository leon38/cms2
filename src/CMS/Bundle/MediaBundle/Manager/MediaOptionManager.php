<?php
/**
 * Created by PhpStorm.
 * User: DCA
 * Date: 16/06/2016
 * Time: 09:10
 */

namespace CMS\Bundle\MediaBundle\Manager;


use CMS\Bundle\CoreBundle\Manager\OptionManager;

class MediaOptionManager extends OptionManager
{

  public function getAllOptions()
  {
    return $this->repo->findBy(array('type' => 2));
  }


  public function getAllOptionsForm()
  {
    $options = $this->getAllOptions();
    $settings = array('settings' => array());
    foreach($options as $option) {
      $sizes = json_decode($option->getOptionValue());
      $settings['settings'][] = array_merge(array('name' => $option->getOptionName()), (array)$sizes);
    }
    return $settings;
  }
}