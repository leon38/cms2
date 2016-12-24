<?php
/**
 * User: DCA
 * Date: 02/08/2016
 * Time: 13:41
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Instagram\Auth;
use Instagram\Instagram;



class InstagramExtension extends \Twig_Extension
{
    private $_container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
        
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('instagram_images', array($this, 'renderInstagramImages'), array('is_safe' => array('html'))),
        );
    }
    
    public function renderInstagramImages()
    {
        $auth = new Auth([
            'client_id'     => 'd4002f53a27a48b48b8bace1e989f7c0',
            'client_secret' => '0c64aa35776a48338fd44d24465b2443',
            'redirect_uri'  => 'http://localhost:8000/'
        ]);
        
        $session = $this->_container->get('session');
    
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
            $medias = $instagram->getCurrentUser()->getMedia(['count' => 3]);
        } catch(Exception $e) {
            die($e->getMessage());
        }
        
        return $this->_container->get('templating')->render(
            'CoreBundle:Twig:instagram.html.twig',
            array('medias' => $medias->getData())
        );
    }
    
    
    public function getName()
    {
        return 'instagram_images';
    }
        
}