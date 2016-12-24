<?php
/**
 * User: DCA
 * Date: 26/10/2016
 * Time: 08:52
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Event;

use CMS\Bundle\ContentBundle\Entity\Content;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ContentSavedEvent
 * @package CMS\Bundle\ContentBundle\Event
 *
 * The content.saved event is dispatched each time an order is created
 */
class ContentSavedEvent extends Event
{
    const NAME = 'content.saved';

    /**
     * @var \CMS\Bundle\ContentBundle\Entity\Content
     */
    protected $content;

    /**
     * @var array settings for twitter api exchange
     */
    protected $settings;

    /**
     * @var mixed status of the event
     */
    protected $status;

    public function __construct(Content $content, $settings)
    {
        $this->content = $content;
        $this->settings = $settings;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
}