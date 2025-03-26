<?php

namespace App\Controllers\Record_room;

use App\Controllers\BaseController;

use App\Models\Record_room\Model_record;
use App\Models\Record_room\TransactionModel;
use App\Models\Entities\Model_Ac;
use App\Models\Record_room\Bar;
use App\Models\Record_room\Advocate;
use App\Models\Record_room\Aor_pendency;
use App\Models\Record_room\registration_new_aor;

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


    public function aor_wise_matter_pending_disposed()
    {
        $model = new Bar();
        $data['aor_record'] = $model->getAorDetails();
        $data['case_type'] = $model->GetAllCaseType();
        return view('Record_room/advt_on_record/aor_wise_pendency', $data);
    }

    public function aor_wise_pendency_process()
    {
        $tvap = $this->request->getPost('tvap');
        $wordChunks = explode(';', $tvap);
        $vform = array_map(function ($item) {
            return str_replace('undefined', '', $item);
        }, $wordChunks);

        if (!$vform[0] || !$vform[1] || !$vform[2] || !$vform[3]) {
            $html = 'Please Enter Mandatory * Values';
        }
        try {
            $aor_name = "AOR Name";
            $model = new Aor_pendency();
            $bar_id = $model->getAorName($vform[0]);

            if (!empty($bar_id)) {
                $records = $model->fetchdetails($bar_id);
                print_r($records);
                if (!empty($records)):

                    $html = '<div id="content"><h4 style="text-align:center;font-size: 16px;"><b> 
                List of Matters Filed by AOR-' . $aor_name . ' as on ' . date("d-m-Y h:i:s A") . '<br></b></h4><br>';

                    $html .= '<div>';
                    $html .= '<table id="tab"border="1">';
                    $html .= '<thead><tr><th bgcolor=silver>SNo</th><th bgcolor=silver>Case</th>
                              <th bgcolor=silver>Cause Title</th><th bgcolor=silver>Main/Connected</th>';
                    $html .= '<th bgcolor=silver>Status</th><th bgcolor=silver>Order/Judgment</th></thead></tr><tbody>';

                    $sr_no = 1;
                    foreach ($records as $index => $data) {
                        $html .= ' <tr><td align="left"> ' . $sr_no++ . '</td>
                                <td align="left">' . $data['No'] . ' </td>
                                <td align="left"> ' . $data['Causetitle'] . ' </td>
                                <td align="left">' . $data['Main_Connected'] . ' </td>

                                <td align="left">' . $data['status'] . '</td>
                                </tr>';
                    }
                    $html .= '</tbody></table>';
                    $html .=  '</div></div>';
                else:
                    $html = "No Records Found !!";
                endif;
            } else {
                $html = "No Data Found !!";
            }
        } catch (\Exception $e) {
            $html = $e->getMessage();
        }
        echo $html;
    }


    public function adv_add_bar()
    {
        $model = new registration_new_aor();
        $data['state_list'] = $model->getStateName();
        return view('Record_room/advt_on_record/adv_add_bar', $data);
    }



    public function registernew_aor()
    {
        $sessionData = session()->get();
        $ucode = $sessionData['login']['usercode'];
        $tvap = $this->request->getPost('tvap');
        $wordChunks = explode(';', $tvap);
        $vform = array_map(function ($item) {
            return str_replace('undefined', '', $item);
        }, $wordChunks);

        try {
            $model = new registration_new_aor();
            $code = $model->getnewaorcode();

            $y = explode('-', $vform[3]);
            $enroll_dt = !empty($vform[3]) ? date('Y-m-d', strtotime($vform[3])) : '';

            // $dob = explode('-', $vform[12]);
            $v_dob = !empty($vform[12]) ? date('Y-m-d', strtotime($vform[12])) : '';

            $check_aor_registration = $model->check_aor_registration($vform[2], $vform[0], $y[2]);
            if ($check_aor_registration > 0) {
                $html = "Record Already Present for given enrollment no and year";
            } else {
                $data = [
                    'state_id' => $vform[0],
                    'if_aor' => $vform[1],
                    'enroll_no' => $vform[2],
                    'enroll_date' => $enroll_dt,
                    'title' => $vform[4],
                    'name' => $vform[5],
                    'fname' => $vform[6],
                    'rel' => $vform[7] ? $vform[7] : 0,
                    'mname' => $vform[8],
                    'sex' => $vform[9],
                    'cast' => $vform[10],
                    'passing_year' => $vform[11],
                    'dob' => $v_dob,
                    'pp' => $vform[13],
                    'caddress' => $vform[14],
                    'ccity' => $vform[15],
                    'mobile' => $vform[16],
                    'email' => $vform[17],
                    'if_sen' => $vform[18],
                    'bentdt' => date("Y-m-d H:i:s"),
                    'aor_code' => $code,
                    'bentuser' => $ucode,
                ];
                // pr($data);
                $model->SET($data);
                $record = $model->INSERT();
                $lastInsertId = $model->insertID();
                if ($lastInsertId) {
                    if (empty($vform[16])) {
                        $html = "AOR registered successfully with AOR code [$code] \n  Mobile No Empty . Can't Send SMS to Advocate.";
                    } else {

                        $db = \Config\Database::connect();
                        $sms_data = [
                            'mobile' => $vform[16],
                            'msg' => $vform[4] . '.' . $vform[5] . ', You have been allotted AOR code: ' . $code . ' in Supreme Court Of India. - Supreme Court of India',
                            'c_status' => 'N',
                            'table_name' => 'AOR',
                            'ent_time' => date('Y-m-d H:i:s'),
                            'template_id' => '1107161234609750003'
                        ];
                        $builder = $db->table('sms_pool'); 
                        $builder->insert($sms_data);
                        $html = "AOR registered successfully with AOR code [$code] \n Msg Sent Successfully to Mobile Number. " . $vform[16];
                    }
                } else {
                    $html = "Failed to saved last records.";
                }
            }
        } catch (\Exception $e) {
            $html = $e->getMessage();
        }
        return $html;
    }
}
