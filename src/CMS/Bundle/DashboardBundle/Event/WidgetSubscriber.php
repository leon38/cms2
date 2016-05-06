<?php
namespace CMS\Bundle\DashboardBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;



class WidgetSubscriber implements EventSubscriberInterface
{

	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	static public function getSubscirbedEvents()
	{
		return array('register.widget' => array('onRegisterWidget', 0));
	}

	/**
	 * Il enregistre le widget dans la base
	 * @param  RegisterWidgetEvent $event Evenement d'enregistrement des widgets
	 */
	public function onRegisterWidget(RegisterWidgetEvent $event)
	{
		$widget = $event->getWidget();
		$this->em->persist($widget);
		$this->em->flush();
	}

}