<?php
/**
 * User: DCA
 * Date: 24/11/2016
 * Time: 11:46
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes;


class OAuthToken {
    // access tokens and request tokens
    public $key;
    public $secret;
    
    private $isAuthorized = false;
    
    /**
     * key = the token
     * secret = the token secret
     */
    function __construct($key, $secret) {
        $this->key = $key;
        $this->secret = $secret;
    }
    
    function setIsAuthorized($is)
    {
        $this->isAuthorized = $is;
    }
    
    function isAuthorized()
    {
        return $this->isAuthorized;
    }
    /**
     * generates the basic string serialization of a token that a server
     * would respond to request_token and access_token calls with
     */
    function to_string() {
        return "oauth_token=" .
        OAuthUtil::urlencode_rfc3986($this->key) .
        "&oauth_token_secret=" .
        OAuthUtil::urlencode_rfc3986($this->secret);
    }
    
    function __toString() {
        return $this->to_string();
    }
}