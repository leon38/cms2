<?php
namespace CMS\Bundle\DashboardBundle\Event;

final class WidgetEvents
{

	/**
	 * L'évènement widget.events est lancé chaque fois qu'un widget est enregistré
	 *
	 * Le "listener" de l'évènement reçoit une instance de
	 * CMS\Bundle\DashboardBundle\Event\RegisterWidgetEvent
	 *
 	 * @var string
	 */
	const REGISTER_WIDGET = 'register.widget';
}