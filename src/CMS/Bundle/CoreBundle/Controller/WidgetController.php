<?php

namespace CMS\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CMS\Bundle\CoreBundle\Entity\Widget;
use CMS\Bundle\CoreBundle\Form\WidgetType;

/**
 * Widget controller.
 *
 * @Route("/admin/widget")
 */
class WidgetController extends Controller
{
    /**
     * Lists all Widget entities.
     *
     * @Route("/", name="admin_widget_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $widgets = $em->getRepository('CoreBundle:Widget')->findAll();
        $widgetstypelist=$this->_generateListTypeWidget();
        
        return $this->render(
            'CoreBundle:Widget:index.html.twig',
            array(
                'widgets' => $widgets,
                'url' => 'admin_widget_delete',
                'widgetstypelist' => $widgetstypelist,
            )
        );
    }
    
    /**
     * Creates a new Widget entity.
     *
     * @Route("/new/{widgetClass}", name="admin_widget_new")
     * @Method({"GET", "POST"})
     */
    public function newAction($widgetClass, Request $request)
    {
        $widget = new Widget();
        $widgetClassObj = $this->get('cms.core.factory.widget')->createWidget($widgetClass);
//        dump($request); die;
        $widget->setWidget($widgetClassObj);
        $form = $this->createForm('CMS\Bundle\CoreBundle\Form\WidgetType', $widget, array(
            'attr'   => array('class' => 'form'),
            'widgetclass' => $widgetClassObj
        ));
        $form->handleRequest($request);
//        dump($form->isSubmitted(), $form->isValid()); die;
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $params = $form['params']->getData();
            $widgetClassObj->setParams($params);
            $widgetClassObj->setEntityManager(null);
            $widgetClassObj->setTemplating(null);
            $widget->setWidget($widgetClassObj);
            $em->persist($widget);
            $em->flush();
            
            return $this->redirectToRoute('admin_widget_index');
        }
        
        return $this->render(
            'CoreBundle:Widget:new.html.twig',
            array(
                'widget' => $widget,
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * Displays a form to edit an existing Widget entity.
     *
     * @Route("/{id}/edit", name="admin_widget_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Widget $widget)
    {
        $widgetClass = $widget->getWidget();
        $editForm = $this->createForm('CMS\Bundle\CoreBundle\Form\WidgetType', $widget, array(
            'action' => $this->generateUrl('admin_widget_edit', array('widget' => $widget)),
            'method' => 'POST',
            'attr'   => array('class' => 'form'),
            'widgetclass' => $widgetClass
        ));
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($widget);
            $em->flush();
            
            return $this->redirectToRoute('admin_widget_index');
        }
        
        return $this->render(
            'CoreBundle:Widget:edit.html.twig',
            array(
                'widget' => $widget,
                'edit_form' => $editForm->createView(),
            )
        );
    }
    
    /**
     * Deletes a Widget entity.
     *
     * @Route("/delete/{id}", name="admin_widget_delete")
     * @Method("GET")
     */
    public function deleteAction(Widget $widget)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($widget);
        $em->flush();
        
        return $this->redirectToRoute('admin_widget_index');
    }
    
    
    /**
     * Retourne la liste des types des champs
     *
     * @return array
     */
    private function _getWidgetsType()
    {
        $path = $this->_getEntityPath();
        $fieldsDirectory = $path.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'Widgets';
        //open my directory
        $myDirectory = opendir($fieldsDirectory);
        // get each entry
        while ($field = readdir($myDirectory)) {
            if ($field != '.' && $field != '..') {
                $field = substr($field, 0, strrpos($field, '.'));
                $fields[] = $field;
            }
        }
        // close directory
        closedir($myDirectory);
        sort($fields);
        return $fields;
    }
    /**
     * Retourne la liste déroulante des types de champ disponible
     *
     * @return String
     */
    private function _generateListTypeWidget()
    {
        $widgetsListType = $this->_getWidgetsType();
        $widgetsDirectory = $this->_getWidgetPath();
        $path = $this->_getEntityPath();
        $html = '<select name="widgettype" id="widgettype" class="form-control" onchange="changeUrl(this)">';
        $html .= '<option value="">--</option>';
        foreach ($widgetsListType as $key => $widgettype) {
            $widgetclass = $widgetsDirectory.$widgettype;
            $widget = new $widgetclass($this->get('templating'), $this->getDoctrine()->getManager());
            $html .= '<option value="'.$widgettype.'">'.$widget->getName().'</option>';
        }
        $html .= '</select>';
        return $html;
    }
    /**
     * Retourne le chemin vers les champs
     *
     * @return String
     */
    private function _getWidgetPath()
    {
        $dirbase = '\CMS\Bundle\CoreBundle\Classes\Widgets\\';
        return $dirbase;
    }
    
    /**
     * Retourne le chemin vers les entités
     *
     * @return String
     */
    private function _getEntityPath()
    {
        $dirbase = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        return $dirbase;
    }
    
}
