<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\ReportModel;
use App\Models\Listing\ListingStatisticsModel;
use App\Models\Listing\SpreadOutCertificateModel;
use App\Models\Listing\RoserRegModel;
use App\Models\Listing\PrintModel;

class PrintController extends BaseController
{
    protected $PrintModel;

    public function __construct()
    {
        $this->PrintModel = new PrintModel;
    }
    public function cl_print_section1()
    {
        $usercode = session()->get('login')['usercode'];
        if (empty($usercode)) {
            return redirect('Login');
        } else {

            $data['section_id'] = $this->PrintModel->getUserdata($usercode);
            $data['userSectionList'] = $this->PrintModel->getRollId();
            return view('Listing/print/cl_print_section1', $data);
        }
    }
    public function get_cause_list_section1()
    {
        $list_dt = date('d-m-Y', strtotime($this->request->getPost('list_dt')));
       
        $orderby = $this->request->getPost('orderby') ?? '';
        if($orderby === '0'){
            $orderby = '';
        }
        $sec_id = $this->request->getPost('sec_id');
        
        $list_dt_val = $this->request->getPost('list_dt');
        $mainhead = $this->request->getPost('mainhead');
        $mainhead_descri = $this->getMainHeadDescription($mainhead);
        $conditions = $this->buildConditions();
        if(!empty($list_dt_val)){
            $data['cause_list'] = $this->PrintModel->getCauseList($conditions,$list_dt,$sec_id,$orderby,$mainhead);
            $data['title'] = 'Cause List for Dated '. $list_dt_val .' ('.$mainhead_descri.')';
        }
        else{
            $data['cause_list'] = [];
            $data['title'] = '';
        }
        
        $data['list_dt'] = $list_dt;
        $data['mainhead_descri'] = $mainhead_descri;
        
        // echo '<pre>';
        // print_r($data['cause_list']);
        // die();
        return json_encode($data);
        //return view('Listing/print/get_cause_list_section1', $data);
    }

    private function getMainHeadDescription($mainhead)
    {
        switch ($mainhead) {
            case 'M': return "Miscellaneous Hearing";
            case 'F': return "Regular Hearing";
            case 'L': return "Lok Adalat";
            default: return "";
        }
    }

    private function buildConditions()
    {
        $conditions = [];

        if ($this->request->getPost('lp') !== "all") {
            $conditions[] = "h.listorder = '" . $this->request->getPost('lp') . "'";
        }

        if ($this->request->getPost('main_suppl') != "0") {
            $conditions[] = "h.main_supp_flag = '" . $this->request->getPost('main_suppl') . "'";
        }

        if ($this->request->getPost('courtno') != "0") {
            $conditions[] = "r.courtno = '" . $this->request->getPost('courtno') . "'";
        }

        if ($this->request->getPost('board_type') != "0") {
            $conditions[] = "h.board_type = '" . $this->request->getPost('board_type') . "'";
        }

        // Add other conditions similarly...

        return implode(' AND ', $conditions);
    }

    public function cl_print_advance_section()
    {
        $data['section_name'] = $this->PrintModel->section_name();
        $data['listing_dates'] = $this->PrintModel->listing_dates();
        $data['purpose_of_listing'] = $this->PrintModel->purpose_of_listing();
        return view('Listing/print/cl_print_advance_section', $data);
    }

    public function cl_print_fresh()
    {
        $data['section_name'] = $this->PrintModel->section_name();
        $data['listing_dates'] = $this->PrintModel->listing_dates_fresh();
        return view('Listing/print/cl_print_fresh', $data);
    }
   
    public function get_cause_list_fresh(){

        $list_dt = $this->request->getPost('list_dt');
        
        if($list_dt == "-1"){ $list_dt_lm = ""; } else{
            $list_dt_lm = date('d-m-Y', strtotime($list_dt));
        }
        $courtno = $this->request->getPost('courtno');
        $board_type = $this->request->getPost('board_type');
        $ma_cc_crlm = $this->request->getPost('ma_cc_crlm');
        $received = $this->request->getPost('received');
        $orderby = $this->request->getPost('orderby');
        $sec_id = $this->request->getPost('sec_id');
        $main_suppl = $this->request->getPost('main_suppl');
        $scn_sts = $this->request->getPost('scn_sts');
        $mainhead = $this->request->getPost('mainhead');
        $limit = $this->request->getPost('limit');
        
        $main_supl_head = '';
        if($mainhead == 'M'){
            $mainhead_descri = "Miscellaneous Hearing";
        }
        if($mainhead == 'F'){
            $mainhead_descri = "Regular Hearing";
        }
        if($mainhead == 'L'){
            $mainhead_descri = "Lok Adalat";
        }
        if($main_suppl == "1"){
            $main_supl_head = "Main List";
        }
        if($main_suppl == "2"){
            $main_supl_head = "Supplimentary List";
        }
    
        $data['list_dt'] = $list_dt_lm;
        $data['mainhead_descri'] = $mainhead_descri;
        $data['main_supl_head'] = $main_supl_head;
        
        $data['listing_dates'] = $this->PrintModel->get_cause_list_fresh($list_dt, $mainhead, $orderby,$courtno,$board_type,$ma_cc_crlm,$received,$sec_id,$main_suppl,$scn_sts,$limit);
        
        return view('Listing/print/get_cl_print_fresh', $data);
    }

    public function get_cl_print_fresh_mainhead()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');

        $option = '';


        $m_f = null;
        if ($mainhead === 'M') {
            $m_f = '1';
        } elseif ($mainhead === 'F') {
            $m_f = '2';
        }

        $builder = $db->table('heardt c');
        $builder->select('c.next_dt')
            ->where('c.mainhead', $mainhead)
            ->where('c.next_dt >=', date('Y-m-d'))
            ->groupStart() 
            ->where('c.main_supp_flag', '1')
            ->orWhere('c.main_supp_flag', '2')
            ->groupEnd()
            ->groupBy('c.next_dt')
            ->orderBy('c.next_dt', 'ASC');

        if ($board_type !== '0') {
            $builder->where('c.board_type', $board_type);
        }
        //echo $builder->getCompiledSelect(); die;
        // Get Data
        $query = $builder->get();
        $resultdata = $query->getResultArray();
        //echo $builder->getCompiledSelect(); die;

        //Generate `<option>` elements
        if (!empty($resultdata)) {
            $option .= '<option value="0" selected>SELECT</option>';
            foreach ($resultdata as $list) {
                $formattedDate = date("d-m-Y", strtotime($list['next_dt']));
                $option .= '<option value="' . $list['next_dt'] . '">' . $formattedDate . '</option>';
            }
        } else {
            $option .= '<option value="0" selected>EMPTY</option>';
        }

        return $option;
    }
    public function get_advance_cause_list_section_data()
    {
        // pr(session()->get('login'));
        $ucode = session()->get('login')['usercode'];
        $usertype = session()->get('login')['usertype'];
        $section1 = session()->get('login')['section'];
        if(!empty($this->request->getPost('list_dt'))){
            $dates = $this->request->getPost('list_dt');
        }else{
            $dates = date('d-m-Y');
        }
        $data['list_dt'] = date('d-m-Y', strtotime($dates));
        $data['listtype'] = $this->request->getPost('listtype');
        if ($data['listtype'] == 'D') {
            $mainhead_descri = "Draft List";
        } else if ($data['listtype'] == 'A') {
            $mainhead_descri = "Advance List";
        }
        if (!empty($this->request->getPost('sec_id'))) {
            $sec_id = $this->request->getPost('sec_id');
            $data['get_usersection'] = $this->PrintModel->get_usersection($sec_id);
            $sec_name = $data['get_usersection'][0]['section_name'];
        } else {
            $sec_id = 'all';
            $sec_name = '';
        }
        if (!empty($this->request->getPost('list_dt'))) {
            $list_dt1 = $this->request->getPost('list_dt');
        } else {
            $list_dt1 =  date('Y-m-d');
        }
       
        $data['title'] = $mainhead_descri . " Cause List for Dated" . $data['list_dt'];
        $data['get_list_section'] = $this->PrintModel->get_list_section($data['listtype'], $list_dt1, $sec_id, $sec_name, $ucode, $usertype, $section1, $this->request->getPost('lp'));
        return $data['get_list_section'];
        // echo '<pre>';
        // print_r($data['get_list_section']);
        // die();
        //return $data;
       // return view('Listing/print/get_advance_cause_list_section', $data);
    }
}
