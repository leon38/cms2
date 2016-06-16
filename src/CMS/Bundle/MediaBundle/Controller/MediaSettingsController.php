<?php
/**
 * Created by PhpStorm.
 * User: DCA
 * Date: 15/06/2016
 * Time: 09:19
 */

namespace CMS\Bundle\MediaBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CMS\Bundle\MediaBundle\Form\MediaSettingsType;

/**
 * Class SettingsMediaController
 * @package CMS\Bundle\MediaBundle\Controller
 *
 * @Route("/admin/media/settings")
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
    $options = $option_manager->getAllOptions();
    if(empty($options)) {
      $option_manager->add('size_1', '', 2);
      $options = $option_manager->getAllOptions();
    }
    $form = $this->createForm(new MediaSettingsType(), $options);
    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {

      }
    }

    return $this->render('MediaBundle:Settings:index.html.twig', array(
      'options' => $options,
      'form'    => $form->createView(),
    ));
  }
}