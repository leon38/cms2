<?php
namespace CMS\Bundle\WidgetBundle\Classes;

use CMS\Bundle\CoreBundle\Entity\Repository\OptionRepository;

class Widget implements IWidget
{

	protected $_name_option;
	protected $_sidebar;
	protected $_name;
	protected $_widget_options;
	protected $_params;

	protected $_repo;
	protected $_em;

	public function __construct(OptionRepository $repo, EntityRepository $em, $name_option, $name, $widget_options)
	{
		$this->_name_option = $name_option;
		$this->_name = $name;
		$this->_widget_options = $widget_options;
		$this->_repo = $repo;
		$this->_em = $em;
	}

	public function renderWidget()
	{
		die('function WP_Widget::widget() must be over-ridden in a sub-class.');
	}

	public function update( $params )
	{
		return $params;
	}

	public function renderForm()
	{
		echo '<p class="no-options-widget">' . __('There are no options for this widget.') . '</p>';
		return 'noform';
	}

	public function getParams()
	{
		return $this->_repo->get($this->_name_option);
	}

}