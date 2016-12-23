<?php
/**
 * User: DCA
 * Date: 21/12/2016
 * Time: 13:54
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;


use CMS\Bundle\CoreBundle\Classes\Widget;

class Share extends Widget
{

    public function display($param = array())
    {
        $params = array_merge($this->getParams(), $param);
        return $this->getTemplating()->render(
            'CoreBundle:Widget:share.html.twig',
            $params
        );
    }
}