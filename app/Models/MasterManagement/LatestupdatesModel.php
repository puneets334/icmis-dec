<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class LatestupdatesModel extends Model
{

    protected $table = 'master.content_for_latestupdates';
    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    function get_menu_latestupdates()
    {
        $sql = "SELECT mno,menu_name FROM master.menu_for_latestupdates where display='Y' order by menu_name";
        $query = $this->db->query($sql);
        // echo $this->db->last_query();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); 
        } else {
            return false;
        }
    }



    function display_Latest_Updates()
    {
        $sql = "SELECT mn.menu_name, f_date, t_date, title_en, ent_dt, user,ip FROM master.content_for_latestupdates
                inner join master.menu_for_latestupdates mn ON content_id=mn.mno
                order by ent_dt desc";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); 
        } else {
            return false;
        }

    }
 

    function get_client_ip()
    {
        
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }


        function getClientMAC()
       {

        $this->db->table('master.content_for_latestupdates');
        $ipAddress = self::get_client_ip();
        $mac = 'UNKNOWN';

        if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
            ob_start();
            system('arp -an ' . escapeshellarg($ipAddress));
            $macfull = ob_get_contents();
            ob_end_clean();

            if (preg_match('/(?:[0-9a-fA-F]:?){12}/', $macfull, $matches)) {
                $mac = $matches[0];
            }
        }

        return $mac;
     
    }

        
    public function insertLatestupdates($menu_id, $from_date, $to_date, $desc, $usercode)
    {
       
        $dsc = $this->db->escapeString($desc);
        $client_ip = $this->get_client_ip();
        $mac = $this->getClientMAC();
       
        if ($usercode != '' && $menu_id != 0) {
            $sql = "INSERT INTO master.content_for_latestupdates (content_id, f_date, t_date, title_en, \"user\", ent_dt, ip, mac_address) 
                    VALUES ($menu_id, '$from_date', '$to_date', '$dsc', '$usercode', NOW(), '$client_ip', '$mac')";
        } elseif ($menu_id != 0) {
            $sql = "INSERT INTO master.content_for_latestupdates (content_id, f_date, t_date, title_en, ent_dt, ip, mac_address) 
                    VALUES ($menu_id, '$from_date', '$to_date', '$dsc', NOW(), '$client_ip', '$mac')";
        } else {
            return false; 
        }
    
        $this->db->query($sql);
        return $this->db->affectedRows() > 0;
    }
    
    

    

    

  }
  
