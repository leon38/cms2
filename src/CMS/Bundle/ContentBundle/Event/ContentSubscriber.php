<?php
/**
 * User: DCA
 * Date: 27/10/2016
 * Time: 08:22
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Event;

use Abraham\TwitterOAuth\TwitterOAuth;
use CMS\Bundle\ContentBundle\Entity\Repository\MetaRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ContentSubscriber
 * @package CMS\Bundle\ContentBundle\Event
 */
class ContentSubscriber implements EventSubscriberInterface
{
    /**
     * @var MetaRepository $metaRepository
     */
    private $metaRepository;

    public function __construct(MetaRepository $metaRepository)
    {
        $this->metaRepository = $metaRepository;
    }

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
        $metas = $this->metaRepository->findByIndexed();
        foreach($metavaluesTemp as $key => $value) {
            if (isset($metas[$key]) && $value == '') {
                switch($metas[$key]->getDefaultValue()) {
                    case 'Title':
                        $value = $content->getTitle();
                        break;
                    case 'Chapo':
                        $value = $content->getChapo();
                        break;
                    case 'URL':
                        $value = $event->getSettings()['base_url'];
                        break;
                    case 'Thumbnail':
                        $value = $event->getSettings()['base_url'].'/'.$content->getThumbnail()->getWebPath();
                        break;
                }
            }
            $metavaluesTemp[$key] = $value;
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