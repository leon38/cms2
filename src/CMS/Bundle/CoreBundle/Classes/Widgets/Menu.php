<?php
/**
 * User: DCA
 * Date: 07/10/2016
 * Time: 16:10
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes\Widgets;

use CMS\Bundle\CoreBundle\Classes\Widget;
use Symfony\Component\CssSelector\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Menu extends Widget
{
    
    public function getName()
    {
        return "Menu";
    }
    
    public function display()
    {
        try {
            $menu = Yaml::parse(
                file_get_contents($this->getContainer()->get('kernel')->getRootDir().'/config/menu.yml')
            );
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
        dump($menu); //die;
        return $this->getTemplating()->render(
            'CoreBundle:Widget:menu.html.twig',
            array('entries' => $menu)
        );
    }
    
    public function getOptions()
    {
        return array();
    }
    
}