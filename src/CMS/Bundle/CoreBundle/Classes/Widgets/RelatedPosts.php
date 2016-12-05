<?php
/**
 * User: DCA
 * Date: 17/08/2016
 * Time: 16:11
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;


use CMS\Bundle\CoreBundle\Classes\Widget;

class RelatedPosts extends Widget
{
    
    /**
     * @param array $param
     * @return string
     */
    public function display($param = array())
    {
        $contents = null;
        if (isset($param['content'])) {
            if (!empty($param['content'])) {
                $contents = $this->getEntityManager()->getRepository('ContentBundle:Content')->getRelatedPosts($param['content'], $this->getParams()['limit']);
            }
        }

        $templating = $this->getTemplating();
        $params = $this->getParams();
        $params['contents'] = $contents;
        return $templating->render("CoreBundle:Widget:relatedPosts.html.twig", array('contents' => $params['contents'], 'title' => $param['title']));
    }
    
    public function getOptions()
    {
        $params = $this->getParams();
        $options['Limit'] = array('type' => 'text' ,'name' => 'limit', 'value' => $this->getParamsValue($params, 'limit'));
        return $options;
    }
}