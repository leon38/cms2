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
            $content->fieldValuesHtml[$fieldvalue->getField()->getName()] = $this->templating->render('ContentBundle:Fields:'.$fieldvalue->getField()->getField()->getTypeField().'.html.twig', array("value" => $fieldvalue->getValue()));
        }

        //dump($content->fieldValuesHtml); die;

        
    }
}