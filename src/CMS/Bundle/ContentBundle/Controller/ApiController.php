<?php

namespace CMS\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
        $json = $this->get('cms.content.api.manager')->getWeather($city);
        return new JsonResponse(json_decode($json));
    }
    
    /**
     * @Route("/spotify/artist/{query}", name="spotify_search_artist")
     */
    public function getArtistSpotify($query)
    {
        $json = $this->get('cms.content.api.manager')->getArtistSpotify($query);
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
        $tracks = $this->get('cms.content.api.manager')->getTopTracksSpotify($id_artist);
        return $this->render('ContentBundle:Fields:result_spotify.html.twig', array("tracks" => $tracks));
    }
    
    
    /**
     * @Route("/deezer/artist/{query}", name="deezer_search_artist")
     */
    public function getArtistDeezer($query)
    {
        $tracklist =  $this->get('cms.content.api.manager')->getArtistDeezer($query);
        return $this->_getTopTracksDeezer($tracklist);
    }
    
    private function _getTopTracksDeezer($url)
    {
        $tracks = $this->get('cms.content.api.manager')->getTopTracksDeezer($url);
        return $this->render('ContentBundle:Fields:result_deezer.html.twig', array("tracks" => $tracks));
    }
    
    /**
     * @param $id_playlist
     * @return Response
     *
     */
    public function getTracksPlaylist($id_playlist)
    {
        $tracks = $this->get('cms.content.api.manager')->getTracksPlaylist($id_playlist);
        return $this->render('ContentBundle:Fields:result_deezer.html.twig', array("tracks" => $tracks));
    }
}
