<?php
/**
 * User: DCA
 * Date: 16/09/2016
 * Time: 15:03
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;

use CMS\Bundle\CoreBundle\Classes\Widget;

class Search extends Widget
{
    public function getName()
    {
        return 'Search';
    }
    
    /**
     * @param array $param
     * @return string
     */
    public function display($param = array())
    {
        $params = $this->getParams();
        $params['title'] = $param['title'];
        return $this->getTemplating()->render(
            'CoreBundle:Widget:search.html.twig',
            $params
        );
    }
    
    
    public function getOptions()
    {
        $params = $this->getParams();
        $options['display_title'] = array('type' => 'choice', 'name' => 'display_title', 'choices' => array(0 => 'Non', 1 => 'Oui'), 'value' => $this->getParamsValue($params, 'display_title', 'choice'));
        return $options;
    }
}