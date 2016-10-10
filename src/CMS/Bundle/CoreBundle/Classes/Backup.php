<?php
/**
 * User: DCA
 * Date: 07/10/2016
 * Time: 14:28
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes;


class Backup
{
    /**
     * @var array $tables Tables de la base de données
     */
    private $tables;
    
    public function __construct()
    {
        $this->tables = array();
    }
    
    public function getTables()
    {
        return $this->tables;
    }
    
    
    public function setTables($tables)
    {
        $this->tables = $tables;
        return $this;
    }
    
}