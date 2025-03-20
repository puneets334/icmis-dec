<?php

namespace App\Controllers\Reports\SCLSC;
use App\Controllers\BaseController;
use App\Models\Entities\Model_main;
use App\Models\Entities\Model_main_a;
use CodeIgniter\Model;

class Report extends BaseController
{
    public $Dropdown_list_model;
   /* public $Model_Model_main;
    public $Model_Model_main_a;*/
    function __construct()
    {
         ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        /*$this->Model_Model_main = new Model_main();
        $this->Model_Model_main_a = new Model_main_a();*/
    }

    public function index(){
        $data['case_result']='';
        $data['app_name']='SCLSC pending Report';
        $data['param']='';

        return view('Reports/SCLSC/sclsc_search_report');
    }
    public function test(){
        $union   = $this->db->table('heardt h')->select('select h.diary_no,h.next_dt as cl_dt')->orderBy('id', 'DESC')->limit(5);
        $builder = $this->db->table('users')->select('id, name')->orderBy('id', 'ASC')->limit(5)->union($union);

        $this->db->newQuery()->fromSubquery($builder, 'q')->orderBy('id', 'DESC')->get();

        echo '<pre>';print_r($_SESSION['login']); exit();
    }
    public function SCLSC_pending_report_bkp(){
        if ($this->request->getMethod() === 'post') {
            $this->validation->setRule('c_type', 'caveat_number', 'required');
            $this->validation->setRule('status', 'caveat_year', 'required');

            $c_type = $this->request->getPost('c_type');
            $status = $this->request->getPost('status');
            $data = [
                'c_type'=>$c_type,
                'status'=>$status,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('c_type').$this->validation->getError('status');
                echo $this->validation->listErrors();exit();
            }
            $is_archival_table = '_a';
            $data['param']=$status;
            $result_array = $this->get_data_SCLSC($c_type,$status,'');
            $result_arrayD = $this->get_data_SCLSC($c_type,$status,$is_archival_table);
            $final_result= array_merge($result_array,$result_arrayD);
            //$final_result= $result_arrayD;
            if(count($final_result) >= 1) {
                //$result_array = $query->getResultArray();
                $data['case_result'] = $final_result;
                $resul_view= view('Reports/SCLSC/get_content_sclsc_report',$data);
                echo '1@@@'.$resul_view;exit();
            }else{
                //echo '3@@@Data not found';exit();
                $data['case_result'] = $final_result;
                $resul_view= view('Reports/SCLSC/get_content_sclsc_report',$data);
                echo '1@@@'.$resul_view;exit();
            }

            exit();

        }
          exit();

    }

    public function get_data_SCLSC($c_type,$status,$is_archival_table){

            $data['param']=$status;
            $sql=""; $condition="";
            $extra_column="";$extra_condition="";
            $extra_join1="";
            //echo "c type".$c_Type;
            if(strcmp($c_type, 'C')== 0) {
                $condition = "case_grp='C' and ";
            }else if(strcmp($c_type, 'R')== 0) {
                $condition = "case_grp='R' and ";
            }
            if(strcmp($status,'P' )==0 ) {
                $c_status = "m.c_status = 'P' and ";
            }else if(strcmp($status,'D' )==0) {
                $c_status = "m.c_status = 'D' and ";
            }else if(strcmp($status,'PD' )==0) {
                $c_status = "m.c_status = 'P' and ";

                $extra_condition=" and m.c_status='P'";
            }else{
                $c_status="";
            }
            if (!empty($is_archival_table) && $is_archival_table=='_a'){
                $Model_main = new Model_main_a();
                //echo 'is_archival_table='.$is_archival_table;  exit();
            }else{

                $Model_main = new Model_main();
            }

        //$this->Model_Model_main = new Model_main();
        //$this->Model_Model_main_a = new Model_main_a();
        $Model_main->select("us.section_name AS user_section,u.name as alloted_to_da,
        concat(m.reg_no_display,' @ ',concat(left((cast(m.diary_no as text)),-4),' / ',right((cast(m.diary_no as text)),4))) as case_no,         
        m.pet_name, m.res_name, m.reg_no_display, m.pno, m.rno, m.c_status");
        $Model_main->select("STRING_AGG(DISTINCT b.name, E'\n') AS advocate", false);
        $Model_main->select("CASE WHEN m.conn_key IS NULL OR m.conn_key = '0' THEN 'M' ELSE 'C' END AS main_connected");
        $Model_main->select('case_grp');
        $Model_main->join('advocate'.$is_archival_table.' a', 'm.diary_no = a.diary_no', 'left');
        $Model_main->join('master.bar b', 'a.advocate_id = b.bar_id', 'left');
        $Model_main->join('master.users u', "u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL)", 'left');
        $Model_main->join('master.usersection us', 'us.id = u.section', 'left');
            if (!empty($status) && $status !=null) {
                if(strcmp($status,'PD' )==0) {
                    // sub_query_joining
                }
            }


            if (!empty($c_type) && $c_type !=null && (strcmp($c_type, 'C')== 0 || strcmp($c_type, 'R')== 0) ){
                $Model_main->where('case_grp', $c_type);
            }
            if (!empty($status) && $status !=null) {
                if (strcmp($status, 'P') == 0) {
                    $Model_main->where('m.c_status', $status);
                }else if(strcmp($status,'D' )==0) {
                    $Model_main->where('m.c_status', $status);
                }else if(strcmp($status,'PD' )==0) {
                    $Model_main->where('m.c_status', $status);
                }
            }
        $Model_main->where('if_sclsc', 1);
        $Model_main->groupStart();
        $Model_main->where('a.display', 'Y');
        $Model_main->orWhere('a.display IS NULL');
        $Model_main->groupEnd();
        //$Model_main->groupBy('m.diary_no, us.section_name, u.name, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, m.c_status, main_connected, case_grp');
        $Model_main->groupBy('m.diary_no,user_section,alloted_to_da,m.reg_no_display,m.pet_name,m.res_name,m.pno,m.rno,m.c_status,m.conn_key,m.case_grp');
        $Model_main->orderBy('case_grp');
            $query = $Model_main->get();
        return  $query->getResultArray();
        //echo $Model_main->getCompiledSelect(false); //exit();
         $query=$this->db->getLastQuery();
         echo (string) $query;//exit();
          /*if($query->getNumRows() >= 1) {
              return  $query->getResultArray();
            }else{
                return false;
            }*/

    }







    public function SCLSC_pending_report(){
        if ($this->request->getMethod() === 'post') {
            $this->validation->setRule('c_type', 'caveat_number', 'required');
            $this->validation->setRule('status', 'caveat_year', 'required');

            $c_type = $this->request->getPost('c_type');
            $status = $this->request->getPost('status');
            $data = [
                'c_type'=>$c_type,
                'status'=>$status,
            ];
            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                //echo $this->validation->getError('c_type').$this->validation->getError('status');
                echo $this->validation->listErrors();exit();
            }
            $data['param']=$status;
            $sql=""; $condition="";
            $extra_column="";$extra_condition="";
            $extra_join1="";
            $c_status_pd_then_group_by="";
            //echo "c type".$c_Type;
            if(strcmp($c_type, 'C')== 0) {
                $condition = "case_grp='C' and ";
            }else if(strcmp($c_type, 'R')== 0) {
                $condition = "case_grp='R' and ";
            }
            if(strcmp($status,'P' )==0 ) {
                $c_status = "m.c_status = 'P' and ";
            }else if(strcmp($status,'D' )==0) {
                $c_status = "m.c_status = 'D' and ";
            }else if(strcmp($status,'PD' )==0) {

                $c_status_pd_then_group_by = ",d.defect_notified_date,h.cl_dt,d.noofdelaydays";
                $c_status = "m.c_status = 'P' and ";
                $extra_column=",to_char(d.defect_notified_date, 'DD-MM-YYYY') as first_defect_notified_date,
                                to_char(h.cl_dt, 'DD-MM-YYYY') as last_listed_on, d.noofdelaydays ";
                $extra_join1=" left join
                                ( select x.* from 
                                    (select h.diary_no,h.next_dt as cl_dt from heardt h 
                                    where h.main_supp_flag in (1,2) GROUP BY diary_no,next_dt
                                    union 
                                    select h.diary_no,MAX(next_dt) as cl_dt from last_heardt h 
                                    where h.main_supp_flag in (1,2) GROUP BY diary_no
                                    union
                                    SELECT c.diary_no, MAX(cl_date) AS cl_dt FROM case_remarks_multiple c        
                                    GROUP BY c.diary_no)x
                                     inner join main m on x.diary_no=m.diary_no
                                    where m.c_status='P'
                                ) h on m.diary_no=h.diary_no
                                inner join 
                                (
                                    SELECT distinct  o.diary_no,o.rm_dt,
                                      MIN(save_dt) AS defect_notified_date,
                                       case  WHEN rm_dt IS NULL THEN (CURRENT_DATE -MIN(save_dt)::date)  ELSE 0
                                       END AS noofdelaydays,o.display
                                       FROM obj_save o
                                       inner join main m on o.diary_no=m.diary_no
                                     WHERE  (o.rm_dt =rm_dt IS NULL) AND o.display = 'Y'
                                      and m.c_status='P'
                                      group by o.diary_no,o.rm_dt,o.display  
                                )d on m.diary_no=d.diary_no";
                $extra_condition=" and m.c_status='P'";
            }else{
                $c_status="";
            }
             $sql = "select
        us.section_name AS user_section,
        u.name alloted_to_da,
        concat(m.reg_no_display,' @ ',concat(left((cast(m.diary_no as text)),-4),' / ',right((cast(m.diary_no as text)),4))) as case_no,         
        m.pet_name, m.res_name, m.reg_no_display, m.pno, m.rno, m.c_status,
         STRING_AGG(DISTINCT b.name, E'\n') AS advocate,
    CASE when (( (m.conn_key is not null and m.conn_key!= '') and  (m.diary_no = cast( m.conn_key as integer) )) or m.conn_key IS NULL OR m.conn_key = '0') THEN 'M' else 'C' end
AS main_connected,
        case_grp $extra_column
        from
          main m
            left join
          advocate a ON m.diary_no = a.diary_no
            left join
          master.bar b ON a.advocate_id = b.bar_id
            LEFT JOIN
          master.users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null)
            LEFT JOIN
          master.usersection us ON us.id = u.section
          $extra_join1
        where
           $condition  $c_status if_sclsc = 1 and (a.display = 'Y' or a.display is null)
            $extra_condition
            group by m.diary_no,user_section,alloted_to_da,m.reg_no_display,m.pet_name,m.res_name,m.pno,m.rno,m.c_status,m.conn_key,m.case_grp
                     $c_status_pd_then_group_by
            order by case_grp";

    //echo '<br>';
            if(strcmp($status,'A' )==0 || strcmp($status,'D' )==0) {
                if(strcmp($status,'D' )==0) {
                    $c_status = "m.c_status = 'D' and ";
                }else{
                    $c_status="";
                }
             $sqlD = "select
        us.section_name AS user_section,
        u.name alloted_to_da,
        concat(m.reg_no_display,' @ ',concat(left((cast(m.diary_no as text)),-4),' / ',right((cast(m.diary_no as text)),4))) as case_no,         
        m.pet_name, m.res_name, m.reg_no_display, m.pno, m.rno, m.c_status,
         STRING_AGG(DISTINCT b.name, E'\n') AS advocate,
    CASE when (( (m.conn_key is not null and m.conn_key!= '') and  (m.diary_no = cast( m.conn_key as integer) )) or m.conn_key IS NULL OR m.conn_key = '0') THEN 'M' else 'C' end
AS main_connected,
        case_grp
        from
          main_a m
            left join
          advocate_a a ON m.diary_no = a.diary_no
            left join
          master.bar b ON a.advocate_id = b.bar_id
            LEFT JOIN
          master.users u ON u.usercode = m.dacode AND (u.display = 'Y' or u.display is null)
            LEFT JOIN
          master.usersection us ON us.id = u.section
        where
           $condition  $c_status if_sclsc = 1 and (a.display = 'Y' or a.display is null)
            group by m.diary_no,user_section,alloted_to_da,m.reg_no_display,m.pet_name,m.res_name,m.pno,m.rno,m.c_status,m.conn_key,m.case_grp
            order by case_grp";
               // exit();
            /*$query=$this->db->getLastQuery();
            echo (string) $query;//exit();*/
            $queryD = $this->db->query($sqlD);
            $result_arrayD = $queryD->getResultArray();

            $query = $this->db->query($sql);
            $result_array = $query->getResultArray();
            $final_result= array_merge($result_array,$result_arrayD);
            //$final_result= $result_arrayD;
            }else{ //exit();
                $query = $this->db->query($sql);
                $final_result = $query->getResultArray();
            }

            /*$query = $this->Model_Model_main->select("us.section_name AS user_section, u.name AS alloted_to_da,
            CONCAT(m.reg_no_display,' @ ', CONCAT(SUBSTRING(m.diary_no, 1, LENGTH(m.diary_no) - 4),' / ', SUBSTRING(m.diary_no, -4))) AS case_no,
             m.pet_name, m.res_name, m.reg_no_display, m.pno, m.rno, m.c_status")
                ->select('STRING_AGG(DISTINCT b.name, E"\n") AS advocate', false)
                ->select("CASE WHEN m.diary_no = CAST(m.conn_key AS bigint) OR m.conn_key IS NULL OR m.conn_key = '0' THEN 'M' ELSE 'C' END AS main_connected")
                ->select('case_grp')
                ->join('advocate a', 'm.diary_no = a.diary_no', 'left')
                ->join('master.bar b', 'a.advocate_id = b.bar_id', 'left')
                ->join('master.users u', 'u.usercode = m.dacode AND (u.display = "Y" OR u.display IS NULL)', 'left')
                ->join('master.usersection us', 'us.id = u.section', 'left')
                ->where('case_grp', $c_type)
                ->where('m.c_status', $status)
                ->where('if_sclsc', 1)
                ->groupStart()
                ->where('a.display', 'Y')
                ->orWhere('a.display IS NULL')
                ->groupEnd()
                ->groupBy('m.diary_no, us.section_name, u.name, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, m.c_status, main_connected, case_grp')
                ->orderBy('case_grp')
                ->get();*/


            //if($query->getNumRows() >= 1) {
            if(count($final_result) >= 1) {
                //$result_array = $query->getResultArray();
                $data['case_result'] = $final_result;
                $resul_view= view('Reports/SCLSC/get_content_sclsc_report',$data);
                echo '1@@@'.$resul_view;exit();
            }else{
                //echo '3@@@Data not found';exit();
                $data['case_result'] = $final_result;
                $resul_view= view('Reports/SCLSC/get_content_sclsc_report',$data);
                echo '1@@@'.$resul_view;exit();
            }

        }
        exit();

    }

}
