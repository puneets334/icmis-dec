<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Models\Entities\Model_main;
use App\Models\Entities\Model_main_a;
use App\Models\Entities\Model_sensitive_cases;

class Sensitive_info extends BaseController
{
   public $Model_sensitive_cases;
   public $Model_main;
   public $Model_main_a;
    function __construct()
    {
        $this->Model_sensitive_cases = new Model_sensitive_cases();
        $this->Model_main = new Model_main();
        $this->Model_main_a = new Model_main_a();
        ini_set('memory_limit','1024M');
    }

    public function index()
    {
        $data['casetype']=get_from_table_json('casetype');

       return view('Filing/sensitive_search',$data);
    }
    public function get_details()
    {
        if ($this->request->getMethod() === 'post') {
            $search_type=$this->request->getPost('search_type');

            $diary_number=$this->request->getPost('diary_number');
            $diary_year=$this->request->getPost('diary_year');

            $case_type=$this->request->getPost('case_type');
            $case_number=$this->request->getPost('case_number');
            $case_number_to=$this->request->getPost('case_number_to');
            $case_year=$this->request->getPost('case_year');

            $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');

            if (!empty($search_type) && $search_type !=null){
                if ($search_type =='D'){
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('diary_number', 'Diary number', 'required');
                    $this->validation->setRule('diary_year', 'Diary year', 'required');

                    $data = [
                        'search_type'=>$search_type,
                        'diary_number'=>$diary_number,
                        'diary_year'=>$diary_year,
                    ];

                }else{
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('case_type', 'Case type', 'required');
                    $this->validation->setRule('case_number', 'Case number', 'required');
                    $this->validation->setRule('case_year', 'Case year', 'required');

                    $data = [
                        'search_type'=>$search_type,
                        'case_type'=>$case_type,
                        'case_number'=>$case_number,
                        'case_year'=>$case_year,
                    ];
                }

            }else{
                $data = [
                    'search_type'=>$search_type
                ];
            }

            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('search_type').$this->validation->getError('case_type');
                echo $this->validation->listErrors();exit();
            }elseif (!empty($case_number_to) && ($case_number > $case_number_to)){
                echo '3@@@To Case No. must be greater than From Case No.!';exit();
            }
            $data['case_detail']=$data;
            $is_a='';
            $final_result=$this->get_case_details($is_a,$search_type,$diary_number,$diary_year,$case_type,$case_number,$case_year,$case_number_to);
            if(!empty($final_result)) {
                $data['case_detail'] = $final_result;
                $resul_view= view('Filing/sensitive_search_get_content',$data);
                echo '1@@@'.$resul_view;exit();
            }else{
                $is_a='_a';
                $final_result=$this->get_case_details($is_a,$search_type,$diary_number,$diary_year,$case_type,$case_number,$case_year,$case_number_to);
                $data['case_detail'] = $final_result;
                $resul_view= view('Filing/sensitive_search_get_content',$data);
                echo '1@@@'.$resul_view;exit();
            }

        }
        exit();
    }


    public function get_case_details($is_a,$search_type,$diary_number,$diary_year,$case_type,$case_number,$case_year,$case_number_to)
    {
        if ($search_type=='D'){
            if ($diary_number != 0 && $diary_year != 0) {
                $diary_no =$diary_number.$diary_year;
                $query = $this->db->table("main$is_a m");
                $query->select('LEFT(CAST(m.diary_no AS TEXT), -4) AS diary_no, RIGHT(CAST(m.diary_no AS TEXT), 4) AS diary_year,');
                $query->select("TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date, m.diary_no AS case_diary, m.c_status,");
                $query->select("CONCAT(m.pet_name, ' Vs. ', m.res_name) AS case_title, s.reason, s.display, us.section_name AS user_section,");
                $query->select("m.reg_no_display, TO_CHAR(s.updated_on, 'DD-MM-YYYY') AS updated_on, u.name AS updated_by");

                $query->join('sensitive_cases s', "m.diary_no = s.diary_no AND s.display = 'Y'", 'left');
                $query->join('master.users u', "u.usercode = s.updated_by AND (u.display = 'Y' OR u.display IS NULL)", 'left');
                $query->join('master.users u1', "m.dacode = u1.usercode AND (u1.display = 'Y' OR u1.display IS NULL)", 'left');
                $query->join('master.usersection us', "us.id = u1.section AND (us.display = 'Y' OR us.display IS NULL)", 'left');
                $query->where('m.diary_no', $diary_no);
               return $result = $query->get()->getRowArray();
            }

        }else  if ($search_type =='C'){

            $query = $this->db->table("main$is_a m");
            $query->select('LEFT(CAST(m.diary_no AS TEXT), -4) AS diary_no');
            $query->select('RIGHT(CAST(m.diary_no AS TEXT), 4) AS diary_year');
            $query->select("TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS diary_date");
            $query->select("m.diary_no AS case_diary, m.c_status, CONCAT(m.pet_name, ' Vs. ', m.res_name) AS case_title");
            $query->select('s.reason, s.display, us.section_name AS user_section');
            $query->select("m.reg_no_display, TO_CHAR(s.updated_on, 'DD-MM-YYYY hh:ii:ss') AS updated_on, u.name AS updated_by");

            if ($case_type != 0 && $case_number != 0 && $case_year != 0 && ($case_number_to !=0 && !empty($case_number_to)) ) {

                $query->select('m.active_reg_year, m.active_fil_no, t.case_type, t.case_range,case_range2');
                $query->join('(SELECT diary_no,
                        CASE WHEN m.active_fil_no::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(m.active_fil_no, \'-\', 1)) AS INTEGER) ELSE 0::INTEGER END AS case_type,
                        CASE WHEN m.active_fil_no::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(m.active_fil_no, \'-\', 2)) AS INTEGER) ELSE 0::INTEGER END AS case_range,
                        CASE WHEN m.active_fil_no::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(m.active_fil_no, \'-\', 2)) AS INTEGER) ELSE 0::INTEGER END AS case_range2
                  FROM main'.$is_a.' m) t', 't.diary_no = m.diary_no', 'inner');

            }else if ($case_type != 0 && $case_number != 0 && $case_year != 0 && ($case_number_to ==0 || empty($case_number_to)) ) {

                $query->select('m.active_reg_year, m.active_fil_no, t.case_type, t.case_range');
                $query->join('(SELECT diary_no,
                        CASE WHEN m.active_fil_no::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(m.active_fil_no, \'-\', 1)) AS INTEGER) ELSE 0::INTEGER END AS case_type,
                        CASE WHEN m.active_fil_no::text ~ \'^[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]$\'::text
                                THEN CAST((SPLIT_PART(m.active_fil_no, \'-\', 2)) AS INTEGER) ELSE 0::INTEGER END AS case_range
                  FROM main'.$is_a.' m) t', 't.diary_no = m.diary_no', 'inner');
            }

            $query->join('sensitive_cases s', "m.diary_no = s.diary_no AND s.display = 'Y'", 'left');
            $query->join('master.users u', "u.usercode = s.updated_by AND (u.display = 'Y' OR u.display IS NULL)", 'left');
            $query->join('master.users u1', "m.dacode = u1.usercode AND (u1.display = 'Y' OR u1.display IS NULL)", 'left');
            $query->join('master.usersection us', "us.id = u1.section AND (us.display = 'Y' OR us.display IS NULL)", 'left');
            $query->where('m.active_fil_no IS NOT NULL');
            $query->where('m.active_fil_no !=', '');
            $query->where('t.case_type', $case_type);
            $query->where('m.active_reg_year', $case_year);
            if ($case_type != 0 && $case_number != 0 && $case_year != 0 && ($case_number_to !=0 && !empty($case_number_to)) ) {
                $query->where("$case_number BETWEEN t.case_range AND t.case_range2");
            }else{
                $query->where('t.case_range', $case_number);
            }
            $result = $query->get();
            return $result->getRowArray();
        }

        return false;
    }
    public function update_case()
    {
        if ($this->request->getMethod() === 'post') {
            $case_diary=$this->request->getPost('case_diary');
            $case_info=$this->request->getPost('case_info');

            $this->validation->setRule('case_diary', 'Case diary number', 'required');
            $this->validation->setRule('case_info', 'Case info', 'required');

            $data = [
                'case_diary'=>$case_diary,
                'case_info'=>$case_info,
            ];

            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }

            $query=$this->Model_sensitive_cases->select('*')->where(['diary_no'=>$case_diary,'display'=>'Y'])->get();
            if ($query->getNumRows() >= 1) {
                $result_sensitive_cases=$query->getRowArray();
                $sensitive_cases_data = [
                    'reason'=>$case_info,
                    'updated_from_ip'=>getClientIP(),

                    'updated_on'=>date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $is_sensitive_cases = update('sensitive_cases', $sensitive_cases_data, ['diary_no' => $case_diary,'display' => 'Y']);
                $response='Data Updated Successfully';
                echo '1@@@'.$response;exit();


            }else{
                $sensitive_cases_data = [
                    'diary_no'=>$case_diary,
                    'reason'=>$case_info,
                    'display'=>'Y',
                    'updated_on'=>date("Y-m-d H:i:s"),
                    'updated_from_ip'=>getClientIP(),

                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                $is_sensitive_cases=$this->Model_sensitive_cases->insert($sensitive_cases_data);
                $response='Data Save Successfully';
                echo '1@@@'.$response;exit();
            }
            echo '3@@@Something went wrong! please contact computer cell!!'; exit();
        }
        exit();
    }

    public function report()
    {
        $data['casetype']=get_from_table_json('casetype');
        return view('Filing/sensitive_report_search',$data);
    }

    public function get_report()
    {
        if ($this->request->getMethod() === 'post') {
            $from_date=$this->request->getPost('from_date');
            $to_date=$this->request->getPost('to_date');

            $this->validation->setRule('from_date', 'Date From', 'required');
            $this->validation->setRule('to_date', 'Date To', 'required');
            $data = [
                'from_date'=>$from_date,
                'to_date'=>$to_date,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();exit();
            }
            $timestamp1 = strtotime($from_date);
            $timestamp2 = strtotime($to_date);
            if ($timestamp1 > $timestamp2){
                echo "3@@@To Date must be greater than From date"; exit();
            }

            $query = $this->Model_main->select("concat(LEFT(CAST(main.diary_no AS TEXT), -4) ,' / ', RIGHT(CAST(main.diary_no AS TEXT), 4)) as diary_no");
            $query->select("CONCAT(main.pet_name, ' Vs. ', main.res_name) AS case_title,us.section_name AS user_section,main.reg_no_display,s.reason,s.display,");
            $query->select("TO_CHAR(s.updated_on, 'DD-MM-YYYY') AS updated_on, u1.name AS updatedBy");

            $query->join('sensitive_cases s', "main.diary_no = s.diary_no AND s.display = 'Y'", 'left');
            $query->join('master.users u', "u.usercode = main.dacode AND (u.display = 'Y' OR u.display IS NULL)", 'left');
            $query->join('master.users u1', "u1.usercode = s.updated_by AND (u1.display = 'Y' OR u1.display IS NULL)", 'left');
            $query->join('master.usersection us', "us.id = u.section AND (us.display = 'Y' OR us.display IS NULL)", 'left');

            $query->where('DATE(s.updated_on) >=', $from_date);
            $query->where('DATE(s.updated_on) <=', $to_date);
            $result= $query->get();

            if($result->getNumRows() >= 1) {
                $final_result = $result->getResultArray();
                $data['case_result'] = $final_result;
                $resul_view= view('Filing/get_content_sensitive_case_report',$data);
                echo '1@@@'.$resul_view;exit();
            }else{
                $query_a = $this->Model_main_a->select("concat(LEFT(CAST(main_a.diary_no AS TEXT), -4) ,' / ', RIGHT(CAST(main_a.diary_no AS TEXT), 4)) as diary_no");
                $query_a->select("CONCAT(main_a.pet_name, ' Vs. ', main_a.res_name) AS case_title,us.section_name AS user_section,main_a.reg_no_display,s.reason,s.display,");
                $query_a->select("TO_CHAR(s.updated_on, 'DD-MM-YYYY') AS updated_on, u1.name AS updatedBy");

                $query_a->join('sensitive_cases s', "main_a.diary_no = s.diary_no AND s.display = 'Y'", 'left');
                $query_a->join('master.users u', "u.usercode = main_a.dacode AND (u.display = 'Y' OR u.display IS NULL)", 'left');
                $query_a->join('master.users u1', "u1.usercode = s.updated_by AND (u1.display = 'Y' OR u1.display IS NULL)", 'left');
                $query_a->join('master.usersection us', "us.id = u.section AND (us.display = 'Y' OR us.display IS NULL)", 'left');
                $query_a->where('DATE(s.updated_on) >=', $from_date);
                $query_a->where('DATE(s.updated_on) <=', $to_date);
                $result_a= $query_a->get();
                if($result_a->getNumRows() >= 1) {
                    $final_result = $result_a->getResultArray();
                    //echo '<pre>';print_r($final_result);exit();
                    $data['case_result'] = $final_result;
                    $resul_view= view('Filing/get_content_sensitive_case_report',$data);
                    echo '1@@@'.$resul_view;exit();
                }else{
                    echo '1@@@<center><span class="text-danger">Data not found<span><center>'; exit();
                }

            }
            echo '3@@@Something went wrong! please contact computer cell!!'; exit();
        }
        exit();
    }
}
