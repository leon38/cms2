<?php
namespace CMS\Bundle\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use CMS\Bundle\CoreBundle\Form\GeneralOptionsType;
use CMS\Bundle\CoreBundle\Form\DataTransformer\EntityToIdTransformer;


/**
 * @Route("/admin/options/general")
 */
class GeneralOptionsController extends Controller
{
    /**
     * @Route("/", name="admin_options_general")
     */
    public function indexAction(Request $request)
    {
    	$general_options = $this->get('cms.core.class.general_option');
    	$options = $general_options->getOptions();
    	$form = $this->createGeneralOptionsForm($general_options, $options);

    	if ($request->isMethod('POST')) {
    		$form->handleRequest($request);
    		if($form->isValid()) {
    			$em = $this->getDoctrine()->getManager();
    			$options = $general_options->getOptions();
    			foreach($options as $option) {
                    if ($option->getType() == 'image') {
                        $option->setOptionValue($option->getOptionValue());
                    }
                    $em->persist($option);
    			}
    			$em->flush();
    			
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'cms.option.general_options.success'
                );
    		}
    	}

    	return $this->render('CoreBundle:GeneralOptions:index.html.twig', array('form' => $form->createView()));
    }

    private function createGeneralOptionsForm($general_options, $options)
    {
    	$form = $this->createForm(GeneralOptionsType::class, $general_options, array(
    		'general_options' => $options,
            'action' => $this->generateUrl('admin_options_general'),
            'method' => 'POST',
        ));

        $form ->add('submit', SubmitType::class, array('label' => 'save', 'attr' => array('class' => 'btn btn-fill btn-info pull-right')));

        return $form;
    }
}