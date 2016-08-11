<?php
namespace CMS\Bundle\ContentBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use CMS\Bundle\ContentBundle\Entity\Content;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ContentListener
{

	private $container;
    private $templating;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->templating = $this->container->get('templating');
    }



    public function postLoad(Content $content, LifecycleEventArgs $args)
    {
        $fieldvalues = $content->getFieldValues();

        foreach($fieldvalues as $fieldvalue) {
            if ($fieldvalue->getField()->getField()->getTypeField() != 'text' && $fieldvalue->getField()->getField()->getTypeField() != 'Date') {
                $type = $fieldvalue->getField()->getField()->getTypeField();
                if ($type == 'music') {
                    $params = $fieldvalue->getField()->getField()->getParams();
                    $type = isset($params['api']) ? $params['api'] : 'deezer';
                }
                $template = 'ContentBundle:Fields:'.$type.'.html.twig';
                if ($this->templating->exists($template)) {
                    //$value = @unserialize($fieldvalue->getValue());
                    $content->fieldValuesHtml[$fieldvalue->getField()->getName()] = $this->templating->render($template, array("value" => $fieldvalue->getValue(), "title" => $fieldvalue->getField()->getTitle()));
                }
            } else {
                $content->fieldValuesHtml[$fieldvalue->getField()->getName()] = $fieldvalue->getValue();
            }
        }

        //dump($content->fieldValuesHtml); die;

        
    }
}