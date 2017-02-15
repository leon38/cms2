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
        $params['bitlyUrl'] = $this->makeBitlyUrl('https://choupdoune.fr/'.$params['content']->getUrl().'.html');
        return $this->getTemplating()->render(
            'CoreBundle:Widget:share.html.twig',
            $params
        );
    }


    private function makeBitlyUrl($url, $login = 'o_58am3tc0eh', $appkey = 'R_a7af71df000d4233911d0b1983084953',$format = 'xml', $version = '2.0.1')
    {
        //create the URL
        $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;

        //get the url
        //could also use cURL here
        $response = file_get_contents($bitly);

        //parse depending on desired format
        if(strtolower($format) == 'json')
        {
            $json = @json_decode($response,true);
            return $json['results'][$url]['shortUrl'];
        }
        else //xml
        {
            $xml = simplexml_load_string($response);
            return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
        }
    }
}