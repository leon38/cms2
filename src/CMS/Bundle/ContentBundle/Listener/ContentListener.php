<?php
namespace CMS\Bundle\ContentBundle\Listener;

use CMS\Bundle\ContentBundle\Classes\EXIFParser;
use CMS\Bundle\ContentBundle\Classes\TCXParser;
use Doctrine\ORM\Event\LifecycleEventArgs;
use CMS\Bundle\ContentBundle\Entity\Content;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;


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
                $value = $fieldvalue->getValue();
                if ($type == 'music') {
                    $params = $fieldvalue->getField()->getField()->getParams();
                    $type = isset($params['api']) ? $params['api'] : 'deezer';
                }
                
                if ($type == 'gallery') {
                    $value = $this->container->get('cms.content.form.data_tranformer.gallery')->transform($value);
                }
                
                if (($type == 'file' || $type == 'kml') && ($value != '' && preg_match("/(.*).tcx/", $value))) {
                    $parse = new TCXParser($value);
                    $value = new File($value);
                    $fieldvalue->setValue($value);
                    $value = array();
                    $value['labels'] = $parse->getTimes(5);
                    $value['data'] = $parse->getCoordinates();
                    $value['altitudes'] = $parse->getAltitudes(5);
                }
                $defaultLanguage = $this->container->get('doctrine')->getRepository('CoreBundle:Language')->find(1);
                $template = 'ContentBundle:Fields:'.$type.'.html.twig';
                if ($this->templating->exists($template)) {
                    $content->fieldValuesHtml[$fieldvalue->getField()->getName()] = $this->templating->render($template, array("value" => $value, "title" => $fieldvalue->getField()->getTitle(), 'defaultLanguage' => $defaultLanguage));
                }
            } else {
                $content->fieldValuesHtml[$fieldvalue->getField()->getName()] = $fieldvalue->getValue();
            }
        }

        //dump($content->fieldValuesHtml); die;

        
    }
}