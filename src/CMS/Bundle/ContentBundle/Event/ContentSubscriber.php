<?php
/**
 * User: DCA
 * Date: 27/10/2016
 * Time: 08:22
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Event;

use CMS\Bundle\ContentBundle\Classes\TwitterAPIExchange;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ContentSubscriber
 * @package CMS\Bundle\ContentBundle\Event
 */
class ContentSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            ContentSavedEvent::NAME => 'onContentSaved',
        );
    }

    /**
     * @param \CMS\Bundle\ContentBundle\Event\ContentSavedEvent $event
     *
     * Met à jour le temps de lecture
     */
    public function onContentSaved(ContentSavedEvent $event)
    {
        $content = $event->getContent();
        $desc = $content->getDescription();
        $nb_words = str_word_count($desc);

        $temps = ceil($nb_words / 250); // 250 mots en 1 minute
        $content->setTempsLecture($temps);

        $chapo = $content->getFieldValue('chapo');
        if (strlen($chapo) <= 140) {
            $apiTwitter = new TwitterAPIExchange($event->getSettings());
            $url = "https://api.twitter.com/1.1/statuses/update.json";
            $requestMethod = "POST";

            $apiTwitter->buildOauth($url, $requestMethod)
                ->setPostfields(array('status' => $chapo))
                ->performRequest();
        }

    }
}