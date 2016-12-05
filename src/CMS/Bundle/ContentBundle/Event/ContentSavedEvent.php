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
    
    protected $content;
    
    public function __construct(Content $content)
    {
        $this->content = $content;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    
}