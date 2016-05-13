<?php
namespace CMS\Bundle\CoreBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * Language controller.
 *
 * @Route("/admin/translations")
 */
class TranslationController extends Controller
{
    /**
     * @Route("/", name="admin_translation_index")
     * @Template()
     */
    public function indexAction()
    {
    	$_locale = $this->get('request')->getLocale();
    	$translator = new Translator($_locale);
        $translator->addLoader('yml', new YamlFileLoader());
		$translator->addResource('yml', $root_dir.'/../src/Locabri/Bundle/CoreBundle/Resources/translations/messages.'.$_locale.'.yml', $_locale);
		$message = new MessageCatalogue
    }
}