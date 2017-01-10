<?php
/**
 * User: DCA
 * Date: 04/08/2016
 * Time: 17:21
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


use CMS\Bundle\ContentBundle\Classes\Fields;
use CMS\Bundle\ContentBundle\Form\Type\WeatherType;

class WeatherField extends Fields
{

    public function getTypeField()
    {
        return WeatherType::class;
    }

    public function getName()
    {
        return 'Champ météo';
    }

    public function getClassName()
    {
        return 'WeatherField';
    }
}