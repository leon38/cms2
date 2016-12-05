<?php
/**
 * Library WBSApi Withings
 * @author webert zélé <webert.zele@withings.com>
 *
 *
 */

namespace CMS\Bundle\CoreBundle\Classes;


/**
 * Class WBSApi
 * @package CMS\Bundle\CoreBundle\Classes
 */
class WBSApi
{
    const BASE_URL = "https://oauth.withings.com/account/";
    const URLWSAPI = "http://wbsapi.withings.net/";
    const URLWSAPIACTIVITY = "https://wbsapi.withings.net/v2/";
    const CALLBACK_URL = "http://www.cms2.local/admin/user/dashboard";
    const OAUTH_CONSUMER_KEY = "6b47d7e402c1f402395b550b2de3bfc3bfff8569bb302bfdc81f4decee10";
    const OAUTH_CONSUMER_SECRET = "14c61610618c990bbf91edb10c54209314df81d9fbf0c3e2713e7480c860";
    
    private $oauthToken;
    private $oauthSecret;
    private $consumer;
    private $acc_tok;
    private $hmac_method;
    private $sig_method;
    private $oauth;
    private $session;
    
    public function __construct($oauthToken = null,$oauthSecret = null)
    {
        
        $this->oauth = new \OAuth(self::OAUTH_CONSUMER_KEY, self::OAUTH_CONSUMER_SECRET);
        
        if($oauthToken != null && $oauthSecret != null)
        {
            $this->oauthToken = $oauthToken;
            $this->oauthSecret = $oauthSecret;
            
        }
        
    }
    
    
    public function setToken($oauthToken,$oauthSecret)
    {
        $this->oauth->disableSSLChecks();
        $this->oauth->setToken($oauthToken,$oauthSecret);
        $this->oauthToken = $oauthToken;
        $this->oauthSecret = $oauthSecret;
        
    }
    
    
    public function api($service_name, $action, $postdata = array())
    {
        $this->consumer = new OAuthConsumer(self::OAUTH_CONSUMER_KEY, self::OAUTH_CONSUMER_SECRET);
        $this->acc_tok = new OAuthToken($this->oauthToken,$this->oauthSecret);
        
        $this->hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
        
        $this->sig_method = $this->hmac_method;
        
        $url = self::URLWSAPI.$service_name ;
        
        $fields_string = "action=".$action."&";
        
        if (count($postdata)>0)
        {
            
            foreach($postdata as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            
            $fields_string = rtrim($fields_string,'&');
        }
        
        $req = OAuthRequest::from_consumer_and_token($this->consumer, $this->acc_tok, "GET",  $url."?".$fields_string);
        $req->sign_request($this->sig_method, $this->consumer, $this->acc_tok);
        
        
        try {
            $s = curl_init();
            
            
            curl_setopt($s,CURLOPT_URL,$req);
            curl_setopt($s,CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($s, CURLOPT_HEADER, true);
            
            $output = curl_exec($s);
            curl_close($s);
            return $output;
            
        } catch ( \Exception $e ) {
            print_r($e);
            return ( false );
        }
    }
    
    public function apiActivity($service_name, $action, $postdata = array())
    {
        $this->consumer = new OAuthConsumer(self::OAUTH_CONSUMER_KEY, self::OAUTH_CONSUMER_SECRET);
        $this->acc_tok = new OAuthToken($this->oauthToken,$this->oauthSecret);
        
        $this->hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
        
        $this->sig_method = $this->hmac_method;
        
        $url = self::URLWSAPIACTIVITY.$service_name ;
        
        $fields_string = "action=".$action."&";
        
        if (count($postdata)>0)
        {
            
            foreach($postdata as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            
            $fields_string = rtrim($fields_string,'&');
        }
        
        $req = OAuthRequest::from_consumer_and_token($this->consumer, $this->acc_tok, "GET",  $url."?".$fields_string);
        $req->sign_request($this->sig_method, $this->consumer, $this->acc_tok);
        
        
        try {
            $s = curl_init();
            
            
            curl_setopt($s,CURLOPT_URL,$req);
            curl_setopt($s,CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($s, CURLOPT_HEADER, true);
            
            $output = curl_exec($s);
            curl_close($s);
            return $output;
            
        } catch ( \Exception $e ) {
            print_r($e);
            return ( false );
        }
    }
    
    
    public function getRequestToken()
    {
        $request_token_info = '';
        try {
            $this->oauth->disableSSLChecks();
            $request_token_info = $this->oauth->getRequestToken(self::BASE_URL."request_token?oauth_callback=".urlencode(self::CALLBACK_URL));
        } catch(\OAuthException $E) {
            echo "Response: ". $E->lastResponse . "\n";
        }
        
        $_SESSION["oauth_token"] = $request_token_info["oauth_token"];
        $_SESSION["oauth_token_secret"] = $request_token_info["oauth_token_secret"];
        
        $req = self::BASE_URL."authorize?oauth_token=".$request_token_info["oauth_token"];
        
        
        return $req;
    }
    
    public function getAccessToken()
    {
        try {
            $this->oauth = new \OAuth(self::OAUTH_CONSUMER_KEY,self::OAUTH_CONSUMER_SECRET);
            $this->oauth->disableSSLChecks();
            $this->oauth->setToken($_SESSION["oauth_token"],$_SESSION["oauth_token_secret"]);
            $access_token_info = $this->oauth->getAccessToken(self::BASE_URL."access_token");
        } catch(\OAuthException $E) {
            dump($E); die;
        }
        
        return $access_token_info;
        
    }
    
    
}