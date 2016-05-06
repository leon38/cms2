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

    /**
     * @Route("/new", name="admin_dashboard_widget_new")
     * @Template()
     */
    public function newWidgetAction()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../Classes')->contains('Description: ');
        $files = array();
        foreach($finder as $file) {
            $contents = $file->getContents();
            preg_match_all('/\*[[:space:]]*(Name[^(\*\/$)]*|Description[^(\*\/$)]*|Author[^(\*\/$)]*|Date[^(\*$)]*)/i', $contents, $matches);
            foreach($matches[1] as $match) {
                $values = preg_split('/\:/i', $match);
                $infos_widget[basename($file->getRealpath())][trim($values[0])] = trim($values[1]);
            }

        }
        return $this->render('DashboardBundle:Dashboard:newWidget.html.twig', array('widgets' => $infos_widget));
    }

    /**
     * Create a new widget
     *
     * @Route("/", name="admin_dashboard_widget_create")
     * @Method("POST")
     */
    public function createWidgetAction(Request $request)
    {
        $values = $request->request->get('form');
        $service = strtolower($values['type']).'.manager';
        $type = 'CMS\Bundle\DashboardBundle\Classes\\'.$values['type'];
        $widget = new $type($this->get('form.factory'),
				           $this->getDoctrine()->getManager(),
				           $this->get('templating'),
				           $values);

        $this->get($service)->setWidget($widget)->save();

        return $this->redirect($this->generateUrl('cmsdashboard_index'));

    }

    /**
     * Creates a form to create a Menu entity.
     *
     * @param Menu $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Widget $widget)
    {
        $form = $widget->form();

        return $form;
    }

    /**
     * Return widgets for a position
     * @param  String $position Widgets position
     * @return array           Widgets display
     *
     * @Route("/widgets/{position}", name="admin_dashboard_widgets_position")
     */
    public function getWidgetsAction($position)
    {
        $sidebar = $this->getDoctrine()->getRepository('DashboardBundle:WidgetEntity')->findBy(array('position' => $position));
        $sidebar_tmp = array();
        foreach($sidebar as $widget_e) {
            $args = $widget_e->getArgs();
            $type = "\CMS\Bundle\DashboardBundle\Classes\\".$args['type'];
            $widget = new $type($this->get('form.factory'), $this->getDoctrine()->getManager(), $this->get('templating'), $this->get('router'), $args);
            $sidebar_tmp[] = $widget->widget();
        }
        return $this->render('DashboardBundle:Templates:'.$position.'.html.twig', array('html' => $sidebar_tmp));
    }
}
