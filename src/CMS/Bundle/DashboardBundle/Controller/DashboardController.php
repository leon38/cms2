<?php

namespace CMS\Bundle\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Finder\Finder;

use CMS\Bundle\DashboardBundle\Classes\MenuWidget;

/**
 * @Route("/admin/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="admin_dashboard_index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('DashboardBundle:Dashboard:index.html.twig', array());
    }
    
}
