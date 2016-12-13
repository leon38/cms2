<?php
/**
 * User: DCA
 * Date: 27/10/2016
 * Time: 08:22
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Event;

use Abraham\TwitterOAuth\TwitterOAuth;
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
     * Met à jour le temps de lecture
     * @param \CMS\Bundle\ContentBundle\Event\ContentSavedEvent $event
     */
    public function onContentSaved(ContentSavedEvent $event)
    {
        $content = $event->getContent();
        $desc = $content->getDescription();
        $nb_words = str_word_count($desc);

        $temps = ceil($nb_words / 250); // 250 mots en 1 minute
        $content->setTempsLecture($temps);

        $settings = $event->getSettings();

        // Remplissage des métas automatiquement
        $metavaluesTemp = $content->getMetaValuesTemp();
        foreach($metavaluesTemp as $key => $value) {
            switch ($key) {
                case 'meta-description':
                    if ($value == null) {
                        $metavaluesTemp[$key] = $content->getChapo();
                    }
                    break;
                case 'meta-title':
                    if ($value == null) {
                        $metavaluesTemp[$key] = $content->getTitle();
                    }
                    break;
                case 'canonical':
                    $metavaluesTemp[$key] = $settings['base_url'].'/'.$content->getUrl().'.html';
                    break;
            }
        }

        $content->setMetaValuesTemp($metavaluesTemp);

        $chapo = $content->getChapo();
        /*if (strlen($chapo) <= 140 && $content->getPublished()) {

            $connection = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $settings['oauth_access_token'], $settings['oauth_access_token_secret']);

            $status = $connection->post("statuses/update", ["status" => $chapo]);
            $event->setStatus($status);
        } else {
            $event->setStatus(array("status" => false));
        }*/
    }
}