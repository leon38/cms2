<?php
/**
 * Name: Calendar Widget
 * Description: Affiche un calendrier
 * Author: Damien Corona
 * Date: 28/01/2015
 */
namespace CMS\Bundle\DashboardBundle\Classes;

use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;

class CalendarWidget extends Widget implements IWidget
{
	public function widget()
	{
		$now = new \DateTime('now');
		$current_month = $now->format('n');
		$today = new \DateTime('now');
    	if(!isset($this->args['month']) || $this->args['month'] == 0) {
    		$month = $today->format('n');
    	}

    	if(!isset($this->args['month']) || $this->args['month'] == 0) {
    		$year = $today->format('Y');
    	}

		return $this->templating->render("DashboardBundle:Templates:CalendarWidget.html.twig", array('month' => $month, 'year' => $year));
	}

	public function form()
	{
		$form = parent::form();

	    return $form;
	}
}