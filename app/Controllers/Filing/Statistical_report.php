<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Models\Entities\Model_filing_stats;
use App\Models\Filing\Model_statistical_report;

class Statistical_report extends BaseController
{
    public $Model_filing_stats;
    public $Model_statistical_report;
    function __construct()
    {
        $this->Model_filing_stats = new Model_filing_stats();
        $this->Model_statistical_report = new Model_statistical_report();
        ini_set('memory_limit','1024M');
        set_time_limit(25000);
    }
    public function data_generation()
    {
        $data['data_generation'] = '';
        // if ($this->request->getMethod() === 'post') {
        //     $result = $this->Model_statistical_report->data_generation();
        //     $data['data_generation'] = $result;
        // }
        return  view('Filing/statistical_report_view',$data);
    }

    public function statistical_report_view()
    {
        $data['data_generation'] = '';
        
            $result = $this->Model_statistical_report->data_generation();
           return $result;
        
        
    }

    public function send_mail()
    {
        $query=$this->Model_filing_stats->select('*')->orderBy('id', 'DESC')->get(1);
        
        if($query->getNumRows() >= 1) {
            $result=$query->getResultArray();
            $output='';
            if (!empty($result)) {

                $from_email_id = 'sci@nic.in';
                $to_email_ids = 'ppavan.sc@nic.in,reg.computercell@sci.nic.in,office.regj1@sci.nic.in,reg.hsshetty@sci.nic.in,reg.puneetsehgal@sci.nic.in';
                //$to_email_ids = 'anshukumargupta92@gmail.com';
                $data['from_email_id']=$from_email_id;
                $data['to_email_ids']=$to_email_ids;
                $data['result']=$result;
                $output= view('Filing/statistical_report_send_mail',$data);

                $subject='Filing Section Statistical Information as on '.date('d-m-Y',strtotime($result[0]['filing_date'])).' at '.$result[0]['updation_time'];

                if (!empty($output)) {
                    $semi_rand = md5(time());
                    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                    // To send HTML mail, the Content-type header must be set
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= "From: sci@nic.in" . "\r\n";
                    $message = $output;
                    if (mail($to_email_ids, $subject, $message, $headers)) {
                        $msg =  '<span style="color:green;font-size: 18px;">Email has been sent successfully.</span>'; 
                    } else {
                        $msg =  '<span style="color:red;font-size: 18px;">Email sending failed.</span>'; 
                    }
                }else{
                    $msg =  '<span style="color:red;font-size: 18px;">Email sending failed.</span>'; 
                }

            }

        }else{
            $msg =  '<span style="color:red;font-size: 18px;">Data not found</span>';
        }

        $data['data_generation'] = $msg;       
        return  view('Filing/send_email_report_view',$data);
    }

}
