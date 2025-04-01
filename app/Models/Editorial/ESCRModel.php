<?php
namespace App\Models\Editorial;

use CodeIgniter\Model;
use App\Models\Entities\Model_escr_users;

class ESCRModel extends Model
{

    public $masterEscrUser;
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->masterEscrUser = new Model_escr_users;
    }

//    DATE WISE EDITORIAL REPORT ***********************************************************

    public function datewise_report($from_date,$to_date)
    {
        $builder = $this->db->table("judgment_summary");
        
        $builder->select("DATE(updated_on) AS uploaded_on,
                    COUNT(DISTINCT (diary_no, orderdate)) AS updated,
                    COUNT(DISTINCT CASE WHEN is_verified = 't' THEN diary_no END) AS verified");
        $builder->where('is_deleted', 'f')->where("DATE(updated_on) BETWEEN '$from_date' AND '$to_date'")->groupBy('DATE(updated_on)');
        $query = $builder->get();
        $result = $query->getResultArray();
        if($result)
        {
            return $result;
        }else{
            return 0;
        }


    }
//    USER WISE EDITORIAL REPORT ***********************************************************

    public function escr_user_role($ucode)
    {

        $builder = $this->db->table("master.escr_users");
        $builder->select("role");
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

    public function userwise_report($from_date,$to_date,$loggedInUserRole,$ucode)
    {
        $builder = $this->db->table("judgment_summary js");
        $builder->select('name, empid, type_name, COUNT(DISTINCT (diary_no, orderdate)) as updated');
        $builder->join('master.users u', 'js.updated_by = u.usercode');
        $builder->join('master.usertype ut', 'u.usertype = ut.id');
        $builder->where('is_deleted', 'f');
        /*  UNCOMMENT FOR A WHILE TO GET DATA COMMENTED */
        if ($loggedInUserRole == 1) {
            $builder->where('js.updated_by', $ucode);
        }
        if ($from_date != '' && $from_date != '1970-01-01' && $to_date != '' && $to_date != '1970-01-01') {
            $builder->where("DATE(js.updated_on) BETWEEN '$from_date' AND '$to_date'");
        } else {
            $builder->where('is_verified', 'f');
        }
        $builder->groupBy('js.updated_by,name,empid,type_name');
        $query = $builder->get();
        $result = $query->getResultArray();
        if($result)
        {
            return $result;
        }else{
            return 0;
        }

    }

    public function get_case_type_list()
    {
        $builder = $this->db->table("master.casetype");
        $builder->select('casecode, skey, casename,short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode!=', '9999');
        $builder->orderBy('short_description');
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

    public function get_role($ucode)
    {

        $sql="select role from master.eSCR_users where usercode=".$ucode;
        $query = $this->db->query($sql);

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



    function show_user_details($from_date,$to_date,$empid)
    {
        
        $builder = $this->db->table("judgment_summary");
        $builder->select('DISTINCT u.name, u.empid, ut.type_name, judgment_summary.id, judgment_summary.diary_no, orderdate, summary, judgment_summary.updated_on, is_verified,
              reg_no_display, m.pet_name, m.res_name, v.name as verified_name, v.empid as verified_empid,
              vt.type_name as verified_desig, verified_on', false);
        $builder->join('main_a m', 'm.diary_no = judgment_summary.diary_no');
        $builder->join('master.users u', 'judgment_summary.updated_by = u.usercode');
        $builder->join('master.usertype ut', 'u.usertype = ut.id');
        $builder->Join('master.users v', 'judgment_summary.verified_by = v.usercode', 'left');
        $builder->Join('master.usertype vt', 'v.usertype = vt.id', 'left');
        $builder->where('is_deleted', 'f');
        $builder->where('u.empid', $empid);
        if ($from_date != '' && $from_date != '1970-01-01' && $to_date != '' && $to_date != '1970-01-01') {
            $builder->where("DATE(judgment_summary.updated_on) BETWEEN '$from_date' AND '$to_date'");
        } else {
            $builder->where('is_verified', 'f');
        }
        $result1 = $builder->get();
            $results = $result1->getResultArray();
            return $results;
    }

    function show_user_report($from_date,$to_date)
    {
        
        $builder = $this->db->table('judgment_summary j');

        $builder->select('j.diary_no, m.reg_no_display, m.pet_name, m.res_name, j.summary, j.updated_on, j.updated_by_ip, u.name, u.empid, j.orderdate, u1.name AS ver_name, u1.empid AS ver_id, j.is_verified, j.verified_on, j.verified_by_ip');

        $builder->join('main m', 'j.diary_no = m.diary_no', 'left');
        $builder->join('master.users u', 'j.updated_by = u.usercode', 'left');
        $builder->join('master.users u1', 'j.verified_by = u1.usercode', 'left');

        $builder->where('j.is_deleted', 'f');
        $builder->where("DATE(j.updated_on) BETWEEN '$from_date' AND '$to_date'"); // Use parameterized query

        $results = $builder->get()->getResultArray();
        return $results;
    }


    public function delete_gist($id,$ucode)
    {
        $columnUpdate = array(
            'is_deleted'=>'t',
            'deleted_on'=>'NOW()' ,
            'deleted_by'=> $ucode,
            'deleted_by_ip'=>getClientIP(),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()

        );

        $builder = $this->db->table('judgment_summary');
        $builder->where('id', $id);
        $builder->update($columnUpdate);
        $affectedRows = $this->db->affectedRows();

        if ($affectedRows > 0) {
            return $affectedRows; 
        }
        else{
            return 0;
        }

    }

    function get_diary_details($optradio=null,$caseTypeId=null,$caseNo=null,$caseYear=null,$diaryNo=null,$diaryYear=null)
    {
        $results = [];
        if ($optradio == 1)
        {
            $sql = "SELECT
                        h.diary_no,
                        SUBSTRING(h.diary_no::TEXT FROM 1 FOR LENGTH(h.diary_no::TEXT) - 4) AS dn,
                        SUBSTRING(h.diary_no::TEXT FROM LENGTH(h.diary_no::TEXT) - 3) AS dy,
                        h.new_registration_year,
                        h.is_deleted,
                        h.new_registration_number
                    FROM
                        main_casetype_history h
                    WHERE
                        cast(SPLIT_PART(h.new_registration_number, '-', 1) as INTEGER) = '$caseTypeId'
                        AND h.new_registration_number SIMILAR TO '[0-9-]+' 
                        AND $caseNo BETWEEN SPLIT_PART(h.new_registration_number, '-', 2)::INTEGER AND SPLIT_PART(h.new_registration_number, '-', 3)::INTEGER 
                        AND h.new_registration_year = $caseYear
                        AND h.is_deleted = 'f'";
        
        }
        if($optradio==2)
        {
            $sql="SELECT
                        diary_no,
                        SUBSTR(TRIM(diary_no::TEXT), +3) AS dy,
                        SUBSTR(diary_no::TEXT, 1, LENGTH(diary_no::TEXT) - 4) AS dn
                    
                    FROM
                        main
                    WHERE
                        SUBSTRING(TRIM(diary_no::TEXT), 1, LENGTH(TRIM(diary_no::TEXT)) - 4) = '$diaryNo'
                        AND SUBSTRING(TRIM(diary_no::TEXT), LENGTH(TRIM(diary_no::TEXT)) - 3, 4) = '$diaryYear'";

        }
        // echo $sql;
        // die();
        $query = $this->db->query($sql);
        $results = $query->getResultArray();
        return $results;

    }
    public function getCaseDetails($diaryNo)
    {
        $builder = $this->db->table('main b');

        $builder->select(
            's.section_name AS user_section, s.id, ' .
            'SUBSTRING(b.diary_no::text FROM 1 FOR LENGTH(b.diary_no::text) - 4) AS diary_no, ' .
            'SUBSTRING(b.diary_no::text FROM (LENGTH(b.diary_no::text) - 3)::integer FOR 4) AS diary_year, ' .
            'TO_CHAR(b.diary_no_rec_date, \'YYYY-MM-DD\') AS diary_date, ' .
            'b.c_status, a.tentative_cl_dt, a.next_dt, a.mainhead, a.subhead, a.brd_slno, a.usercode, a.ent_dt, ' .
            'b.pet_name, b.res_name, b.active_fil_no, b.reg_no_display, b.dacode, b.conn_key, ' .
            'c.stagename, a.main_supp_flag, u.name AS alloted_to_da, a.descrip, u1.name AS updated_by, ' .
            'a.listorder, br1.name AS pet_adv_name, br2.name AS res_adv_name, ' .
            'br1.aor_code AS pet_aor_code, br2.aor_code AS res_aor_code'
        );

        $builder->distinct();
        $builder->join('heardt a', 'a.diary_no = b.diary_no', 'left outer');
        $builder->join('master.subheading c', 'a.subhead = c.stagecode AND c.display = \'Y\'', 'left outer');
        $builder->join('master.users u', 'u.usercode = b.dacode AND u.display = \'Y\'', 'left outer');
        $builder->join('master.users u1', 'u1.usercode = a.usercode AND u1.display = \'Y\'', 'left outer');
        $builder->join('master.master_main_supp mms', 'mms.id = a.main_supp_flag', 'left outer');
        $builder->join('master.listing_purpose lp', 'lp.code = a.listorder AND lp.display = \'Y\'', 'left outer');
        $builder->join('master.usersection s', 's.id = u.section AND s.display = \'Y\'', 'left outer');
        $builder->join('master.bar br1', 'b.pet_adv_id = br1.bar_id', 'left outer');
        $builder->join('master.bar br2', 'b.res_adv_id = br2.bar_id', 'left outer');
        $builder->join('mul_category mc', 'a.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left outer');
        $builder->where('b.diary_no', $diaryNo);
        // echo  $builder->getCompiledSelect();
        // die();
        $results = $builder->get()->getResultArray();

        return $results;
    }

    public function getJudgmentDetails($diaryNumber,$judgmentDate){
            $sql="SELECT
                        o.diary_no AS diary_no,
                        o.dated::DATE AS dated
                    FROM
                        tempo o
                    LEFT JOIN
                        main m ON (o.dn || o.dy) = m.diary_no::text
                    LEFT JOIN
                        master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN
                        master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN
                        master.casetype c ON m.active_casetype_id = c.casecode
                    WHERE
                        m.diary_no = '$diaryNumber'
                        AND o.jt NOT LIKE '%or%'
                        AND o.jt NOT LIKE '%rop%'
                        AND o.dated = '$judgmentDate'

                    UNION

                    SELECT
                        o.diary_no AS diary_no,
                        o.orderdate::DATE AS dated
                    FROM
                        ordernet o
                    LEFT JOIN
                        main m ON o.diary_no::text = m.diary_no::text
                    LEFT JOIN
                        master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN
                        master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN
                        master.casetype c ON m.active_casetype_id = c.casecode
                    LEFT JOIN
                        dispose d1 ON m.diary_no::text = d1.diary_no::text
                    WHERE
                        o.diary_no = '$diaryNumber'
                        AND o.type = 'J'
                        AND o.display = 'Y'
                        AND o.orderdate = '$judgmentDate'

                    UNION

                    SELECT
                        o.dn AS diary_no,
                        o.juddate::DATE AS dated
                    FROM
                        scordermain o
                    LEFT JOIN
                        main m ON o.dn::text = m.diary_no::text
                    LEFT JOIN
                        master.bar pet ON m.pet_adv_id = pet.bar_id
                    LEFT JOIN
                        master.bar res ON m.res_adv_id = res.bar_id
                    LEFT JOIN
                        master.casetype c ON m.active_casetype_id = c.casecode
                    WHERE
                        o.dn = '$diaryNumber'
                        AND o.juddate = '$judgmentDate'";
            $query = $this->db->query($sql);
            $results = $query->getResultArray();
            return $results;
    }

    public function getRemarkFunction($diaryNumber ,$judgmentDate)
    {
        $builder = $this->db->table('judgment_summary');
        $builder->where('is_deleted', 'f');
        $builder->where('diary_no', $diaryNumber);
        $builder->where('orderdate', $judgmentDate);

        $query = $builder->get(); // Executes the query

        $results = $query->getResultArray(); 

        return $results;
    }

    public function saveSummary($diaryNumber=null,$remarks=null,$judgmentDate=null)
     {
         $client_ip = getClientIP();
         $ucode = session()->get('login')['usercode'];
        
         $userrole = $this->masterEscrUser->select('role')->where('usercode', $ucode)->findAll();
         $userrole = $userrole[0]['role'];
         $updated_by = '';
         $updated_on = '';
         $updated_by_ip = '';
         $remarks = trim(htmlspecialchars($remarks,ENT_QUOTES));
         $sqlCheck = $this->judgement_summary_check($diaryNumber,$judgmentDate);
         if(count($sqlCheck)>0)
         {
            foreach($sqlCheck as $row)
            {
                $updated_by=$row['updated_by'];
                $updated_on=$row['updated_on'];
                $updated_by_ip=$row['updated_by_ip'];

            }
            $update = $this->judgment_summary_update($diaryNumber,$judgmentDate,$ucode,$client_ip);
        }
         if($userrole==1){
         
         $insert = $this->judgment_summary_insertion($userrole,$diaryNumber,$remarks,$judgmentDate,$ucode,$client_ip);
         }
         else if($userrole==2) {
            if(($updated_on != '') && ($updated_by != '') && ($updated_by_ip != '') && ($updated_by != '0'))
            {
                $insert = $this->judgment_summary_insertion($userrole,$diaryNumber, $remarks, $judgmentDate, $ucode, $client_ip, $updated_by, $updated_on, $updated_by_ip);

            } else {

                $insert = $this->judgment_summary_insertion($userrole,$diaryNumber, $remarks, $judgmentDate, $ucode, $client_ip);
            }
           
        }
        if($insert)
        {
            if($userrole==2) {
               
                $empid = session()->get('login')['empid'];
                $role=2;
                $fromDate=$toDate='1970-01-01';
                //$this->user_report_details($empid,$role);
                $data['status'] = 1;
                $data['msg'] = 'ESCR/user_report_details?empid='.$empid.'&from_date='.$fromDate.'&to_date='.$toDate.'&userrole='.$role;
                return json_encode($data);

            }else{
                $data['status'] = 2;
                $data['msg'] = 'Record Added Successfully';
                return json_encode($data);
            }

        }


       
        
         
     }
     


    public function judgement_summary_check($dno,$juddate)
    {
        $builder = $this->db->table('judgment_summary');
        $builder->select('*');
        $builder->where('is_deleted','f')->where('diary_no',$dno)->where('orderdate',$juddate);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function judgment_summary_update($dno,$juddate,$userCode,$client_ip)
    {
        $data=[
            'is_deleted'=>'t',
            'updated_by'=>$userCode,
            'updated_on'=>'NOW()',
            'updated_by_ip'=>$client_ip,
        ];

        $builder = $this->db->table('judgment_summary');

        $builder->where('diary_no',$dno)->where('orderdate',$juddate);

        $query = $builder->update($data);
       
        return $query;
    }

    public function judgment_summary_insertion($userrole,$diaryNumber,$remarks,$judgmentDate,$ucode,$client_ip,$updated_by='',$updated_on='',$updated_by_ip='')
    {
        if($userrole==1)
        {
            $data=[
                'diary_no'=>$diaryNumber,
                'summary'=>$remarks,
                'is_deleted'=>'f',
                'updated_by'=>$ucode,
                'updated_on'=>'NOW()',
                'updated_by_ip'=>$client_ip,
                'orderdate'=>$judgmentDate,

            ];

            $builder = $this->db->table('judgment_summary');
            $query =$builder->insert($data);

        }else if($userrole==2){

            $data=[
                'diary_no'=>$diaryNumber,
                'summary'=>$remarks,
                'is_deleted'=>'f',
                'updated_by'=>$ucode,
                'updated_on'=>'NOW()',
                'updated_by_ip'=>$client_ip,
                'is_verified'=>'t',
                'verified_by'=>$ucode,
                'verified_on'=>'NOW()',
                'verified_by_ip'=>$client_ip,
                'orderdate'=>$judgmentDate,

            ];

            $builder = $this->db->table('judgment_summary');
            $query =$builder->insert($data);

        }else{

            $data=[
                'diary_no'=>$diaryNumber,
                'summary'=>$remarks,
                'is_deleted'=>'f',
                'updated_by'=>$ucode,
                'updated_on'=>'NOW()',
                'updated_by_ip'=>$client_ip,
                'is_verified'=>'t',
                'verified_by'=>$ucode,
                'verified_on'=>'NOW()',
                'verified_by_ip'=>$client_ip,
                'orderdate'=>$judgmentDate,

            ];

            $builder = $this->db->table('judgment_summary');
            $query = $builder->insert($data);
        }
        //$query=$this->db->getLastQuery();echo (string) $query."<br>";exit;

        if($query)
        {
            return $this->db->affectedRows();
        }else{
            return 0;
        }


    }

    public function judgment_detail($dno,$juddate)
    {
//        echo $id.">>>";echo "<pre>";print_r($data);die;

        $sql="SELECT o.diary_no diary_no,o.dated::text dated FROM tempo o 
            LEFT JOIN main m ON concat(o.dn,o.dy) = m.diary_no::text 
            left join master.bar pet on m.pet_adv_id=pet.bar_id 
            left join master.bar res on m.res_adv_id=res.bar_id 
            LEFT JOIN master.casetype c ON m.active_casetype_id = casecode WHERE m.diary_no =$dno and o.jt NOT LIKE '%or%' AND o.jt NOT LIKE '%rop%' and o.dated='$juddate' 
            union 
            SELECT o.diary_no diary_no,o.orderdate::text dated FROM ordernet o 
            LEFT JOIN main m ON o.diary_no = m.diary_no 
            left join master.bar pet on m.pet_adv_id=pet.bar_id 
            left join master.bar res on m.res_adv_id=res.bar_id 
            LEFT JOIN master.casetype c ON m.active_casetype_id = casecode 
            left join dispose d1 on m.diary_no=d1.diary_no WHERE o.diary_no =$dno AND o.type='J' and o.display='Y' and o.orderdate='$juddate' 
            union 
            SELECT o.dn diary_no,o.juddate::text dated FROM scordermain o 
            LEFT JOIN main m ON o.dn = m.diary_no 
            left join master.bar pet on m.pet_adv_id=pet.bar_id 
            left join master.bar res on m.res_adv_id=res.bar_id 
            LEFT JOIN master.casetype c ON m.active_casetype_id = casecode WHERE o.dn = $dno and o.juddate='$juddate'";
        $query = $this->db->query($sql);
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);die;
        if($result)
        {
            return $result;
        }else{
            $sql="SELECT o.diary_no diary_no,o.dated::text dated FROM tempo o 
            LEFT JOIN main_a m ON concat(o.dn,o.dy) = m.diary_no::text 
            left join master.bar pet on m.pet_adv_id=pet.bar_id 
            left join master.bar res on m.res_adv_id=res.bar_id 
            LEFT JOIN master.casetype c ON m.active_casetype_id = casecode WHERE m.diary_no =$dno and o.jt NOT LIKE '%or%' AND o.jt NOT LIKE '%rop%' and o.dated='$juddate' 
            union 
            SELECT o.diary_no diary_no,o.orderdate::text dated FROM ordernet_a o 
            LEFT JOIN main_a m ON o.diary_no = m.diary_no 
            left join master.bar pet on m.pet_adv_id=pet.bar_id 
            left join master.bar res on m.res_adv_id=res.bar_id 
            LEFT JOIN master.casetype c ON m.active_casetype_id = casecode 
            left join dispose d1 on m.diary_no=d1.diary_no WHERE o.diary_no =$dno AND o.type='J' and o.display='Y' and o.orderdate='$juddate' 
            union 
            SELECT o.dn diary_no,o.juddate::text dated FROM scordermain o 
            LEFT JOIN main_a m ON o.dn = m.diary_no 
            left join master.bar pet on m.pet_adv_id=pet.bar_id 
            left join master.bar res on m.res_adv_id=res.bar_id 
            LEFT JOIN master.casetype c ON m.active_casetype_id = casecode WHERE o.dn = $dno and o.juddate='$juddate'";
            $query = $this->db->query($sql);
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
            $result = $query->getResultArray();
            if($result)
            {
                return $result;
            }else{
                return 0;
            }
        }


    }


}

?>
