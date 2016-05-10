<?php

namespace CMS\Bundle\CoreBundle\Manager;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Gère les thèmes du site
 *
 * @package cms
 * @subpackage Core
 * @since 0.1
 */
class ThemeManager {


	public static function listThemes($dir_theme, $root_dir)
	{
		$themes = array();
		$finder = new Finder();

		// On récupère tous les dossiers du chemin donné dans config.yml
		$dirs = $finder->depth('<=0')->directories()->in($dir_theme);
		$fs = new Filesystem();
		// On parcourt tous les dossiers
		foreach($dirs as $dir) {
			$info_theme = array();
			$screenshot = array();
			$current_dir = $dir_theme.'/'.$dir->getRelativePathname();
			$finder = new Finder();
			$theme_files = $finder->files()->in($current_dir)->name('style.css');
			$info_theme['dir'] = $dir->getRelativePathname();
			foreach($theme_files as $theme_file) {
				$current_theme_info = $theme_file;
				break;
			}
			$finder = new Finder();
			$screenshots = $finder->files()->in($current_dir)->name('screenshot.*');

			foreach($screenshots as $s) {
				$screenshot['screenshot']['path'] = $s->getPathname();
				$screenshot['screenshot']['name'] = $s->getFilename();
				break;
			}

			if ($fs->exists($root_dir.'/../templates/'.$info_theme['dir'].'/style.css')) {
				$fs->mirror($root_dir.'/../templates/'.$info_theme['dir'], $root_dir.'/../web/templates/'.$info_theme['dir']);
			}

			if (isset($screenshot['screenshot']))
				$info_theme['screenshot'] = '/templates/'.$info_theme['dir'].'/'.$screenshot['screenshot']['name'];
			else
				$info_theme['screenshot'] = '';

			$info_theme = array_merge($info_theme, self::_getInfoTheme($theme_file->getContents()));
			$themes[] = $info_theme;

		}
		return $themes;
	}

	public function getAdminCss($dir_theme)
	{
		$finder = new Finder();
		if (count($finder->files()->in($dir_theme)->name('admin.css')))
			return $dir_theme.'/admin.css';
		return false;
	}

	private static function _getInfoTheme($content)
	{
		$info_theme = array();
	    preg_match_all('/\*[[:space:]]*([^(\*$)]*)/i', $content, $matches);
        foreach($matches[1] as $match) {
            $values = preg_split('/\:/i', $match, 2);
            if(count($values) > 1)
            	$info_theme[trim($values[0])] = trim($values[1]);
        }
        return $info_theme;
	}
}