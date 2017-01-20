<?php
namespace CMS\Bundle\ContentBundle\Listener;

use CMS\Bundle\ContentBundle\Classes\TCXParser;
use CMS\Bundle\ContentBundle\Entity\Fields\GalleryField;
use CMS\Bundle\ContentBundle\Entity\Fields\KMLField;
use CMS\Bundle\ContentBundle\Entity\Fields\MusicField;
use CMS\Bundle\ContentBundle\Form\Type\GalleryType;
use Doctrine\ORM\Event\LifecycleEventArgs;
use CMS\Bundle\ContentBundle\Entity\Content;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $content = $args->getEntity();
        $fieldvalues = $content->getFieldValues();

        foreach($fieldvalues as $fieldvalue) {
            $type = $fieldvalue->getField()->getField()->getType();
            if (($fieldvalue->getField()->getField()->getTypeField() != TextType::class && $fieldvalue->getField()->getField()->getTypeField() != TextareaType::class && $fieldvalue->getField()->getField()->getTypeField() != DateType::class) || $type == 'music') {
                $typeField = $fieldvalue->getField()->getField()->getTypeField();
                $value = $fieldvalue->getValue();
                if ($type == 'music') {
                    $params = $fieldvalue->getField()->getField()->getParams();
                    $type = isset($params['api']) ? $params['api'] : 'deezer';
                }

                if ($typeField == GalleryType::class) {
                    $value = $this->container->get('cms.content.form.data_tranformer.gallery')->transform($value);
                }

                if (($typeField == FileType::class || $typeField == KMLField::class) && ($value != '' && preg_match("/(.*).tcx/", $value))) {
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
    }
}