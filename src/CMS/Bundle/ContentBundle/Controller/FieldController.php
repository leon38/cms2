<?php
/**
 * Controle un champ de contenu
 *
 * PHP version 5.4
 *
 * @category PHP
 * @package  ContentBundle
 * @author   Damien Corona <leoncorono@gmail.com>
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version  SVN: 0.1: CMS Symfony
 * @link     https://github.com/damienc38/CMS/blob/master/src/CMS/ContentBundle/Controller/CategoryController.php
 */
namespace CMS\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CMS\Bundle\ContentBundle\Entity\Field;
use CMS\Bundle\ContentBundle\Form\FieldType;

/**
 * Controle un champ de contenu
 *
 * PHP version 5.4
 *
 * @category PHP
 * @package  ContentBundle
 * @author   Damien Corona <leoncorono@gmail.com>
 * @license  http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version  SVN: 0.1: CMS Symfony
 * @link     https://github.com/damienc38/CMS/blob/master/src/CMS/ContentBundle/Controller/FieldController.php
 *
 * @Route("/admin")
 */
class FieldController extends Controller
{
    /**
     * Retourne la liste des champs
     *
     * @return array
     *
     * @Route("/field/", name="admin_field")
     * @Template()
     */
    public function indexAction()
    {
        $fieldslist=$this->_generateListTypeField();
        $entities = $this->getDoctrine()->getRepository('ContentBundle:Field')->findAll();
        $session = $this->get('session');
        $session->set('active', 'Contenus');
        return $this->render('ContentBundle:Field:index.html.twig',
            array(
            'entities' => $entities,
            'fieldstype' => $fieldslist,
            'url' => 'admin_field_delete'
            ));
    }

    /**
     * Retourne la liste des types des champs
     *
     * @return array
     */
    private function _getFieldsType()
    {
        $path = $this->_getEntityPath();
        $fieldsDirectory = $path.DIRECTORY_SEPARATOR.'Entity'.DIRECTORY_SEPARATOR.'Fields';
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
    private function _generateListTypeField()
    {
        $fieldsListType = $this->_getFieldsType();
        $fieldsDirectory = $this->_getFieldPath();
        $path = $this->_getEntityPath();
        $html = '<select name="fieldtype" id="fieldtype" class="form-control">';
        foreach ($fieldsListType as $key => $fieldtype) {
            $fieldclass = $fieldsDirectory.$fieldtype;
            $field = new $fieldclass;
            $html .= '<option value="'.$fieldtype.'">'.$field->getName().'</option>';
        }
        $html .= '</select>';
        return $html;
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
    /**
     * Retourne le chemin vers les champs
     *
     * @return String
     */
    private function _getFieldPath()
    {
        $dirbase = '\CMS\Bundle\ContentBundle\Entity\Fields\\';
        return $dirbase;
    }
    /**
     * Insère un nouveau champ
     *
     * @param Request $request Objet Request
     *
     * @return array
     *
     * @Route("/fields/new/{fieldtype}", name="admin_field_new", defaults={"fieldtype": ""})
     * @Template()
     */
    public function newAction($fieldtype, Request $request)
    {
        $field = new Field();
        $fieldsOptions = null;

        if ($fieldtype == "") {
            $fieldtype = $request->query->get('fieldtype');
        }

        if ($fieldtype != "") {
            $fieldPath = $this->_getFieldPath();
            $fieldclass = $fieldPath.$fieldtype;
            $fieldclass = new $fieldclass;
            $field->setField($fieldclass);
        }
        $form = $this->createCreateForm($field, $fieldclass, $fieldtype);
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $fieldtype = $request->get('fieldtype');
                $fieldPath = $this->_getFieldPath();
                $fieldclass = $fieldPath.$fieldtype;
                $fieldValue = new $fieldclass;
                $params = $form['params']->getData();
                $fieldValue->setParams($params);
                $field->setField($fieldValue);
                $field->setType($fieldtype);
                foreach ($field->getContentTaxonomy() as $key => $type) {
                    $type->addField($field);
                    $em->persist($type);
                }
                $em->persist($field);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_field'));
            }
        }
        return $this->render('ContentBundle:Field:new.html.twig',
            array('form' => $form->createView(), 'fieldsOptions' => $fieldsOptions, 'field' => $field, 'fieldtype' => $fieldtype));
    }
    /**
     * @Route("/fields/edit/{id}", name="admin_field_edit")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $field = $this->getDoctrine()->getRepository('ContentBundle:Field')->find($id);
        $fieldsOptions = null;
        $fieldtype = get_class($field->getField());
        $fieldclass = '';
        if ($fieldtype != "") {
            $fieldclass = new $fieldtype;
            $fieldclass->setParams($field->getField()->getParams());
        }

        $form = $this->createEditForm($field, $fieldtype, $fieldclass);

        if ($request->isMethod('POST')) {
            $old_field = $field;
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $options = $request->request->get('options');
                $params = $form['params']->getData();
                $fieldClass = $field->getField();
                if (is_object($fieldClass)) {
                    $fieldClass->setParams($params);
                }
                $field->setField(null);
                foreach ($field->getContentTaxonomy() as $key => $type) {
                    $em->persist($type);
                }
                $em->persist($field);
                $em->flush();
                $field->setField($fieldClass);
                $em->persist($field);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_field'));
            }
        }
        return $this->render('ContentBundle:Field:edit.html.twig',
            array(
                'edit_form' => $form->createView(),
                'fieldsOptions' => $fieldsOptions,
                'field' => $field,
                'fieldtype' => $fieldtype));
    }

    private function getDateTimeObject($date)
    {
        //input format : M/d/Y
        $date = explode('-', $date);
        if (is_array($date) && count($date)==3) {
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];
            $date = new \DateTime();
            $date->setDate($year,$month,$day);
        } else {
            $date = new \DateTime();
        }
        return $date;
    }

    private function getStringDate($content)
    {
        $datetime = $content->getCreated();
        $datetime = $datetime->format('m/d/Y');
        $content->setCreated($datetime);
        return $content;
    }

    private function getCopyItem($field)
    {
        $copy = new Field();
        $copy->setTitle($field->getTitle());
        $copy->setName($field->getName());
        $copy->setField($field->getField());
        $copy->setType($field->getType());
        return $copy;
    }

    /**
     * @Route("/fields/copy/{id}", name="fields_copy")
     * @Template("CMSContentBundle:ContentManager:list.html.twig")
     */
    public function copyItemAction(Request $request, $id)
    {
        $field = $this->getDoctrine()->getRepository('ContentBundle:Field')->find($id);
        $copy = $this->getCopyItem($field);
        $em = $this->getDoctrine()->getManager();
           $em->persist($copy);
           $em->flush();
        return $this->redirect($this->generateUrl('admin_field'));
    }

    /**
     * @Route("/fields/published/{id}", name="fields_published")
     * @Template("CMSContentBundle:ContentManager:list.html.twig")
     */
    public function publishedItemAction(Request $request, $id)
    {
        $field = $this->getDoctrine()->getRepository('ContentBundle:Field')->find($id);
        if($field->getPublished())
            $field->setPublished(0);
        else
            $field->setPublished(1);
        $em = $this->getDoctrine()->getManager();
           $em->persist($field);
           $em->flush();
        return $this->redirect($this->generateUrl('admin_field'));
    }

    /**
     * @Route("/field/delete/{id}", name="admin_field_delete")
     * @Template("CMSContentBundle:ContentManager:list.html.twig")
     */
    public function deleteAction(Request $request, $id)
    {
        $field = $this->getDoctrine()->getRepository('ContentBundle:Field')->find($id);
           $em = $this->getDoctrine()->getManager();
           $em->remove($field);
           $em->flush();
           return $this->redirect($this->generateUrl('admin_field'));
    }


    /**
     * Creates a form to create a Content entity.
     *
     * @param Content $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Field $entity, $fieldclass, $fieldtype)
    {
        $form = $this->createForm(new FieldType(), $entity, array(
            'action' => $this->generateUrl('admin_field_new', array('fieldtype' => $fieldtype)),
            'method' => 'POST',
            'attr'   => array('class' => 'form'),
            'fieldclass' => $fieldclass,
            'fieldtype'  => $fieldtype
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill')));

        return $form;
    }

    /**
    * Creates a form to edit a Content entity.
    *
    * @param Content $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Field $entity, $fieldtype, $fieldclass)
    {
        $form = $this->createForm(new FieldType(), $entity, array(
            'action' => $this->generateUrl('admin_field_edit', array('id' => $entity->getId())),
            'fieldtype' => $fieldtype,
            'fieldclass' => $fieldclass
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-info btn-fill')));

        return $form;
    }
}