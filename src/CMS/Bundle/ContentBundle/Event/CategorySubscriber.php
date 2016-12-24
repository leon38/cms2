<?php
/**
 * User: DCA
 * Date: 23/12/2016
 * Time: 09:19
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Event;


use CMS\Bundle\ContentBundle\Entity\MetaValue;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Asset\Packages;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class CategorySubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var Router $router
     */
    protected $router;

    /**
     * @var $assetExtension
     */
    protected $assetExtension;

    public function __construct(EntityManager $em, Router $router, Packages $assetExtension)
    {
        $this->em = $em;
        $this->router = $router;
        $this->assetExtension = $assetExtension;
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
        return array(
            CategorySavedEvent::NAME => 'onCategorySaved',
        );
    }

    /**
     * Met à jour le temps de lecture
     * @param \CMS\Bundle\ContentBundle\Event\CategorySavedEvent $event
     */
    public function onCategorySaved(CategorySavedEvent $event)
    {
        $category = $event->getCategory();
        $settings = $event->getSettings();
        // Remplissage des métas automatiquement
        $metavaluesTemp = $category->getMetaValuesTemp();

        if (!empty($metavaluesTemp)) {
            foreach ($metavaluesTemp as $metaname => $metavalue) {
                $meta = $this->em->getRepository('ContentBundle:Meta')->findOneBy(array('alias' => $metaname));
                if ($metavalue == "") {
                    $default_value = $meta->getDefaultValue();
                    switch($default_value) {
                        case 'Title':
                            $metavalue = $category->getTitle();
                            break;
                        case 'Chapo':
                            $metavalue = $category->getDescription();
                            break;
                        case 'URL':
                            $metavalue = $this->router->generate('front_single', array('alias' => $category->getUrl()), true);
                            break;
                        case 'Thumbnail':
                            if ($category->getBanner() !== null) {
                                $metavalue = $this->assetExtension->getUrl($category->getWebPath());
                            }
                            break;
                    }
                }
                $metavalueObj = $this->em->getRepository('ContentBundle:MetaValue')->findOneBy(
                    array('category' => $category, 'meta' => $meta)
                );
                if ($metavalueObj !== null) {
                    $metavalueObj->setValue($metavalue);
                } else {
                    $metavalueObj = new MetaValue();
                    $metavalueObj->setCategory($category);
                    $metavalueObj->setMeta($meta);
                    $metavalueObj->setValue($metavalue);
                    $category->addMetaValue($metavalueObj);
                }

                $this->em->persist($metavalueObj);
                $this->em->flush();
            }
        }
    }
}