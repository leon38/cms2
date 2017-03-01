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
    public function getWeatherYahoo($city)
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

    public function getCoordinates($city)
    {
        $BASE_URL = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=".urlencode($city);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BASE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        $json = json_decode($json);
        curl_close($ch);
        if($json->status ='OK'){
            return $json->results[0]->geometry->location;
        }else{
            return false;
        }
    }

    public function getWeatherDarkSky($city)
    {
        $coords = $this->getCoordinates($city);
        if ($coords !== false) {
            $BASE_URL = "https://api.darksky.net/forecast/460bd50d0aa12b3276c3c8813d239743/".$coords->lat.",".$coords->lng;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $BASE_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $json = curl_exec($ch);
            curl_close($ch);
            return $json;
        }
        return false;
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
        $BASE_URL = 'https://api.deezer.com/search?q='.urlencode($query);
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
        $BASE_URL = 'https://api.deezer.com/playlist/'.$id_playlist.'/tracks';
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

    public function getWithingsRequestToken()
    {
        $oauth_consumer_key = '6b47d7e402c1f402395b550b2de3bfc3bfff8569bb302bfdc81f4decee10';
        $oauth_consumer_secret = '14c61610618c990bbf91edb10c54209314df81d9fbf0c3e2713e7480c860';
        $callback_url = 'http://www.cms2.local/admin/user/dashboard';
        $query = 'oauth_callback='.$callback_url.'&oauth_consumer_key='.$oauth_consumer_key.'&oauth_consumer_secret='.$oauth_consumer_secret.'&oauth_nonce=ac500d5bf8664d3720813652af256bbe&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1479985014&oauth_version=1.0';

        header(
                'Location:https://oauth.withings.com/account/request_token?oauth_callback=http%3A%2F%2Fwww.cms2.local%2Fadmin%2Fuser%2Fdashboard&oauth_consumer_key=6b47d7e402c1f402395b550b2de3bfc3bfff8569bb302bfdc81f4decee10&oauth_nonce=ac500d5bf8664d3720813652af256bbe&oauth_signature=4gUaC6RPHJwMWlC4%2BTGCguGs6Vw%3D&oauth_signature_method=HMAC-SHA1&oauth_version=1.0&oauth_timestamp=1479992249'
        );

        exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BASE_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:x.x.x) Gecko/20041107 Firefox/x.x");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        dump($json); die;
    }
}