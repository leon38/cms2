<?php
/**
 * User: DCA
 * Date: 24/11/2016
 * Time: 11:45
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes;


class OAuthConsumer {
    public $key;
    public $secret;
    
    function __construct($key, $secret, $callback_url=NULL) {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback_url = $callback_url;
    }
    
    function __toString() {
        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
    }
}