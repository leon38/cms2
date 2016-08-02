<?php
/**
 * User: DCA
 * Date: 02/08/2016
 * Time: 11:44
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Instagram\Auth;
use Instagram\Instagram;


class ApiController extends Controller
{
    
    /**
     * @Route("/instagram", name="api_instagram")
     */
    public function instagramAction()
    {
    
        $auth = new Auth([
            'client_id'     => '914b8b3b0dc44194acde211b6ab03c14',
            'client_secret' => 'a2ceb0d905d44fc98c889b17f34eebd2',
            'redirect_uri'  => 'http://localhost:8000/instagram'
        ]);
    
        if(!isset($_SESSION['instagram_token'])){
            if(!isset($_GET['code'])){
                $auth->authorize();
            } else {
                $access_token = $auth->getAccessToken($_GET['code']);
                $_SESSION['instagram_token'] = $access_token;
            }
        }
    
        try{
            $instagram = new Instagram();
            $instagram->setAccessToken($_SESSION['instagram_token']);
            $medias = $instagram->getCurrentUser()->getMedia(['count' => 3]);
        } catch(Exception $e) {
            die($e->getMessage());
        }
        
        foreach($medias as $media){
            if ($media->type == 'image') {
                echo "<img src='{$media->images->standard_resolution->url}' width='100'>";
            }
        }
        
    }
    
}