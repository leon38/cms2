<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;


use Symfony\Component\HttpFoundation\RequestStack;

class SocialExtension extends \Twig_Extension
{

    private $_requestStack;
    private $_templating;

    public function __construct(RequestStack $requestStack)
    {
        $this->_requestStack = $requestStack;

    }

    public function setTemplating(\Twig_Environment $templating)
    {
        $this->_templating = $templating;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('social_buttons', array($this, 'renderSocialButtons'), array('is_safe' => array('html'))),
        );
    }

    public function renderSocialButtons($content)
    {

        $url = $this->makeBitlyUrl('https://choupdoune.fr/'.$content->getUrl().'.html');

        return $this->_templating->render(
            'CoreBundle:Twig:social.html.twig',
            array('url' => urlencode($url))
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'social';
    }
}
