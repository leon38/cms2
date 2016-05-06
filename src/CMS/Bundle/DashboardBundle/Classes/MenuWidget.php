<?php
/**
 * Name: Menu Widget
 * Description: Widget affichant un menu
 * Author: Damien Corona
 * Date: 01/12/2015
 */
namespace CMS\Bundle\DashboardBundle\Classes;

use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use CMS\MenuBundle\Entity\MenuTaxonomy;

/**
 * Définit les méthodes pour le Widget affichant les menus
 */
class MenuWidget extends Widget implements IWidget
{

	public function widget()
	{
		$menuTaxonomy = $this->em->getRepository('SCMSMenuBundle:MenuTaxonomy')->find($this->args['menuTaxonomy']);
		$repo = $this->em->getRepository('SCMSMenuBundle:Menu');
		$language = $this->args['language'];
		$entries = $repo->getEntriesMenuByLangQuery($menuTaxonomy, $language);
    	$options = array(
		    'decorate' => true,
		    'rootOpen' => '<ul>',
		    'rootClose' => '</ul>',
		    'childOpen' => function($node) {
		    	return (count($node['__children'])) ? '<li class="has_children">' : '<li>';
		    },
		    'childClose' => '</li>',
		    'nodeDecorator' => function($node) {
		    	$str = ($node['intern'] && $node['name_route'] != '#') ? '<a href="'.$this->router->generate($node['name_route']).'">' : '<a href="'.$node['path'].'">';
		        $str .= ($node['displayIcon'] && $node['classIcon'] != '') ? '<i class="'.$node['classIcon'].'"></i>' : '';
		        $str .= '<span>'.$node['title'].'</span></a>';
		        return $str;
		    }
		);
		$htmlTree = $repo->buildTree(
			$entries->getArrayResult(),
		    $options
		);

		return $this->templating->render("DashboardBundle:Templates:MenuWidget.html.twig", array("html" => $htmlTree));
	}

	public function form()
	{
		$form = parent::form();
		$form->add('menuTaxonomy', 'entity', array(
					'class' => 'SCMSMenuBundle:MenuTaxonomy',
					'property' => 'name',
					'empty_value' => 'Choisissez un menu',
					'label' => false
					)
				)
			 ->add('language', 'entity', array(
			 	'class' => 'SCMSAdminBundle:Language',
			 	'property' => 'title',
			 	'empty_value' => 'Chosissez une langue',
			 	'label' => false
			 	)
			 )
			 ->add('type', 'hidden', array('data' => 'MenuWidget'));
		return $form;
	}

}