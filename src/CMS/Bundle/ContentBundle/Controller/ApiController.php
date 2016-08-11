<?php

namespace CMS\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ApiController
 * @package CMS\Bundle\ContentBundle\Controller
 * @Route("/api")
 */
class ApiController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
    
    /**
     * @param $city
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/weather/{city}", name="weather_ajax")
     */
    public function getWeatherAction($city)
    {
        $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
        $yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$city.'")';
        $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&u=c";
        // Make call with cURL
        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
        $json = curl_exec($session);
        // Convert JSON to PHP object
//        $phpObj =  json_decode($json);
        return new JsonResponse(json_decode($json));
    }
    
    /**
     * @Route("/spotify/artist/{query}", name="spotify_search_artist")
     */
    public function getArtistSpotify($query)
    {
        $BASE_URL = 'https://api.spotify.com/v1/search?q='.urlencode($query).'&type=artist';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BASE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        $json = json_decode($json);
        curl_close($ch);
        return new JsonResponse($json);
    }
    
    /**
     * @param $id_artist Identifiant de l'artiste
     *
     * @return JsonResponse
     * @Route("/spotify/top-tracks/{id_artist}", name="spotify_top_tracks")
     */
    public function getTopTracksSpotify($id_artist)
    {
        $BASE_URL = "https://api.spotify.com/v1/artists/".$id_artist."/top-tracks?country=FR";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BASE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        $json = json_decode($json);
        curl_close($ch);
        return $this->render('ContentBundle:Fields:result_spotify.html.twig', array("tracks" => $json->tracks));
        //new JsonResponse(json_decode($json));
    }
    
    
    /**
     * @Route("/deezer/artist/{query}", name="deezer_search_artist")
     */
    public function getArtistDeezer($query)
    {
        $BASE_URL = 'http://api.deezer.com/search?q='.urlencode($query);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BASE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        $json = json_decode($json);
        curl_close($ch);
        return $this->_getTopTracksDeezer($json->data[0]->artist->tracklist);
    }
    
    private function _getTopTracksDeezer($url)
    {
        $BASE_URL = $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BASE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        $json = json_decode($json);
        curl_close($ch);
        return $this->render('ContentBundle:Fields:result_deezer.html.twig', array("tracks" => $json->data));
    }
}
