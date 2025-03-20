<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Listing\MonitoringModel;

class MonitoringTeam extends BaseController
{

    public $model;
    public $diary_no;
    public  $MonitoringModel;

    function __construct()
    {
        $this->MonitoringModel = new MonitoringModel();
    }

    /**
     * To display monitoring team verification module
     *
     * @return void
     */
    public function index_page()
    {
        $data = [];
        $data['MonitoringModel'] = $this->MonitoringModel;
        $data['sections'] = $this->MonitoringModel->get_sections();
        $data['purpose_list'] = $this->MonitoringModel->get_listing_purpose();
        return  view('Listing/monitoring_team/index_page', $data);
    }

    public function verify_get()
    {
        $list_dt = $this->request->getPost('list_dt');
        $data['list_dt'] = $list_dt = !empty($list_dt) ? $list_dt : null;
        $data['mainhead'] = $mainhead = $this->request->getPost('mainhead');
        $data['board_type'] = $board_type = $this->request->getPost('board_type');
        $data['sec_id'] = $sec_id = $this->request->getPost('sec_id');
        $data['no_rec'] = $no_rec = $this->request->getPost('no_rec');
        $data['listed_not'] = $listed_not = $this->request->getPost('listed_not');
        $data['listorder'] = $listorder = $this->request->getPost('listorder');

        $ucode =  session()->get('login')['usercode'];
        $usertype = session()->get('login')['usertype'];

        $get_deatils = $this->MonitoringModel->get_monitoring_data($usertype, $ucode, $data);

        $data = array_merge($data, $get_deatils);
        $diary_numbers = $data['cat_codes'] = $data['advocates'] = $data['max_entry_dt']  = $data['case_verify'] = $data['remarks_list'] = $data['not_before_details'] = $data['get_rop_data'] = [];

        foreach ($get_deatils['monitoring_data'] as $result) {
            $diary_numbers[] = $result['diary_no'];
        }

        if (!empty($diary_numbers)) {
            $data['cat_codes'] = $this->MonitoringModel->get_category_code($diary_numbers);
            $data['advocates'] = $this->MonitoringModel->get_advocate_details($diary_numbers);
            $data['max_entry_dt'] = $this->MonitoringModel->max_entry_date($diary_numbers);

            $data['case_verify'] = $this->MonitoringModel->case_verify_details($data['max_entry_dt']);

            $data['remarks_list'] = $this->MonitoringModel->remarks_list();

            $data['not_before_details'] = $this->MonitoringModel->get_not_before_details($diary_numbers);

            $data['get_rop_data'] = $this->MonitoringModel->get_rop_data($diary_numbers);

            $data['f_get_docdetail'] = $this->MonitoringModel->f_get_docdetail($diary_numbers);
            $data['get_cl_brd_remark'] = $this->MonitoringModel->get_cl_brd_remark($diary_numbers);
        }

        return  view('Listing/monitoring_team/verify_get', $data);
    }


    /**
     * To display daily court remarks page
     *
     * @return void
     */
    public function daily_court_remarks()
    {
        $data = [];
        $hd_ud = $this->request->getGet('hd_ud');
        $data['aw1'] = $this->request->getPost('aw1');
        $data['get_dtd'] = $this->request->getPost('dtd');
        $data['get_hdate'] = $this->request->getPost('hdate');
        $data['get_mf'] = $this->request->getPost('mf');
        $users = $this->MonitoringModel->get_users($hd_ud);
        $paps = $jcode = '0';
        if (!empty($users)) {
            $paps = $users['pa_ps'];
            $jcode = session()->set('jcode', $users['jcode']);
        }
        $data['userid'] = session()->get('login')['empid'];
        $data['c_list'] = $this->MonitoringModel->c_list($paps, $jcode);
        $data['case_remarks_head'] = $this->MonitoringModel->case_remarks_head();
        $data['case_remarks_head_side'] = $this->MonitoringModel->case_remarks_head_side();
        return  view('Listing/monitoring_team/daily_court_remarks', $data);
    }


    public function daily_court_remarks_process()
    {
        $data['crt'] = $this->request->getPost('courtno');
        $data['dtd'] = $this->request->getPost('dtd');
        $data['jcd'] = $this->request->getPost('aw1');
        $data['mf'] = $this->request->getPost('mf');
        $data['r_status'] = $this->request->getPost('r_status');
        $data['vstats'] = $this->request->getPost('vstats');
        $data['userid'] = session()->get('login')['empid'];
        $tdt = explode("-", $data['dtd']);
        $data['tdt1'] = $tdt[2] . "-" . $tdt[1] . "-" . $tdt[0];
        $data['court_data'] = $this->MonitoringModel->getCourtData($data);
        $data['case_verify_by_sec_remark'] = $this->MonitoringModel->case_verify_by_sec_remark();
        return  view('Listing/monitoring_team/daily_court_remarks_process', $data);
    }

    public function response_verify_rop()
    {
        $data['rremark'] = $this->request->getPost('rremark');
        $ucode =  session()->get('login')['usercode'];
        $data['dno'] = $this->request->getPost('dno');
        $is_saved = $this->MonitoringModel->save_case_verify_rop($data['dno'], $ucode, $data['rremark']);
        $response = !empty($is_saved) ? $is_saved : '';
        return $this->response->setJSON($response);
    }

    public function verify_index()
    {
        return view('Listing/monitoring_team/verify_index');
    }

    public function verify_report()
    {
        $data['usercode'] = session()->get('login')['usercode'];
        $data['verify_dt']  = $this->request->getPost('verify_dt');
        $data['result_array'] = $this->MonitoringModel->verify_report($data['verify_dt']);
        //pr($data);
        return view('Listing/monitoring_team/verify_report', $data);
    }

    public function response_verify()
    {
        $data['rremark'] = $this->request->getPost('rremark');
        $ucode =  session()->get('login')['usercode'];
        $data['dno'] = $this->request->getPost('dno');
        $is_saved = $this->MonitoringModel->save_case_verify($data['dno'], $ucode, $data['rremark']);
        $response = !empty($is_saved) ? $is_saved : '';
        return $this->response->setJSON($response);
    }

    public function verify_detail_report()
    {

        //$data['str'] = $_GET['str'];
        //$data['remarks'] = $_GET['remarks'];
        //$data['str_exp'] = explode("_", $_GET['str']);
        $data['str'] = $this->request->getGet('str');
        $data['remarks'] = $this->request->getGet('remarks');
        $data['str_exp'] = explode("_", $data['str']);
        
      
         $data['list_dt'] = $data['str_exp'][0];
         $data['userid_str'] = $data['str_exp'][1];
         $data['ucode'] =  session()->get('login')['usercode'];
         $data['usertype'] =  session()->get('login')['usertype'];
         $data['MonitoringModel'] = $this->MonitoringModel;
        //  pr($data['str_exp'][1]);
        
        return view('Listing/monitoring_team/verify_detail_report', $data);
    }
}
