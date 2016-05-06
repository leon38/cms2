<?php
namespace CMS\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class SecurityController extends Controller
{

	/**
	 * @Route("/admin/login", name="admin_login")
	 */
	public function loginAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
        $role = $em->getRepository('CoreBundle:Role')->findOneBy(array('role_nicename' => 'Super Administrator'));
        if ($role === null) {
            return $this->redirect($this->generateUrl('admin_install'));
        }

		$authenticationUtils = $this->get('security.authentication_utils');

	    // get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();

	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();

	    $activated = $this->get('cms.core.option_manager')->get('theme', '');
	    $dir = $this->getParameter('theme_dir');

	    $adminCss = $this->get('cms.core.theme_manager')->getAdminCss($dir.'/'.$activated);
	    if($adminCss !== false)
	    	$adminCss = str_replace($dir, '', $adminCss);


	    return $this->render('CoreBundle:Security:login.html.twig', array(
	            // last username entered by the user
	            'last_username' => $lastUsername,
	            'error'         => $error,
	            'adminCss'		=> $adminCss
	        ));
	}

}