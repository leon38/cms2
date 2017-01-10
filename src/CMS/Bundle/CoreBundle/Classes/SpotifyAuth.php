<?php
/**
 * User: DCA
 * Date: 08/08/2016
 * Time: 16:37
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes;


class SpotifyAuth
{
    
    /**
     * Configuration array
     *
     * Contains a default client and proxy
     *
     * client_id:       These three items are required for authorization
     * redirect_uri:    URL that the Spotify API should redirect to
     * grant_type:      Grant type from the Spotify API. Only authorization_code is accepted right now.
     * scope:           {@link https://developer.spotify.com/web-api/using-scopes/}
     * display:         Pass in "touch" if you'd like your authenticating users to see a mobile-optimized
     *                  version of the authenticate page and the sign-in page.
     *
     * @var array
     * @access protected
     */
    protected $config = array(
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => '',
        'grant_type'    => 'authorization_code',
        'scope'         => array( 'user-read-private', 'user-read-email' ),
        'display'       => ''
    );
    
    
    public function __construct(array $config = null)
    {
        $this->config = (array) $config + $this->config;
    }
    
    /**
     * Authorize
     *
     * Redirects the user to the Instagram authorization url
     * @access public
     */
    public function authorize() {
        header(
            sprintf(
                'Location:https://accounts.spotify.com/authorize/?client_id=%s&redirect_uri=%s&response_type=code&scope=%s',
                $this->config['client_id'],
                $this->config['redirect_uri'],
                implode( '+', $this->config['scope'] )
            )
        );
        exit;
    }
    
    
    /**
     * Get the access token
     *
     * POSTs to the Instagram API and obtains and access key
     *
     * @param string $code Code supplied by Instagram
     * @return string Returns the access token
     * @throws \Instagram\Core\ApiException
     * @access public
     */
    public function getAccessToken( $code ) {
        $post_data = array(
            'client_id'         => $this->config['client_id'],
            'client_secret'     => $this->config['client_secret'],
            'grant_type'        => $this->config['grant_type'],
            'redirect_uri'      => $this->config['redirect_uri'],
            'code'              => $code
        );
        $response = $this->proxy->getAccessToken( $post_data );
        if ( isset( $response->getRawData()->access_token ) ) {
            return $response->getRawData()->access_token;
        }
        throw new \Instagram\Core\ApiException( $response->getErrorMessage(), $response->getErrorCode(), $response->getErrorType() );
    }
    
}