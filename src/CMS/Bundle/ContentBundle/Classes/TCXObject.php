<?php
/**
 * User: DCA
 * Date: 11/10/2016
 * Time: 09:58
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Classes;


class TCXObject
{
    /**
     * @var \DateTime
     * Heure et date à laquelle le point a été pris
     */
    private $time;
    
    /**
     * @var int
     * Distance parcourue depuis le début
     */
    private $distance;
    
    /**
     * @var float
     * Altitude du point
     */
    private $altitude;
    
    /**
     * @var float
     * Latitude du point
     */
    private $latitude;
    
    /**
     * @var float
     * Longitude du point
     */
    private $longitude;
    
    public function __construct(\SimpleXMLElement $node)
    {
        $this->time = new \DateTime((string)$node->Time);
        $this->distance = (int)$node->DistanceMeters;
        $this->altitude = (float)$node->AltitudeMeters;
        $this->latitude = (float)$node->Position->LatitudeDegrees;
        $this->longitude = (float)$node->Position->LongitudeDegrees;
    }
    
    
    public function getAltitude()
    {
        return $this->altitude;
    }
    
    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }
    
    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }
}