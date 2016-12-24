<?php
/**
 * Created by PhpStorm.
 * User: DCA
 * Date: 15/06/2016
 * Time: 09:19
 */

namespace CMS\Bundle\MediaBundle\Controller;


use CMS\Bundle\CoreBundle\Entity\Option;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CMS\Bundle\MediaBundle\Form\MediaSettingsType;

/**
 * Class SettingsMediaController
 * @package CMS\Bundle\MediaBundle\Controller
 *
 * @Route("/settings")
 */
class MediaSettingsController extends Controller
{

  /**
   * @param Request $request
   * @Route("/", name="admin_media_settings_index")
   */
  public function indexAction(Request $request)
  {
    $option_manager = $this->get('cms.media.media_option_manager');
    $options = $option_manager->getAllOptionsForm();

    $form = $this->createForm(MediaSettingsType::class, $options);
    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        $data = $form->getData();

        foreach ($data['settings'] as $setting) {
          $option_manager->add($setting['name'], json_encode(array('width' => $setting['width'], 'height' => $setting['height'])), 2);
        }
      }
    }

    return $this->render('MediaBundle:Settings:index.html.twig', array(
      'options' => $options,
      'form'    => $form->createView(),
    ));
  }
}