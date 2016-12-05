<?php
/**
 * User: DCA
 * Date: 24/08/2016
 * Time: 09:39
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;

use Instagram\Auth;
use Instagram\Instagram;

use CMS\Bundle\CoreBundle\Classes\Widget;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class InstagramFlow extends Widget
{
    
    /**
     * @param array $param
     * @return string
     */
    public function display($param = array())
    {
        $params = $this->getParams();
        $auth = new Auth([
            'client_id'     => $params['client_id'], //'d4002f53a27a48b48b8bace1e989f7c0',
            'client_secret' => $params['client_secret'], //'0c64aa35776a48338fd44d24465b2443',
            'redirect_uri'  => $params['redirect_uri'], //'http://localhost:8000/'
        ]);
    
        $session = $this->getContainer()->get('session');
    
        if($session->get('instagram_token', null) == null){
            if(!isset($_GET['code'])){
                $auth->authorize();
            } else {
                $access_token = $auth->getAccessToken($_GET['code']);
                $session->set('instagram_token', $access_token);
            }
        }
    
        try{
            $instagram = new Instagram();
            $instagram->setAccessToken($session->get('instagram_token', null));
            if ($params['limit'] != 0) {
                $medias = $instagram->getCurrentUser()->getMedia(['count' => $params['limit']]);
            } else {
                $medias = $instagram->getCurrentUser()->getMedia(['count' => 100]);
            }
            
        } catch(Exception $e) {
            die($e->getMessage());
        }
        
        $params['medias'] = $medias->getData();
        return $this->getTemplating()->render(
            'CoreBundle:Widget:instagram.html.twig',
            $params
        );
    }
    
    public function getOptions()
    {
        $params = $this->getParams();
        $options['display_title'] = array('type' => ChoiceType::class, 'name' => 'display_title', 'choices' => array(0 => 'Non', 1 => 'Oui'), 'value' => $this->getParamsValue($params, 'display_title', 'choice'));
        $options['limit'] = array('type' => TextType::class, 'name' => 'limit', 'value' => $this->getParamsValue($params, 'limit'));
        $options['client_id'] = array('type' => TextType::class ,'name' => 'client_id', 'value' => $this->getParamsValue($params, 'client_id'));
        $options['client_secret'] = array('type' => TextType::class ,'name' => 'client_secret', 'value' => $this->getParamsValue($params, 'client_secret'));
        $options['redirect_uri'] = array('type' => UrlType::class ,'name' => 'redirect_uri', 'value' => $this->getParamsValue($params, 'redirect_uri'));
        return $options;
    }
}