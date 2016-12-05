<?php
/**
 * User: DCA
 * Date: 15/09/2016
 * Time: 16:08
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;

use CMS\Bundle\CoreBundle\Classes\Widget;

class SocialFollow extends Widget
{
    
    /**
     * @param array $param
     * @return string
     */
    public function display($param = array())
    {
        $params = $this->getParams();
        return $this->getTemplating()->render(
            'CoreBundle:Widget:socialfollow.html.twig',
            $params
        );
    }
    
    
    public function getOptions()
    {
        $params = $this->getParams();
        $options['display_title'] = array('type' => 'choice', 'name' => 'display_title', 'choices' => array(0 => 'Non', 1 => 'Oui'), 'value' => $this->getParamsValue($params, 'display_title', 'choice'));
        $options['facebook'] = array('type' => 'url', 'name' => 'facebook', 'value' => $this->getParamsValue($params, 'facebook'));
        $options['twitter'] = array('type' => 'url', 'name' => 'twitter', 'value' => $this->getParamsValue($params, 'twitter'));
        $options['instagram'] = array('type' => 'url', 'name' => 'instagram', 'value' => $this->getParamsValue($params, 'instagram'));
        $options['pinterest'] = array('type' => 'url', 'name' => 'pinterest', 'value' => $this->getParamsValue($params, 'pinterest'));
        $options['gplus'] = array('type' => 'url', 'name' => 'gplus', 'value' => $this->getParamsValue($params, 'gplus'));
        $options['youtube'] = array('type' => 'url', 'name' => 'youtube', 'value' => $this->getParamsValue($params, 'youtube'));
        $options['linkedin'] = array('type' => 'url', 'name' => 'linkedin', 'value' => $this->getParamsValue($params, 'linkedin'));
        return $options;
    }
}