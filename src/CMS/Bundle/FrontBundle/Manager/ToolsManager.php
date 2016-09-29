<?php
namespace CMS\Bundle\FrontBundle\Manager;
/**
 * User: DCA
 * Date: 29/09/2016
 * Time: 09:45
 * cms2
 */
class ToolsManager
{
    public function getTinyUrl($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}