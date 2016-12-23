<?php
/**
 * User: DCA
 * Date: 23/12/2016
 * Time: 09:13
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Event;


use CMS\Bundle\ContentBundle\Entity\Category;
use Symfony\Component\EventDispatcher\Event;

class CategorySavedEvent extends Event
{
    const NAME = 'category.saved';

    /**
     * @var \CMS\Bundle\ContentBundle\Entity\Category
     */
    protected $category;

    /**
     * @var array settings for twitter api exchange
     */
    protected $settings;

    /**
     * @var mixed status of the event
     */
    protected $status;

    public function __construct(Category $category, $settings)
    {
        $this->category = $category;
        $this->settings = $settings;
    }

    public function getCategory()
    {
        return $this->category;
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