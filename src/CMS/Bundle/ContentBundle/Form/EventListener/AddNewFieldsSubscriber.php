<?php
/**
 * User: DCA
 * Date: 29/11/2016
 * Time: 11:10
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Form\EventListener;


use CMS\Bundle\ContentBundle\Entity\Field;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddNewFieldsSubscriber implements EventSubscriberInterface
{

    protected $fields;

    /**
     * AddNewFieldsSubscriber constructor.
     * @param ArrayCollection|Field[] $fields
     */
    public function __construct($fields)
    {
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

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $fieldvaluesTemp = $form->get('fieldValuesTemp');
        foreach ($this->fields as $field) {
            $params = $field->getField()->getParams();
            $required = (isset($params['required'])) ? $params['required'] : false;
            $options = array('label' => $field->getTitle(), 'required' => $required);
            $type = $field->getField()->getTypeField();
            if ($type == DateType::class) {
                $options['attr'] = array('class' => 'datetimepicker');
                $type = TextType::class;
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

            $fieldvaluesTemp->add($field->getName(), $type, $options);
        }
    }
}