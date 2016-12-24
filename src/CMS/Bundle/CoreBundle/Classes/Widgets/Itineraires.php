<?php
/**
 * User: DCA
 * Date: 01/12/2016
 * Time: 08:29
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;


use CMS\Bundle\ContentBundle\Classes\TCXParser;
use CMS\Bundle\ContentBundle\Entity\Fields\KMLField;
use CMS\Bundle\CoreBundle\Classes\Widget;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Itineraires extends Widget
{
    
    public function display($param = array())
    {
        $params = $this->getParams();
        $taxonomy = $this->getEntityManager()->getRepository('ContentBundle:ContentTaxonomy')->find($params['content_taxonomy']);
        $randos = $this->getEntityManager()->getRepository('ContentBundle:Content')->findBy(array('taxonomy' => $taxonomy));
        $points = array();
        foreach($randos as $rando) {
            $path = $rando->getFieldValue('fichier-kml');
            if ($path != '') {
                
                $tcxparser = new TCXParser($path);
                $points_rando = $tcxparser->getPoints();
                $point_tmp = end($points_rando);
                $points[] = array('latitude' => $point_tmp->getLatitude(), 'longitude' => $point_tmp->getLongitude());
            }
        }
        $this->addScript('/bundles/core/js/itineraires.js');
        return $this->getTemplating()->render('CoreBundle:Widget:itineraires.html.twig', array('points' => json_encode($points)));
    }
    
    public function getOptions()
    {
        $params = $this->getParams();
        
        $taxonomies = $this->getEntityManager()->getRepository('ContentBundle:ContentTaxonomy')->findAll();
        $taxs = array();
        foreach ($taxonomies as $taxonomy) {
            $taxs[$taxonomy->getTitle()] = $taxonomy->getId();
        }
        $options['display_title'] = array('type' => 'choice', 'name' => 'display_title', 'choices' => array('Oui' => true, 'Non' => false), 'value' => $this->getParamsValue($params, 'display_title', 'choice'));
        $options['content_taxonomy'] = array('type' => 'choice', 'name' => 'content_taxonomy', 'choices' => $taxs, 'value' => $this->getParamsValue($params, 'content_taxonomy', 'choice'));
        return $options;
    }
}