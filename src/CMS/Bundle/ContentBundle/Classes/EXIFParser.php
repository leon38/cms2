<?php
/**
 * User: DCA
 * Date: 13/10/2016
 * Time: 11:48
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Classes;

/**
 * Class EXIFParser
 * @package CMS\Bundle\ContentBundle\Classes
 */
class EXIFParser
{
    /**
     * @var float Latitude de la photo en décimal
     */
    private $_latitude;
    
    /**
     * @var float longitude de la photo en décimal
     */
    private $_longitude;
    
    /**
     * @var string latitude de la photo en degré, minute, seconde
     */
    private $_latitude_ref;
    
    /**
     * @var string longitude de la photo en degré, minute, seconde
     */
    private $_longitude_ref;
    
    
    /**
     * EXIFParser constructor.
     */
    public function __construct(){
        $this->_latitude = 0.0;
        $this->_longitude = 0.0;
        $this->_latitude_ref = '';
        $this->_longitude_ref = '';
    }
    
    public function getCoordinates($image){
        $metas = $this->_checkGPSCoordinates($image);
        $_GPS = $metas['GPS'];
        dump($metas, $image); die;
//        $_latitude = $this->_DMStoDEC(
//            $_GPS['GPSLatitude'][0],
//            $_GPS['GPSLatitude'][1],
//            $_GPS['GPSLatitude'][2],
//            $_GPS['GPSLatitudeRef']
//        );
//        $_longitude = $this->DMStoDEC(
//            $_GPS['GPSLongitude'][0],
//            $_GPS['GPSLongitude'][1],
//            $_GPS['GPSLongitude'][2],
//            $_GPS['GPSLongitudeRef']
//        );
        
//        $_location = array($_latitude, $_longitude);
//        return $_location;
    }
    
    private function _checkGPSCoordinates($image)
    {
        return exif_read_data($image, 'GPS', true);
    }
    
    
    private function _DMStoDEC($deg, $min, $sec, $ref){
        
        $array = explode('/', $deg);
        $deg = $array[0]/$array[1];
        $array = explode('/', $min);
        $min = $array[0]/$array[1];
        $array = explode('/', $sec);
        $sec = $array[0]/$array[1];
        
        $coordinate = $deg+((($min*60)+($sec))/3600);
        /**
         *  + + = North/East
         *  + - = North/West
         *  - - = South/West
         *  - + = South/East
         */
        if('s' === strtolower($ref) || 'w' === strtolower($ref)){
            // Negatify the coordinate
            $coordinate = 0-$coordinate;
        }
        
        return $coordinate;
    }
    
    private function _DECtoDMS($dec){
        $vars = explode(".", $dec);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];
        
        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = $tempma - ($min*60);
        
        return array("deg"=>$deg, "min"=>$min, "sec"=>$sec);
    }
}