<?php

namespace App\Controllers\BulkDismissal;

use App\Controllers\BaseController;
use App\Models\BulkDismissal\MonitoringTeam_Model;
use App\Models\Common\Dropdown_list_model;

class MonitoringTeam extends BaseController
{
    public $Dropdown_list_model;
    public $MonitoringTeam_Model;

    function __construct()
    {
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->MonitoringTeam_Model = new MonitoringTeam_Model();
    }

    public function index()
    {
        $data['current_page_url'] = base_url('BulkDismissal/MonitoringTeam');
        $data['judges'] = $this->MonitoringTeam_Model->getJudges();
        $data['disposals'] = $this->MonitoringTeam_Model->getDisposals();

        return view('BulkDismissal/index', $data);
    }

    public function bulk_dispose()
    {
        $diary_no = $this->request->getVar('diaryno');
        // $bar_id = $this->request->getVar('d2');
        $ucode = session()->get('login')['usercode'];
        $jcode = $this->request->getVar('jcode');
        $dismissal_date = $this->request->getVar('dismissal_date');
        $disp_type = $this->request->getVar('disp_type');
        $rj_date = $this->request->getVar('rj_date');
        $h_date = $this->request->getVar('h_date');
        
        $rs_check_already_disposed = $this->MonitoringTeam_Model->checkAlreadyDisposed($diary_no);
        
        foreach ($rs_check_already_disposed as $rw_already_disposed) {
            if ($rw_already_disposed['ttl'] > 0) {
                echo "Diary Numbers - " . $rw_already_disposed['existing'] . " are already disposed off. Please remove the numbers from list and retry.  ";
                exit();
            }
        }

        $rs_next_dt = $this->MonitoringTeam_Model->checkFutureDates($diary_no);
        foreach ($rs_next_dt as $rw_next_dt) {
            if ($rw_next_dt['future_date_count'] > 0) {
                echo "Diary Numbers - " . $rw_next_dt['future_date'] . " are listed in future dates. Please remove the numbers from list and retry.  ";
                exit();
            }
        }
        $diary_no_list = explode(',', $diary_no);
        $peremptory_diary = '';
        foreach ($diary_no_list as $dno) {

            $result_remarks = $this->MonitoringTeam_Model->getCaseRemarksMultiple($dno);
            if ($result_remarks) {
                $cl_date = new \DateTime($result_remarks['cl_date']);
                $current_date = new \DateTime();

                $date_diff = $cl_date->diff($current_date)->days;

                if ($result_remarks['r_head'] == 133 && $date_diff <= $result_remarks['head_content']) {
                    $peremptory_diary .= $dno . ",";
                }
            }
        }
        

        if (!empty($peremptory_diary)) {
            echo "Peremptory order was ordered in Diary Numbers - " . rtrim($peremptory_diary, ',') . ". Please remove the numbers from list and retry.  ";
            exit();
        }

        $data = [
            'iastat' => 'D',
            'lst_mdf' => date('Y-m-d H:i:s'),
            'dispose_date' => $dismissal_date,
            'last_modified_by' => $ucode,
        ];
        $sql_docdetails = $this->MonitoringTeam_Model->updateDocdetails($data, $diary_no);
        #for setting last order 
        $timestamp = strtotime($dismissal_date);
        // Creating new date format from that timestamp
        $new_date = date("d-m-Y", $timestamp);
        //echo $new_date; // Outputs: 31-03-2019
        $lorder = "Dismissed Ord dt :" . $new_date;

        $data = [
            'last_dt' => date('Y-m-d H:i:s'),
            'lastorder' => $lorder,
            'head_code' => $disp_type,
            'c_status' => 'D',
            'last_usercode' => $ucode,
        ];
        $sql_update_main = $this->MonitoringTeam_Model->updateMain($data, $diary_no);

        # for updating rgo
        $data = ['remove_def' => 'Y'];
        $sql_rgo = $this->MonitoringTeam_Model->updateRgoDefault($data, $diary_no);

        $rs_insert_case_remarks = $this->MonitoringTeam_Model->insertCaseRemarksMultiple($diary_no, $dismissal_date, $disp_type, $jcode, $ucode);

        $x = explode('-', $dismissal_date);
        $mon = $x[1];
        $year = $x[0];

        $rs_insert_dispose = $this->MonitoringTeam_Model->insertDispose($diary_no, $mon, $year, $jcode, $h_date, $dismissal_date, $disp_type, $ucode, $rj_date);

        if ($rs_insert_dispose) {
            # insert into log tables 
            echo "<center>Matters Disposed off successfully !!</center>";
            
            $data = [
                'diary_nos'              => $diary_no,
                'ucode'                  => $ucode,
                'jcodes'                 => $jcode,
                'rj_date'                => $rj_date,
                'dismissal_type'         => $disp_type,
                'dismissal_order_dt'     => $dismissal_date,
                'entered_on'             => date('Y-m-d H:i:s'), // NOW() equivalent
            ];
            $rs_log = $this->MonitoringTeam_Model->insertData('bulk_dismissal_log', $data);

            if ($rs_log) {
                echo "<center> Log maintained successfully !!</center>";
            }
        }
    }

    /*end MonitoringTeam UP-DATION*/
}
