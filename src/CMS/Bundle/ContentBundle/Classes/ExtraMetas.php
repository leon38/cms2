<?php

namespace CMS\Bundle\ContentBundle\Classes;

use CMS\Bundle\ContentBundle\Entity\MetaValueContent;
use CMS\Bundle\ContentBundle\Entity\MetaValueCategory;

class ExtraMetas
{
    public static function loadMetas($repository)
    {
        $html = null;


        $metas = $repository->findBy(array('published' => 1));
        return $metas;
        // foreach($metas as $meta) {
        //     if($meta->getPublished() && strpos($meta->getValue(), "%s") !== false)
        //         $html .= $meta->displayMetaInForm();
        // }

        // return $html;
    }

    public static function loadEditMetas($content, $controller)
    {
        $html = '';
        $metas = $controller->getDoctrine()->getRepository('ContentBundle:Meta')->findBy(array('published' => 1));
        $metas_res = array();
        foreach ($metas as $meta) {
            $display_elem = false;
            foreach ($content->getMetaValues() as $metavalue) {
                if ($meta->getPublished()) {
                    if ($meta->getId() == $metavalue->getMeta()->getId() && $content->getId() == $metavalue->getContent()->getId()) {
                        $metas_res['metavalue'][$meta->getId()] = $metavalue;
                        $display_elem = true;
                        break;
                    }
                }
            }
            if (!$display_elem) {
                $metas_res['meta'][$meta->getId()] = $meta;
            }
            // if (!$display_elem) {
            //     if ($meta->getPublished()) {
            //         $html .= $meta->displayMetaInform();
            //     }
            // }
        }
        return $metas_res;
    }

    public static function loadEditMetasCategory($category, $controller)
    {
        $html = '';
        $metas = $controller->getDoctrine()->getRepository('ContentBundle:Meta')->findBy(array('published' => 1));
        foreach ($metas as $meta) {
            $display_elem = false;
            foreach ($category->getMetaValues() as $metavalue) {
                if ($meta->getPublished()) {
                    if ($meta->getId() == $metavalue->getMeta()->getId() && $category->getId() == $metavalue->getCategory()->getId()) {
                        $html .= $meta->displayMetaInform($metavalue->getValue());
                        $display_elem = true;
                    }
                }
            }
            if (!$display_elem) {
                if ($meta->getPublished()) {
                    $html .= $meta->displayMetaInform();
                }
            }
        }
        return $html;
    }

    public static function saveMetas($controller, $em, $request, $content)
    {
        $metas = $controller->getDoctrine()->getRepository('ContentBundle:Meta')->findBy(array('published' => 1));
        foreach ($metas as $meta) {
            $value = $request->request->get($meta->getName());
            $metavalue = new MetaValueContent();
            $metavalue->setValue($value);
            $metavalue->setContent($content);
            $metavalue->setMeta($meta);
            $content->addMetaValue($metavalue);
            $meta->addMetavaluescontent($metavalue);
            $em->persist($metavalue);
            $em->persist($meta);
        }

        return true;
    }

    public static function saveMetasCategory($controller, $em, $request, $category)
    {
        $metas = $controller->getDoctrine()->getRepository('ContentBundle:Meta')->findBy(array('published' => 1));
        foreach ($metas as $meta) {
            $value = $request->request->get($meta->getName());
            $metavalue = new MetaValueCategory();
            $metavalue->setValue($value);
            $metavalue->setCategory($category);
            $metavalue->setMeta($meta);
            $category->addMetaValue($metavalue);
            $meta->addMetavaluescategory($metavalue);
            $em->persist($metavalue);
            $em->persist($meta);
        }

        return true;
    }

    public static function updateMetas($controller, $em, $request, $content)
    {
        $metas = $controller->getDoctrine()->getRepository('ContentBundle:Meta')->findBy(array('published' => 1));
        foreach ($metas as $meta) {
            $additem = false;
            foreach ($content->getMetavalues() as $metavalue) {
                if ($metavalue->getMeta()->getId() == $meta->getId()) {
                    $value = $request->request->get($meta->getName());
                    $metavalue->setValue($value);
                    $em->persist($metavalue);
                    $additem = true;
                }
            }
            if (!$additem) {
                $value = $request->request->get($meta->getName());
                $metavalue = new MetaValueContent();
                $metavalue->setValue($value);
                $metavalue->setContent($content);
                $metavalue->setMeta($meta);
                $content->addMetaValue($metavalue);
                $meta->addMetavaluescontent($metavalue);
                $em->persist($metavalue);
            }
        }
    }


    public static function updateMetasCategory($controller, $em, $request, $category)
    {
        $metas = $controller->getDoctrine()->getRepository('ContentBundle:Meta')->findBy(array('published' => 1));
        foreach ($metas as $meta) {
            $additem = false;
            foreach ($category->getMetavalues() as $metavalue) {
                if ($metavalue->getMeta()->getId() == $meta->getId()) {
                    $value = $request->request->get($meta->getName());
                    $metavalue->setValue($value);
                    $em->persist($metavalue);
                    $additem = true;
                }
            }
            if (!$additem) {
                $value = $request->request->get($meta->getName());
                $metavalue = new MetaValueCategory();
                $metavalue->setValue($value);
                $metavalue->setCategory($category);
                $metavalue->setMeta($meta);
                $category->addMetavalue($metavalue);
                $meta->addMetavaluescategory($metavalue);
                $em->persist($metavalue);
            }
        }
    }
}