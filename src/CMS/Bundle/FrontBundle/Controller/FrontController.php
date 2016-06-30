<?php

namespace CMS\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{
	private $_theme;
	private $_title;

	public function init()
	{
		$this->_theme = $this->get('cms.core.option_manager')->get('theme', '');
		$this->_title = $this->get('cms.core.option_manager')->get('sitename', '');
	}

	/**
	 * Plain url (no rewrite)
	 * @param Request $request parameter query string
	 *
	 * @Route("/", name="home")
	 */
	public function indexAction(Request $request)
	{
		$this->init();
		$em = $this->getDoctrine()->getManager();
		$contents = $em->getRepository('ContentBundle:Content')->findBy(array('published' => 1), array('created' => 'DESC'));
		return $this->render('@cms/'.$this->_theme.'/category.html.twig', array('title' => $this->_title, 'theme' => $this->_theme, 'contents' => $contents));
	}

	/**
	 * Affiche les postes de la catégorie
	 * @param  String  $categoryName alias de la category
	 * @param  Request $request
	 * @return template
	 *
	 * @Route("/category/{categoryName}")
	 */
	public function categoryAction($categoryName, Request $request)
	{
		$this->init();
		$em = $this->getDoctrine()->getManager();
		$category = $em->getRepository('ContentBundle:Category')->findOneBy(array('url' => $categoryName));
		return $this->render('@cms/'.$this->_theme.'/category.html.twig', array('title' => $category->getTitle(), 'theme' => $this->_theme, 'contents' => $category->getContents()));
	}

	/**
	 * Affiche le post ou les posts de la catégorie
	 * @param  String $alias Alias d'une catégorie ou d'un post
	 * @return template
	 *
	 * @Route("/{alias}", name="front_single")
	 */
	public function singleAction($alias, Request $request)
	{
		$this->init();
		$em = $this->getDoctrine()->getManager();
		$content = $em->getRepository('ContentBundle:Content')->findOneBy(array('url' => $alias));
		if (is_null($content)) {
			$category = $em->getRepository('ContentBundle:Category')->findOneBy(array('url' => $alias));
			if (is_null($category)) {
				$taxonomy = $em->getRepository('ContentBundle:ContentTaxonomy')->findOneBy(array('alias' => $alias));
				if (!is_null($taxonomy)) {
					return $this->render('@cms/'.$this->_theme.'/archive.html.twig', array('title' => $taxonomy->getTitle(), 'theme' => $this->_theme, 'contents' => $taxonomy->getContents()));
				}
				throw new NotFoundHttpException("Page not found");
			}
			return $this->render('@cms/'.$this->_theme.'/category.html.twig', array('title' => $category->getTitle(), 'theme' => $this->_theme, 'contents' => $category->getContents()));
		}
		return $this->render('@cms/'.$this->_theme.'/single.html.twig', array('title' => $content->getTitle(), 'theme' => $this->_theme, 'content' => $content));
	}

}