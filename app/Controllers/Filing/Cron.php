<?php

namespace App\Controllers\Filing;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use CodeIgniter\Model;
use App\Models\Filing\CronModel;

class Cron extends BaseController
{
    public $session;
    public $CronModel;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->CronModel = new CronModel();
        date_default_timezone_set('Asia/Calcutta');
    }
    public function filing_stats_insertion()
    {
        $cron_model=new CronModel();
        $details=$cron_model->filing_stats();
        if($details)
                echo "Data inserted successfully";
        else
                echo "Data not inserted successfully";
    }

    public function filing_stats_mail_message(){
        $output='';

       // if($type=='html'){

       // }
        $message='';
        $files ='';
        $data = array();
        $builder1 = $this->db->table("filing_stats");
        $builder1->select("*");
        $builder1->orderBy('id desc');
        $query = $builder1->get(1);
        if ($query->getNumRows() >= 1) {
            $row = $query->getRowArray();
            $data['row'] = $row;
            $message = view('Common/email/filing_stats_template',$data);
           // $message="hello";
           // echo $message;
           // $data['message'] = $message;

            $to=array('ppavan.sc@nic.in','reg.computercell@sci.nic.in','office.regj1@sci.nic.in','reg.pavaneshd@sci.nic.in','reg.puneetsehgal@sci.nic.in');
           // $subject="test email final";
            $subject="Filing Section Statistical Information as on ".date('d-m-Y',strtotime($row['filing_date']))." at ".$row['updation_time'];
            $files = array();
            send_email($to,$subject,$message,$files);
            // return
        } else {
            return false;
        }
    }


}
