<?php
/**
 * User: DCA
 * Date: 05/10/2016
 * Time: 10:40
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;

use CMS\Bundle\CoreBundle\Classes\Widget;

class LastPosts extends Widget
{
    public function getName()
    {
        return 'LastPosts';
    }
    
    /**
     * @param array $param
     * @return string
     */
    public function display($param = array())
    {
        $params = $this->getParams();
        $contents = $this->getEntityManager()->getRepository('ContentBundle:Content')->findBy(array(), array('created' => 'DESC'), $params['limit'], 0);
        $templating = $this->getTemplating();
        $params['contents'] = $contents;
        return $templating->render("CoreBundle:Widget:lastPosts.html.twig", array('contents' => $params['contents'], 'title' => $param['title']));
    }
    
    public function getOptions()
    {
        $params = $this->getParams();
        $options['Limit'] = array('type' => 'text' ,'name' => 'limit', 'value' => $this->getParamsValue($params, 'limit'));
        return $options;
    }
}