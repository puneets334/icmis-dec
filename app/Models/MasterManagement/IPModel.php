<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class IPModel extends Model
{


    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }


    function data_list()
    {
        $sql="select court_no, court_ip.ip_address, users.name as entered_by, entered_on, entered_ip 
                from master.court_ip 
                left join master.users
                on court_ip.entered_by=users.usercode
                where court_ip.display='Y'
                order by court_no";

        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); 
        }else {
            return false;
        }
    }


    function getip($courtno)
    {
        $sql="SELECT string_agg(ip_address, '---') AS xx FROM master.court_ip WHERE court_no = $courtno AND display = 'Y';";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return $query->getResultArray(); 
        }else {
            return false;
        }
    }


    function delete_ip($courttype,$enterip,$user)
    {
        if ($courttype != '' && $enterip!='' && $user!='')
//            $sql = "UPDATE court_ip SET display = 'N', deleted_by='$user',deleted_on=now(),deleted_ip='$enterip' WHERE court_no='$courttype'";
        $sql = "UPDATE master.court_ip SET display = 'N', deleted_by=(select usercode from master.users where empid='$user'),deleted_on=now(),deleted_ip='$enterip' WHERE court_no='$courttype'";

        $this->db->query($sql);
        $affectedRows = $this->db->affectedRows();  

        if ($affectedRows >= 1) {
            return true;
        } else {
            return false;
        }
    }



    function update_ip($courttype,$ip_address,$enterip,$user)
    {
        if ($courttype != '' && $ip_address!='' && $enterip!='' && $user!='')
        // $sql = "insert into court_ip (court_no,ip_address,display,entered_by,entered_on,entered_ip) VALUES ('$courttype','$ip_address','Y', '$user', now(),'$enterip')";
        $sql = "insert into master.court_ip (court_no,ip_address,display,entered_by,entered_on,entered_ip) 
        SELECT  '$courttype','$ip_address','Y', '$user', now(),'$enterip'
        FROM    master.users
        WHERE   users.empid= $user";

        $this->db->query($sql);
        $affectedRows = $this->db->affectedRows();  
        if ($affectedRows >= 1) {
            return true;
        } else {
            return false;
        }
    }


    function insert_ip($courttype,$ip_address,$enterip, $user)
    {
        if ($courttype != '' && $ip_address!='' && $enterip!='' && $user!='')
        //   $sql = "insert into court_ip (court_no,ip_address,display,entered_by,entered_on,entered_ip) VALUES ('$courttype','$ip_address','Y', '$user', now(),'$enterip')";
            $sql="INSERT INTO master.court_ip (court_no,ip_address,display,entered_by,entered_on,entered_ip)
            SELECT  '$courttype', '$ip_address','Y', users.usercode, now(),'$enterip'
            FROM    master.users
            WHERE   users.empid= $user";
        $this->db->query($sql);
        $affectedRows = $this->db->affectedRows();  

        if ($affectedRows >= 1) {
            return true;
        } else {
            return false;
        }
    }
      
      

 
        

  }
  
