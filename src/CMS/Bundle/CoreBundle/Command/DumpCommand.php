<?php

namespace CMS\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Console\Question\ChoiceQuestion;

class DumpCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('core:dump')
            ->setDescription('Sauvegarde la base de données')
            ->setDefinition(array(
                    new InputOption('all', '', InputOption::VALUE_NONE, 'Faire une sauvegarde complete')
            ))
            ->addArgument('table_names', InputArgument::OPTIONAL, 'Nom des tables a sauvegarder separes par une ","');
    }
    
    
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table_names = '';
        $db_server = $this->getContainer()->getParameter('database_host');
        $db_charset = "utf8";
        $db_name = $this->getContainer()->getParameter('database_name');
        $db_username = $this->getContainer()->getParameter('database_user');
        $db_password = $this->getContainer()->getParameter('database_password');
    
        $io = new SymfonyStyle($input, $output);
        $output->writeln(
            [
                'Dump Creator',
                '============',
                ''
            ]
        );
    
    
        $datation = date("Y-m-d");
        $archive_GZIP = "dump-".$datation.".gz";
    
        $output->writeln(
            " Sauvegarde de la base <info>$db_name</info> par <info>mysqldump</info> dans le fichier <info>$archive_GZIP</info>"
        );
        
        if (!$input->getOption('all')) {
            $table_names = $input->getArgument('table_names');
            if ($table_names == '') {
                $em = $this->getContainer()->get('doctrine')->getManager();
                $schema = $em->getConnection()->getSchemaManager();
                $tables = $schema->listTables();
                $table_res = array();
                $table_choice = array();
                for ($i = 0; $i < count($tables); $i = $i + 3) {
                    $j = $i + 1;
                    $k = $i + 2;
                    if (isset($tables[$i])) {
                        $table_choice[] = $tables[$i]->getName();
                    }
                    if (isset($tables[$j])) {
                        $table_choice[] = $tables[$j]->getName();
                    }
                    if (isset($tables[$k])) {
                        $table_choice[] = $tables[$k]->getName();
                    }
                    $table_res[] = array(
                        isset($tables[$i]) ? $tables[$i]->getName() : '',
                        isset($tables[$j]) ? $tables[$j]->getName() : '',
                        isset($tables[$k]) ? $tables[$k]->getName() : ''
                    );
                }
                $io->table(array(), $table_res);
                
                $name = '';
                $confirm_first = $io->confirm('Sauvegarde complete ?', true);
                if (!$confirm_first) {
                    $table_names = array();
                    do {
                        $name = $io->ask(
                            'Quelle table voules-vous sauvegarder ',
                            null,
                            function ($value) use ($table_choice) {
                                if (!in_array($value, $table_choice)) {
                                    throw new \RuntimeException('La table n\'existe pas');
                                }
                        
                                return $value;
                            }
                        );
                        $table_names[] = $name;
                        $confirm = $io->confirm('Ajouter une table ?', true);
                    } while ($confirm);
                }
            } else {
                $table_names = explode(',', trim($table_names));
            }
    
    
            if (count($table_names) > 0) {
                $table_names = implode(' ', $table_names);
            }
        }
        
        $commande = "mysqldump --host=$db_server --user=$db_username --password=$db_password -C -Q -e --default-character-set=$db_charset  $db_name  $table_names  | gzip -c > $archive_GZIP ";
        system($commande);
        $output->writeln("Fichier $archive_GZIP cree");
    }
    

}
