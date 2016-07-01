<?php
/**
 * User: DCA
 * Date: 01/07/2016
 * Time: 09:32
 * cms2
 */

namespace CMS\Bundle\ContactBundle\Twig;


class ContactFormExtension extends \Twig_Extension
{

  public function getFilters()
  {
    return array(
      new \Twig_SimpleFilter('contact_form', array($this, 'contactFormFilter')),
    );
  }

  public function contactFormFilter($value)
  {
    preg_match_all('/^\[(.*)\]/', $value, $fields);
    $fields = $fields[1];
    foreach($fields as $field) {
      list($type, $name, $atts) = explode(" ", $field);
      switch($type) {
        case 'text':
        case 'text*':
          $value = str_replace('['.$field.']', $this->handleTextField($name, $atts), $value);
          break;
      }
    }
    return $value;
  }

  public function handleTextField($name, $atts)
  {
    $temp_atts = array();
    foreach($atts as $att) {
      list($att_label, $att_value) = explode(":", $atts);
      $temp_atts[] = $att_label.'="'.$att_value.'"';
    }
    return '<input type="text" name="'.$name.'" '. implode(' ', $temp_atts).' />';
  }

  public function getName()
  {
    return 'contact_form_extension';
  }
}