<?php
/**
 * User: DCA
 * Date: 29/11/2016
 * Time: 11:25
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Form\EventListener;


use CMS\Bundle\ContentBundle\Entity\Meta;
use CMS\Bundle\ContentBundle\Entity\MetaValue;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddEditMetasSubscriber implements EventSubscriberInterface
{
    
    /**
     * @var ArrayCollection|MetaValue[] $metavalues
     */
    protected $metavalues;
    
    /**
     * @var ArrayCollection|Meta[] $metas
     */
    protected $metas;
    
    public function __construct($metavalues, $metas)
    {
        $this->metavalues = $metavalues;
        $this->metas = $metas;
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
        $metavaluesTemp = $form->get('metaValuesTemp');
        $names = array();
        foreach ($this->metavalues as $metavalue) {
            $meta = $metavalue->getMeta();
            $names[] = $meta->getName();
            $metavaluesTemp->add($meta->getAlias(), $meta->getType(), array('label' => $meta->getName(), 'data' => $metavalue->getValue()));
        }
        $diff = array_diff(array_keys($this->metas), $names);
        foreach ($diff as $name) {
            $meta = $this->metas[$name];
            $metavaluesTemp->add($meta->getAlias(), $meta->getType(), array('label' => $meta->getName()));
        
        }
    }
    
    
}