<?php
/**
 * User: DCA
 * Date: 11/10/2016
 * Time: 09:50
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Classes;

use Symfony\Component\HttpFoundation\File\File;

class TCXParser
{
    /**
     * File to parse
     * @var \Symfony\Component\HttpFoundation\File\File
     */
    private $path;
    
    /**
     * @var \SimpleXMLElement
     * File's content to parse
     */
    private $data;
    
    /**
     * @var array
     * Points de l'itinéraire
     */
    private $points;
    
    
    public function __construct($path)
    {
        $this->path = new File($path);
        $this->data = simplexml_load_file($this->path->getPathname());
        $this->points = array();
    }
    
    public function getPoints()
    {
        
        foreach ($this->data->Activities[0] as $simpleXMLActivity) {

            // Track points.
            foreach ($simpleXMLActivity->Lap as $lap) {
                foreach ($lap->Track as $track) {
                    foreach ($track->Trackpoint as $trackPoint) {
                        $point = new TCXObject($trackPoint);
                        $this->points[] = $point;
                    }
                }
            }
        }
        return $this->points;
    }
    
    public function getAltitudes($precision = 100)
    {
        if (empty($this->points)) {
            $this->points = $this->getPoints();
        }
        $altitudes = array();
        $prec = 100 / $precision;
        $i = 0;
        foreach ($this->points as $point) {
            if ($i == 0 || $i % $prec == 0) {
                $altitudes[] = $point->getAltitude();
            }
            $i++;
        }
        return $altitudes;
    }
    
    public function getTimes($precision = 100)
    {
        if (empty($this->points)) {
            $this->points = $this->getPoints();
        }
        $times = array();
        $prec = 100 / $precision;
        $i = 0;
        foreach ($this->points as $point) {
            if ($i == 0 || $i % $prec == 0) {
                $times[] = $point->getTime()->format('H:i:s');
            }
            $i++;
        }
        return $times;
    }
    
    public function getCoordinates()
    {
        if (empty($this->points)) {
            $this->points = $this->getPoints();
        }
        $coords = array();
        $i = 0;
        $j = -1;
        $speeds = $this->getMaxMinSpeed();
        foreach ($this->points as $point) {
            $coords[$i]['latitude'] = $point->getLatitude();
            $coords[$i]['longitude'] = $point->getLongitude();
            if ($i > 0) {
                $distance = $point->getDistance() - $this->points[$j]->getDistance();
                $temps = date_diff($point->getTime(), $this->points[$j]->getTime());
                $speed = $distance / (int)$temps->format('%s');
                $coords[$i]['speed'] = $speed;
                $coords[$i]['speed_percent'] = ($speed == 0) ? $speeds['min_speed'] : (($speed / $speeds['max_speed']) * 100);
            } else {
                $coords[$i]['speed'] =  $speeds['min_speed'];
                $coords[$i]['speed_percent'] =  $speeds['min_speed'];
            }
            $i++;
            $j++;
        }
        $coords['max_speed'] = $speeds['max_speed'];
        $coords['min_speed'] = $speeds['min_speed'];
        return $coords;
    }
    
    public function getMaxMinSpeed()
    {
        if (empty($this->points)) {
            $this->points = $this->getPoints();
        }
        // Vitesse en metre par seconde
        $speeds = array();
        $i = 0;
        $j = -1;
        $max_speed = 0;
        $min_speed = 10000;
        foreach ($this->points as $point) {
            if ($i > 0) {
                $distance = $point->getDistance() - $this->points[$j]->getDistance();
                $temps = date_diff($point->getTime(), $this->points[$j]->getTime());
                $speed = $distance / (int)$temps->format('%s');
                if ($speed > $max_speed) {
                    $max_speed = $speed;
                }
                if ($speed < $min_speed) {
                    $min_speed = $speed;
                }
            }
            $i++;
            $j++;
        }
        $speeds['max_speed'] = $max_speed;
        $speeds['min_speed'] = $min_speed;
    
        
        return $speeds;
    }
}