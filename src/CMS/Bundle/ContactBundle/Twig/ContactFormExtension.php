<?php
/**
 * User: DCA
 * Date: 01/07/2016
 * Time: 09:32
 * cms2
 */

namespace CMS\Bundle\ContactBundle\Twig;

use CMS\Bundle\ContactBundle\Entity\Repository\ContactFormRepository;

class ContactFormExtension extends \Twig_Extension
{
    private $_contactFormRepo;
    private $_templating;

    public function __construct(ContactFormRepository $contactFormRepo)
    {
        $this->_contactFormRepo = $contactFormRepo;
    }

    public function setTemplating(\Twig_Environment $templating)
    {
        $this->_templating = $templating;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('contact_form', array($this, 'contactFormFilter')),
        );
    }

    public function contactFormFilter($value)
    {
        preg_match_all('/\[(.*)\]/', $value, $forms);
        $forms = $forms[0];
        $contactForm = $this->_contactFormRepo->findOneBy(array('tag' => $forms));
        if (!is_null($contactForm)) {
            preg_match_all('/\[(.*)\]/', $contactForm->getHtmlForm(), $fields);
            $htmlForm = $contactForm->getHtmlForm();
            //dump($fields[1]); die;
            foreach ($fields[1] as $field) {
                $atts = explode(" ", $field);
                $type = $atts[0];
                $name = $atts[1];
                unset($atts[0]);
                unset($atts[1]);
                switch ($type) {
                    case 'text':
                        $htmlForm = str_replace('[' . $field . ']', $this->handleTextField($name, $atts), $htmlForm);
                        break;
                    case 'email':
                        $htmlForm = str_replace('[' . $field . ']', $this->handleEmailField($name, $atts), $htmlForm);
                        break;
                    case 'textarea':
                        $htmlForm = str_replace('[' . $field . ']', $this->handleTextareaField($name, $atts), $htmlForm);
                        break;
                    case 'submit':
                        $htmlForm = str_replace('[' . $field . ']', $this->handleSubmitField($name, $atts), $htmlForm);
                        break;
                }
            }
            $htmlForm = $this->_templating->render('ContactBundle:Extension:contactForm.html.twig', array('htmlForm' => $htmlForm, 'contactFormId' => $contactForm->getId()));
            return str_replace($value, $htmlForm, $value);
        }
        return $value;
    }



    private function handleTextField($name, $atts)
    {
        $temp_atts = $this->handleAttributes($atts);
        $name = $this->handleRequiredAttribute($temp_atts, $name);

        return '<input type="text" name="contact[' . $name . ']" ' . implode(' ', $temp_atts) . ' />';
    }


    private function handleEmailField($name, $atts)
    {
        $temp_atts = $this->handleAttributes($atts);
        $name = $this->handleRequiredAttribute($temp_atts, $name);

        return '<input type="email" name="contact[' . $name . ']" ' . implode(' ', $temp_atts) . ' />';
    }

    private function handleTextareaField($name, $atts)
    {
        $temp_atts = $this->handleAttributes($atts);
        $name = $this->handleRequiredAttribute($temp_atts, $name);

        return '<textarea name="contact[' . $name . ']" ' . implode(' ', $temp_atts) . '></textarea>';
    }

    private function handleSubmitField($name, $atts)
    {
        $temp_atts = $this->handleAttributes($atts);
        $name = $this->handleRequiredAttribute($temp_atts, $name);

        return '<input type="submit" name="' . $name . '" ' . implode(' ', $temp_atts) . ' />';
    }

    private function handleAttributes($atts)
    {
        $temp_atts = array();
        foreach ($atts as $att) {
            list($att_label, $att_value) = explode(":", $att);
            if ($att_label == 'placeholder') {
                $temp_atts[] = $att_label . '="' . str_replace('_', ' ', $att_value) . '"';
            } else {
                $temp_atts[] = $att_label . '="' . $att_value . '"';
            }
        }
        return $temp_atts;
    }

    private function handleRequiredAttribute(&$temp_atts, $name)
    {
        if (strstr($name, '*')) {
            $temp_atts[] = 'required="required"';
            $name = str_replace('*', '', $name);
        }
        return $name;
    }

    public function getName()
    {
        return 'contact_form_extension';
    }
}