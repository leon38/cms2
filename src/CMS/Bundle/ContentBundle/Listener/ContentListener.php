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
            $template = 'ContentBundle:Fields:'.$fieldvalue->getField()->getField()->getTypeField().'.html.twig';
            if ($this->templating->exists($template)) {
                //$value = @unserialize($fieldvalue->getValue());
                $content->fieldValuesHtml[$fieldvalue->getField()->getName()] = $this->templating->render($template, array("value" => $fieldvalue->getValue(), "title" => $fieldvalue->getField()->getTitle()));
            }
        }

        //dump($content->fieldValuesHtml); die;

        
    }
}