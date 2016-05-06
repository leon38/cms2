<?php
namespace CMS\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class AssetsCommand extends ContainerAwareCommand
{

	protected function configure()
	{
		$this
			->setName('cms:assets:install')
			->setDescription("Update templates assets")
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('Installation of the assets...');
		$output->writeln(' ');
		$em = $this->getContainer()->get('doctrine')->getManager();
		$root_dir  = $this->getContainer()->get('kernel')->getRootDir();
		$dir_theme = $this->getContainer()->getParameter('theme_dir');

		$themes = array();
		$finder = new Finder();
		
		// On récupère tous les dossiers du chemin donné dans config.yml
		$dirs = $finder->directories()->in($dir_theme);
		$fs = new Filesystem();

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
			$infos = self::_getInfoTheme($current_theme_info->getContents());
			$output->writeln('Theme : '.$infos['Theme name']);
			$finder = new Finder();
			$screenshots = $finder->files()->in($current_dir)->name('screenshot.*');

			foreach($screenshots as $s) {

				$screenshot['screenshot']['path'] = $s->getPathname();
				$screenshot['screenshot']['name'] = $s->getFilename();
				
				break;
			}

			if (!$fs->exists($root_dir.'/templates/'.$info_theme['dir'])) {
				$fs->mkdir($root_dir.'/templates/'.$info_theme['dir']);
			}
			$output->write('Screenshot : '.$screenshot['screenshot']['name'].'...');
			$fs->copy($screenshot['screenshot']['path'], $root_dir.'/../templates/'.$info_theme['dir'].'/'.$screenshot['screenshot']['name']);
			$output->writeln(' OK');
			$output->write('Style : style.css...');
			$fs->copy($root_dir.'/../templates/'.$info_theme['dir'].'/style.css', $root_dir.'/../web/css/'.$info_theme['dir'].'/style.css');
			$output->writeln(' OK');
		}
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