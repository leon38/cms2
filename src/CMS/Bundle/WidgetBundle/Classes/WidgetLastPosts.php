<?php
namespace CMS\Bundle\WidgetBundle\Classes;

class WidgetLastPosts extends Widget implements IWidget
{

	public function renderWidget()
	{
		$params = $this->getParams();
		$last_posts = $this->_em->getRepository('ContentBundle:Content')->findBy(array(), array('created' => 'DESC'), 0, $params['limit']);
		echo '<ul>';
		foreach($last_posts as $post) {
			echo '<li><a href="'.$post->getUrl().'">'.$post->getTitle().'</a></li>';
		}
		echo '</ul>';
	}
}