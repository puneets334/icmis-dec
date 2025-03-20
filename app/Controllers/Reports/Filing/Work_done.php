<?php

namespace App\Controllers\Reports\Filing;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;

class Work_done extends BaseController
{
    public $Dropdown_list_model;
    function __construct()
    {
        ini_set('memory_limit','1024M');
        $this->Dropdown_list_model= new Dropdown_list_model();
    }


    public function index(){
        $ddl_all_blank = $_REQUEST['ddl_all_blank'];
        $date = $_REQUEST['date'];
        $date=!empty($date) ? date("Y-m-d", strtotime($date)) : '';
        $data['app_name']='Work Done IB Extension';
        $data['date']=$date;
        $data['reports']= $this->get_work_done_data($date,$ddl_all_blank);

        return view('Reports/filing/get_content_work_done',$data);
    }
    public function get_work_done_data($date,$ddl_all_blank){
        $section=77;
        $builder = $this->db->table("master.users u");
        $builder->select('u.name,u.empid ,u.usercode,ut.type_name');
        $builder->join('master.usertype ut','u.usertype=ut.id');
        $builder->whereIn("ut.id",[17,50,51]);
        $builder->WHERE('u.section',$section);
        $builder->orderBy('u.empid,ut.type_name');
        $query =$builder->get();
        $reports_final_array=array();
        if($query->getNumRows() >= 1) {
            $reports =$query->getResultArray();
            $total_sum=0;$office_report_details_count=0;
            foreach($reports as $row) {
                $usercode = $row['usercode'];
                $office_report_details = is_data_from_table('office_report_details', ['rec_user_id' => $usercode, 'DATE(rec_dt)' => $date, 'display' => 'Y', 'web_status' => 1]);
                if (empty($office_report_details)) {
                    $office_report_details = is_data_from_table('office_report_details_a', ['rec_user_id' => $usercode, 'DATE(rec_dt)' => $date, 'display' => 'Y', 'web_status' => 1]);
                }
                if (!empty($office_report_details)) {
                    $office_report_details_count = count($office_report_details);
                    $total_sum = ($total_sum + $office_report_details_count);
                }
                $reports_final_array[]= array_merge($row,array('total_sum'=>$total_sum))    ;
            }
           return $reports_final_array;
        }else{
            return $reports_final_array;
        }
    }

}
