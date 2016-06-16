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
    return $this->repo->findBy(array('general' => 2));
  }

}