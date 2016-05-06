<?php
namespace CMS\Bundle\MenuBundle\Twig;

use Doctrine\ORM\EntityRepository;

class RenderMenuExtension extends \Twig_Extension
{
	private $_menuTaxRepository;

	public function __construct(EntityRepository $repository)
	{
		$this->_menuTaxRepository = $repository;
	}

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('render_menu', array($this, 'renderMenuFilter')),
		);
	}

	public function renderMenuFilter($slugMenu, $class, $id)
	{
		$menuTax = $this->_menuTaxRepository->findOneBy(array('slug' => $slugMenu));
		$entries = $menuTax->getEntries();
		$id = ($id != '') ? $id : $slugMenu;
		$class = ($class != '') ? 'menu '.$class : 'menu';
		$str = '<ul class="'.$class.'" id="'.$id.'">';
		$oldlvl = 1;
		$multilvl = false;
		foreach($entries as $entry) {
			if ( $entry->getLvl() >= 1) {
				if ($entry->getLvl() < $oldlvl) {
					$str .= '</ul>';
					$str .= '</li>';
					$oldlvl = $entry->getLvl();
					$multilvl = false;
				}
				if ($entry->getLvl() > $oldlvl) {
					$str .= '<ul class="menu-items-children">';
					$oldlvl = $entry->getLvl();
					$multilvl = true;
				}
				$str .= '<li class="menu-item lvl-'.$entry->getLvl().''.(($entry->hasChildren()) ? ' menu-parent' : '').'">';
				$str .= '<a href="'.$entry->getUrl()['url'].'"'.(($entry->getUrl()['external']) ? ' target="_blank"' : '').'>'.$entry->getTitle().'</a>';
				if (!$entry->hasChildren()) {
					$str .= '</li>';
				}
			}
		}
		$str .= ($multilvl) ? '</ul>' : '';
		$str .= '</ul>';
		return $str;
	}

	public function getName()
	{
		return 'render_menu';
	}
}