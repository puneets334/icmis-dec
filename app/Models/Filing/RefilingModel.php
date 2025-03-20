<?php
namespace App\Models\Filing;

use CodeIgniter\Model;
use Psr\Log\NullLogger;

class RefilingModel extends Model
{


    public function __construct()
    {
        parent::__construct();

        $this->db = db_connect();
    }

    public function  get_filing_details($diary_no)
    {
        $builder = $this->db->table("main m", false);
        $builder->select("concat(m.pet_name,' VS ', m.res_name) as cause_title, to_char(m.diary_no_rec_date,'dd-mm-yyyy') as diary_date, to_char(min(o.save_dt),'dd-mm-yyyy') as defect_date, to_char(min(o.save_dt),'dd-mm-yyyy') as df, o.display", false);
        $builder->join('obj_save o', 'm.diary_no=o.diary_no', 'inner', false);
        $builder->where('o.display', 'Y')->where('m.diary_no', $diary_no);
        $builder->groupBy('m.pet_name,m.res_name,m.diary_no_rec_date,o.display',false );
        $query = $builder->get();
        $result = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//      print_r($result);
//      die;
//        if($result[0]['cause_title'])
        if($result){
            return $result;
        }else{
            $builder = $this->db->table("main_a m", false);
            $builder->select("concat(m.pet_name,' VS ', m.res_name) as cause_title, to_char(m.diary_no_rec_date,'dd-mm-yyyy') as diary_date, to_char(min(o.save_dt),'dd-mm-yyyy') as defect_date, to_char(min(o.save_dt),'dd-mm-yyyy') as df, o.display", false);
            $builder->join('obj_save_a o', 'm.diary_no=o.diary_no', 'inner', false);
            $builder->where('o.display', 'Y')->where('m.diary_no', $diary_no);
            $builder->groupBy('m.pet_name,m.res_name,m.diary_no_rec_date,o.display',false );
            $query = $builder->get();
            $result_a = $query->getResultArray();
//                    $query=$this->db->getLastQuery();echo (string) $query;exit();

            if($result_a)
            {
                return $result_a;
            }else{
                return [];
            }


        }



    }

    public function get_no_of_days($curDate)
    {
        $builder = $this->db->table('master.defect_policy');
        $builder->select('no_of_days');
        $builder->where('master_module',1)->groupStart()
            ->groupStart()->where('from_date<=',$curDate)->where('to_date>=', $curDate)->groupEnd()
            ->orGroupStart()->where('from_date<=',$curDate )->where('to_date is null')
        ->groupEnd()->groupEnd();
        $query = $builder->get();
        $result = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
        //  print_r($result);die;
        if($result)
        {
            return $result[0];
        }else{
            return 0;
        }



    }

    public function get_registry_holiday($checkDate)
    {
        $builder = $this->db->table('master.sc_working_days');
        $builder->select('working_date');
        $builder->where('working_date',$checkDate)->where('holiday_for_registry',1);
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

    public function check_ia_exist($diary_no)
    {
        $sql = "SELECT a.*, b.docdesc FROM 
                ( SELECT doccode,doccode1,docnum,docyear,filedby,other1,ent_dt FROM docdetails WHERE doccode='8' AND diary_no='$diary_no' AND iastat='P' AND display='Y' )a 
                JOIN master.docmaster b ON a.doccode = b.doccode AND a.doccode1 = b.doccode1 AND b.display='Y' ORDER BY CASE WHEN b.doccode1 = 28 THEN 1 ELSE b.doccode1 END";

        $query = $this->db->query($sql);
//      $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
//        var_dump($result);die;
        return $result;

    }

    public function check_if_defect_exists($diary_no)
    {
        $builder = $this->db->table('obj_save');
        $builder->select('*');
        $builder->where('diary_no',$diary_no)->where( 'display','Y')->where(' date(rm_dt) is null ');
        $query = $builder->get();
        $result = $query->getResultArray();
//        var_dump($result);
//        die;
//      $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//        print_r($result);
//        die;
    if($result){
//        echo "FF";
//        die;
        return $result;
    }else{
//        echo "DSD";
//        die;
        $builder = $this->db->table('obj_save_a');
        $builder->select('*');
        $builder->where('diary_no',$diary_no)->where( 'display','Y')->where(' date(rm_dt) is null ');
        $query = $builder->get();
        $result_a = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//        print_r($result_a);
//        die;
        if($result_a)
        {
            return $result_a;
        }else{
            return [];
        }
      }


    }

    public function check_refile_date($diary_no)
    {
        $builder = $this->db->table('obj_save');
        $builder->select('max(rm_dt) as rm_dt');
        $builder->where('diary_no',$diary_no)->where( 'display','Y')->where(' date(rm_dt) is not null ');
        $query = $builder->get();
        $result = $query->getResultArray();

//       $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//        print_r($result);
//        die;

        if(($result[0]['rm_dt'] != '') || ($result[0]['rm_dt'] != null)){
//            echo "GGG";
//            die;
            return $result[0];
        }else{
//        echo "DSD";die;
            $builder = $this->db->table('obj_save_a');
            $builder->select('max(rm_dt) as rm_dt');
            $builder->where('diary_no',$diary_no)->where( 'display','Y')->where(' date(rm_dt) is not null ');
            $query = $builder->get();
            $result_a = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//        print_r($result_a);
//        die;
        if($result_a)
        {
            return $result_a[0];
        }else{
            return [];
        }
    }
    }

    public function get_date_difference($refilDate, $lastDateOfRefiling)
    {
       $sql =" SELECT DATE_PART('day', '".$refilDate."'::timestamp - '".$lastDateOfRefiling."'::timestamp) as days";

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

    public function check_defects($diary_no)
    {
//echo "JJJ";die;
//        $builder = $this->db->table('obj_save');
//        $builder->select('min( date(save_dt) ) as save_dt ,min(date(save_dt)) as df,min(date(rm_dt)) as rm_dt');
//        $builder->where('diary_no',$diary_no)->where( 'display','Y');
        $builder = $this->db->table('obj_save');
        $builder->select("min(date(save_dt)) as save_dt,min(date(save_dt)) as df, date(rm_dt)as rm_dt ");
        $builder->where('diary_no',$diary_no)->where( 'display','Y');
        $builder->groupBy('rm_dt');
        $builder->orderBy('date(rm_dt)','desc')->limit(1);
        $query = $builder->get();
        $result = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        echo "<pre>";
//        print_r($result);
//        die;
        if(!empty($result))
        {
            $result1 = $result[0];

//        if((($result1['save_dt'] != '') || ($result1['rm_dt'] != '') || ($result1['df'] != '')) && (($result1['save_dt'] != null) || ($result1['rm_dt'] != null) || ($result1['df'] != null)))
        if(($result1['save_dt'] !='') || ($result1['rm_dt'] != ''))
        {
//            echo "GDFHG";  die;
            return $result;
        }
        }else{
//            echo "OOOO";die;
            $builder = $this->db->table('obj_save_a');
//            $builder->select("min(date(save_dt)) AS save_dt, min(date(save_dt)) AS df,
//                                CASE  WHEN EXISTS (SELECT 1 FROM obj_save WHERE diary_no = '$diary_no' AND display = 'Y' AND rm_dt IS NULL) THEN NULL
//                                ELSE MIN(DATE(rm_dt)) END AS rm_dt");
            $builder->select("min(date(save_dt)) as save_dt,min(date(save_dt)) as df, date(rm_dt)as rm_dt ");
            $builder->where('diary_no',$diary_no)->where( 'display','Y');
            $builder->groupBy('rm_dt');
            $builder->orderBy('date(rm_dt)','desc')->limit(1);
            $query = $builder->get();
            $result_a = $query->getResultArray();
//            $query=$this->db->getLastQuery();echo (string) $query;exit();
//            echo "<pre>";
//            print_r($result_a);die;

            if($result_a)
            {
                return $result_a;
            }else{
                return [];
            }
        }

    }

    public function check_fil_trap($diary_no)
    {
        $builder = $this->db->table("fil_trap f ");
        $builder->select("remarks, d_to_empid, usercode,name");
        $builder->join('master.users u',' f.d_to_empid=u.empid');
        $builder->where('diary_no', $diary_no)->where('remarks', 'FDR -> SCR');
        $query = $builder->get();
        $result = $query->getResultArray();
//        echo $this->db->getLastQuery();exit;
//        echo "<pre>";
//        print_r($result);die;
        if($result)
        {
            return $result[0];
        }else{
            return 0;
        }

    }

    public function check_soft_defect($diary_no)
    {
        $builder = $this->db->table("obj_save");
        $builder->select("*");
        $builder->where('diary_no', $diary_no)->where('org_id', '10193')->where('display', 'Y')->where('rm_dt is null');
        $query = $builder->get();
        $result = $query->getResultArray();
//        echo $this->db->getLastQuery();exit;
//        echo "<pre>";
//        print_r($result);die;

        if(!empty($result)) {
            if ($query->getNumRows() > 0) {
                return 1;
            }
        }else{
            $builder = $this->db->table("obj_save_a");
            $builder->select("*");
            $builder->where('diary_no', $diary_no)->where('org_id', '10193')->where('display', 'Y')->where('rm_dt is null');
            $query = $builder->get();
            $result_a = $query->getResultArray();
            if(!empty($result_a))
            {
                if ($query->getNumRows() > 0) {
                    return 1;
                }
            }else{
                return 0;
            }

        }

    }

    public function check_doc_details($diary_no)
    {
        $builder = $this->db->table('docdetails');
        $builder->select("*");
        $builder->where('diary_no', $diary_no)->where('doccode', '8')->where('doccode1', '226')->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        if ($query->getNumRows() > 0)
        {
            return 1;
        }else{
            return 0;
        }
//      echo $this->db->getLastQuery();exit;
//        return $result;

    }

    public function check_defect_display($diary_no)
    {

        $sql = "SELECT zz . * FROM ( SELECT a. * , b.c, b.dt FROM 
( SELECT DISTINCT id, rm_dt, status , a.diary_no, org_id objcode, pet_name, res_name, a.remark, 
to_char(b.diary_no_rec_date,'yyyy-mm-dd HH24:MI:SS') AS fdt, save_dt, a.mul_ent, objdesc obj_name,name FROM obj_save a 
JOIN main b ON a.diary_no = b.diary_no JOIN master.objection c ON c.objcode = a.org_id 
JOIN master.users u on u.usercode=a.usercode 
WHERE rm_dt is null and c_status='P' 
AND (c.display = 'Y' or (c.display = 'N' and c.objcode<10075)) 
and a.diary_no='$diary_no' AND ( (status = '0' OR status = '11' OR status = '7' ) ) 
AND a.display = 'Y' AND ( b.fixed != '9' AND b.fixed != '10' ) )a 

JOIN ( SELECT count( org_id ) c, a.diary_no, b.fil_no, rm_dt, min( date( save_dt ) ) dt FROM obj_save a 
JOIN main b ON a.diary_no = b.diary_no 
JOIN master.objection c ON c.objcode = a.org_id 
WHERE rm_dt is null and c_status='P' and (c.display = 'Y' or (c.display = 'N' and c.objcode<10075)) AND ( (status = '0' OR status = '11' OR status = '7') ) AND a.display = 'Y' 
  and a.diary_no='$diary_no' AND ( b.fixed != '9' AND b.fixed != '10' ) 
GROUP BY a.diary_no,b.fil_no,rm_dt )b ON a.diary_no = b.diary_no )zz ORDER BY id";
        $query = $this->db->query($sql);
//        $query=$this->db->getLastQuery();echo (string) $query;exit();
//        dno=414162023
        $result = $query->getResultArray();
//echo "<pre>";
//print_r($result);
//die;
        if($result)
        {
            return $result;
        }else{
            return 0;
        }


    }

    public function defect_update_function($data)
    {


        $id_a = $data['defect_id'];
        $builder = $this->db->table('obj_save');
        $builder->whereIn('id', explode(',',$id_a));
        $builder->where('display', 'Y');
        $query = $builder->update([
            'rm_dt' => date("Y-m-d H:i:s"),
            'rm_user_id' => $data['ucode'],
            'updated_on'=>date("Y-m-d H:i:s"),
            'updated_by'=>session()->get('login')['usercode'],
            'updated_by_ip'=>getClientIP()]);
        if(!$query)
        {
            return 0;
        }else{

            $message='';
            $mobileNumber='';
            $count = 0;
            $messToSendAdvocate='';
            $allData = array();

        $builder = $this->db->table("main m", false);
        $builder->select("mobile", false);
        $builder->join('master.bar b ', 'm.pet_adv_id=b.bar_id', 'inner', false);
        $builder->where('m.diary_no',  $data['dno']);
        $query = $builder->get();
        $result = $query->getResultArray();

        foreach ($query->getResultArray() as $row) {
            $mobileNumber = $row['mobile'];
        }
//        echo "M>>".$mobileNumber;
       $alreadyDefect = $this->check_if_defect_exists($data['dno']);
//       echo gettype($alreadyDefect);
//        echo count($alreadyDefect);die;
       $countOfDefect = count($alreadyDefect);
//       echo $countOfDefect;die;
//            $countOfDefect=0;
       if($countOfDefect == 0)
       {
           // MESSAGE TO BE SENT TO THE ADVOCATE IF DEFECTS IS CURED.................
           $messToSendAdvocate = "As per circular F. No. 10/Judl. 2020 dated. 27.07.2020,
           You are required to send the  soft copy of the petition of Diary no <b>" . $data['dno'] . "</b>" . "
           on email id i.e. <b> soft.petition@sci.nic.in </b> in pdf format. <br> Regards,<br>Section-IB <br> Supreme Court of India";

           $columnsData = array(
               'mobile' => $mobileNumber,
               'msg' => $messToSendAdvocate,
               'table_name' => 'Soft Petition_Default_removal',
               'c_status' => 'N',
               'ent_time' => date("Y-m-d H:i:s"),
               'create_modify' => date("Y-m-d H:i:s"),
               'updated_on' => date("Y-m-d H:i:s"),
               'updated_by' => session()->get('login')['usercode'],
               'updated_by_ip' => getClientIP()
           );

           $builder = $this->db->table('sms_pool');

           $query1 = $builder->insert($columnsData);
//           QUERRY CHECKED SUCCESSFULLY INSERTING THE RECORD WHEN HARDCODE $countOfDefect
//           $query=$this->db->getLastQuery();echo (string) $query;
//           exit();

           if ($query1)
           {
               $count=1;
//               $message = "SMS for emailing Soft Petition Sent Successfully to Mobile no $mobileNumber with message as follows: $messToSendAdvocate";
//               $message = "SMS for emailing Soft Petition Sent Successfully to Mobile no $mobileNumber with message as follows: $messToSendAdvocate";
//               $allData['message']=$message;
           }
       }
       $arrayOfIds = [];
       $arrayOfIds = explode(',',$id_a);
       if($count == 1)
       {
           $message = "SMS for emailing Soft Petition Sent Successfully to Mobile no $mobileNumber with message as follows: $messToSendAdvocate";
           $allData=[
               'id_updated'=> $arrayOfIds,
               'message'=>$message
           ];
           return $allData;
        }


       //SEND MESSAGE TO ADVOCATE AND PARTY ABOUT DEFECT


       $mobile = '';
       $from='Refiling';
//     $templateId ='1107161234619089003';
       $templateId = env('templateId');
       $textMessage = "The case filed by you with Diary No.".$data['dno']." is still defective having $countOfDefect objections. Please collect the same from Re-filing counter. - Supreme Court of India";


        $mobileParty = get_party_mobile_number($data['dno'],'P');
        $mobileAdvocate = get_advocate_mobile_number($data['dno'],'P');
//        echo "adv=".$mobileAdvocate."  pa=".$mobileParty;
//        die;
        $mobile = $mobileParty.",".$mobileAdvocate;

        //LINE 314 TO BE UNCOMMENTED LATER --------------------------------------------ON TESTING TIME

//        $sendSmsFuncReturnValue =  send_sms($mobile,$textMessage,$from,$templateId);

            // HARDCODE VALUE ------- TO BE DELETE LATER
            $sendSmsFuncReturnValue='Function of sending sms is commented as of now but same is functional on live server. ';

            $allData=[
                'id_updated'=> $arrayOfIds,
                'message'=>$sendSmsFuncReturnValue
            ];
            return $allData;

    }

   }

//   public function defect_listing_function($diaryNo)
//   {
//       echo "<pre>";
//       print_r($data);
//       die;
//       $diaryNo = $data['dno'];
//       $allIdString = implode(",",$data['all_defect_ids']);
//       echo $allIdString;
//       die;
//       $builder = $this->db->table('obj_save');
//       $builder->select('*');
//       $builder->where('diary_no',$diaryNo)->where( 'display','Y')->whereIn('id',$data['all_defect_ids']);
//       $builder->where('diary_no',$diaryNo)->where( 'display','Y')->where('date(rm_dt) is null ');
//       $query = $builder->get();
//       $result = $query->getResultArray();
//       echo $this->db->getLastQuery();exit;

//       return $result;
//
//   }

//   public function get_IA_detail($diaryNo)
//   {
//       $builder = $this->db->table('docdetails a');
//       $builder->select('*');
//       $builder->join('master.docmaster b','a.doccode=b.doccode  and a.doccode1=b.doccode1');
//       $builder->where('diary_no',$diaryNo)->where( 'display','Y')->whereIn('id',$data['all_defect_ids']);
//       $builder->where('diary_no',$diaryNo)->where('a.doccode','8')->where( 'b.display','Y');
//       $builder->orderBy('docyear, docnum','desc');
//       $query = $builder->get();
//       $result = $query->getResultArray();
//       echo $this->db->getLastQuery();exit;

//       return $result;
//
//   }

//   public function get_data_fixedfor()
//   {
//       $builder = $this->db->table('master.master_fixedfor');
//       $builder->select('id,fixed_for_desc');
//       $builder->orderBy('id','asc');
//       $query = $builder->get();
//       $result = $query->getResultArray();
//      echo $this->db->getLastQuery();exit;

//       return $result;
//
//   }



   public function check_defects_refiling_bkdt($diary_no)
   {
       $builder = $this->db->table('obj_save');
       $builder->select('rm_dt,status');
       $builder->where('diary_no', $diary_no);
       $builder->where('display', 'Y');
       $query = $builder->get();

//        $query=$this->db->getLastQuery();
//        echo (string) $query;exit();
       $result = $query->getResultArray();
//echo $query->getNumRows();die;
       if($query->getNumRows()>0)
       {
           return $result;
       }else{
           return [];
       }
   }

    public function get_defect($diary_no)
    {
        $builder = $this->db->table("obj_save a");
        $builder->select("a.org_id,objdesc obj_name, rm_dt,remark, ARRAY_TO_STRING(ARRAY_AGG(mul_ent), ',') mul_ent");
        $builder->join('master.objection b', 'a.org_id = b.objcode', 'inner', false);
        $builder->where('diary_no', $diary_no)->where('a.display', 'Y')->groupStart()->where('a.status is null')->orwhere('a.status','0')->groupEnd();
        $builder->groupBy('diary_no,a.org_id, a.remark,b.objdesc,rm_dt,a.id');
        $builder->orderBy('id');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
        if($result)
        {
            return $result;
        }else{
            return [];
        }




    }

    public function check_for_back_date($diary_no)
    {
        $builder = $this->db->table("obj_save");
        $builder->select("min( date(save_dt) ) as save_dt ,min(date(rm_dt)) as rm_dt");
        $builder->where('diary_no', $diary_no)->where('display', 'Y');
        $builder->groupBy('diary_no');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
        if($result)
        {
            return $result;
        }else{
            return [];
        }


    }

    public function update_refiling_date($diary_no, $backDate,$usercode)
    {
        $columnsUpdate = array(
            'rm_dt' =>$backDate,
            'rm_user_id' =>$usercode,
            'rm_on_back_date' =>'NOW()',
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => $usercode,
            'updated_by_ip' => getClientIP()
        );

        $builder = $this->db->table('obj_save');
        $builder->where('diary_no', $diary_no)->where('rm_dt is null')->groupStart()->where('status is null')->orwhere('status','0')->groupEnd();
        $query = $builder->update($columnsUpdate);
        if($query) {
            return 1;
        }else
        {
            return 0;
        }



    }

    public function check_if_listed($diary_no)
    {
        $sql = "select min(next_dt) next_dt 
                from( select diary_no,next_dt from heardt where main_supp_flag in (1,2) and diary_no='$diary_no' 
                union select diary_no,next_dt from last_heardt where main_supp_flag in(1,2) and (bench_flag is null or bench_flag='') and diary_no='$diary_no')aa";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);
//        die;
        if( $query->getNumRows() > 0) {
            return $result[0];
        }else
        {
            return 0;
        }


    }

    public function check_if_verified($diary_no)
    {

        $builder = $this->db->table("defects_verification");
        $builder->select("*");
        $builder->where('diary_no', $diary_no)->groupStart()->where('verification_status is null')->orwhere('verification_status','0')->groupEnd();
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);
//        die;
        if( $query->getNumRows() > 0)
        {
            return $result;
        }else{
            return [];
        }


    }
    public function check_if_registered($diary_no)
    {
//        select * from main where diary_no='411132023' and (fil_no is not null or fil_no!='')
        $builder = $this->db->table("main");
        $builder->select("*");
        $builder->where('diary_no', $diary_no)->groupStart()->where('fil_no is not null')->orwhere('fil_no !=','')->groupEnd();
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
        if( $query->getNumRows() > 0)
        {
            return $result;
        }else{
            return [];
        }

    }

    public function get_defect_refiling($diary_no)
    {
        $builder = $this->db->table("obj_save a");
        $builder->select("a.org_id,objdesc obj_name, rm_dt,remark, ARRAY_TO_STRING(ARRAY_AGG(mul_ent), ',') mul_ent");
        $builder->join('master.objection b', 'a.org_id = b.objcode', 'inner', false);
        $builder->where('diary_no', $diary_no)->where('a.display', 'Y')->where('date(a.rm_dt) is not null')->groupStart()->where('a.status is null')->orwhere('a.status','0')->groupEnd();
        $builder->groupBy('diary_no,a.org_id, a.remark,b.objdesc,rm_dt,a.id');
        $builder->orderBy('id');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
        if($result)
        {
            return $result;
        }else{
            return [];
        }

    }

    public function check_for_bo($ucode)
    {

        $builder = $this->db->table("master.users");
        $builder->select("*");
        $builder->where('usercode', $ucode,false)->where('section', 19)->where('usertype',14)->where('attend','P');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
        $row = $query->getNumRows();
        if($row > 0)
        {
            return $row;
        }else{
            return $row;
        }

    }

    public function update_cancel_refiling($diary_no,$ucode)
    {
//        Update obj_save set rm_dt='0000-00-00 00:00:00',rm_user_id='',refil_cancel_user='$ucode',refil_cancel_date=now()
//        where diary_no = '$diary_no' and date(rm_dt)!='0000-00-00' and status='0'
        $columnsUpdate = array(
            'rm_dt' => null,
            'rm_user_id' =>null,
            'refil_cancel_user'=>$ucode,
            'refil_cancel_date' =>'NOW()',
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => $ucode,
            'updated_by_ip' => getClientIP()
        );

        $builder = $this->db->table('obj_save');
        $builder->where('diary_no', $diary_no)->where('date(rm_dt) is not null')->groupStart()->where('status is null')->orwhere('status','0')->groupEnd();
        $query = $builder->update($columnsUpdate);
        if($query) {
            return 1;
        }else
        {
            return 0;
        }

    }

    public function get_specific_role($ucode)
    {
        $builder = $this->db->table("master.specific_role");
        $builder->select("*");
        $builder->where('usercode', $ucode,false)->where('display', 'Y')->where('flag', 'C');
        $query = $builder->get();
//        $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
        $result = $query->getResultArray();
//        echo "<pre>";
//        print_r($result);

        $row = $query->getNumRows();
//        echo ">>".$row;
//        die;
        if($row > 0)
        {
            return $result[0];
        }else{
            return [];
        }

    }
    
    
}


?>