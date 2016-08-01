<?php

namespace CMS\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;

use CMS\Bundle\CoreBundle\Manager\ThemeManager;

/**
 * Gère les thèmes
 *
 * @package cms
 * @subpackage Core
 * @since 0.1
 *
 * @Route("/admin")
 */
class ThemeController extends Controller
{

	/**
	 * Retourne la liste des themes
	 *
	 * @Route("/themes", name="admin_themes")
	 */
	public function indexAction()
	{
		$dir = $this->getParameter('theme_dir');

		$themes = ThemeManager::listThemes($dir, $this->get('kernel')->getRootDir());

		$activated = $this->get('cms.core.option_manager')->get('theme', '');


		return $this->render('CoreBundle:Theme:index.html.twig', array('themes' => $themes, 'dir' => $dir, 'activated' => $activated));
	}


	/**
	 * Active le theme
	 * @param  String $theme Thème du CMS
	 * @return Response
	 *
	 * @Route("/activate/{theme}", name="admin_activate_theme")
	 */
	public function activateAction($theme)
	{
        $infos = ThemeManager::getInfo($this->get('kernel')->getRootDir().'/../templates/'.$theme);
        $positions = explode(', ',$infos['Positions']);
        $this->get('cms.core.option_manager')->add('positions', serialize($positions), 'array');
		$this->get('cms.core.option_manager')->add('theme', $theme);

		return new JsonResponse(array('theme' => $theme));
	}
}