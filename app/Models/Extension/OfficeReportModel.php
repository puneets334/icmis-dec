<?php
namespace App\Models\Extension;

use CodeIgniter\Model;
use Psr\Log\NullLogger;

class OfficeReportModel extends Model
{


    public function __construct()
    {
        parent::__construct();

        $this->db = db_connect();
    }

//   ********************************** REPRINT MODULE FUNCTIONSS - START ***********************************

    public function record_for_table($fromDate, $toDate, $user_code='')
    {

        $builder1 = $this->db->table("office_report_details");
        $builder1->select("web_status,office_report_details.display,office_report_details.diary_no,reg_no_display, 
                  case when case_nature = 'R' then 'Criminal' else 'Civil' end as case_nature ,r_nature,rec_dt, order_dt,office_repot_name, date(rec_dt) as d, summary,
                  office_report_details.office_repot_name");
        $builder1->join('master.office_report_master', 'office_report_details.office_report_id=office_report_master.id', 'left', false);
        $builder1->join("main", "office_report_details.diary_no=main.diary_no", false);
        $builder1->where("date(rec_dt) BETWEEN '$fromDate' AND '$toDate'");
        if(!empty($user_code)) {
            $builder1->where("rec_user_id", $user_code);
        }
        $builder1->orderBy("rec_dt");

        $builder2 = $this->db->table("office_report_details_a as orda");
        $builder2->select("web_status, orda.display, orda.diary_no, reg_no_display, case when case_nature = 'R' then 'Criminal' else 'Civil' end as case_nature, 
        r_nature, rec_dt, order_dt, office_repot_name, date(rec_dt) as d, summary, orda.office_repot_name");
        $builder2->join('master.office_report_master', 'orda.office_report_id=office_report_master.id', 'left', false);
        $builder2->join("main_a", "orda.diary_no=main_a.diary_no", false);
        $builder2->where("date(rec_dt) BETWEEN '$fromDate' AND '$toDate'");
        if(!empty($user_code)) {
            $builder2->where("rec_user_id", $user_code);
        }
        $builder2->orderBy("rec_dt");

        $final_query = $builder1->union($builder2);
        $query = $final_query->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if($result)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function discard_data($diary_no, $rec_date)
    {
        $columnData = array(
            'display' => 'N',
            'discarded_by' => session()->get('login')['usercode'],
            'user_ip' => session()->get('login')['usercode'],
            'discarded_date' => 'NOW()',
            'updated_on' => date('Y-m-d H:i:s'),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        );

        $builder = $this->db->table('office_report_details');
        $builder->where('diary_no', $diary_no)->where('date(rec_dt)',$rec_date);
        $query = $builder->update($columnData);
            if($query) {
                return 1;
            }else
            {
                return 0;
            }
    }

//   ********************************** REPRINT MODULE FUNCTIONSS - END ***********************************





//   ********************************** REPORT MODULE FUNCTIONSS - START ***********************************


     public function nature_from_cassetype()
     {
         $builder = $this->db->table("master.casetype");
         $builder->distinct();
         $builder->select('nature');
         $builder->where("display",'Y');
         $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
         $result = $query->getResultArray();

         if($result)
         {
             return $result;
         }else{
             return 0;
         }


     }

    public function check_report_type($type)
    {
        $builder = $this->db->table("master.office_report_master ");
        $builder->select('id,r_nature');
        $builder->where("case_nature",$type);
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if($result)
        {
            return $result;
        }else{
            return 0;
        }


    }

    public function check_section($ucode)
    {
        $builder = $this->db->table("master.users ");
        $builder->select('section');
        $builder->where("usercode",$ucode);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if($result)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function check_heardt($diary_no)
    {
        $builder = $this->db->table("heardt");
        $builder->select('next_dt');
        $builder->where("diary_no",$diary_no);
        $builder->where("next_dt >= '2023-10-01'");           // ***************************** TO BE CHANGED TO  $builder->where("next_dt >= CURRENT_DATE"); LATER ON ***************************
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if($result)
        {
            return $result;
        }else{
            return 0;
        }

    }



    public function check_office_report($diary_no,$nxtdt)
    {

        $builder = $this->db->table("office_report_details");
        $builder->select('count(id) as id');
        $builder->where("diary_no",$diary_no);
        $builder->where("order_dt",$nxtdt);
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if($result)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function check_for_connected_case($diary_no)
    {

        $builder = $this->db->table("main");
        $builder->select('conn_key');
        $builder->where("diary_no",$diary_no);
        $builder->where("conn_key is not null and conn_key!=''");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }
    }



    public function check_report_already_generated($diary_no,$r_chk_pnt)
    {

        $builder = $this->db->table("office_report_details");
        $builder->select('office_repot_name,office_report_id, summary');
        $builder->where("diary_no",$diary_no);
        $builder->where("order_dt='$r_chk_pnt' and display='Y'");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }
    }

    public function check_cases($r_connected_cases)
    {

        $builder = $this->db->table("conct");
        $builder->select('diary_no');
        $builder->where("conn_key",$r_connected_cases);
        $builder->where("diary_no!='$r_connected_cases'");
        $builder->orderBy("substr( diary_no::text , -4 ),substr( diary_no::text, 1, char_length( diary_no::text ) -4 )");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }
    }

    public function get_batch_officereportdetails($diary_no,$nextdt)
    {

        $builder = $this->db->table("office_report_details");
        $builder->select('batch');
        $builder->where("diary_no",$diary_no);
        $builder->where("order_dt",$nextdt);
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function get_dno_officereportdetails($ex_connected_cases,$nextdt,$r_get_batch,$res_max_o_r)
    {
//        echo $res_max_o_r.">>";die;

        $builder = $this->db->table("office_report_details");
        $builder->select('diary_no');
        $builder->where("diary_no",$ex_connected_cases);
        $builder->where("order_dt",$nextdt);
        $builder->where("display",'Y');
        $builder->where("office_report_id",$res_max_o_r);
        $builder->where("batch",$r_get_batch);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function filing_details($diary_no)
    {
        $builder = $this->db->table("main a");
        $builder->select('case when (active_casetype_id is null or active_casetype_id =0) then casetype_id else active_casetype_id end as casetype_id,
        active_fil_no as fil_no,short_description,casename,fil_dt,pet_name,res_name,pet_adv_id,lastorder');
        $builder->join('master.casetype b', 'a.casetype_id=b.casecode');
        $builder->where("diary_no",$diary_no);
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function check_listed($diary_no)
    {


        $builder = $this->db->table("heardt");
        $builder->select('tentative_cl_dt');
        $builder->where("diary_no",$diary_no);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function check_ofc_report_detail_publish($diary_no,$ddl_ord_date)
    {

        $builder = $this->db->table("office_report_details");
        $builder->select('count(id) as id');
        $builder->where("diary_no",'325672023');
        $builder->where("web_status",'0');
        $builder->where("order_dt",'2023-10-09');
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if($result)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function upload_office_report_publish($diary_no,$ddl_ord_date)
    {

        $columnData = array(
            'web_status' => '1',
            'updated_on' => date('Y-m-d H:i:s'),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        );

        $builder = $this->db->table('office_report_details');
        $builder->where('diary_no', $diary_no)->where('order_dt',$ddl_ord_date)->where('display', 'Y');
        $query = $builder->update($columnData);
        if($query) {
            return 1;
        }else
        {
            return 0;
        }
    }

    public function get_cause_list_detail_check($diary_no)
    {

        $builder = $this->db->table("heardt");
        $builder->select('brd_slno,roster_id');
        $builder->where("diary_no",$diary_no);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function get_court_no($roster_id)
    {

        $builder = $this->db->table("master.roster");
        $builder->select('courtno');
        $builder->where("id",$roster_id);
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result[0];
        }else{
            return 0;
        }

    }

    public function check_defect($diary_no)
    {

        $builder = $this->db->table("obj_save");
        $builder->select('count(id) as count_id');
        $builder->where("diary_no",$diary_no);
        $builder->where("display",'Y');
        $builder->where("rm_dt is null");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if(!empty($result[0]))
        {
            return $result[0];
        }else{

            return 0;
        }

    }

    public function check_refiling($diary_no)
    {

        $builder = $this->db->table("obj_save");
        $builder->select('date(max(rm_dt)) rm_dt ,date(min(save_dt)) save_dt');
        $builder->where("diary_no",$diary_no);
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if(!empty($result[0]))
        {
            return $result;
        }else{

            return 0;
        }

    }

    public function get_holiday_for_court($checkDate)
    {
        $builder = $this->db->table('master.sc_working_days');
        $builder->select('working_date');
        $builder->where('working_date',$checkDate)->where('is_holiday',1);
        $query = $builder->get();
        $result = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//        print_r($result);
//        die;
//        if($result[0])
//        {
//            return 1;
//        }else{
//            return 0;
//        }
        return $result;
    }

    public function check_date_diff($date1, $ans)
    {
        $sql =" SELECT DATE_PART('day', '".$date1."'::timestamp - '".$ans."'::timestamp) as days";
//        $sql = "select
//                 case
//                 when $date1 IS NULL or $ans IS NULL then 0
//                  else DATE_PART('day', '".$date1."'::timestamp - '".$ans."'::timestamp)
//                   end as days";
//        SELECT DATE_PART('day', COALESCE('$date1'::timestamp, CURRENT_TIMESTAMP) - COALESCE('$ans'::timestamp, CURRENT_TIMESTAMP)) as days
        $query = $this->db->query($sql);
//       $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
//        var_dump($result);die;
        if($result)
        {
            return $result;
        }else{
            return [];
        }

    }

    public function check_for_docdetails($diary_no)
    {

        $builder = $this->db->table("docdetails a");
        $builder->select('b.docdesc,docnum,docyear,verified,ent_dt,other1');
        $builder->join('master.docmaster b', 'a.doccode=b.doccode and a.doccode1=b.doccode1');
        $builder->where("a.display='Y' and b.display='Y'");
        $builder->where("diary_no",$diary_no);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function check_caveat_info($diary_no)
    {

        $sql =" select concat(name, ' ',coalesce(concat(p.pet_res,' [',p.sr_no_show,']'),'')) as name ,c.caveat_no, 
            to_char(d.ent_dt,'dd-mm-yyyy') as rec_dt  from caveat_diary_matching cd left join caveat c on cd.caveat_no=c.caveat_no 
            join master.bar on c.pet_adv_id = bar.bar_id left join docdetails d on cd.diary_no=d.diary_no and doccode=18 and d.display='Y' 
            left join caveat_party cp on cd.caveat_no=cp.caveat_no and cp.pet_res='P' left join party p on cd.diary_no=p.diary_no  
            and p.partyname like concat('%',cp.partysuff,'%') where c_status='P' and  cd.diary_no=$diary_no";

        $query = $this->db->query($sql);
//       $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
//        var_dump($result);die;
        if($result)
        {
            return $result;
        }else{
            return 0;
        }

//        $builder = $this->db->table("caveat_diary_matching");
//        $builder->select('(name),caveat.caveat_no');
//        $builder->join('caveat', 'caveat_diary_matching.caveat_no=caveat.caveat_no','left');
//        $builder->join('master.bar', 'caveat.pet_adv_id = bar.bar_id','left');
//        $builder->where("c_status",'P');
//        $builder->where("diary_no",$diary_no);
//        $query = $builder->get();
////        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
//        $result = $query->getResultArray();
////        echo "<pre>";
////        print_r($result);die;
//        if($query->getNumRows()>0)
//        {
//            return $result;
//        }else{
//            return 0;
//        }

    }

    public function check_pof($diary_no)
    {

        $builder = $this->db->table("docdetails");
        $builder->select("TO_CHAR(ent_dt, 'DD-MM-YYYY') AS rec_dt ");
        $builder->where("doccode=18 and display='Y'");
        $builder->where("diary_no",$diary_no);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function check_partystuff($caveatno)
    {

        $builder = $this->db->table("caveat_party");
        $builder->select("partysuff");
        $builder->where("caveat_no", $caveatno);
        $builder->where("pet_res",'P');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }
    public function check_party($diary_no,$partyname)
    {
        $sql ="select  concat(pet_res,' [',sr_no_show,']') from party where diary_no=$diary_no and partyname like '%$partyname%'";

        $query = $this->db->query($sql);
//       $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);
//        var_dump($result);die;
        if(!empty($result[0]['concat']))
        {
            return $result[0];
        }else{
            return [];
        }

    }
    public function check_amt_query($diary_no)
    {

        $builder = $this->db->table("main m");
        $builder->select('claim_amt');
        $builder->join('mul_category c', 'm.diary_no=c.diary_no');
        $builder->join('master.submaster s', 'c.submaster_id=s.id');
        $builder->where("c.display",'Y');
        $builder->where("s.subcode1 in(3,4) and s.flag='s' and s.flag_use='S' and s.display='Y' and m.diary_no='$diary_no'");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function checked_linked_con_case($diary_no)
    {
        $builder = $this->db->table("conct");
        $builder->select('conn_key');
        $builder->where("diary_no",$diary_no);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{
            return 0;
        }

    }


    public function linked_case($res_linked_case)
    {

        $sql =" Select pet_name,res_name,pno,rno,c_status,active_fil_no, date_part('year',active_fil_dt) active_fil_dt,short_description from main a 
join master.casetype b on 
(case when (a.active_casetype_id is null or a.active_casetype_id=0) then a.casetype_id
else a.active_casetype_id end ) =b.casecode 
where diary_no=$res_linked_case and b.display='Y'";

        $query = $this->db->query($sql);
//       $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
//        var_dump($result);die;
        if($result)
        {
            return $result;
        }else{
            return [];
        }

    }

    public function check_listed_in_court($diary_no)
    {



        $builder1 = $this->db->table('heardt');
        $builder1->select('diary_no')->where("(main_supp_flag=1 or main_supp_flag=2 or main_supp_flag=0) and diary_no=$diary_no");

        $builder2   = $this->db->table('last_heardt');
        $builder2->select('diary_no')->where("(main_supp_flag=1 or main_supp_flag=2 or main_supp_flag=0) and (bench_flag is null or bench_flag='') and diary_no=$diary_no");

        $final_query = $builder1->union($builder2);
        $query = $final_query->get();

//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return 1;
        }else{
            return 0;
        }



    }

    public function check_report_table_for_status($diary_no)
    {

        $builder = $this->db->table("office_report_details");
        $builder->select('count(id) as id');
        $builder->where("diary_no",$diary_no);
        $builder->where("status",'1');
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if($result[0]['id'] > 0)
        {
            return 1;
        }else{
//            echo "FF";die;
            return 0;
        }

    }


    public function detail_from_main($diary_no)
    {

        $builder = $this->db->table("main");
        $builder->select('fil_no,casetype_id,fil_dt,pet_name,res_name,padvt,pno,rno');
        $builder->where("diary_no",$diary_no);
        $builder->where("c_status",'P');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if(!empty($result[0]))
        {
            return $result;
        }else{
//            echo "FF";die;
            return 0;
        }

    }

    public function check_casetype($casetypeid)
    {

        $builder = $this->db->table("master.casetype");
        $builder->select('short_description');
        $builder->where("casecode",$casetypeid);
        $builder->where("display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if(!empty($result[0]))
        {
            return $result;
        }else{
//            echo "FF";die;
            return 0;
        }

    }

    public function check_process_id($year)
    {

        $builder = $this->db->table("master.tw_max_process");
        $builder->select('office_report');
        $builder->where("year",$year);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if(!empty($result[0]))
        {
            return $result[0]['office_report'];
        }else{
//            echo "FF";die;
            return 0;
        }

    }

    public function check_limitation_period_leave($diary_no)
    {

        $builder = $this->db->table('lowerct a');
        $builder->select(" lct_dec_dt, l_dist, ct_code, l_state, b.name, 
                           CASE 
                           WHEN ct_code = 3 THEN (SELECT s.name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y')
                           ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f')
                           END AS agency_name, 
                           lct_casetype, lct_caseno, lct_caseyear, 
                           CASE 
                           WHEN ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype)
                           ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y')
                           END AS type_sname, 
                           a.lower_court_id ");
        $builder->join('master.state b', "a.l_state = b.id_no AND b.display = 'Y'", 'left');
        $builder->join('main e', 'e.diary_no = a.diary_no');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('lw_display', 'Y');
        $builder->where('c_status', 'P');
        $builder->where('is_order_challenged', 'Y');
        $builder->orderBy('a.lower_court_id');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if(!empty($result[0]))
        {
            return $result;
        }else{
//            echo "FF";die;
            return 0;
        }


       

    }

    public function get_reg_no_display_main($connectedId)
    {

        $builder = $this->db->table("main");
        $builder->select('reg_no_display');
        $builder->where("diary_no",$connectedId);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if($query->getNumRows()>0)
        {
            return $result;
        }else{
//            echo "FF";die;
            return 0;
        }

    }

    public function check_limitation_period_petition($petition_data,$diary_no)
    {

        $builder = $this->db->table("case_limit");
        $builder->select('limit_days');
        $builder->where("diary_no",$diary_no);
        $builder->where("o_d",$petition_data);
        $builder->where("case_lim_display",'Y');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if(!empty($result[0]))
        {
            return $result[0];
        }else{
//            echo "FF";die;
            return 0;
        }

    }




    function get_new_name($dairy_no,$sp_listed_on){
        $dno=$dairy_no;
        // $sql_ordet="select office_repot_name from office_report_details where diary_no='$dairy_no' and order_dt='$sp_listed_on' and display='Y'";
        // $rs_ordet=mysql_query($sql_ordet);
        $builder = $this->db->table("office_report_details");
        $builder->select('office_repot_name');
        $builder->where("diary_no",$dairy_no);
        $builder->where("order_dt", $sp_listed_on);
        $builder->where("display",'Y');
        $query = $builder->get();
        $rs_ordet = $query->getResultArray();

        if(!empty($rs_ordet)){
            foreach ($rs_ordet as $rw_ordet) {
                $orname=$rw_ordet['office_repot_name'];
                $a=explode('.',$orname);               //30015_2018_2018-09-11_825.html
                $name1=$a[0];
                $extension=$a[1];
                $c= substr_count($name1,"@");
                if($c==0){
                    $sno=1;
                    $nm=$name1;
                }else{
                    $s=explode('@',$name1);
                    $sno = $s[1] +1;
                    $nm=$s[0];
                }
                $new_file_name = $nm.'@'.$sno.'.'.$extension;
            }
            return $new_file_name;
        }else{
            return false;
        }

    }


    function save_revised_records($year,$diary_no,$sp_listed_on,$hd_or_id,$ggg,$str,$dairy_no,$ucode,$ddl_rt,$batch,$new_file_name, $summary=null){



        $parent = 'officereports';
        if (!file_exists($parent)) {
            mkdir($parent, 0755, true);
        }
        chdir($parent);

        if (!file_exists($year)) {
            mkdir($year, 0755, true);
        }
        chdir($year);

        if (!file_exists($diary_no)) {
            mkdir($diary_no, 0755, true);
        }
        chdir($diary_no);
        //echo " the present directory is ".getcwd() ;

        $fil_nm= $new_file_name;
        $fil_nm = str_replace(' ', '', $fil_nm);
        //echo " the file name is =".$fil_nm;
        $gh=fopen($fil_nm, 'w');
        $fwrite=fwrite($gh, $ggg) or die ("unable to write in the file ");
        fclose($gh);
        if($fwrite===false){
            echo "Error in saving";
        }
        if($fwrite==true){
            // echo "<script>alert('Revised Office Report saved successfully')</script>";

            // $update_or_det="update office_report_details set display='N' where diary_no='$dairy_no'  and display='Y' and order_dt='$sp_listed_on' ";
            // $rs_update_or_det=mysql_query($update_or_det) or die("Error: ".LINE.mysql_error());

            $builder5 = $this->db->table("office_report_details");
            $builder5->set('display', 'N' );
            $builder5->where('diary_no', $dairy_no);
            $builder5->where('display', 'Y' );
            $builder5->where('order_dt', $sp_listed_on);
            $rs_update_or_det =$builder5->update();

            // $sel_rec="Select count(id) from office_report_details where diary_no='$dairy_no' and order_dt='$sp_listed_on' and display='Y' and status='$str'";
            // $sel_rec=mysql_query($sel_rec) or die("Error: ".LINE.mysql_error());
            // $res_sel=mysql_result($sel_rec,0);
            $builder = $this->db->table("office_report_details");
            $builder->select('count(id)');
            $builder->where("diary_no",$dairy_no);
            $builder->where("order_dt", $sp_listed_on);
            $builder->where("display",'Y');
            $builder->where("status", $str);
            $query = $builder->get();
            $res_sel = $query->getResultArray();
            if(!empty($res_sel)){
                $res_sel = $res_sel[0]['count'];
                if($res_sel <= 0){
                    // $up_off_id="Update tw_max_process set office_report='$hd_or_id' where year='$year'";
                    // $up_off_id=mysql_query($up_off_id) or die("Error: ".LINE.mysql_error());
                    $builder6 = $this->db->table("master.tw_max_process");
                    $builder6->set('office_report', $hd_or_id );
                    $builder6->where('year', $year);
                    $up_off_id =$builder6->update();

                    $web_status='';
                    $web_status_val='';

                    if($str=='1' || $str=='2'){
                        $web_status="web_status";
                        $web_status_val= '1';
                    }

                    if(file_exists($fil_nm)){
                        // $ins_rec="Insert Into office_report_details (diary_no,office_report_id,rec_dt,rec_user_id,status,office_repot_name,order_dt,master_id,batch $web_status, summary)
                        // values ('$dairy_no','$hd_or_id',now(),'$ucode','$str','$new_file_name','$sp_listed_on','$ddl_rt','$batch' $web_status_val, '".mysql_real_escape_string($summary)."')";
                        // if(!mysql_query($ins_rec)){
                        //     die("Error: ".LINE.mysql_error());
                        // }

                        $insertData = [
                            'diary_no' => $dairy_no,
                            'office_report_id' => $hd_or_id,
                            'rec_dt' => 'NOW()',
                            'rec_user_id' => $ucode,
                            'status' => $str,
                            'office_repot_name' => $new_file_name,
                            'order_dt' => $sp_listed_on,

                            'master_id' => $ddl_rt,
                            'batch' => $batch,
                            $web_status =>  $web_status_val,
                            'summary' => $summary
                        ];
                        $builder9 = $this->db->table("office_report_details");
                        $builder9->insert($insertData);
                    }
                }
            }

            return  "Revised Office Report saved successfully";
        }

    }

    function save_records($year,$diary_no,$sp_listed_on,$hd_or_id,$ggg,$str,$dairy_no,$ucode,$ddl_rt,$batch, $summary=null){
        // $master_to_path = '/home/';
        // chdir($master_to_path);




        $parent = 'officereports';
        if (!file_exists($parent)) {
            mkdir($parent, 0755, true);
        }
        chdir($parent);

        if (!file_exists($year)) {
            mkdir($year, 0755, true);
        }
        chdir($year);

        if (!file_exists($diary_no)) {
            mkdir($diary_no, 0755, true);
        }
        chdir($diary_no);
        // echo " the present directory is ".getcwd() ; die;

        $fil_nm = '';
        if($hd_or_id=='0'){

            // $sql_ordet="select office_repot_name from office_report_details where diary_no='$dairy_no' and order_dt='$sp_listed_on' and display='Y' and status='$str'";
            // $rs_ordet=mysql_query($sql_ordet);
            $builder = $this->db->table("office_report_details");
            $builder->select('office_repot_name');
            $builder->where("diary_no",$dairy_no);
            $builder->where("order_dt", $sp_listed_on);
            $builder->where("display",'Y');
            $builder->where("status", $str);

            // $queryString = $builder->getCompiledSelect();
            // echo $queryString;
            // exit();

            $query = $builder->get();
            $rs_ordet = $query->getResultArray();
            // while($rw_ordet=mysql_fetch_array($rs_ordet)){
            //     $fil_nm=$rw_ordet['office_repot_name'];
            // }
            if(!empty($rs_ordet)){
                foreach ($rs_ordet as $rw_ordet) {
                    $fil_nm .= $rw_ordet['office_repot_name'];
                }
            }
        }else{
            $fil_nm .= $diary_no.'_'.$year.'_'.$sp_listed_on.'_'.$hd_or_id.'.html';
        }
        // echo $fil_nm; die;
        $fil_nm = str_replace(' ', '', $fil_nm);
        $gh=fopen($fil_nm, 'w');
        $fwrite=fwrite($gh, $ggg);
        fclose($gh);
        if($fwrite===false){
            echo "Error in saving";
        }else{
            // $sel_rec="Select count(id) from office_report_details where diary_no='$dairy_no' and order_dt='$sp_listed_on' and display='Y' and status='$str'";
            // $sel_rec=mysql_query($sel_rec) or die("Error: ".LINE.mysql_error());
            // $res_sel=mysql_result($sel_rec,0);

            $chk_status = '';

            $builder1 = $this->db->table("office_report_details");
            $builder1->select('count(id)');
            $builder1->where("diary_no",$dairy_no);
            $builder1->where("order_dt", $sp_listed_on);
            $builder1->where("display",'Y');
            $builder1->where("status", $str);
            $query1 = $builder1->get();
            $res_sel = $query1->getResultArray();
            // echo "<pre>"; print_r($res_sel); die;
            if(!empty($res_sel)){
                $res_sel = $res_sel[0]['count'];
                if($res_sel <= 0){
                    // $up_off_id="Update tw_max_process set office_report='$hd_or_id' where year='$year'";
                    // $up_off_id=mysql_query($up_off_id) or die("Error: ".LINE.mysql_error());
                    $builder6 = $this->db->table("master.tw_max_process");
                    $builder6->set('office_report', $hd_or_id );
                    $builder6->where('year', $year);
                    $up_off_id =$builder6->update();

                    $web_status='';
                    $web_status_val='';
                    if($str=='1' || $str=='2'){
                        $web_status = "web_status";
                        $web_status_val = '1';
                    }
                    if(file_exists($fil_nm)){
                        // $ins_rec="Insert Into office_report_details (diary_no,office_report_id,rec_dt,rec_user_id,status,office_repot_name,order_dt,master_id,batch $web_status, summary)
                        // values ('$dairy_no','$hd_or_id',now(),'$ucode','$str','$fil_nm','$sp_listed_on','$ddl_rt','$batch' $web_status_val, '".mysql_real_escape_string($summary)."')";
                        // if(!mysql_query($ins_rec)){
                        //     die("Error: ".LINE.mysql_error());
                        // }else {
                        //     $chk_status=1;
                        // }

                        $insertData = [
                            'diary_no' => $dairy_no,
                            'office_report_id' => $hd_or_id,
                            'rec_dt' => 'NOW()',
                            'rec_user_id' => $ucode,
                            'status' => $str,
                            'office_repot_name' => $fil_nm,
                            'order_dt' => $sp_listed_on,
                            'master_id' => $ddl_rt,
                            'batch' => $batch,
                            $web_status =>  $web_status_val,
                            'summary' => $summary
                        ];
                        $builder9 = $this->db->table("office_report_details");
                        $ins_rec = $builder9->insert($insertData);
                        if($ins_rec){
                            $chk_status .= '1';
                        }
                    }
                }else{
                    // $ins_rec="Update office_report_details set rec_dt=now(),rec_user_id='$ucode',office_repot_name='$fil_nm',master_id='$ddl_rt',batch='$batch',
                    //                  summary='".mysql_real_escape_string($summary)."' where diary_no='$dairy_no' and order_dt='$sp_listed_on' and status='$str' and display='Y' and office_report_id='$hd_or_id' ";
                    // if(!mysql_query($ins_rec)){
                    //     die("Error: ".LINE.mysql_error());
                    // }else{
                    //     $chk_status=2;
                    // }

                    $builder6 = $this->db->table("office_report_details");
                    $builder6->set('rec_dt', 'NOW()' );
                    $builder6->set('rec_user_id', $ucode );
                    $builder6->set('office_repot_name', $fil_nm );
                    $builder6->set('master_id', $ddl_rt );
                    $builder6->set('batch', $batch );
                    $builder6->set('summary', $summary );
                    $builder6->where("diary_no",$dairy_no);
                    $builder6->where("order_dt", $sp_listed_on);
                    $builder6->where("display",'Y');

                    $builder6->where("status", $str);
                    $builder6->where('office_report_id', $hd_or_id);
                    // $queryString = $builder6->getCompiledUpdate();
                    // echo $queryString;
                    // exit();
                    if($builder6->update()){
                        $chk_status .= '2';
                    }

                }
            }

            return $chk_status;

        }

        //   <input type="hidden" name="hd_chk_status" id="hd_chk_status" value="<?php echo $chk_status;

    }


    public function save_office_report($dataArr){


        $dataMsg = [];

        // $str=$dataArr['str'];
        $str = '1';
        $summary=$dataArr['summary'];
        $ucode=$_SESSION['login']['usercode'];
        $dairy_no=$dataArr['d_no'].$dataArr['d_yr'];
        $batch=$dairy_no;
        $hd_or_id=$dataArr['hd_or_id'];
        $sp_listed_on=date('Y-m-d',strtotime($dataArr['hd_next_dt']));
        $c_date=date('Y-m-d-H-i-s');
        $year=date('Y');
        $year = $dataArr['d_yr'];
        $diary_no = $dataArr['d_no'];

        $ddl_rt=$dataArr['ddl_rt'];
        $ggg=urldecode($dataArr['ggg']);


        // $sql_previous_id1="select id,rec_dt from office_report_details where display='Y' and  diary_no='$dairy_no' and order_dt='$sp_listed_on' and status='$str'";
        // $rs_previous_id1=mysql_query($sql_previous_id1);

        $builder = $this->db->table("office_report_details");
        $builder->select('id,rec_dt');
        $builder->where("diary_no",$dairy_no);
        $builder->where("status", $str);
        $builder->where("order_dt", $sp_listed_on);
        $builder->where("display",'Y');
        $query = $builder->get();
        $rs_previous_id1 = $query->getResultArray();

        if(empty($rs_previous_id1)){
            // $dataMsg = ['msg' => "no record found hence fresh file to be created"];
            $chk_status = $this->save_records($year, $diary_no, $sp_listed_on, $hd_or_id, $ggg, $str, $dairy_no, $ucode, $ddl_rt, $batch, $summary);
            $dataMsg = ['msg' => $chk_status];
        }else{
            // while($rs_data=mysql_fetch_array($rs_previous_id1)){
            //     $or_date=$rs_data['rec_dt'];
            // }
            $or_date = '';
            foreach ($rs_previous_id1 as $rs_data) {
                $or_date .= $rs_data['rec_dt'];
            }

            // $sql_docdetails="select  max(ent_dt) as res from docdetails where diary_no='$dairy_no' and display='Y'";
            // $rs_docdetails=mysql_query($sql_docdetails);

            $builder1 = $this->db->table("docdetails");
            $builder1->select('max(ent_dt) as res');
            $builder1->where("diary_no",$dairy_no);
            $builder1->where("display",'Y');
            $query1 = $builder1->get();
            $rs_docdetails = $query1->getResultArray();

            // while($rw_ia_detail=mysql_fetch_array($rs_docdetails)){
            //     $iaa_date=$rw_ia_detail['res'];
            // }
            $iaa_date = '';
            foreach ($rs_docdetails as $rw_ia_detail) {
                $iaa_date .= $rw_ia_detail['res'];
            }

            if($iaa_date  < $or_date){
                $chk_status = $this->save_records($year, $diary_no, $sp_listed_on, $hd_or_id, $ggg, $str, $dairy_no, $ucode, $ddl_rt, $batch, $summary);
                $dataMsg = ['msg' => $chk_status];
            }else{
                // echo "revised or to be generated";
                $x = $this->get_new_name($dairy_no,$sp_listed_on);
                // echo  " the new file name is ".$x;
                $dataRevis = $this->save_revised_records($year, $diary_no, $sp_listed_on, $hd_or_id, $ggg, $str, $dairy_no, $ucode, $ddl_rt, $batch, $x, $summary);

                $dataMsg = ['msg' => $dataRevis];
            }
        }

        return json_encode($dataMsg);


    }








//   ********************************** REPORT MODULE FUNCTIONSS - END ***********************************


// *************************************************************** C_CURATIVE_OFFICEREPORT  -START ********************************************************


    public function getcasetype($diary_no)
    {

        $builder = $this->db->table("main");
        $builder->select("case when (active_casetype_id is null or active_casetype_id =0) then casetype_id else active_casetype_id end as casetypeid");
        $builder->where("diary_no",$diary_no);

        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if(!empty($result[0]['casetypeid']))
        {
            return $result;
        }else{
//            echo "FF";die;
            return 0;
        }

    }


    public function check_casetype_lower_court($diary_no)
    {

        $builder = $this->db->table("main");
        $builder->select("active_casetype_id");
        $builder->where("diary_no",$diary_no);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if(!empty($result[0]['active_casetype_id']))
        {
            return $result;
        }else{

            return 0;
        }

    }

    public function check_lower_court($condition, $diary_no)
    {

        $builder = $this->db->table("lowerct a");
        $builder->select("lct_dec_dt, l_dist, ct_code, l_state, b.name,
 case when ct_code =3 then ( SELECT s.name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y' )
 else ( SELECT concat(agency_name,', ',address) agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f' ) end as agency_name,
 crimeno, crimeyear, polstncode,
 ( SELECT policestndesc FROM master.police p WHERE p.policestncd = a.polstncode AND p.display = 'Y' AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist )policestndesc,
 lct_casetype, lct_caseno, lct_caseyear,
 case when ct_code =4 then ( SELECT short_description FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype )
 else ( SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y' ) end as type_sname,
 a.lower_court_id, is_order_challenged, full_interim_flag,
 judgement_covered_in,lct_judge_desg ");
        $builder->join("master.state b","a.l_state = b.id_no AND b.display = 'Y'","left");
        $builder->join("main e","e.diary_no = a.diary_no");
        $builder->where("a.diary_no",$diary_no);
        if($condition != '')
        {
            $builder->where("$condition");
        }$builder->orderBy("a.lower_court_id");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if(!empty($result[0]))
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function get_petitioner_advocate_cur($diary_no)
    {

        $builder = $this->db->table("advocate a");
        $builder->select("title,name");
        $builder->join("master.bar b", "a.advocate_id=b.bar_id");
        $builder->where("diary_no",$diary_no);
        $builder->where("display","Y");
        $builder->where("pet_res","P");
        $builder->where("adv_type","M");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if(!empty($result[0]))
        {
            return $result;
        }else{

            return 0;
        }

    }
    public function get_misc_re_ofcreport($diary_no)
    {
        $reg_no='';
         $builder = $this->db->table("main_casetype_history a");
        $builder->select("new_registration_number, new_registration_year, short_description,casename,order_date");
        $builder->join("master.casetype b", "(substr( a.new_registration_number, 1, 2 )::integer) = b.casecode");
        $builder->where("diary_no",$diary_no);
        $builder->where("a.is_deleted = 'f' AND b.display = 'Y' AND cs_m_f = 'M'");
        $builder->orderBy("a.updated_on","DESC");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{

            return 0;
        }

    }


    public function get_diary_case_type_ofcreport($ct,$cn,$cy)
    {
//        echo $ct.">>".$cn.">>".$cy;die;

        if($ct != ''){
//            $get_dno = "SELECT substr( diary_no, 1, length( diary_no ) -4 ) as dn, substr( diary_no , -4 ) as dy
//    FROM main
//    WHERE (SUBSTRING_INDEX(fil_no, '-', 1) = $ct AND CAST($cn AS UNSIGNED)
//    BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2),'-',-1))
//    AND (SUBSTRING_INDEX(fil_no, '-', -1)) AND  if((reg_year_mh=0 OR DATE(fil_dt)>DATE('2017-05-10')), YEAR(fil_dt)=$cy, reg_year_mh=$cy) ) or (SUBSTRING_INDEX(fil_no_fh, '-', 1) = $ct
//    AND CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2),'-',-1))
//    AND (SUBSTRING_INDEX(fil_no_fh, '-', -1)) AND if(reg_year_fh=0, YEAR(fil_dt_fh)=$cy, reg_year_fh=$cy))";
////$get_dno = "SELECT substr( diary_no, 1, length( diary_no ) -4 ) as dn, substr( diary_no , -4 ) as dy FROM registered_cases WHERE casetype_id=$ct AND case_no=$cn AND case_year=$cy AND display='Y'";
//            $get_dno = mysql_query($get_dno) or die(__LINE__.'->'.mysql_error());
//            if(mysql_affected_rows()>0){
//                $get_dno = mysql_fetch_array($get_dno);
////    $_REQUEST['d_no'] = $get_dno['dn'];
////    $_REQUEST['d_yr'] = $get_dno['dy'];
//                return $get_dno['dn'].$get_dno['dy'];
//            }
//            else
//            {
//
//                $get_dno ="SELECT
//SUBSTR( h.diary_no, 1, LENGTH( h.diary_no ) -4 ) AS dn,
//SUBSTR( h.diary_no , -4 ) AS dy,
//if(h.new_registration_number!='',SUBSTRING_INDEX(h.new_registration_number, '-', 1),'') as ct1,
//            if(h.new_registration_number!='',SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1 ),'') as crf1,
//            if(h.new_registration_number!='',SUBSTRING_INDEX(h.new_registration_number, '-', -1),'') as crl1 FROM
// main_casetype_history h
//WHERE
//((SUBSTRING_INDEX(h.new_registration_number, '-', 1) = $ct AND
//CAST($cn AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2),'-',-1)) AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1)) AND h.new_registration_year=$cy) OR
//  (
//    SUBSTRING_INDEX(h.old_registration_number, '-', 1) = $ct
//    AND CAST($cn AS UNSIGNED) BETWEEN (
//      SUBSTRING_INDEX(
//        SUBSTRING_INDEX(h.old_registration_number, '-', 2),
//        '-',
//        - 1
//      )
//    )
//    AND (
//      SUBSTRING_INDEX(
//        h.old_registration_number,
//        '-',
//        - 1
//      )
//    )
//    AND h.old_registration_year = $cy
//  )) AND h.is_deleted='f'";
//
//
//
//                $get_dno = mysql_query($get_dno) or die(__LINE__.'->'.mysql_error());
//                if(mysql_affected_rows()>0){
//                    $get_dno = mysql_fetch_array($get_dno);
//                    // print_r($get_dno);die;
//                    return $get_dno['dn'].$get_dno['dy'];
//                }
//            }
        }else{
            return 0;
        }


    }

    public function get_dismissal_type($diary_no)
    {

        $builder = $this->db->table("dispose a");
        $builder->select("disp_type,dispname");
        $builder->join("master.disposal b", "a.disp_type=b.dispcode");
        $builder->where("display",'Y' );
        if(!empty($diary_no))
        {
            $builder->where("diary_no",$diary_no);
        }else{
            $builder->where("diary_no",0);
        }
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows()>0)
        {
            return $result;
        }else{

            return 0;
        }
    }

    function send_to_advocate_z($dno) {
        $o_array = array();
        // $send_to = "SELECT title,name,caddress, ccity FROM advocate a join bar b on a.advocate_id=b.bar_id
        //     WHERE diary_no='$str' and display='Y'";
        // $send_to = mysql_query($send_to) or die("Error : " . LINE . mysql_error());
        $builder = $this->db->table("advocate a");
        $builder->select("title,name,caddress, ccity");
        $builder->join('master.bar b', 'a.advocate_id=b.bar_id', 'left');
        $builder->where("diary_no",$dno);
        $builder->where("display", 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        $sno = 0;
        if(!empty($result)){
            foreach ($result as $res_send_to) {
                $c_array = array();
                $c_array[0] = $res_send_to['title'];
                $c_array[1] = $res_send_to['name'];
                $c_array[2] = $res_send_to['caddress'];
                $c_array[3] = $res_send_to['ccity'];
                $o_array[] = $c_array;
                $sno++;
            }
        }

        return $o_array;
    }



    function get_application_registration($dairy_no) {
        $outer_array = array();
        // $sql = "Select docdesc,other1,docnum,docyear from docdetails a join docmaster b on a.doccode=b.doccode and
        //       a.doccode1=b.doccode1 where a.display='Y' and b.display='Y' and diary_no='$dairy_no'
        //       and iastat='P' and a.doccode='8'";
        // $sql = mysql_query($sql)or die("Error: " . __LINE__ . mysql_error());
        $builder = $this->db->table('docdetails a');
        $builder->select('docdesc, other1, docnum, docyear');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('diary_no', $dairy_no);
        $builder->where('iastat', 'P');
        $builder->where('a.doccode', '8');
        $query = $builder->get();
        $result = $query->getResultArray();
        $outer_array = array();
        $docname = '';
        if(!empty($result)){
            foreach ($result as $row) {
                $inner_array = array();
                if ($row['docdesc'] != 'XTRA'){
                    $docname = $row['docdesc'];
                }else{
                    $docname = $row['other1'];
                }
                $inner_array[0] = $docname;
                $inner_array[1] = $row['docnum'] . '/' . $row['docyear'];
                $outer_array[] = $inner_array;
            }
        }
        return $outer_array;
    }


    public function get_last_listed_date($dairy_no){
        // $check_listed = "select orderdate from ordernet where diary_no='$dairy_no' order by orderdate desc limit 1;";
        // $check_listed = mysql_query($check_listed)or die("Error: " . LINE . mysql_error());
        $builder = $this->db->table('ordernet');
        $builder->select('orderdate');
        $builder->where('diary_no', $dairy_no);
        $builder->orderBy('orderdate', 'DESC');
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            $res_check_listed = $result[0]['orderdate'];
            if ($res_check_listed != ''){
                return $res_check_listed;   
            }
        }
    }



    function get_text_pdf($dairy_no, $n_date_ymd) {
        // $rop = "Select pdfname,orderdate from ordernet where diary_no='$dairy_no' and display='Y'
        //           and orderdate='$n_date_ymd' order by
        //             orderdate desc,ent_dt desc limit 0,1";
        // $rop = mysql_query($rop) or die("Error: " . __LINE__ . mysql_error());

        $final_path = '';


        $builder = $this->db->table('ordernet');
        $builder->select('pdfname, orderdate');
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');
        $builder->where('orderdate', $n_date_ymd);
        $builder->orderBy('orderdate', 'DESC');
        $builder->orderBy('ent_dt', 'DESC');
        $builder->limit(1, 0);
        $query = $builder->get();
        $rop = $query->getResultArray();

        if (!empty($rop) ) {
            // while ($row_rop = mysql_fetch_array($rop)) {
            foreach ($rop as $row_rop) {                        
                $path = "/home/reports/" . $row_rop['pdfname'];
                $ex_path = explode('/', $path);    
                $final_path = '/' . $ex_path[1] . '/' . $ex_path[2] . '/' . $ex_path[3] . '/' . $ex_path[4] . '/' . $ex_path[5] . '/';    
                exec('pdftotext -layout ' . $path . ' ' . $final_path . 'dummy_text.txt', $output, $return);
                ?>
    
            <?php } 
        } else {
            // $rop = "Select  jm pdfname,dated orderdate from tempo where diary_no='$dairy_no' and
            //             dated='$n_date_ymd' and jt='rop' order by
            //         dated desc limit 0,1";
            // $rop = mysql_query($rop) or die("Error: " . __LINE__ . mysql_error());
            $builder = $this->db->table('tempo');
            $builder->select('jm as pdfname, dated as orderdate');
            $builder->where('diary_no', $dairy_no);
            $builder->where('jt', 'rop');
            $builder->where('dated', $n_date_ymd);
            $builder->orderBy('dated', 'DESC');
            $builder->limit(1, 0);
            $query = $builder->get();
            $rop = $query->getResultArray();
            if (!empty($rop) ) {
                // while ($row_rop = mysql_fetch_array($rop)) {
                foreach ($rop as $row_rop) {                        
                    $path = "/home/judgment/" . $row_rop['pdfname'];
                    $ex_path = explode('/', $path);    
                    $final_path = '/' . $ex_path[1] . '/' . $ex_path[2] . '/' . $ex_path[3] . '/' . $ex_path[4] . '/' . $ex_path[5] . '/' . $ex_path[6] . '/';    
                    exec('pdftotext -layout  ' . $path . ' ' . $final_path . 'dummy_text.txt', $output, $return);
                        
                }
            } else {
                // $rop = "Select  concat('ropor/rop/all/',pno,'.pdf') pdfname,orderDate orderdate from
                //         rop_text_web.old_rop where dn='$dairy_no' and
                //         orderDate='$n_date_ymd' order by
                //     orderDate desc limit 0,1";
                // $rop = mysql_query($rop) or die("Error: " . __LINE__ . mysql_error());
                $rop = "SELECT CONCAT('ropor/rop/all/', pno, '.pdf') AS pdfname, orderDate AS orderdate
                        FROM rop_text_web.old_rop
                        WHERE dn = '$dairy_no' 
                        AND orderDate = '$n_date_ymd' ORDER BY orderDate DESC LIMIT 1 OFFSET 0";
                $query = $this->db->query($rop);
                $rop = $query->getResultArray();
                if (!empty($rop)) {
                    // while ($row_rop = mysql_fetch_array($rop)) {    
                    foreach ($rop as $row_rop) {                            
                        $path = "/home/judgment/" . $row_rop['pdfname'];
                        $ex_path = explode('/', $path);    
                        $final_path = '/' . $ex_path[1] . '/' . $ex_path[2] . '/' . $ex_path[3] . '/' . $ex_path[4] . '/' . $ex_path[5] . '/';    
                        exec('pdftotext -layout ' . $path . ' ' . $final_path . 'dummy_text.txt', $output, $return);    
                    }
                } else {
                    // $rop = "Select  concat('judis/',filename,'.pdf') pdfname,juddate orderdate from
                    //     scordermain where dn='$dairy_no' and
                    //     juddate='$n_date_ymd' order by
                    //     juddate desc limit 0,1";
                    // $rop = mysql_query($rop) or die("Error: " . __LINE__ . mysql_error());
                    $rop = "SELECT CONCAT('judis/', filename, '.pdf') AS pdfname, juddate AS orderdate
                            FROM scordermain
                            WHERE dn = '$dairy_no' 
                            AND juddate = '$n_date_ymd' 
                            ORDER BY juddate DESC 
                            LIMIT 1 OFFSET 0";
                    $query = $this->db->query($rop);
                    $rop = $query->getResultArray();
                    if (!empty($rop)) {
                        // while ($row_rop = mysql_fetch_array($rop)) {
                        foreach ($rop as $row_rop) {  
                            $path = "/home/judgment/" . $row_rop['pdfname'];
                            $ex_path = explode('/', $path);
                            $final_path = '/' . $ex_path[1] . '/' . $ex_path[2] . '/' . $ex_path[3] . '/';
                            exec('pdftotext -layout ' . $path . ' ' . $final_path . 'dummy_text.txt', $output, $return);
                            ?>
    
                            <?php
    
                        }
                    } else {
                        // $rop = "Select  concat('bosir/orderpdf/',pno,'.pdf') pdfname,orderdate orderdate from
                        // rop_text_web.ordertext where dn='$dairy_no' and
                        // orderdate='$n_date_ymd' order by
                        // orderdate desc limit 0,1";
                        // $rop = mysql_query($rop) or die("Error: " . __LINE__ . mysql_error());

                        $rop = "SELECT CONCAT('bosir/orderpdf/', pno, '.pdf') AS pdfname, orderdate AS orderdate
                            FROM rop_text_web.ordertext
                            WHERE dn = '$dairy_no' 
                            AND orderdate = '$n_date_ymd' 
                            ORDER BY orderdate DESC 
                            LIMIT 1 OFFSET 0";
                        $query = $this->db->query($rop);
                        $rop = $query->getResultArray();
                        if (!empty($rop)) {
                            // while ($row_rop = mysql_fetch_array($rop)) {    
                            foreach ($rop as $row_rop) {  
                                $path = "/home/judgment/" . $row_rop['pdfname'];
                                $ex_path = explode('/', $path);    
                                $final_path = '/' . $ex_path[1] . '/' . $ex_path[2] . '/' . $ex_path[3] . '/' . $ex_path[4] . '/';  
                                exec('pdftotext -layout ' . $path . ' ' . $final_path . 'dummy_text.txt', $output, $return);
                            }
                        } else {
                            // $rop = "Select  concat('bosir/orderpdfold/',pno,'.pdf') pdfname,orderdate orderdate from
                            // rop_text_web.oldordtext where dn='$dairy_no' and
                            // orderdate='$n_date_ymd' order by
                            // orderdate desc limit 0,1";
                            // $rop = mysql_query($rop) or die("Error: " . __LINE__ . mysql_error());

                            $rop = "SELECT CONCAT('bosir/orderpdfold/', pno, '.pdf') AS pdfname, orderdate AS orderdate
                                FROM rop_text_web.oldordtext
                                WHERE dn = '$dairy_no' 
                                AND orderdate = '$n_date_ymd' 
                                ORDER BY orderdate DESC 
                                LIMIT 1 OFFSET 0";
                            $query = $this->db->query($rop);
                            $rop = $query->getResultArray();
                            if (!empty($rop)) {
                                // while ($row_rop = mysql_fetch_array($rop)) {    
                                foreach ($rop as $row_rop) {     
                                    $path = "/home/judgment/" . $row_rop['pdfname'];
                                    $ex_path = explode('/', $path);    
                                    $final_path = '/' . $ex_path[1] . '/' . $ex_path[2] . '/' . $ex_path[3] . '/' . $ex_path[4] . '/';
                                    exec('pdftotext -layout ' . $path . ' ' . $final_path . 'dummy_text.txt', $output, $return);
                                        
                                }
                            }
                        }
                    }
                }
            }
        }
        return $fil_nm = $final_path . "dummy_text.txt";
    }

    function read_txt_file($fil_nm) {

        $ds = fopen($fil_nm, 'r');
        $b_z = '';
  
        $b_z = fread($ds, filesize($fil_nm));
        fclose($ds);
    
        if (!unlink($fil_nm)) {
    
        }
    
        $ex_explode = explode('O R D E R', $b_z);
    
        echo $ex_explode[1];
        if ($ex_explode[1] == '') {
            echo $b_z;
        }
    }


    public function get_filnm2($dairy_no){
        // $last_listed_dt="Select next_dt,board_type from heardt where diary_no='$dairy_no'
        //        and clno!=0 and  brd_slno!=0  and
        //        (main_supp_flag='1' or main_supp_flag='2') and next_dt<=curdate()   union
        //         Select next_dt,board_type from last_heardt where diary_no='$dairy_no'
        //        and clno!=0 and  brd_slno!=0  and
        //        (main_supp_flag='1' or main_supp_flag='2')  and next_dt<=curdate()  and (bench_flag is null or bench_flag='')
        //        order by next_dt desc limit 0,1";
        // $last_listed_dt=  mysql_query($last_listed_dt) or die("Error: ".__LINE__.mysql_error());

        // First subquery
        $subquery1 = "(SELECT next_dt::date, board_type::text
        FROM heardt
        WHERE diary_no = '$dairy_no'
            AND clno != 0
            AND brd_slno != 0
            AND (main_supp_flag = '1' OR main_supp_flag = '2')
            AND next_dt <= CURRENT_DATE)";

        // Second subquery
        $subquery2 = "(SELECT next_dt::date, board_type::text
        FROM last_heardt
        WHERE diary_no = '$dairy_no'
            AND clno != 0
            AND brd_slno != 0
            AND (main_supp_flag = '1' OR main_supp_flag = '2')
            AND next_dt <= CURRENT_DATE
            AND (bench_flag IS NULL OR bench_flag = ''))";

        // Combine subqueries using UNION
        $unionQuery = "$subquery1 UNION $subquery2"; 

        // Full query with ORDER BY and LIMIT
        $query = $this->db->query("$unionQuery ORDER BY next_dt DESC LIMIT 1");

        // $result = $query->getResult();
        $last_listed_dt = $query->getResultArray();

        $l_date='';
        $l_date_ymd='';
        $board_type='';
        if(!empty($last_listed_dt)){
            $r_last_listed_dt=  $last_listed_dt[0];
            $l_date = $r_last_listed_dt['next_dt'] != '' ? date('d-m-Y',  strtotime($r_last_listed_dt['next_dt'])) : '' ;
            $l_date_ymd = $r_last_listed_dt['next_dt'];
            $board_type_sh = $r_last_listed_dt['board_type'];
            
            // $board_nm="Select board_name from master_board_type where board_id='$board_type_sh' and board_display='Y'";
            // $board_nm=  mysql_query($board_nm) or die("Error: ".__LINE__.mysql_error());
            $builder = $this->db->table('master.master_board_type');
            $builder->select('board_name');
            $builder->where('board_id', $board_type_sh);
            $builder->where('board_display', 'Y');
            $query1 = $builder->get();
            $board_nm = $query1->getResultArray();
            if(!empty($board_nm)){
                $board_type .=  $board_nm[0]['board_name'] ;
            }

            $fil_nm2 = '';
            if($l_date!=''){
                ///////// Discuss (rop_text_web.old_rop) table with Preeti mam
                // $fil_nm = $this->get_text_pdf($dairy_no,$l_date_ymd);
                // $fil_nm2 .= $this->read_txt_file($fil_nm);
            }

            $dataArr = [
                'l_date' => $l_date,
                'board_type' => $board_type,
                'fil_nm2' => $fil_nm2
            ];

            return $dataArr;

        }
        
    }


    public function get_serve_status($d_no, $n_date_ymd){

        // $serve_status="SELECT process_id,a.name,address,b.name nt_type,del_type,tw_sn_to,copy_type,
        //      send_to_type,fixed_for,rec_dt,dispatch_dt,ser_date,ser_dt_ent_dt,serve,sendto_district,sendto_state,
        //      b.name nt_type
        //      FROM tw_tal_del a join tw_notice b on a.nt_type=b.id  join tw_o_r c on c.tw_org_id=a.id
        //      join tw_comp_not d on d.tw_o_r_id=c.id
        //      WHERE diary_no='$dairy_no' and a.display='Y'
        //       and order_dt='$n_date_ymd'
        //      and print=1 and b.display='Y'
        //      and c.display='Y' and d.display='Y' order by process_id";
        //  $serve_status=  mysql_query($serve_status) or die("Error: ".__LINE__.mysql_error());

        $builder = $this->db->table('tw_tal_del a');
        $builder->select('a.process_id, a.name AS address, b.name AS nt_type, del_type, tw_sn_to, copy_type, send_to_type, fixed_for, rec_dt, dispatch_dt, ser_date, ser_dt_ent_dt, serve, sendto_district, sendto_state');
        $builder->join('master.tw_notice b', 'cast(a.nt_type as text) = cast(b.id as text)');
        $builder->join('tw_o_r c', 'c.tw_org_id = a.id');
        $builder->join('tw_comp_not d', 'd.tw_o_r_id = c.id');
        $builder->where('diary_no', $d_no);
        $builder->where('a.display', 'Y');
        $builder->where('order_dt', $n_date_ymd);
        $builder->where('print', 1);
        $builder->where('b.display', 'Y');
        $builder->where('c.display', 'Y');
        $builder->where('d.display', 'Y');
        $builder->orderBy('process_id');
        // $queryString = $builder->getCompiledSelect();
        // echo $queryString;
        // exit();
        $query = $builder->get();
        $serve_status = $query->getResultArray();

        if(!empty($serve_status)){
            return $serve_status;
        }else{
            return [];
        }

    }



    function get_filed_by($advocate_id){
        // $filed_by="Select aor_code,name from bar where bar_id='$row1[advocate_id]'";
        // $filed_by=  mysql_query($filed_by) or die("Error: ".__LINE__.mysql_error());
        $builder = $this->db->table('master.bar');
        $builder->select('aor_code,name');
        $builder->where('bar_id', $advocate_id);
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            $res = $result[0];
            return $res;   
        }
    }


    function get_partyName($diary_no, $pet_res, $party_sno){
        // $party_name="Select pet_res,sr_no,partyname from party where diary_no='$dairy_no' and pflag='P'
        // and pet_res='$row2[pet_res]' $party_sno";
        // $party_name=  mysql_query($party_name) or die("Error: ".__LINE__.mysql_error());

        $builder = $this->db->table('party');
        $builder->select('pet_res,sr_no,partyname');
        $builder->where('diary_no', $diary_no);
        $builder->where('pflag', 'P');
        $builder->where('pet_res', $pet_res);
        if($party_sno != 0){
            $builder->where('sr_no', $party_sno);
        }
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            $res = $result[0];
            return $res;   
        }
    }

    function get_party_details($diary_no, $advocate_id){
    //     $parties="Select pet_res,pet_res_no from advocate where diary_no='$dairy_no' and display='Y' and
    //            advocate_id='$advocate_id'";
    //    $parties=  mysql_query($parties) or die("Error: ".__LINE__.mysql_error());

        $builder = $this->db->table('advocate');
        $builder->select('pet_res,pet_res_no');
        $builder->where('diary_no', $diary_no);
        $builder->where('display', 'Y');
        $builder->where('advocate_id', $advocate_id);
        $query = $builder->get();
        $result = $query->getResultArray();
        // echo "<pre>"; print_r($result); die;
        if(!empty($result)){
            $row2 = $result[0];
            $party_sno='';
            $pet_res_no=$row2['pet_res_no'];
            $pet_res = $row2['pet_res'];
            if($pet_res_no!=0){
                $party_sno = $row2['pet_res_no'];
            }
            $party_name = $this->get_partyName($diary_no, $pet_res, $party_sno);
            return $party_name;
        }      
       
    }

    function get_indexing($dairy_no, $doccode, $doccode1){
        // $indexing="Select fp,tp from indexing where diary_no='$dairy_no' and display='Y'
        //             and doccode='$row1[doccode]' and doccode1='$row1[doccode1]'";
        //     $indexing=  mysql_query($indexing) or die("Error: ".__LINE__.mysql_error());

        $builder = $this->db->table('indexing');
        $builder->select('fp,tp');
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');
        $builder->where('doccode', $doccode);
        $builder->where('doccode1', $doccode1);
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            $res = $result[0];
            return $res;   
        }
    }

    public function get_doc_case_status($diary_no){
        // $documents="SELECT docnum, docyear, other1, docdesc,advocate_id,a.doccode,a.doccode1 FROM docdetails a
        //  JOIN docmaster b ON a.doccode = b.doccode AND a.doccode1 = b.doccode1 WHERE
        //  diary_no = '$dairy_no' AND a.display = 'Y' AND b.display = 'Y' AND iastat = 'P'";
        // $documents=  mysql_query($documents) or die("Error: ".__LINE__.mysql_error());

        $builder = $this->db->table('docdetails a');
        $builder->select('docnum, docyear, other1, docdesc, advocate_id, a.doccode, a.doccode1');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('a.iastat', 'P');
        $query = $builder->get();
        $doc_case = $query->getResultArray();
        if(!empty($doc_case)){

            $dataArr = [];
            foreach ($doc_case as $row) {
                // echo "<pre>"; print_r($row); die;

                $advocate_id = $row['advocate_id'];
                $doccode = $row['doccode'];
                $doccode1 =$row['doccode1']; 

                $party_details =  $this->get_party_details($diary_no, $advocate_id);
                $filed_by = $this->get_filed_by($advocate_id);
                $indexing = $this->get_indexing($diary_no, $doccode, $doccode1);

                $dataArr[] = [
                    'docnum' => $row['docnum'],
                    'docyear' => $row['docyear'],
                    'other1' => $row['other1'],
                    'docdesc' => $row['docdesc'],
                    'advocate_id' => $row['advocate_id'],
                    'doccode' => $row['doccode'],
                    'doccode1' => $row['doccode1'],
                    'party_details' => $party_details, 
                    'filed_by' => $filed_by, 
                    'indexing' => $indexing
                ];

            }

            return $dataArr;
        }else{
            return [];
        }

    }


    public function get_defect_ent_dt($dairy_no){
       
        $builder = $this->db->table('obj_save');
        $builder->select('date(min(save_dt))');
        $builder->where('diary_no', $dairy_no);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        if(!empty($result)){
            $res = $result[0]['date'];
            return $res;   
        }

    }

// *************************************************************** C_CURATIVE_OFFICEREPORT  -END ********************************************************

// *************************************************************** C_DEFECTIVE_MATTERS_OFFICEREPORT  -END ********************************************************


    function get_last_listed_date_df($dno) {



        $sql ="Select next_dt from heardt where diary_no='$dno' and (main_supp_flag=1 or main_supp_flag=2) and next_dt<=current_date
union
Select next_dt from last_heardt where diary_no='$dno' and next_dt<=current_date and (main_supp_flag=1 or main_supp_flag=2) and (bench_flag is null or bench_flag='') order by next_dt desc
limit 1 offset 0";

        $query = $this->db->query($sql);
//       $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
//        var_dump($result);die;
        if($query->getNumRows())
        {
            foreach($result as $row)
            {
//                print_r($row);die;
             $res_check_listed = $row['next_dt'];
            return $res_check_listed;

            }

        }else{
            $dispose_detail = $this->dispose_detail($dno);
            return $dispose_detail;
        }



    }

    function dispose_detail($dno)
    {

        $builder = $this->db->table("dispose");
        $builder->select("ord_dt");
        $builder->where("diary_no",$dno);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($query->getNumRows())
        {
            foreach($result as $row)
            {
//                print_r($row);die;
                $res_sql = $row['ord_dt'];
                return $res_sql;

            }

        }else{

            return 0;
        }
    }

    function delay_days_jp($dno)
    {

        $builder = $this->db->table("case_limit");
        $builder->select("limit_days");
        $builder->where("diary_no",$dno);
        $builder->where("case_lim_display","Y");
        $builder->limit(1);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if(!empty($result))
        {
           return $result;
        }else{

            return 0;
        }
    }

    function get_doc_ia_type_details($dno,$doc_ia_type)
    {

        $outer_array = array();
        $docname = '';
        $builder = $this->db->table("docdetails a");
        $builder->select("docdesc,other1,docnum,docyear");
        $builder->join("master.docmaster b"," a.doccode=b.doccode and a.doccode1=b.doccode1");
        $builder->where("a.display='Y' and b.display='Y' and diary_no='$dno' and iastat='P' and a.doccode='8' and doc_ia_type='$doc_ia_type'");
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;

        if(!empty($result))
        {
           foreach ($result as $row)
           {
               $inner_array = array();

               if ($row['docdesc'] != 'XTRA')
                   $docname = $row['docdesc'];
               else
                   $docname = $row['other1'];

               $inner_array[0] = $docname;
               $inner_array[1] = $row['docnum'];
               $inner_array[2] = $row['docyear'];
               $outer_array[] = $inner_array;
           }
            return $outer_array;


           }else{
            return 0;
           }

    }


    function get_respondent_advocate($diary_no)
    {
//        $sql = "Select title,name from  advocate a join bar b on a.advocate_id=b.bar_id where
//          diary_no='$dairy_no' and display='Y' and pet_res='R' order by ent_dt  limit 0,1 ";
//        $sql = mysql_query($sql)or die("Error: " . __LINE__ . mysql_error());
//        $res_name = mysql_result($sql, 0, 'name');
//        $res_title = mysql_result($sql, 0, 'title');
//        $res_sql = $res_title . ' ' . $res_name;
//        return $res_sql;

        $builder = $this->db->table("advocate a");
        $builder->select("title,name");
        $builder->join("master.bar b", "a.advocate_id=b.bar_id");
        $builder->where("diary_no",$diary_no);
        $builder->where("display","Y");
        $builder->where("pet_res","R");
        $builder->orderBy("ent_dt");
        $builder->limit(1);
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if(!empty($result))
        {
            return $result;
        }else{

            return 0;
        }
    }





}


?>