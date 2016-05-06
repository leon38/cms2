<?php

namespace CMS\Bundle\ContentBundle\Classes;

use CMS\Bundle\ContentBundle\Entity\FieldValue;

class ExtraFields
{
    public static function loadFields($controller, $contenttype)
    {
        $html = null;

        if ($contenttype) {
            foreach ($contenttype->getFields() as $key => $field) {
                if ($field->getPublished()) {
                    $html .= $field->getField()->displayfield($field,null);
                }
            }
        }

        return $html;
    }

    public static function loadEditFields($content)
    {
        $html = '';
        foreach ($content->getContentType()->getFields() as $key1 => $field) {
            $display_elem = false;
            foreach ($content->getFieldValues() as $key2 => $value) {
                if ($field->getPublished()) {
                    if ($field->getId() == $value->getField()->getId() && $content->getId() == $value->getContent()->getId()) {
                        $html .= $field->getField()->displayfield($field, $value->getValue());
                        $display_elem = true;
                    }
                }
            }
            if (!$display_elem) {
                if ($field->getPublished()) {
                    $html .= $field->getField()->displayfield($field);
                }
            }
        }
        return $html;
    }

    public static function saveFields($controller, $em, $request, $content, $contenttype)
    {
        if ($contenttype) {
            $type = $controller->getDoctrine()->getRepository('ContentBundle:ContentTaxonomy')->find($contenttype);
            $content->setContentType($type);
            foreach ($type->getFields() as $key => $field) {
                $typefield = $field->getField()->getTypeField();
                switch($typefield) {
                    case 'file':
                        $file = $request->files->get($field->getName());
                        $value = ExtraFields::upload($file);
                        break;
                    default:
                        $value = $request->request->get($field->getName());

                        break;
                }
                $fieldvalue = new FieldVaue();
                $fieldvalue->setValue($value);
                $fieldvalue->setContent($content);
                $fieldvalue->setField($field);
                $content->addFieldValue($fieldvalue);
                $field->addFieldValue($fieldvalue);
                $em->persist($fieldvalue);
                $em->persist($field);

            }
        }

        return true;
    }

    public static function updateFields($controller, $em, $request, $content, $contenttype)
    {

        foreach ($contenttype->getFields() as $key => $field) {
            $additem=null;
            foreach ($content->getFieldValues() as $key => $fieldvalue) {
                if ($fieldvalue->getField()->getId() == $field->getId()) {
                    $typefield = $field->getField()->getTypeField();
                    switch($typefield) {
                        case 'file':
                            $file = $request->files->get($field->getName());
                            $oldvalue = $fieldvalue->getValue();
                            $value = ExtraFields::upload($file,$oldvalue);
                            break;
                        default:
                            $value = $request->request->get($field->getName());
                            break;
                    }
                    $fieldvalue->setValue($value);
                    $em->persist($fieldvalue);
                    $additem=1;
                }
            }
            if (!$additem) {
                $typefield = $field->getField()->getTypeField();
                switch($typefield) {
                    case 'file':
                        $file = $request->files->get($field->getName());
                        $value = ExtraFields::upload($file);
                        break;
                    default:
                        $value = $request->request->get($field->getName());
                        break;
                }

                $value = $request->request->get($field->getName());
                $fieldvalue = new FieldVaue();
                $fieldvalue->setValue($value);
                $fieldvalue->setContent($content);
                $fieldvalue->setField($field);
                $content->addFieldValue($fieldvalue);
                $field->addFieldValue($fieldvalue);
                $em->persist($fieldvalue);
            }
        }
    }

    public static function upload($file, $oldvalue='')
    {

        //var_dump($file); die;
        if (is_object($file)) {

            $root_dir = __DIR__.'/../../../../web';
            $web_dir = '/uploads/'.date('Y').'/'.date('F');
            $dest = $root_dir.$web_dir;

            $path = $file->getClientOriginalName();
            //$extension = $file->guessExtension();
            $file->move($dest, $path);
            return $web_dir.'/'.$path;
        } else {
            return $oldvalue;
        }
    }
}