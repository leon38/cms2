<?php
namespace CMS\Bundle\DashboardBundle\Manager;

use Doctrine\ORM\EntityManager;


use CMS\Bundle\DashboardBundle\Entity\WidgetEntity;
use CMS\Bundle\DashboardBundle\Classes\Widget;

class MenuWidgetManager
{

	protected $args;

	protected $menuWidget;

	protected $em;

	public function __construct(array $args, EntityManager $em)
	{
		$this->args = $args;
		$this->em = $em;
	}


	public function setWidget(Widget $menuWidget)
	{
		$this->menuWidget = $menuWidget;
		$this->args = array_merge($this->args, $menuWidget->getArgs());
		return $this;
	}



	public function save()
	{
		$widgetEntity = new WidgetEntity();
		$repoMenu = $this->em->getRepository('SCMSMenuBundle:Menu');
		$widgetEntity->setTitle($this->args['title']);
		$widgetEntity->setDescription($this->args['description']);
		$widgetEntity->setPosition($this->args['position']);

		unset($this->args['title']);
		unset($this->args['description']);
		unset($this->args['position']);
		unset($this->args['args']);
		unset($this->args['repoMenuTaxonomy']);
		unset($this->args['_token']);

		$widgetEntity->setArgs($this->args);
		$this->em->persist($widgetEntity);
		$this->em->flush();
	}
}