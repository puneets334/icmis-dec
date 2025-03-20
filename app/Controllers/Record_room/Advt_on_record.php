<?php

namespace App\Controllers\Record_room;

use App\Controllers\BaseController;

use App\Models\Record_room\Model_record;
use App\Models\Record_room\TransactionModel;
use App\Models\Entities\Model_Ac;
use App\Models\Record_room\Bar;
use App\Models\Record_room\Advocate;



class Advt_on_record extends BaseController
{
    public $Model_Ac;

    public $model;

    function __construct()
    {
        $this->model = new Model_record();
        ini_set('memory_limit', '51200M');

        $this->model = new TransactionModel();
        ini_set('memory_limit', '51200M');

        $this->Model_Ac = new Model_Ac();
    }


    public function pending_matters_with_order_and_judgement()
    {

        $model = new Bar();
        $data['aor_list'] = $model->getAOR();
        return view('Record_room/advt_on_record/pending_matters_with_order_and_judgement', $data);
    }

    public function getRecord()
    {
        $aor = $this->request->getPost('aor');
        $model = new Bar();
        $modelA = new Advocate;
        $dd = $model->GetAorCode($aor);
        $bar_id = $dd[0]['bid'];
        $getAdvtDetails = $modelA->getAdvtDetails($bar_id);

        return view('Record_room/advt_on_record/getRecord', ['getAdvtDetails' => $getAdvtDetails]);
    }


    public function sendMail()
    {
       
        $aor = $this->request->getPost('aor_code');
        $modelA = new Bar();  
        $getAdvtDetails = $modelA->GetAorData($aor);
    
        $emailAddress = $getAdvtDetails[0]['email'];
        $mobile = $getAdvtDetails[0]['mobile']; 
        $aorName = $getAdvtDetails[0]['name'];
    
    
        $email = \Config\Services::email();
        $email->setFrom('sci@nic.in', 'sci@nic.in');
        $email->setTo('ppavan.sc@nic.in');  
    
        
        $subject = "List of Matters of {$aor}-{$aorName} in Supreme Court of India including ROP and Judgment";
        $email->setSubject($subject);
    
        $email->setMailType('html');
        $htmlContent = '
            <p>Sir/Madam,</p>
            <p>Please find the file attached herewith the list of matters in Supreme Court of India.</p>
            <p>Regards,</p>
            <p>Record Room,</p>
            <p>Supreme Court of India.</p>
        ';
        $email->setMessage($htmlContent);
        $email->setCC('ppavan.sc@nic.in,itcell@sci.nic.in,ca.vandanabangari@sci.nic.in,ca.pnbartwal@sci.nic.in');
        if ($email->send()) {
            echo 'Email sent successfully!';
        } else {
            // $data = $email->printDebugger(['headers', 'subject', 'message']);
            // echo 'Failed to send email: <pre>' . print_r($data, true) . '</pre>';
            echo 'Please provide SMTP credentials..!';
        }
    }
    







}
