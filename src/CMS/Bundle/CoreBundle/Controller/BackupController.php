<?php
/**
 * User: DCA
 * Date: 07/10/2016
 * Time: 14:02
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Controller;

use CMS\Bundle\CoreBundle\Classes\Backup;
use CMS\Bundle\CoreBundle\Form\BackupType;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class BackupController extends Controller
{
    /**
     * @Route("/admin/backup", name="admin_backup_index")
     * @Method({"POST", "GET"})
     */
    public function backupDB(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $schema = $em->getConnection()->getSchemaManager();
        $tables_temp = $schema->listTables();
        $tables = array('all' => 'all');
        foreach ($tables_temp as $table) {
            $tables[$table->getName()] = $table->getName();
        }
        
        $backup = new Backup();
        $form = $this->createForm(BackupType::class, $backup, array('tables' => $tables));
        
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                $kernel = $this->get('kernel');
                $application = new Application($kernel);
                $application->setAutoExit(false);
    
                $array_input = array('command' => 'core:dump');
                if (in_array('all', $backup->getTables())) {
                    $array_input = array_merge($array_input, array('--all' => true));
                } else {
                    $array_input = array_merge($array_input, array('table_names' => implode(',', $backup->getTables())));
                }
                
                $input = new ArrayInput($array_input);
                // You can use NullOutput() if you don't need the output
                $output = new NullOutput();
                $application->run($input, $output);
                
                $filename = 'dump-'.date("Y-m-d").'.gz';
                $filePath = $this->get('kernel')->getRootDir().'/../web/'.$filename;
    
                $response = new BinaryFileResponse($filePath);
                $response->trustXSendfileTypeHeader();
                $response->setContentDisposition(
                    ResponseHeaderBag::DISPOSITION_INLINE,
                    $filename,
                    iconv('UTF-8', 'ASCII//TRANSLIT', basename($filename))
                );
    
                return $response;
            }
        }
        
        return $this->render('CoreBundle:Backup:index.html.twig', array('tables' => $tables, 'form' => $form->createView()));
    }
}