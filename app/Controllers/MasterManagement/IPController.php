<?php

namespace App\Controllers\MasterManagement;
use App\Controllers\BaseController;
use App\Models\MasterManagement\CaseBlockLooseDocModels;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\MasterManagement\IPModel;
use DateTime;
use CodeIgniter\I18n\Time;

 
class IPController extends BaseController
{

public $Ip_model;
    function __construct()
    {
        ini_set('memory_limit','51200M'); 
        $this->Ip_model = new IPModel();
        //error_reporting(0);
    }

   
    public function courtsip()
    {
       // echo $session."<br>";
       $session = session()->get('login')['usercode'];
        if($session!=null && $session !='') {
            $data['user'] = $session;
            $data['app_name'] = 'IP LIST';
            $data['ip_list'] = $this->Ip_model->data_list();
            return view('MasterManagement/ip_courts/ipaddress', $data);
        }
        else
        {
            echo "You are not logged in:";
        }
    }



    public function get_ip()
    {
        $courtno=$this->request->getPost('selectedValue');
        $data= $this->Ip_model->getip($courtno);
        $getdata=0;
        foreach ($data as $gdata) {
            $getdata=$gdata['xx'];
        }
        echo $getdata;

    }



    public function delete_ip(){

        $enterip= $this->request->getIPAddress();
        $user= $this->request->getPost('custId');
        $court_number = $this->request->getPost('court_number');
        $virtual_court_number = $this->request->getPost('virtual_court_number');
        $ip = $this->request->getPost('ip_address');
        $courttype='';
        if($court_number!='' && $court_number!=null)
        {
            $courttype=$court_number;
        }
        elseif ($virtual_court_number!='' && $virtual_court_number!=null)
        {
            $courttype=$virtual_court_number;
        }
        $data['delete_ip'] = $this->Ip_model->delete_ip($courttype, $enterip, $user);
        echo "IP ".$ip." of Court ".$courttype." has been deleted";
    }




    public function update_ip(){

        $enterip= $this->request->getIPAddress();
        $user=$this->request->getPost('custId');
        $court_number = $this->request->getPost('court_number');
        $virtual_court_number = $this->request->getPost('virtual_court_number');
        $ip = $this->request->getPost('ip_address');
        $courttype='';

        if($court_number!='' && $court_number!=null)
        {
            $courttype=$court_number;
        }
        elseif ($virtual_court_number!='' && $virtual_court_number!=null)
        {
            $courttype=$virtual_court_number;
        }
        $data['delete_ip'] = $this->Ip_model->delete_ip($courttype, $enterip, $user);
        $data['update_ip'] = $this->Ip_model->update_ip($courttype, $ip, $enterip, $user);
        echo "New IP for Court No. ".$courttype." is ".$ip;
    }



    public function save_ip()
    {
        $enterip= $this->request->getIPAddress();
        $user=$this->request->getPost('custId');
        $court_number = $this->request->getPost('court_number');
        $virtual_court_number = $this->request->getPost('virtual_court_number');
        $ip = $this->request->getPost('ip_address');
        $courttype='';

        if($court_number!='' && $court_number!=null)
        {
            $courttype=$court_number;
        }
        elseif ($virtual_court_number!='' && $virtual_court_number!=null)
        {
            $courttype=$virtual_court_number;
        }
        $data['save_ip'] = $this->Ip_model->insert_ip($courttype, $ip, $enterip, $user);
        echo "IP for Court No. ".$courttype." is ".$ip;

    }


    
}
