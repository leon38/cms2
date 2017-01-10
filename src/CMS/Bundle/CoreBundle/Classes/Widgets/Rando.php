<?php
/**
 * User: Administrateur
 * Date: 04/10/2016
 * Time: 13:40
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;

use CMS\Bundle\CoreBundle\Classes\Widget;

class Rando extends Widget
{
    
    /**
     * @param array $param
     * @return string
     */
    public function display($param = array())
    {
        $params = $this->getParams();
        $taxonomy = $this->getEntityManager()->getRepository('ContentBundle:ContentTaxonomy')->find($params['content_taxonomy']);
        $randos_distance = $this->getEntityManager()->getRepository('ContentBundle:Content')->getValuesByMonth($taxonomy, 'Distance');
        $randos_denivele = $this->getEntityManager()->getRepository('ContentBundle:Content')->getValuesByMonth($taxonomy, 'Dénivelé');

        $labels = array();
        $data_distance = array();
        $data_denivele = array();
        $i = 0;
        foreach ($randos_distance as $rando) {
            $labels[$rando['month_created'].$rando['year_created']] = $rando['month_created'].'/'.$rando['year_created'];
            if (isset($data_distance[$rando['month_created'].$rando['year_created']])) {
                $data_distance[$rando['month_created'].$rando['year_created']] += (int)unserialize($rando['total']);
                $data_denivele[$rando['month_created'].$rando['year_created']] += (int)unserialize($randos_denivele[$i]['total']);
            } else {
                $data_distance[$rando['month_created'].$rando['year_created']] = (int)unserialize($rando['total']);
                $data_denivele[$rando['month_created'].$rando['year_created']] = (int)unserialize($randos_denivele[$i]['total']);
            }
            $i++;
        }
        $this->addScript('/bundles/core/js/randos.js');
        return $this->getTemplating()->render(
            'CoreBundle:Widget:rando.html.twig',
            array('labels' => $labels, 'data_distance' => $data_distance, 'data_denivele' => $data_denivele)
        );
    }
    
    
    public function getOptions()
    {
        $params = $this->getParams();
        
        $taxonomies = $this->getEntityManager()->getRepository('ContentBundle:ContentTaxonomy')->findAll();
        $taxs = array();
        foreach ($taxonomies as $taxonomy) {
            $taxs[$taxonomy->getId()] = $taxonomy->getTitle();
        }
        $options['display_title'] = array('type' => 'choice', 'name' => 'display_title', 'choices' => array(0 => 'Non', 1 => 'Oui'), 'value' => $this->getParamsValue($params, 'display_title', 'choice'));
        $options['content_taxonomy'] = array('type' => 'choice', 'name' => 'content_taxonomy', 'choices' => $taxs, 'value' => $this->getParamsValue($params, 'content_taxonomy', 'choice'));
        return $options;
    }
}