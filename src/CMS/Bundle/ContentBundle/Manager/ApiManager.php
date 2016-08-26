<?php
/**
 * User: DCA
 * Date: 23/08/2016
 * Time: 17:33
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Manager;


class ApiManager
{
    public function getWeather($city)
    {
        $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
        $yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$city.'")';
        $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&u=c";
        // Make call with cURL
        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
        $json = curl_exec($session);
        return $json;
    }
    
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
        return $json;
    }
    
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
        return $json->tracks;
    }
    
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
        return $json->data[0]->artist->tracklist;
    }
    
    
    public function getTopTracksDeezer($url)
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
        return $json->data;
    }
    
    public function getTracksPlaylist($id_playlist)
    {
        $BASE_URL = 'http://api.deezer.com/playlist/'.$id_playlist.'/tracks';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BASE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        $json = json_decode($json);
        curl_close($ch);
        return $json->data;
    }
}