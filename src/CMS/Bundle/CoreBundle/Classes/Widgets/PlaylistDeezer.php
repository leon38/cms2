<?php
/**
 * User: DCA
 * Date: 23/08/2016
 * Time: 17:28
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;

use CMS\Bundle\CoreBundle\Classes\Widget;

class PlaylistDeezer extends Widget
{
    
    /**
     * @param array $param
     * @return string
     */
    public function display($param = array())
    {
        $contents = null;
        $params = $this->getParams();
        if (isset($params['playlist']) && !empty($params['playlist'])) {
            $tracks = $this->getContainer()->get('cms.content.api.manager')->getTracksPlaylist($params['playlist']);
        }
        
        $templating = $this->getTemplating();
        
        $params['tracks'] = $tracks;
        return $templating->render('ContentBundle:Fields:result_deezer.html.twig', array("tracks" => $tracks, "title" => $param['title']));
    }
    
    public function getOptions()
    {
        $params = $this->getParams();
        $options['playlist'] = array('type' => 'text' ,'name' => 'playlist', 'value' => $this->getParamsValue($params, 'playlist'));
        return $options;
    }
}