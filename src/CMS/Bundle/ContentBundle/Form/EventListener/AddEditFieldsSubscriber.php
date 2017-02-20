<?php
namespace CMS\Bundle\ContentBundle\Form\EventListener;

use CMS\Bundle\ContentBundle\Form\Type\DeezerType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;

/**
 * User: DCA
 * Date: 29/11/2016
 * Time: 11:02
 * cms2
 */
class AddEditFieldsSubscriber implements EventSubscriberInterface
{

    protected $fieldvalues;
    protected $fields;

    public function __construct($fieldvalues, $fields)
    {
        $this->fieldvalues = $fieldvalues;
        $this->fields = $fields;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $fieldvaluesTemp = $form->get('fieldValuesTemp');
        $names = array();
        $i = 0;
        foreach ($this->fieldvalues as $fieldvalue) {

            $field = $fieldvalue->getField();
            $names[] = $field->getName();
            $params = $field->getField()->getParams();
            $required = (isset($params['required'])) ? $params['required'] : false;
            $options = array(
                'label' => $field->getTitle(),
                'data' => $fieldvalue->getValue(),
                'required' => $required,
            );

            $type = $field->getField()->getTypeField();
            if ($type == DateType::class) {
                $options['attr'] = array('class' => 'datetimepicker');
                $options['widget'] = 'single_text';
                $options['data'] = \DateTime::createFromFormat("d/m/Y H:i", $options['data']);
            }

            if (isset($params['editor']) && $params['editor'] == true) {
                $options['attr'] = array('class' => 'summernote');
            }

            if (isset($params['options']) && $params['options'] != '') {
                $tmp_choices = explode('%%', $params['options']);
                $choices = array();
                foreach ($tmp_choices as $choice) {
                    $choices[] = explode('::', $choice);
                }
                $options['choices'] = $choices;
            }

            if ($field->getName() == 'musique') {
                $type = isset($options['api']) ? $options['api'] : DeezerType::class;
            }

            if ($type == FileType::class && $fieldvalue->getValue() != '') {
                $options['data'] = new File($fieldvalue->getValue());
            }

            // $options = array_merge($options, $attributes);
            $fieldvaluesTemp->add($field->getName(), $type, $options);

        }
        //die;
        $diff = array_diff(array_keys($this->fields), $names);
        foreach ($diff as $name) {
            $field = $this->fields[$name];
            $params = $field->getField()->getParams();
            $required = (isset($params['required'])) ? $params['required'] : false;
            $options = array('label' => $field->getTitle(), 'required' => $required);
            $type = $field->getField()->getTypeField();
            if ($type == DateType::class) {
                $options['attr'] = array('class' => 'datetimepicker');
                $options['widget'] = 'single_text';
            }
            if (isset($params['editor']) && $params['editor'] == true) {
                $options['attr'] = array('class' => 'summernote');
            }

            if (isset($params['attr'])) {
                $options['attr'] = $params['attr'];
            }

            if (isset($params['options']) && $params['options'] != '') {
                $tmp_choices = explode('%%', $params['options']);
                $choices = array();
                foreach ($tmp_choices as $choice) {
                    list($value, $label) = explode('::', $choice);
                    $choices[$value] = $label;
                }
                $options['choices'] = $choices;
                $options['expanded'] = true;
            }

            if ($type == 'music') {
                $type = isset($options['api']) ? $options['api'] : 'deezer';
            }

            //$options = array_merge($options, $attributes);

            $fieldvaluesTemp->add($field->getName(), $type, $options);

        }
    }
}