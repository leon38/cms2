<?php
namespace CMS\Bundle\DashboardBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use CMS\Bundle\DashboardBundle\Classes\Widget;

class RegisterWidgetEvent extends Event
{
	protected $widget;

	public function __construct(Widget $widget)
	{
		$this->widget = $widget;
	}

	public function getWidget()
	{
		return $this->widget;
	}

}