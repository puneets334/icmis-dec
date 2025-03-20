<?php

namespace App\Controllers\Editorial;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Entities\Model_Casetype;
use App\Models\Entities\Model_main_a;
use App\Models\Entities\Model_judgment_summary;
use App\Models\Entities\Model_main;
use App\Models\Entities\Model_users;
use App\Models\Entities\Model_escr_users;
use App\Models\Entities\Model_usertype;
use App\Models\Entities\Model_tempo;
use App\Models\Entities\Model_ordernet_a;
use App\Models\Entities\Model_ordernet;
use App\Models\Entities\Model_Bar;
use App\Models\Entities\Model_scordermain;
use App\Models\Entities\Model_dispose;
use App\Models\Editorial\ESCRModel;
use CodeIgniter\Model;


class ESCR extends BaseController
{

    public $caseTypeModel;
    public $main_a;
    public $judgmentSummary;
    public $main;
    public $masterUsers;
    public $masterEscrUser;
    public $masterUserType;
    public $tempo;
    public $bar;
    public $ordernet_a;
    public $ordernet;
    public $dispose;
    public $scordermain;
    public $escr_model;

    function __construct()
    {
        $this->escr_model = new ESCRModel();
        $this->caseTypeModel = new Model_Casetype();
        $this->main_a = new Model_main_a();
        $this->judgmentSummary = new Model_judgment_summary();
        $this->main = new Model_main();
        $this->masterUsers = new Model_users;
        $this->masterEscrUser = new Model_escr_users;
        $this->masterUserType = new Model_usertype;
        $this->tempo = new Model_tempo;
        $this->ordernet_a = new Model_ordernet_a();
        $this->scordermain = new Model_scordermain();
        $this->bar = new Model_bar();
        $this->dispose = new Model_dispose();
        $this->ordernet = new Model_ordernet();

    }

    public function index()
    {
        $data['caseInfo']=null;
        $data['listingInfo']=null;
        unset($_SESSION['diaryDetails']);
        $userCode = $_SESSION['login']['usercode'];
        $diaryNumber = '';
        $data['listingInfo'] = null;
        $casetypes = $this->escr_model->get_case_type_list();
        if(!empty($casetypes))
        {
            $data['caseTypes']=$casetypes;
        }else{
            $data['caseTypes']='';
        }

        $getRole = $this->escr_model->get_role($userCode);

        if(!empty($getRole))
        {
            $data['userrole']=$getRole[0]['role'];
        }else{
            $data['userrole']='';
        }
        if (!empty($_POST))
        {

            if ($_POST['optradio'] == 1)
            {
                if (((isset($_POST['caseType']))) || (!empty($_POST['caseType'])) && (((isset($_POST['caseNo']))) || (!empty($_POST['caseNo'])))) {

                    $caseTypeId = $_POST['caseType'];
                    $caseNo = $_POST['caseNo'];
                    $caseYear = $_POST['caseYear'];
                    $getdiarydetail = $this->escr_model->get_diary_details($caseTypeId, $caseNo, $caseYear);
                    echo "<pre>"; print_r($getdiarydetail); die;
                    if(!empty($getdiarydetail))
                    {
                        $data['diaryDetails'] = $getdiarydetail[0];

                    }
                }
            }
            // if ($_POST['optradio'] == 2)
            // {
            //     $diaryNo = $_POST['diaryNumber'];
            //     $diaryYear = $_POST['diaryYear'];

            //     $data['diaryDetails'] = [
            //         'dn' => $diaryNo,
            //         'dy' =>$diaryYear,
            //         'diary_no' => $diaryNo . $diaryYear,
            //     ];
            // }

            if (!empty($data['diaryDetails']))
            {
// //                echo "EWEWE";

//                 $diaryNumber = $data['diaryDetails']['diary_no'];

//                 $judgmentDate = $_POST['judgmentDate'];

//                 $data['judgmentDate'] = $_POST['judgmentDate'];
//                 session()->set(array('diaryno' => $diaryNumber));

//                 session()->set(array('judgmentDate' => $_POST['judgmentDate']));
// //                echo 'www';
// //                MADE 3 FUNCTION TO GET CASE DETAILS,JUDGMENT INFO AND REMARKS
//                 $caseInfo = $this->get_caseinfo_function($diaryNumber);
// //                echo "<pre>";  print_r($caseInfo);die;
//                 if(!empty($caseInfo))
//                 {
//                     $data['caseInfo']=$caseInfo[0];
//                 }else{
//                     $data['caseInfo']='';
//                 }

//                 $judgmentdetail = $this->get_judgmentdetail_function( $diaryNumber,$judgmentDate);
// //                echo "<pre>";  print_r($judgmentdetail);die;
//                 if(!empty($judgmentdetail))
//                 {
//                     $data['judgmentInfo']=$judgmentdetail;
//                 }else{
//                     $data['judgmentInfo']='';
//                 }

//                 $remarks = $this->get_remark_function( $diaryNumber,$judgmentDate);
// //                echo "<pre>"; print_r($remarks);die;
//                 if(!empty($remarks))
//                 {
//                     $data['remarksInfo']= $remarks;
//                 }else{
//                     $data['remarksInfo']='';
//                 }
// //                echo "<pre>"; echo ">>>".print_r($data);die;

//                 if(empty($data['judgmentInfo'] ) )
//                 {
//                     $data['caseInfo'] = '';
//                     $data['judgmentInfo'] = '';
//                     $data['remarksInfo']='';
//                     session()->setFlashdata("message_error", 'Judgment has not been uploaded yet');
//                     // $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Judgment has not been uploaded yet</div>');
// //
// //                    return redirect()->to("Editorial/eSCREntry");
//                 }
//                 else if(!empty($data['remarksInfo'])&&($data['remarksInfo']['is_verified']=='t'))
//                 {
//                     $data['caseInfo'] = '';
//                     $data['judgmentInfo'] = '';
//                     $data['remarksInfo']='';
//                     session()->setFlashdata('message_error', 'Gist for the searched Case has been verified.Updation not allowed.');
// //                    return redirect()->to("Editorial/eSCREntry");
//                 }

            } else
            {
                $data['save']='saveMentionMemo';
                $data['caseInfo'] = '';
                $data['judgmentInfo'] = '';
                $data['remarksInfo']='';
                session()->setFlashdata('message_error', 'Case Not Found');
//                return redirect()->to("Editorial/eSCREntry");
            }
        }

        return view('Editorial/eSCREntry', $data);
    }



    public function saveSummary()
    {
//           echo "<pre>";
//           print_r($_POST);  die;
        $this->db = \Config\Database::connect();
        $this->db->transStart();
        $remarks = $_POST['remark'];
        $remarks=trim(htmlspecialchars($remarks,ENT_QUOTES));
        $dno = $_SESSION['diaryno'];
        $juddate = $_SESSION['judgmentDate'];
        $client_ip = getClientIP();
        $userCode = $_SESSION['login']['usercode'];
        $loggedInUserRole = $this->masterEscrUser->select('role')->where('usercode', $userCode)->findAll();
        $userrole=$loggedInUserRole[0]['role'];
        $updated_by='';
        $updated_on='';
        $updated_by_ip='';
//        echo $remarks;die;

        $sqlCheck = $this->escr_model->judgement_summary_check($dno,$juddate);
//        echo "<pre>"; print_r($sqlCheck);die;
        if(!empty($sqlCheck))
        {
            foreach($sqlCheck as $row)
            {
                $updated_by=$row['updated_by'];
                $updated_on=$row['updated_on'];
                $updated_by_ip=$row['updated_by_ip'];

            }
            $update = $this->escr_model->judgment_summary_update($dno,$juddate,$userCode,$client_ip);

        }
        if($userrole==1)
        {

            $insert = $this->escr_model->judgment_summary_insertion($userrole,$dno,$remarks,$juddate,$userCode,$client_ip);
        }else if($userrole==2) {
            if(($updated_on != '') && ($updated_by != '') && ($updated_by_ip != '') && ($updated_by != '0'))
            {
                $insert = $this->escr_model->judgment_summary_insertion($userrole,$dno, $remarks, $juddate, $userCode, $client_ip, $updated_by, $updated_on, $updated_by_ip);

            } else {

                $insert = $this->escr_model->judgment_summary_insertion($userrole,$dno, $remarks, $juddate, $userCode, $client_ip);
            }
        }
            if($insert)
            {
//                echo $sql.">>".$userrole;die;
                if($userrole==2) {
                    session()->setFlashdata('success_msg', 'Case is Verified.');
                    $empid = session()->get('login')['empid'];
                    $role=2;
                    $this->user_report_details($empid,$role);

                }else{
                   echo "DATA IS SAVED SUCCESSFULLY!!!!";
                }

            }

        $this->db->transComplete();
    }

//         MADE 3 FUNCTION DEFINITION OF CASE DETAILS,JUDGMENT INFO AND REMARKS

    public function get_caseinfo_function($diaryNumber)
    {
        $query1 =$this->main_a->select("s.section_name AS user_section,
                                            s.id,
                                            SUBSTRING(CAST(main_a.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(main_a.diary_no AS TEXT))-4) AS diary_no,
                                            SUBSTRING(CAST(main_a.diary_no AS TEXT) FROM -4) AS diary_year,
                                            TO_CHAR(main_a.diary_no_rec_date, 'YYYY-MM-DD') AS diary_date,
                                            main_a.c_status,
                                            tentative_cl_dt,
                                            next_dt,
                                            mainhead,
                                            subhead,
                                            brd_slno,
                                            a.usercode,
                                            ent_dt,
                                            pet_name,
                                            res_name,
                                            active_fil_no,
                                            main_a.reg_no_display,
                                            dacode,
                                            a.conn_key,
                                            stagename,
                                            main_supp_flag,
                                            u.name AS alloted_to_da,
                                            descrip,
                                            u1.name AS updated_by,
                                            listorder,
                                            br1.name AS pet_adv_name,
                                            br2.name AS res_adv_name,
                                            br1.aor_code AS pet_aor_code,
                                            br2.aor_code AS res_aor_code")
            ->join('heardt_a a', 'a.diary_no = main_a.diary_no', 'left')
            ->join('master.subheading c', 'a.subhead = c.stagecode AND c.display = \'Y\'', 'left')
            ->join('master.users u', 'u.usercode = main_a.dacode AND u.display = \'Y\'', 'left')
            ->join('master.users u1', 'u1.usercode = a.usercode AND u1.display = \'Y\'', 'left')
            ->join('master.master_main_supp mms', 'mms.id = a.main_supp_flag', 'left')
            ->join('master.listing_purpose lp', 'lp.code = a.listorder AND lp.display = \'Y\'', 'left')
            ->join('master.usersection s', 's.id = u.section AND s.display = \'Y\'', 'left')
            ->join('master.bar br1', 'main_a.pet_adv_id = br1.bar_id', 'left')
            ->join('master.bar br2', 'main_a.res_adv_id = br2.bar_id', 'left')
            ->join('mul_category_a mc', 'a.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left')
            ->where('main_a.diary_no',$diaryNumber)->get()->getResultArray();

        $query2 =$this->main->select(" s.section_name AS user_section,
                                                s.id,
                                                SUBSTRING(CAST(main.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(main.diary_no AS TEXT))-4) AS diary_no,
                                                SUBSTRING(CAST(main.diary_no AS TEXT) FROM -4) AS diary_year,
                                                TO_CHAR(main.diary_no_rec_date, 'YYYY-MM-DD') AS diary_date,
                                                main.c_status,
                                                tentative_cl_dt,
                                                next_dt,
                                                mainhead,
                                                subhead,
                                                brd_slno,
                                                a.usercode,
                                                ent_dt,
                                                pet_name,
                                                res_name,
                                                active_fil_no,
                                                main.reg_no_display,
                                                dacode,
                                                a.conn_key,
                                                stagename,
                                                main_supp_flag,
                                                u.name AS alloted_to_da,
                                                descrip,
                                                u1.name AS updated_by,
                                                listorder,
                                                br1.name AS pet_adv_name,
                                                br2.name AS res_adv_name,
                                                br1.aor_code AS pet_aor_code,
                                                br2.aor_code AS res_aor_code")
            ->join('heardt a', 'a.diary_no = main.diary_no', 'left')
            ->join('master.subheading c', 'a.subhead = c.stagecode AND c.display = \'Y\'', 'left')
            ->join('master.users u', 'u.usercode = main.dacode AND u.display = \'Y\'', 'left')
            ->join('master.users u1', 'u1.usercode = a.usercode AND u1.display = \'Y\'', 'left')
            ->join('master.master_main_supp mms', 'mms.id = a.main_supp_flag', 'left')
            ->join('master.listing_purpose lp', 'lp.code = a.listorder AND lp.display = \'Y\'', 'left')
            ->join('master.usersection s', 's.id = u.section AND s.display = \'Y\'', 'left')
            ->join('master.bar br1', 'main.pet_adv_id = br1.bar_id', 'left')
            ->join('master.bar br2', 'main.res_adv_id = br2.bar_id', 'left')
            ->join('mul_category mc', 'a.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left')
            ->where('main.diary_no',$diaryNumber)->get()->getResultArray();

        $combinedResults = array_merge($query1, $query2);
        return $combinedResults;


    }

    public function get_judgmentdetail_function($diaryNumber ,$judgmentDate)
    {
        $detail = $this->escr_model->judgment_detail($diaryNumber ,$judgmentDate);
//      echo "<pre>"; print_r($detail);die;
        return $detail;
   }

    public function get_remark_function($diaryNumber ,$judgmentDate)
    {
        // echo $diaryNumber.">>>>>".$judgmentDate;
        // die;
        $remark=[];
        $query4 = $this->judgmentSummary->select('*')
            ->where('is_deleted','f')->where('diary_no', $diaryNumber)->where('orderdate',$judgmentDate)->get()->getResultArray();
        //  $query4 = $this->db->getLastQuery();
        //  echo $query4;
        //  die;
        // echo "<pre>";
        // print_r($query4);
        // die;
        if(!empty($query4))
        {

            $remark = $query4[0];
            return $remark;

        }
        else{

            return false;

        }



    }
    public function report()
    {
        $fromDate = $this->request->getGet('fromDate');
        $toDate = $this->request->getGet('toDate');
        
        // echo $sql="select j.diary_no,reg_no_display,m.pet_name,m.res_name,j.summary,j.updated_on,j.updated_by_ip,u.name,u.empid,j.orderdate,u1.name as ver_name,
        // u1.empid as ver_id,j.is_verified,j.verified_on,j.verified_by_ip from judgment_summary j left join main m on j.diary_no=m.diary_no left join 
        // users u on j.updated_by=u.usercode left join users u1 on j.verified_by=u1.usercode
        //       where is_deleted='f' and date(updated_on) between '$fromDate' and '$toDate'";
        $userReport = $this->escr_model->show_user_report($fromDate,$toDate);
        $data['from_date'] = $fromDate;
        $data['to_date'] = $toDate;
        $data['list'] = $userReport;
        return view('Editorial/report', $data);

    }

    public function report_data()
    {
        error_reporting(0);
        // echo "REPORT";
        // $this->data['app_name']='Report';
        // $from_date=$this->input->get('fromDate');
        // $to_date=$this->input->get('toDate');
        // var_dump($_REQUEST);
        // die;
        $sql="select j.diary_no,reg_no_display,m.pet_name,m.res_name,j.summary,j.updated_on,j.updated_by_ip,u.name,u.empid,j.orderdate,u1.name as ver_name,
        u1.empid as ver_id,j.is_verified,j.verified_on,j.verified_by_ip from judgment_summary j left join main m on j.diary_no=m.diary_no left join 
        users u on j.updated_by=u.usercode left join users u1 on j.verified_by=u1.usercode
              where is_deleted='f' and date(updated_on) between '$from_date' and '$to_date'";
        if ($_REQUEST) {
            $from_date = $_REQUEST['fromDate'];
            $to_date = $_REQUEST['toDate'];

            $query1 = $this->judgmentSummary->select("judgment_summary.diary_no, ma.reg_no_display as reg_no_display, ma.pet_name as pet_name, 
                ma.res_name as res_name, judgment_summary.summary, judgment_summary.updated_on, judgment_summary.updated_by_ip, u.name, 
                u.empid, judgment_summary.orderdate, u.name as ver_name, u.empid as ver_id, judgment_summary.is_verified, judgment_summary.verified_on, 
                judgment_summary.verified_by_ip")
                ->join('main_a ma', 'judgment_summary.diary_no = ma.diary_no', 'left')
                ->join('master.users u', 'judgment_summary.updated_by = u.usercode', 'left')
                ->join('master.users u1', 'judgment_summary.verified_by = u1.usercode', 'left')
                ->where('is_deleted', 'f')
                ->where("DATE(judgment_summary.updated_on) BETWEEN '$from_date' AND '$to_date'")
                ->findAll();

            $query2 = $this->judgmentSummary->select("judgment_summary.diary_no, m.reg_no_display, m.pet_name, m.res_name, judgment_summary.summary,
                 judgment_summary.updated_on, judgment_summary.updated_by_ip, u1.name as ver_name, u1.empid as ver_id, judgment_summary.is_verified, 
                 judgment_summary.verified_on, judgment_summary.verified_by_ip")
                ->join('main m', 'judgment_summary.diary_no = m.diary_no')
                ->join('master.users u', 'judgment_summary.verified_by = u.usercode')
                ->join('master.users u1', 'judgment_summary.verified_by = u1.usercode')
                ->where('is_deleted', 'f')
                ->where("DATE(judgment_summary.updated_on) BETWEEN '$from_date' AND '$to_date'")
                ->findAll();

            // Combine the results in your application code
            // $results1 = $query1->getResult();
            // $results2 = $query2->getResult();

            // Combine the results as needed
            $combinedResults = array_merge($query1, $query2);

            // $query = $this->db->getLastQuery();
            // echo $query;
            // die;

            if (!empty($combinedResults)) {
                // echo "<pre>";
                // print_r($query1);
                // die;
                $data['list'] = $combinedResults;
                $data['from_date'] = $from_date;
                $data['to_date'] = $to_date;
                return view('Editorial/report', $data);
            }
        }
    }


    public function show_count()
    {

     
        if (!empty($_POST)) {

            $from_date = $_POST['fromDate'];
            $to_date = $_POST['toDate'];
            $radio_id = $_POST['optradio'];
//            var_dump($_POST);     die;
//          DATE WISE FUNCTIONALITY ***********************************
            if ($radio_id == 1)
            {
                if (isset($from_date) && $from_date != '' && $from_date != '1970-01-01' && isset($to_date) && $to_date != '' && $to_date != '1970-01-01')
                {
                    $datewiseData = $this->escr_model->datewise_report($from_date,$to_date);
//                     echo "<pre>";
//                     print_r($datewiseData);   die;
                    if(!empty($datewiseData))
                    {
                        $data['list_stats'] = $datewiseData;
                    }else{
                        $data['list_stats'] = '';
                    }


                    //pr($datewiseData);
                }

                $data['from_date'] = $from_date;
                $data['to_date'] = $to_date;
                echo view('Editorial/datewise_report', $data);
                exit();


            } elseif ($radio_id == 2)               //        USER WISE FUNCTIONALITY ************************************
            {
                $loggedInUserRol ='';
                $userCode = $_SESSION['login']['usercode'];
                
                $loggedInUserRole = $this->escr_model->escr_user_role($userCode);
                
                if(!empty($loggedInUserRole))
                {
                    $loggedInUserRol=$loggedInUserRole[0]['role'];
                }
                
                $userwiseData = $this->escr_model->userwise_report($from_date,$to_date,$loggedInUserRol,$userCode);
                if(!empty($userwiseData))
                {
                    $data['list_stats'] = $userwiseData;
                }else{
                    $data['list_stats'] = '';
                }
                $data['from_date']=$from_date;
                $data['to_date']=$to_date;
                $data['userrole'] = $loggedInUserRol;
                echo view('Editorial/show_user_stats', $data);
                exit();
            }
        }else{
            return view('Editorial/show_stats');                         // WHEN NO RADIO BUTTON IS CLICKED SHOW PAGE FOR DATES
        }


    }



    public function user_report_details($emp_id='',$role='')
    {
        $from_date='';
        $to_date='';
        if(!empty($_GET))
        {
        $from_date = $this->request->getGet('from_date');
        $to_date = $this->request->getGet('to_date');
        $empid = $this->request->getGet('empid');
        $userrole = $this->request->getGet('userrole');
        }else{
            $empid = $emp_id;
            $userrole = $role;
        }


        $userDetails = $this->escr_model->show_user_details($from_date,$to_date,$empid);
        if(!empty($userDetails))
        {
            $data['uploaded_details'] = $userDetails;
        }else{
            $data['uploaded_details'] = '';
        }
        $data['userrole'] = $userrole;

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        return view('Editorial/show_user_details', $data);
    }


    public function delete_gist()
    {
        if(!empty($_POST))
        {
            $id = $_POST['id'];
        }
        $userCode = $_SESSION['login']['usercode'];
        $delete_gist = $this->escr_model->delete_gist($id,$userCode);


        if($delete_gist == 0)
        {
            echo "There is some problem. Please contact Computer Cell";
        }else{
            echo "Record Deleted Successfully";
        }




    }

    public function verify_gist()
    {
        $userCode = $_SESSION['login']['usercode'];
        $client_ip = getClientIP();
        $id=$_POST['id'];

        $columnsUpdate = array(
            'is_verified'=>'t',
            'verified_on'=> 'NOW()',
            'verified_by'=>$userCode,
            'verified_by_ip'=>$client_ip
        );

        $query = $this->judgmentSummary->update($id,$columnsUpdate);

        if($query)
        {
            echo "Record Verified Successfully";
        }
        else
        {
            echo "There is some problem. Please contact Computer Cell";
        }

    }

    public function edit_gist()
    {
        $userCode = $_SESSION['login']['usercode'];
        $diaryNumber = $_REQUEST['diary_no'];
        $judgmentDate = $_REQUEST['judgment_date'];
        $casetypes = $this->escr_model->get_case_type_list();
        if(!empty($casetypes))
        {
            $data['caseTypes']=$casetypes;
        }else{
            $data['caseTypes']='';
        }
        $getRole = $this->escr_model->get_role($userCode);
        if(!empty($getRole))
        {
            $data['userrole']=$getRole[0]['role'];
        }else{
            $data['userrole']='';
        }

        $caseInfo = $this->get_caseinfo_function($diaryNumber);
        if(!empty($caseInfo))
        {
            $data['caseInfo']=$caseInfo[0];


        }else{
            $data['caseInfo']='';
        }

        $judgmentdetail = $this->get_judgmentdetail_function( $diaryNumber,$judgmentDate);
        if(count($judgmentdetail)>0)
        {
            $data['judgmentInfo']=$judgmentdetail;


        }else{
            $data['judgmentInfo']='';
        }

        $remarks = $this->get_remark_function( $diaryNumber,$judgmentDate);
        if(!empty($remarks))
        {
            $data['remarksInfo']= $remarks;
        }else{
            $data['remarksInfo']='';
        }
        echo view('Editorial/eSCREntry', $data);

    }



}
