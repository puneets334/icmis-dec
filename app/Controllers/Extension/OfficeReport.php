<?php

namespace App\Controllers\Extension;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\Extension\OfficeReportModel;
use App\Models\Filing\RefilingModel;
use App\Models\Entities\Model_office_report_details;

class OfficeReport extends BaseController
{

    public $officereportModel;
    public $or_details;
    public $session;
    public $rModel;
    protected $diary_no;


    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Calcutta');
        $this->officereportModel = new OfficeReportModel();
        $this->rModel = new RefilingModel();
        ini_set('memory_limit','51200M');
        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
            // $getUrl = $uri->getSegment(3).'-'.$uri->getSegment(4);
			$getUrl = $uri->getPath();
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }
    public function index()
    {
        return view('Extension/OfficeReportViewFiles/office_report_landing_page');
    }
    public function upload()
    {
        return view('Extension/OfficeReportViewFiles/upload_office_report');
    }
	
	
	public function get_upload_office_report()
	{
		//pr($_REQUEST);
		 
		$user_details = session()->get("login");
        $ucode = $user_details['usercode'];
		$sp_listed_on=date('Y-m-d',  strtotime($_REQUEST['ddl_ord_date'])) ;
	 
		 
		$dairy_no= session()->get('filing_details')['diary_no'] ;
		 
		//$nwSql = "SELECT * FROM heardt where diary_no= '".$dairy_no."' and next_dt='".$sp_listed_on."' and board_type = 'C'";
		//$exNwQuery =  mysql_query($nwSql) or die("Error: ".__LINE__.mysql_error());
		
		$exNwQuery = is_data_from_table('heardt'," diary_no= $dairy_no and DATE(next_dt)='$sp_listed_on' and board_type = 'C' ",'*','');
		$not_found = 0;
		if(!empty($exNwQuery)){
	 
			if( strtotime( $sp_listed_on ) == strtotime(date('y-m-d'))  ){
				if( strtotime(date('H:i:s')) > strtotime(date('10:50:00')) ){
					return $msg = "No Record Found";
					die;
				}
			}
		}
 
		 
		$res_sql = is_data_from_table('main'," diary_no=$dairy_no ",'c_status','');
		if(!empty($res_sql)) 
		{
			 
			$res_sel = is_data_from_table('office_report_details'," diary_no='$dairy_no' and order_dt='$sp_listed_on' and display='Y' ",'count(id)','');
			
			$res_sel = $res_sel['count'];			 
			if($res_sel == 0)
			{
				 
				if($res_sql['c_status']=='D')
				{
					return $msg = "Case already disposed";
					die;
				}
				else
				{
					 
					
					$check_da = is_data_from_table('main'," diary_no='$dairy_no' ",'dacode','');
					$check_da = $check_da['dacode'];
					//echo $ucode;
					 
					
					$check_section = is_data_from_table('master.users'," usercode='$ucode' ",'section','');
					$check_section = $check_section['section'];

					  if(($check_da=='' or $check_da==null or $check_da==0) && $check_section!=77){
						return $msg = "<div align='center'><font style='color: red'>DA not found in matter.. Office Report can not be generated. Please Update DA in matter !!!!!</font></div>";
						exit();
					}

					else if($check_da!=$ucode  && $check_section!=77){
						return $msg = "<div align='center'><font style='color: red'>Only Concerned Dealing Assistant can upload Office Report</font></div>";
						exit();
					}  
					 
					$msg = '<input type="hidden" name="hd_diary_no" id="hd_diary_no" value="'.$dairy_no.'"/>';
					 
 
					if($_REQUEST['upd_file']!='')
					{
						$master_to_path = FCPATH .'uploads/home/';
						chdir($master_to_path);

						$parent = 'officereport';
						$year = session()->get('filing_details')['diary_year'];
						$diary_number = session()->get('filing_details')['diary_number'];

						if (!file_exists($parent)) {
							mkdir($parent, 0755, true);
						}
						chdir($parent);

						if (!file_exists($year)) {
							mkdir($year, 0755, true);
						}
						chdir($year);

						if (!file_exists($diary_number)) {
							mkdir($diary_number, 0755, true);
						}
						chdir($diary_number);
						$temp = explode(".", $_FILES["file"]["name"]);
						$extension = end($temp);

						$imageFileType = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);



						if($imageFileType != "pdf" && $_REQUEST['upd_file']!='')
						{
							$msg = "Sorry, only pdf files are allowed.";

						}
						else
						{
 
							$fil_nm=$diary_number.'_'.$year.'_'.$sp_listed_on.'.pdf';
							if(move_uploaded_file($_FILES["file"]["tmp_name"],$fil_nm))
							{
								
								$data = [
									'diary_no'          => $dairy_no,               
									'rec_dt'            => date('Y-m-d H:i:s'),     
									'rec_user_id'       => $ucode,                  
									'office_repot_name' => $fil_nm,                 
									'order_dt'          => $sp_listed_on,           
									'web_status'        => 1,                       
									'summary'           => $_REQUEST['summary'] 
								];

								// Insert data into 'office_report_details' table using Query Builder
								$this->db->table('office_report_details')->insert($data);
								if ($this->db->affectedRows() > 0) {
									$filepath='https://registry.sci.gov.in/api_for_web/website.php?key_verify=9dkf990dkjkj4323dsfkdsitef2sghDiI0W&flag=5&diary_no='.$dairy_no.'&order_date='.$sp_listed_on;
									send_whatsapp($dairy_no,$sp_listed_on,$filepath,$fil_nm);
									$msg =  "Record Save Successfully";
								} else {
									$msg = "Failed to insert record.";
								}
 
								 
							}
						}
					}
				}
			}
			else
			{
				$msg = "Record already uploaded for order date";
			}
			return $msg;
		}

	}


    public function report()
    {
//        echo "DASD";die;

        $data=[];
        $nature = $this->officereportModel->nature_from_cassetype();
//        echo "<pre>";
//        print_r($nature);die;
        if(!empty($nature))
        {
            $data['nature']= $nature;
        }else{
            $data['nature']='Error';
        }
//        echo "<pre>";
//        print_r($data);die;
        return view('Extension/OfficeReportViewFiles/report_office_report',$data);
    }

    public function get_report_type()
    {
        if(!empty($_POST))
        {
//            var_dump($_POST);die;
            $type =  $_POST['ddl_nature'];
            $reportType =  $this->officereportModel->check_report_type($type);
//           echo "<pre>";
//           print_r($reportType);die;
            if(!empty($reportType)) {
                echo json_encode($reportType);
            }else{
                echo 'Error';
            }
        }
    }

    public function display_office_report()
    {
//        echo "DASF";die;
//        var_dump($_POST);die;
        $data=[];
        if(!empty($_POST))
        {
            $rType = $_POST['rtype'];
            $nature = $_POST['nature'];
            $dno = $_POST['dno'];
            $data=[];
            $res_office_report=$res_max_o_r=$civilData=$criminalData='';

//            var_dump($_SESSION);die;
            $usercode = $_SESSION['login']['usercode'];
            $dacode = $_SESSION['filing_details']['dacode'];
//            echo $usercode.">>".$dacode;die;

            $checkSection = $this->officereportModel->check_section($usercode);
            if(!empty($checkSection[0]['section'])) {
                $section = $checkSection[0]['section'];
            }
//            print_r( $checkSection);die;
            if(($dacode =='' or $dacode==null or $dacode==0) && $section!=77){
//                $data['message_exit'] = "DA not found in matter.. Office Report can not be generated. Please Update DA in matter";
                $data['flag'] = 'da_not';
                echo view('Extension/OfficeReportViewFiles/message_page_report_office_report', $data);
                exit();

            }else if($dacode!= $usercode && $section!=77){

//                $data['message_exit'] = "Only Concerned Dealing Assistant can upload Office Report !!!!";
                $data['flag'] = 'da';
                echo view('Extension/OfficeReportViewFiles/message_page_report_office_report', $data);
                exit();
            }

            $checkHeardt = $this->officereportModel->check_heardt($dno);
//            echo "<pre>";
//            print_r($checkHeardt);die;


            if(!empty($checkHeardt[0]['next_dt']))
            {
                $nextdt = $checkHeardt[0]['next_dt'];
                $data['heardt_date']=$checkHeardt[0]['next_dt'];
                $data['d_no']=$dno;
                $office_report = $this->officereportModel->check_office_report($dno,$nextdt);
//                echo "<pre>";
//                print_r($office_report);die;
                if(!empty($office_report[0]))
                {
                    $res_office_report = $office_report[0]['id'];
                }
//                echo ">>".$res_office_report;die;
                if($res_office_report <=0)
                {

                    $emp_full_con = 0;

//              *************************************************** CHECK  FOR CONNECTED CASES ******************************************

                    $check_connected_case = $this->connected_case($dno, $emp_full_con, $nextdt, $res_max_o_r);
//                    echo "<pre>";
//                    print_r(count($check_connected_case['ex_connected_cases']));echo ">>";die;
                    if (array_key_exists('ex_connected_cases', $check_connected_case)) {
//                        echo "EE";die;
                        $ex_connected_cases = $check_connected_case['ex_connected_cases'];
//                        echo "<pre>";
//                        print_r($ex_connected_cases);die;

                        $data['connected_cases'] = $ex_connected_cases;
//  ********************************************** HANDLED THIS PART IN VIEW *********************************************************
                        $r_chk_al_con='';
                        $ch_text ='';
                        $ch_checked ='';
                        for ($i = 0; $i < count($ex_connected_cases); $i++) {
//                            echo "EEE";die;
                            if ($emp_full_con == 1) {

                                $get_batch = $this->officereportModel->get_batch_officereportdetails($dno, $nextdt);
                                if (!empty($get_batch[0]['batch'])) {
                                    $r_get_batch = $get_batch[0]['batch'];
                                }
                                $chk_al_con = $this->officereportModel->get_dno_officereportdetails($ex_connected_cases[$i], $nextdt, $r_get_batch, $res_max_o_r);
                                if (!empty($chk_al_con[0]['diary_no'])) {
                                    $r_chk_al_con = $chk_al_con[0]['diary_no'];   // THIS VALUE IS USED FOR CHECKBOX CHECKED OR NOT *****************
                                }
                                $ch_checked .= $r_chk_al_con."_";
                            }

                            $case_t = $this->officereportModel->get_reg_no_display_main($ex_connected_cases[$i]);
//                            echo "<pre>";
//                            print_r($case_t);die;
                            if (!empty($case_t[0]['reg_no_display']))
                            {
//                                echo "TT";die;
                                $ch_text .= $case_t[0]['reg_no_display']."_";
                            }else{
//                                echo "RR";die;
                                $ch_text .= "Diary No. " . substr($ex_connected_cases[$i], 0, strlen($ex_connected_cases[$i]) - 4) . '/' . substr($ex_connected_cases[$i], -4)."_";
                            }
                        }
//                        echo "<pre>";
//                        echo $ch_text;
//                        print_r($data['checkbox_text']);die;
                        $data['checkbox_text'] = $ch_text;
                        $data['checkbox_checked_value'] =$ch_checked;
                    } else {
//                        echo"FF";die;
                        $data['connected_cases'] = '';
                        $data['checkbox_text'] = '';
                        $data['checkbox_checked_value'] ='';

                    }

// ****************************************************** PAGES WILL BE START FROM HERE ********************************************************

                    $fil_detail = $this->officereportModel->filing_details($dno);
                    if (!empty($fil_detail[0])) {
//                        echo "<pre>";
//                        print_r($fil_detail);die;
                        $data['fil_details'] = $fil_detail[0];
                    }

                    $listed_in_court = $this->officereportModel->check_listed_in_court($dno);
//                    echo "<pre>";
//                    print_r($listed_in_court);die;
                    if (!$listed_in_court) {
//                        $data['message_exit'] = " Can't generate fresh office report because case is not yet updated or listed in court. ";
                        $data['flag']='c_up';
                        echo view('Extension/OfficeReportViewFiles/message_page_report_office_report', $data);
                        exit();

                    } else {
                        $listedOn = $this->get_tentative_date($dno);
//                    echo $listedOn;die;
                        if ($listedOn) {
                            $data['listed_on'] = $listedOn;
                        } else {
                            $data['listed_on'] = '';
                        }
                        $list_detail = $this->get_cause_list_details($dno);
//                    print_r($list_detail);die;
                        if (!empty($list_detail[0])) {
                            $roster_id = $list_detail[0]['roster_id'];
                            $court_no = $this->officereportModel->get_court_no($roster_id);
//                            print_r($court_no);die;
                            if(!empty($court_no))
                                $data['court_no'] = $court_no['courtno'];
                            else
                                $data['court_no'] = '';
                            $data['item_no'] = $list_detail[0]['brd_slno'];
                        } else {

                            $data['item_no'] = '0';
                        }
                        $petname=$resname='';
                        $check_report_table_for_status = $this->officereportModel->check_report_table_for_status($dno);
//                        print_r(gettype($check_report_table_for_status));die;
                        if($check_report_table_for_status <=0)
                        {
//  **************************************** LINE BELOW OF SUPREME COURT OF INDIA **********************************************************************
                            $case_no = $this->officereportModel->detail_from_main($dno);
//                          echo "<pre>";  print_r($case_no);die;
                            if(!empty($case_no[0]))
                            {
                                $res_case_no = $case_no[0];
//                                echo $res_case_no['pet_name'];die;

                                $petname = $res_case_no['pet_name'];
                                if ($res_case_no['pno'] === 2)
                                {
                                    $petname .= " and another";
                                }elseif ($res_case_no['pno'] > 2)
                                {
                                    $petname .= " and others";
                                }
//                                    print_r($petname);die;

                                $resname = $res_case_no['res_name'];
                                if ($res_case_no['rno'] == 2)
                                {
                                    $resname .= " and another";
                                } else if ($res_case_no['rno'] > 2) {
                                    $resname .= " and others";
                                }
//                                    print_r($resname);die;
                                $data['pet_names']= $petname;
                                $data['res_names']= $resname;
                                if($res_case_no['fil_no']!=NULL && $res_case_no['fil_no']!='')
                                {

                                    $sql_ct_type = $this->officereportModel->check_casetype($res_case_no['casetype_id']);
//                                    print_r($sql_ct_type);die;
                                    if(!empty($sql_ct_type[0]))
                                    {
                                        $res_ct_typ = $sql_ct_type[0]['short_description'];
                                    }
                                    if( $res_case_no['fil_dt']!='')
                                    {
                                        $fil_dt = date('Y', strtotime($res_case_no['fil_dt']));
                                    }

                                    $data['description_or_dno'] = $res_ct_typ." ".substr($res_case_no['fil_no'], 3)." of ".$fil_dt;

//                                    print_r($data['description_or_dno']);die;
                                }
                                else
                                {

                                    $data['description_or_dno'] = "Diary No. ".substr($dno, 0,  -4 )."-".substr($dno, -4 );
                                }
                            }
                            $year = date('Y');

                            $max_o_r = $this->officereportModel->check_process_id($year);
//                            echo "<pre>";
//                            print_r($max_o_r);die;
                            if(!empty($max_o_r))
                            {
                                $res_max_o_r = $max_o_r +1;
                                $data['process_id'] = $res_max_o_r.'/'.$year;
                                $data['res_max_o_r'] = $res_max_o_r;
//                                print_r($data['res_max_o_r']); die;
                            }else{
                                $data['process_id'] = '';
                            }
//                            print_r($data['process_id']);die;


//  ******************************************** 1. The limitation period of the appeal(s)/special leave petition(s) is as follows. **************************

                            $limitation_period = $this->officereportModel->check_limitation_period_leave($dno);
                            if(!empty($limitation_period))
                            {
                                $data['limitation_period'] = $limitation_period;
                                //                            echo "<pre>";
//                            print_r($data['limitation_period']);die;
                                $petition_data = $data['limitation_period'][0]['lct_dec_dt'];
//                            echo "<pre>";
//                            print_r($petition_data);die;
                                $limitation_period_petition = $this->officereportModel->check_limitation_period_petition($petition_data,$dno);
//                            echo "<pre>";
//                            print_r($limitation_period_petition);die;
                                if(!empty($limitation_period_petition))
                                {
                                    $data['limitation_period_petition'] = $limitation_period_petition;
                                }else{
                                    $data['limitation_period_petition'] ='';
                                }
//                                DEFECT CHECK FOR DELAY DAYS IN REFILING ***************************************
                                $refiling= $this->officereportModel->check_defect($dno);
//                                echo "<pre>";
//                                print_r($refiling);die;
                                $date1=$date2='';
                                $res_no_of_days=28;
                                if(!empty($refiling))
                                {
                                    $count_id = $refiling['count_id'];
                                    if($count_id <=0)
                                    {
                                        $max_refiling = $this->officereportModel->check_refiling($dno);
                                        if(!empty($max_refiling))
                                        {
                                            foreach($max_refiling as $row)
                                            {
                                                $date1=$row['rm_dt'];
                                                $date2=$row['save_dt'];
                                            }
                                            $def_rem_max_date = date('Y-m-d', strtotime($date2 . ' + ' . $res_no_of_days . ' days'));
//                                            echo $def_rem_max_date;die;
                                            $ans = $this->next_date($def_rem_max_date,1);
//                                            echo "NN".$ans;die;
                                            if($date1 == '' || $ans == '')
                                            {
                                                $data['delay_days'] = "Refiling is within time";
                                            }else{
                                                $diffq = $this->officereportModel->check_date_diff($date1,$ans);
//                                            echo "<pre>";
//                                            print_r($diffq);die;
                                                if(!empty($diffq))
                                                {
                                                    $days = $diffq[0]['days'];
                                                }
                                                $diffq1 = $this->officereportModel->check_date_diff($date1,$date2);
                                                if(!empty($diffq1))
                                                    $d_days = $diffq1[0]['days'];

                                                $diff = $d_days-28;
                                                $data['delay_days'] = $diff." days";

                                            }

//                                            if($days <=0)
//                                            {
//                                                $data['delay_days'] = "Refiling is within time";
//                                            }else{
//                                                $diffq1 = $this->officereportModel->check_date_diff($date1,$date2);
//                                                if(!empty($diffq1))
//                                                    $d_days = $diffq1[0]['days'];
//
//                                                $diff = $d_days-28;
//                                                $data['delay_days'] = $diff." days";
//                                            }
                                        }
                                    }
                                }

                            }else{
                                $data['limitation_period'] = '';
                            }

// ******************************************  2. The advocate has filed Document(s)/Interlocutory Application(s) as follows:- ***************************************

                            $doc_details = $this->officereportModel->check_for_docdetails($dno);
                            if(!empty($doc_details))
                            {
//                                $count_loop = count($doc_details);
//                                echo $count_loop;die;
                                $data['doc_detail'] = $doc_details;
                            }else{
                                $data['doc_detail']='';
                            }

// ****************************************** 3. Similarity found in the present case is based on: *******************************************************************


                            $linked_con_case = $this->officereportModel->checked_linked_con_case($dno);

//                            echo "<pre>";
//                            print_r($linked_con_case);die;
                            if(!empty($linked_con_case))
                            {

                                foreach($linked_con_case as $res_linked_con_case)
                                {
                                    $res_linked_case = $res_linked_con_case['conn_key'];
                                }
//                                echo "???".$res_linked_case;die;
                                if($res_linked_case !='')
                                {
                                    $linked_case = $this->officereportModel->linked_case($res_linked_case);
//                                    echo"<pre>";
//                                    print_r($linked_case);die;
                                    if(!empty($linked_case))
                                    {
                                        $data['linked_case'] = $linked_case;
                                        $data['res_linked_con_case_connkey'] = $res_linked_case;
                                    }

                                }
                            }else{
                                $data['linked_case'] = '';
                            }




// ************  4. It is submitted that, in terms of Order XV Rule 2, the status of proof of service upon the respondent(s)/caveator(s) is as follows:- ******************

                            $caveat_info = $this->officereportModel->check_caveat_info($dno);
//                            echo "<pre>";
//                            print_r($caveat_info);die;
                            if(!empty($caveat_info))
                            {
                                $data['caveat_data']= $caveat_info;
                            }else{
                                $data['caveat_data']='';
                            }
//                            echo "<pre>";
//                            print_r($data['caveat_data']);die;



// **********************************************  5. Amount involved in tax matters is as follows:- *****************************************

                            $claim_amt_query = $this->officereportModel->check_amt_query($dno);
                            if(!empty($claim_amt_query))
                            {
//                                print_r($claim_amt_query);die;
                                foreach($claim_amt_query as $amt)
                                {
                                    $claim_amt = $amt['claim_amt'];
                                }
//                                echo $claim_amt;die;
                                if($claim_amt !=0)
                                {
                                    $amount = $this->moneyFormatIndia($claim_amt);
//                                    echo $amount;die;
                                    $data['claim_amt'] = 'Rs. '.$amount;
                                }
                            }

                        }
//                        echo gettype($rType).">>>".gettype($nature);die;
                        if(($rType !='17') && ($rType !='18') )
                        {

                            if($nature == 'C'){
                                $data['civilData'] = $this->civilFormat($rType,$nature,$data);
                                // echo "<pre>"; print_r($data);die;
                            }
                            elseif( $nature == 'R'){
//                                echo "YYY"; die;
                                $data = $this->criminalFormat($rType,$nature,$data);
                            }
                        }


                        switch ($rType) {
                            case 1:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_after_notice', $data);
                                break;
                            case 2:
                                echo view('Extension/OfficeReportViewFiles/Civil/c_curative_petition.php', $data);
                                break;
                            case 3:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_curative', $data);
                                break;
                            case 4:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_review', $data);
                                break;
                            case 5:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_contempt', $data);
                                break;
                            case 6:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_defective_matters', $data);
                                break;
                            case 7:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_bail', $data);
                                break;
                            case 8:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_direction', $data);
                                break;
                            case 9:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_crl_mp', $data);
                                break;
                            case 10:
                                echo view('Extension/OfficeReportViewFiles/Civil/c_after_notice.php', $data);
                                break;
                            case 11:
                                include('office_report/civil/11.php');
                                break;
                            case 12:
                                include('office_report/civil/12.php');
                                break;
                            case 13:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_jail_petition', $data);
                                break;
                            case 14:
                                echo view('Extension/OfficeReportViewFiles/Criminal/r_delay_in_jail_petition', $data);
                                break;
                            case 15:
                                include('office_report/civil/15.php');
                                break;
                            case 16:
                                include('office_report/civil/16.php');
                                break;
                            case 17:
                                echo view('Extension/OfficeReportViewFiles/report_format_generate_officereport', $data);
                                break;
                            case 18:
                                include('office_report/civil/17.php');
                                break;
                            case 19:
                                include('office_report/civil/18.php');
                                break;
                            case 20:
                                include('office_report/civil/18.php');
                                break;
                            case 21:
                                include('office_report/civil/21.php');
                                break;
                            case 22:
                                include('office_report/civil/21.php');
                                break;
                            default:
                                break;
                        }


                    }
                }else{

                    $office_report = $this->officereportModel->check_report_already_generated($dno,$nextdt);
                    if(!empty($office_report))
                    {
//                        echo "<pre>";
//                        print_r($office_report);die;
                        $res_office_report = $office_report[0]['office_repot_name'];
                        $res_office_report = str_replace(' ', '', $res_office_report);
                        $summary = $office_report[0]['summary'];
                        $res_max_o_r = $office_report[0]['office_report_id'];
                        $data['res_max_o_r'] = $res_max_o_r;


                    }
                    $emp_full_con=1;
                    $data['textarea'] = $summary;
//                    echo $res_max_o_r.">>";die;
//              *************************************************** CHECK  FOR CONNECTED CASES ******************************************

                    $check_connected_case = $this->connected_case($dno, $emp_full_con, $nextdt, $res_max_o_r);
//                    echo "<pre>";
//                    print_r(count($check_connected_case['ex_connected_cases']));echo ">>";die;
                    if (array_key_exists('ex_connected_cases', $check_connected_case)) {
//                        echo "EE";die;
                        $ex_connected_cases = $check_connected_case['ex_connected_cases'];
//                        echo "<pre>";
//                        print_r($ex_connected_cases);die;

                        $data['connected_cases'] = $ex_connected_cases;
//  ********************************************** HANDLED THIS PART IN VIEW *********************************************************
                        $r_chk_al_con='';
                        $ch_text ='';
                        $ch_checked ='';
                        for ($i = 0; $i < count($ex_connected_cases); $i++) {
//                            echo "EEE";die;
                            if ($emp_full_con == 1) {

                                $get_batch = $this->officereportModel->get_batch_officereportdetails($dno, $nextdt);
                                if (!empty($get_batch[0]['batch'])) {
                                    $r_get_batch = $get_batch[0]['batch'];
                                }
                                $chk_al_con = $this->officereportModel->get_dno_officereportdetails($ex_connected_cases[$i], $nextdt, $r_get_batch, $res_max_o_r);
                                if (!empty($chk_al_con[0]['diary_no'])) {
                                    $r_chk_al_con = $chk_al_con[0]['diary_no'];   // THIS VALUE IS USED FOR CHECKBOX CHECKED OR NOT *****************
                                }
                                $ch_checked .= $r_chk_al_con."_";
                            }

                            $case_t = $this->officereportModel->get_reg_no_display_main($ex_connected_cases[$i]);
//                            echo "<pre>";
//                            print_r($case_t);die;
                            if (!empty($case_t[0]['reg_no_display']))
                            {
//                                echo "TT";die;
                                $ch_text .= $case_t[0]['reg_no_display']."_";
                            }else{
//                                echo "RR";die;
                                $ch_text .= "Diary No. " . substr($ex_connected_cases[$i], 0, strlen($ex_connected_cases[$i]) - 4) . '/' . substr($ex_connected_cases[$i], -4)."_";
                            }
                        }
//                        echo "<pre>";
//                        echo $ch_text;
//                        print_r($data['checkbox_text']);die;
                        $data['checkbox_text'] = $ch_text;
                        $data['checkbox_checked_value'] =$ch_checked;
                    } else {

                        $data['connected_cases'] = '';
                        $data['checkbox_text'] = '';
                        $data['checkbox_checked_value'] ='';

                    }

//                    echo $dno;die;
                    $diary_length=strlen($dno);
                    $d_yr=substr($dno, ($diary_length-4));
                    $d_no=substr($dno, 0, -4);


//                    $fil_nm = "../officereport/" . $_REQUEST[d_yr] . '/' . $_REQUEST[d_no] . '/' . $res_office_report;
//                    $fil_nm = "./home/ubuntu/PhpstormProjects/Master/public/upload/".$d_no.'/'.$d_yr.'/'. $res_office_report;
                    //$fil_nm = "./home/ubuntu/PhpstormProjects/Master/public/upload/";

                    $baseurl = base_url();
                    $fil_nm= $baseurl."/officereports/".$d_yr."/".$d_no."/".$res_office_report;

                    //$fil_nms="<embed src='$fil_nm' type='application/html' width='100%' height='800'>";
//                        $baseurl."/upload/officereports/".$d_no."/".$d_yr."/". $res_office_report;

//                    <embed src="/uploaded_docs/user_manual/3pdf_user_manual.pdf" type="application/pdf" width="100%" height="800">
//                        -v /mnt/sc_efm/uploaded_docs:/var/www/html/uploaded_docs
                    //print_r( $fil_nm);die;

//                    $ds = fopen($fil_nm, 'r');
//                    print_r($ds);die;
//                    $b_z = fread($ds, filesize($fil_nm));
//                    $path = trim($fil_nm);
//                    echo $path;die;
                    $b_z = file_get_contents("$fil_nm");
//                    echo $b_z;exit();
//                    fclose($ds);
//                    echo utf8_encode($b_z);
                    $data['filecontent']=$b_z;

                    echo view('Extension/OfficeReportViewFiles/upload_report_office_report', $data);
                    exit();
                }



            }else{
//                echo "DDF";die;
//                $data['message_exit'] = "Can't generate office report because cause list not yet printed.";
                $data['flag']='c_lt';
                echo view('Extension/OfficeReportViewFiles/message_page_report_office_report', $data);
                exit();

            }

        }

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

    public function civilFormat($reportType, $natureSelected, $records )
    {
        // echo "CIVIL";
        // echo "<pre>"; print_r($records);
        if($reportType == '2' && $natureSelected == 'C'){

            $data = [];
            $lower_court = $this->lower_court($records['d_no']);
            for ($index1 = 0; $index1 < count($lower_court); $index1++) {
                // $agency_name=$lower_court[$index1][2];
                $skey=$lower_court[$index1][3];
                $lct_caseno=$lower_court[$index1][4];
                $lct_caseyear=$lower_court[$index1][5];
                $lct_casetype=$lower_court[$index1][6];

                if($lct_casetype=='9' ){
                    $data['caseinof'] = array([
                        'skey' => $lower_court[$index1][3],
                        'lct_caseno' => $lower_court[$index1][4],
                        'lct_caseyear' => $lower_court[$index1][5],
                        'lct_casetype' => $lower_court[$index1][6],
                        'judgement_dt_lw' => $new_date = date('dS F, Y', strtotime($lower_court[$index1][0])),
                        'lw_cur_lw' =>  $lower_court[$index1][3],
                        'lw_cur_no' => $lower_court[$index1][4],
                        'lw_cur_yr' => $lower_court[$index1][5],
                        'lw_case_code' => $lower_court[$index1][5]
                    ]);
                }
                if($lct_casetype!='9' ){
                    $data['caseinof'] = array([
                        'skey' => $lower_court[$index1][3],
                        'lct_caseno' => $lower_court[$index1][4],
                        'lct_caseyear' => $lower_court[$index1][5],
                        'lct_casetype' => $lower_court[$index1][6],
                        'judgement_dt_lw_s'=> $new_date = date('dS F, Y', strtotime($lower_court[$index1][0])),
                        'lw_cur_lw_s'=>  $lower_court[$index1][3],
                        'lw_cur_no_s'=> $lower_court[$index1][4],
                        'lw_cur_yr_s'=> $lower_court[$index1][5],
                        'lw_case_code_s' => $lower_court[$index1][5]
                    ]);
                }
            }
            $data['get_petitioner_advocate']= $this->officereportModel->get_petitioner_advocate_cur($records['d_no']);
            $data['get_application_registration'] = $this->officereportModel->get_application_registration($records['d_no']);
            $data['send_to_advocate_z'] = $this->officereportModel->send_to_advocate_z($records['d_no']);
            return $data;

        }
        if($reportType == '10' && $natureSelected == 'C'){
            $data = [];


            $get_last_listed_date_lst = $this->officereportModel->get_last_listed_date($records['d_no']);
            $data['listed_dt_lst'] = $get_last_listed_date_lst != '' ? date('dS F, Y', strtotime($get_last_listed_date_lst)) : '' ;
            $data['n_date_ymd'] =    $get_last_listed_date_lst != '' ? date('Y-m-d', strtotime($get_last_listed_date_lst)) : '' ;

            if($get_last_listed_date_lst !=''){
                // $fil_nm = $this->officereportModel->get_text_pdf($records['d_no'],$get_last_listed_date_lst);
                // echo "<pre>"; print_r($fil_nm); die;
                // $data['fil_nm'] = $this->read_txt_file($fil_nm);
                $data['fil_nm'] = "";
            }


            
            $data['fil_nm2'] = $this->officereportModel->get_filnm2($records['d_no']);
            
            if($data['n_date_ymd'] != ''){
                $data['serve_status'] = $this->officereportModel->get_serve_status($records['d_no'], $data['n_date_ymd']);
            }


            $data['doc_case_status'] = $this->officereportModel->get_doc_case_status($records['d_no']);
            // echo "<pre>"; print_r($data['doc_case_status']); die;

            $data['send_to_advocate_z'] = $this->officereportModel->send_to_advocate_z($records['d_no']);

            return $data;
        }

        if($reportType == '11' && $natureSelected == 'C'){

            $data = [];

            $lower_court = $this->lower_court($records['d_no']);
            for ($index1 = 0; $index1 < count($lower_court); $index1++) {
                // $agency_name=$lower_court[$index1][2];
                $skey=$lower_court[$index1][3];
                $lct_caseno=$lower_court[$index1][4];
                $lct_caseyear=$lower_court[$index1][5];
                $lct_casetype=$lower_court[$index1][6];

                if($lct_casetype=='9' ){
                    $data['caseinof'] = array([
                        'skey' => $lower_court[$index1][3],
                        'lct_caseno' => $lower_court[$index1][4],
                        'lct_caseyear' => $lower_court[$index1][5],
                        'lct_casetype' => $lower_court[$index1][6],
                        'judgement_dt_lw' => $new_date = date('dS F, Y', strtotime($lower_court[$index1][0])),
                        'lw_cur_lw' =>  $lower_court[$index1][3],
                        'lw_cur_no' => $lower_court[$index1][4],
                        'lw_cur_yr' => $lower_court[$index1][5],
                        'lw_case_code' => $lower_court[$index1][5]
                    ]);
                }
                if($lct_casetype!='9' ){
                    $data['caseinof'] = array([
                        'skey' => $lower_court[$index1][3],
                        'lct_caseno' => $lower_court[$index1][4],
                        'lct_caseyear' => $lower_court[$index1][5],
                        'lct_casetype' => $lower_court[$index1][6],
                        'judgement_dt_lw_s'=> $new_date = date('dS F, Y', strtotime($lower_court[$index1][0])),
                        'lw_cur_lw_s'=>  $lower_court[$index1][3],
                        'lw_cur_no_s'=> $lower_court[$index1][4],
                        'lw_cur_yr_s'=> $lower_court[$index1][5],
                        'lw_case_code_s' => $lower_court[$index1][5]
                    ]);
                }
            }


            $get_defect_ent_dt = $this->officereportModel->get_defect_ent_dt($records['d_no']);
            $data['defect_ent_dt_fmt']=  date('dS F, Y', strtotime($get_defect_ent_dt));


            $get_last_listed_date_lst = $this->officereportModel->get_last_listed_date($records['d_no']);
            $data['listed_dt_lst'] = $get_last_listed_date_lst != '' ? date('dS F, Y', strtotime($get_last_listed_date_lst)) : '' ;

            if($get_last_listed_date_lst !=''){
                // $fil_nm = $this->officereportModel->get_text_pdf($records['d_no'],$get_last_listed_date_lst);
                // echo "<pre>"; print_r($fil_nm); die;
                // $data['fil_nm'] = $this->read_txt_file($fil_nm);
                $data['fil_nm'] = "";
            }

            $data['send_to_advocate_z'] = $this->officereportModel->send_to_advocate_z($records['d_no']);


            return $data;
        }
    }


    public function criminalFormat($reportType, $natureSelected, $records)
    {
//        **************************************************CURATIVE REPORT TYPE CONDITION STARTS FROM HERE ****************************************************
//        echo "<pre>";
//        print_r($records);die;
//        echo $reportType;die;
        $dataCr=[];
        $ct='';
        $dataCr['d_no'] = $records['d_no'];
        $dataCr['heardt_date'] = $records['heardt_date'];
        $dataCr['checkbox_text'] = $records['checkbox_text'];
        $dataCr['checkbox_checked_value'] = $records['checkbox_checked_value'];
        $dataCr['connected_cases'] = $records['connected_cases'];
        $getCaseType = $this->getcasetype($records['d_no']);
        if (!empty($getCaseType)) {
            $ct = $getCaseType[0]['casetypeid'];
            if ($ct == 1 or $ct == 2) {
                $dataCr['casetype_detail'] = "EXTRA-ORDINARY APPELLATE JURISDICTION";
            } else {
                $dataCr['casetype_detail'] = "CIVIL/CRIMINAL APPELLATE JURISDICTION";
            }

        } else {
            $dataCr['casetype_detail'] = '';
        }

        $casename = $records['fil_details']['casename'];
        $case_range = intval(substr($records['fil_details']['fil_no'], 3));
        if (!empty($records['fil_details']['fil_dt'])) {
            $reg_year = date('Y', strtotime($records['fil_details']['fil_dt']));
        } else {
            $reg_year = '';
        }

        $dataCr['pet_line_casename'] = $casename;
        $dataCr['pet_line_case_range'] = $case_range;
        $dataCr['pet_line_year'] = $reg_year;

//        print_r($dataCr['casetype_id']);die;
        $year = date('Y');
        $max_o_r = $this->officereportModel->check_process_id($year);
//                            echo "<pre>";
//                            print_r($max_o_r);die;
        if (!empty($max_o_r)) {
            $res_max_o_r = $max_o_r + 1;
            $dataCr['process_id'] = $res_max_o_r . '/' . $year;
            $dataCr['res_max_o_r'] = $res_max_o_r;
//                                print_r($data['res_max_o_r']); die;
        } else {
            $dataCr['process_id'] = '';
        }
        $dataCr['pet_name'] = $records['fil_details']['pet_name'];
        $dataCr['res_name'] = $records['fil_details']['res_name'];
        $get_petitioner_advocate = $this->get_petitioner_advocate($records['d_no']);
        if (!empty($get_petitioner_advocate)) {
            foreach ($get_petitioner_advocate as $row) {
                $pet_adv_title = $row['title'];
                $pet_adv_name = $row['name'];
                $dataCr['pet_adv_title_name'] = $pet_adv_title . ' ' . $pet_adv_name;
            }
        }
        $get_misc_re = $this->get_misc_re($records['d_no']);
//        echo "<pre>";print_r($get_misc_re);die;
        if (!empty($get_misc_re)) {
            $dataCr['get_misc_re'] = $get_misc_re;
        }

        if($reportType == '3') {

//        echo $lw_case_code."??".$lw_cur_no.">>".$lw_cur_yr;die;
            $judgement_dt_lw =$agency_name= $lw_cur_yr =$dispname= '';
            $lw_case_code = $lw_cur_no = $lw_cur_lw = $skey = $lct_caseno = $lct_caseyear = $lct_casetype = '';
            $lower_court = $this->lower_court($records['d_no']);
//        echo "<pre>";
//        print_r($lower_court);
            $dataCr['lower_court'] = $lower_court;
            $dataCr['casetype_id'] = $ct;
            for ($index1 = 0; $index1 < count($lower_court); $index1++) {
                $agency_name = $lower_court[$index1][2];
                $skey = $lower_court[$index1][3];
                $lct_caseno = $lower_court[$index1][4];
                $lct_caseyear = $lower_court[$index1][5];
                $lct_casetype = $lower_court[$index1][6];
//                echo ">>".$lct_casetype;
//                if ($ct == '26' && $lct_casetype == '10') {
//                    $judgement_dt = $new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
//                    $lw_cur = $lower_court[$index1][3];
//                }
                if ($ct == '26' && $lct_casetype == '10') {
                    $judgement_dt_lw = $new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
                    $lw_cur_lw = $lower_court[$index1][3];
                    $lw_cur_no = $lower_court[$index1][4];
                    $lw_cur_yr = $lower_court[$index1][5];
                    $lw_case_code = $lower_court[$index1][6];
                }
            }
//            echo $lw_case_code.">>".$lw_cur_no.">>".$lw_cur_yr;die;

//        ***********************************************************************BELOW FUNCTION IS NOT COMPLETE NEED VANDANA MAM HELP
            $get_diary_case_type = get_diary_case_type($lw_case_code, $lw_cur_no, $lw_cur_yr);
//          echo "<pre>";
//          print_r($get_diary_case_type);echo "FFF";die;
            $get_dismissal_type = $this->officereportModel->get_dismissal_type($get_diary_case_type);
//          echo "<pre>";
//          print_r($get_dismissal_type);die;
            if (!empty($get_dismissal_type)) {
                $dispname = $get_dismissal_type[0]['dispname'];
                $dataCr['dispname'] = $dispname;
            }else{
                $dataCr['dispname'] = null;
            }

            $dataCr['send_to_advocate_z'] = $this->officereportModel->send_to_advocate_z($records['d_no']);
//        echo "<pre>";
//        print_r($dataCr['send_to_advocate_z']);die;

            return $dataCr;
        }elseif ($reportType == '4')
        {

            $lower_court = $this->lower_court($records['d_no']);
            if(!empty($lower_court))
            {
               $dataCr['lower_court'] = $lower_court;
            }
//            echo "<pre>";
//            print_r($lower_court);die;
            $dataCr['send_to_advocate_z'] = $this->officereportModel->send_to_advocate_z($records['d_no']);
            return $dataCr;
        }elseif ($reportType == '5')
        {
            $lower_court = $this->lower_court($records['d_no']);
            if(!empty($lower_court))
            {
                $dataCr['lower_court'] = $lower_court;
            }
            $dataCr['send_to_advocate_z'] = $this->officereportModel->send_to_advocate_z($records['d_no']);
            return $dataCr;
        }elseif ($reportType == '6')
        {
            $get_last_listed_date= $this->officereportModel->get_last_listed_date_df($records['d_no']);
//            echo "<pre>";
//            print_r($get_last_listed_date);die;
            $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
//            echo "<pre>";
//            print_r($listed_dt);die;
            if(!empty($listed_dt))
            {
                $dataCr['listed_dt'] = $listed_dt;
            }else{
                $dataCr['listed_dt'] ='';
            }
            $lower_court = $this->lower_court($records['d_no']);
            if(!empty($lower_court))
            {
               $dataCr['lower_court'] = $lower_court;
            }
            $get_defect_ent_dt = $this->officereportModel->get_defect_ent_dt($records['d_no']);
//            echo "<pre>";print_r($get_defect_ent_dt);die;
            $dataCr['defect_ent_dt_fmt']=  date('dS F, Y', strtotime($get_defect_ent_dt));
            if($get_last_listed_date !='')
            {
//                FUNCTION MADE BY NITIN WAITING FOR GIT TO UP HOLD AS IF NOW
//                $fil_nm= $this->get_text_pdf($records['d_no'],$get_last_listed_date);
                // echo "<pre>"; print_r($fil_nm); die;
                // $data['fil_nm'] = $this->read_txt_file($fil_nm);
                $dataCr['fil_nm'] = "";

            }

            $dataCr['send_to_advocate_z'] = $this->officereportModel->send_to_advocate_z($records['d_no']);
            return $dataCr;
        }elseif($reportType == '14')
        {
            $lower_court = $this->lower_court($records['d_no']);
            if(!empty($lower_court))
            {
                $dataCr['lower_court'] = $lower_court;
            }
            $dd= $this->officereportModel->delay_days_jp($records['d_no']);
            if(!empty($dd))
            {
                $dataCr['dd'] = $dd[0]['limit_days'];
            }else{
                $dataCr['dd'] = '';
            }
            $ia_name='';
            $get_doc_ia_type_details=$this->officereportModel->get_doc_ia_type_details($records['d_no'],'bail');
            // echo count($get_doc_ia_type_details);die;
            if(!empty($get_doc_ia_type_details))
            {
                for ($index2 = 0; $index2 < count($get_doc_ia_type_details); $index2++) {
                    if($ia_name=='')
                        $ia_name=$get_doc_ia_type_details[$index2][1].' of'.$get_doc_ia_type_details[$index2][2].'- '.$get_doc_ia_type_details[$index2][0];
                    else
                        $ia_name=$ia_name.', '.$get_doc_ia_type_details[$index2][1].' of'.$get_doc_ia_type_details[$index2][2].'- '.$get_doc_ia_type_details[$index2][0];
                }
                $dataCr['ia_name'] = $ia_name;

            }else{

                $dataCr['ia_name'] = $ia_name;
            }

            $get_respondent_advocate= $this->officereportModel->get_respondent_advocate($records['d_no']);
            if(!empty($get_respondent_advocate))
            {
                $dataCr['get_respondent_advocate'] = $get_respondent_advocate;
            }else{
                $dataCr['get_respondent_advocate'] = '';
            }


            $dataCr['send_to_advocate_z'] = $this->officereportModel->send_to_advocate_z($records['d_no']);
            return $dataCr;
        }
        /*elseif($reportType == '8')
        {

        }elseif($reportType == '9')
        {

        }elseif($reportType == '13')
        {

        }elseif($reportType == '22')
        {

        }elseif($reportType == '')
        {

        }*/



    }

    public function getcasetype($dno)
    {
        $getData = $this->officereportModel->getcasetype($dno);
//        echo "<pre>";
//        print_r($getData);die;
        if(!empty($getData))
        {
            return $getData;
        }else{
            return 0;
        }
    }


    public function get_petitioner_advocate($dno)
    {
        $getData = $this->officereportModel->get_petitioner_advocate_cur($dno);
//        echo "<pre>";
//        print_r($getData);die;
        if(!empty($getData))
        {
            return $getData;
        }else{
            return 0;
        }

    }



    public function get_misc_re($dno)
    {
        $getData = $this->officereportModel->get_misc_re_ofcreport($dno);
//        echo "<pre>";
//        print_r($getData);die;
        if(!empty($getData))
        {
            $outer_array = array();
            foreach($getData as $row)
            {
                $outer_array[0] = $row['short_description'];
                $regno=substr($row['new_registration_number'], 3);
                $regno=explode('-',$regno);
                if(sizeof($regno)>1){
                    $reg_no=ltrim($regno[0],'0').'-'.ltrim($regno[1],'0');
                }
                else {
                    $reg_no = ltrim($regno[0], '0');
                }
                $outer_array[1] = $reg_no;
                $outer_array[2] = $row['new_registration_year'];
                $outer_array[3] = $row['casename'];
                $outer_array[4] = $row['order_date'];
                //  print_r($outer_array);die;
                return $outer_array;

            }
        }else{
            return 0;
        }

    }

    public function lower_court($dno)
    {
        $res_chk_casetype='';
        $chk_casetype = $this->officereportModel->check_casetype_lower_court($dno);
//        echo "<pre>";
//        print_r($chk_casetype);die;
        if(!empty($chk_casetype))
        {
            $res_chk_casetype = $chk_casetype[0]['active_casetype_id'];
        }
//        echo ">>".$res_chk_casetype;die;
        $is_order_challenged = '';
        if ($res_chk_casetype != 25 && $res_chk_casetype != 26 && $res_chk_casetype != 7 && $res_chk_casetype != 8) {
            $is_order_challenged = "is_order_challenged = 'Y' ";
        }
        $query = $this->officereportModel->check_lower_court($is_order_challenged,$dno);
//        echo "<pre>";
//        print_r($query);die;

        if(!empty($query))
        {
            $outer_array = array();

            foreach ($query as $row)
            {
                $inner_array = array();
                $inner_array[0] = $row['lct_dec_dt'];
                $inner_array[1] = $row['name'];
                $inner_array[2] = $row['agency_name'];
                $inner_array[3] = $row['type_sname'];
                $inner_array[4] = $row['lct_caseno'];
                $inner_array[5] = $row['lct_caseyear'];
                $inner_array[6] = $row['lct_casetype'];
                $inner_array[7] = $row['lct_judge_desg'];
                $inner_array[8] = $row['lower_court_id'];
                $outer_array[] = $inner_array;

            }

            return $outer_array;
        }else{
            return 0;
        }
    }

    public function publish_office_report()
    {

//        var_dump($_POST);
//        die;

        if(!empty($_POST))
        {
            $chk_status=0;
            $con_case = $_POST['connected_case'];
            $hd_next_dt = $_POST['hd_next_dt'];
            $dno = $_POST['dno'];
            $sel_off_rpt = $this->officereportModel->check_ofc_report_detail_publish($dno,$hd_next_dt);
//            echo "<pre>";
//            print_r($sel_off_rpt);
//            die;
            if($sel_off_rpt[0]['id']>0)
            {
                $upd_publish = $this->officereportModel->upload_office_report_publish($dno,$hd_next_dt);
//                echo "QQ";die;
                if($upd_publish)
                {
//                    echo "QQ1";
                    if($con_case !='') {
                        $ex_exp = explode(',', $con_case);

                        for ($index = 0; $index < count($ex_exp); $index++) {

                            $sel_off_rpt_conn_case = $this->officereportModel->check_ofc_report_detail_publish($ex_exp[$index], $hd_next_dt);
                            if ($sel_off_rpt_conn_case[0]['id'] > 0) {
                                $upd_publish = $this->officereportModel->upload_office_report_publish($ex_exp[$index], $hd_next_dt);
                                if ($upd_publish) {
                                    $chk_status = 1;
                                }
                            } else {
                                $chk_status = 2;
                            }
                        }
                    }

                    $chk_status=1;
                }

            } else
            {
                $chk_status=2;
            }
            echo $chk_status;

        }

    }

    public function is_holiday($date)
    {
        $holiday = $this->officereportModel->get_holiday_for_court($date);
//        echo "<pre>";
//        print_r($holiday[0]);
//        die;
        if (!empty($holiday[0]))
            return 1;
        else
            return 0;
    }

    public function next_date($date, $day)
    {
//        echo $date.">>".$day;die;

        $nxt_dt = $date;
        $count = 1;
        while ($count <= $day) {
//            echo "DSA";
            $ch = $this->is_holiday($nxt_dt);
//echo $ch.">>>";
//die;

            if ($ch == 1) {
                $nxt_dt = date('Y-m-d', strtotime($nxt_dt . '+1day'));
//                echo "HHHH=".$nxt_dt;
                continue;
            } else {
                if ($count == $day) {
                    return $nxt_dt;
                }
                $count++;

                $nxt_dt = date('Y-m-d', strtotime($nxt_dt . '+1day'));
                echo "next date is " . $nxt_dt;
            }
        }
    }

    public function moneyFormatIndia($num) {
        $explrestunits = "" ;
        if(strlen($num)>3) {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if($i==0) {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash; // writes the final format where $currency is the currency symbol.
    }

    public function get_tentative_date($dairy_no)
    {

        $check_listed = $this->officereportModel->check_listed($dairy_no);
        if (!empty($check_listed[0])) {
            return $check_listed[0]['tentative_cl_dt'];
        }else{
            return 0;
        }
    }

    public function get_cause_list_details($dairy_no)
    {

        $list_detail = $this->officereportModel->get_cause_list_detail_check($dairy_no);
        if (!empty($list_detail[0])) {
            return $list_detail;
        }else{
            return 0;
        }
    }

    public function connected_case($diaryNo,$emp_full_con,$nextdt,$res_max_o_r='')
    {

        $res_connected_cases=$r_connected_cases='';
        $cases=[];
        $connected_cases = $this->officereportModel->check_for_connected_case($diaryNo);
//        echo "<pre>";
//        print_r($connected_cases);die;
        if(!empty($connected_cases[0]['conn_key']))
        {
            $r_connected_cases = $connected_cases[0]['conn_key'];
            if($r_connected_cases != $diaryNo && $r_connected_cases != '')
                $res_connected_cases = $r_connected_cases;
//            echo $res_connected_cases;die;
            $cnt_cases = $this->officereportModel->check_cases($r_connected_cases);
//            echo "<pre>";
//            print_r($cnt_cases);die;
            if(!empty($cnt_cases))
            {
                foreach($cnt_cases as $row)
                {
                    if($res_connected_cases=='' && $row['diary_no']!=$diaryNo)
                        $res_connected_cases=$row['diary_no'];
                    else if($row['diary_no']!=$diaryNo)
                        $res_connected_cases=$res_connected_cases.','.$row['diary_no'];
                }
            }
//            print_r($res_connected_cases);die;
            $ex_connected_cases =  explode(',', $res_connected_cases);
//            print_r($ex_connected_cases);die;
//            for ($i = 0; $i < count($ex_connected_cases); $i++) {
//
//                if($emp_full_con==1)
//                {
//                    $r_chk_al_con='';
//                    $get_batch = $this->officereportModel->get_batch_officereportdetails($diaryNo,$nextdt);
//                    if(!empty($get_batch[0]['batch']))
//                    {
//                        $r_get_batch = $get_batch[0]['batch'];
//                    }
//                    $chk_al_con = $this->officereportModel->get_dno_officereportdetails($ex_connected_cases[$i],$nextdt,$r_get_batch,$res_max_o_r);
//                    if(!empty($chk_al_con[0]['diary_no']))
//                    {
//                        $r_chk_al_con = $chk_al_con[0]['diary_no'];   // THIS VALUE IS USED FOR CHECKBOX CHECKED OR NOT *****************
//                    }
//
//                }
//
//            }
            $cases['ex_connected_cases']=$ex_connected_cases;
            return $cases;

        }else{
            $cases['message_connected_case']='No Connected Case';
            return $cases;
        }
    }







    public function reprint()
    {
//        var_dump($_POST);die;
        $user_code='';
        $data =[];
        if(!empty($_POST))
        {
            $txtFromDate = $_POST['fdate'];
            $txtToDate = $_POST['tdate'];
//            $dno = $_POST['dno'];
            $user_details = session()->get("login");
            $ucode = $user_details['usercode'];
//            echo $ucode;
            if($ucode!=1)
            {

                $server_status = $this->officereportModel->record_for_table($txtFromDate,$txtToDate,$ucode);
            }else{
                $server_status = $this->officereportModel->record_for_table($txtFromDate,$txtToDate);
            }
//            echo "<pre>";
//            print_r($server_status);die;
            if(!empty($server_status))
            {
                $data['table_record_display']=$server_status;
                $data['fdate']=$txtFromDate;
                $data['tdate']=$txtToDate;
//                $data['dno']=$dno;

                echo view('Extension/OfficeReportViewFiles/reprint_office_report_table_data',$data);
            }else{
                echo "<center><h4 style='color:Red'>NO RECORD FOUND</h4></center>";
            }

        }else{
            return view('Extension/OfficeReportViewFiles/reprint_office_report');
        }

    }





    public function reprint_discard_data()
    {
       // var_dump($_POST);die;
        if(!empty($_POST))
        {
            $dno = $_POST['dno'];
            $recDate = date('Y-m-d',strtotime($_POST['recdt']));
//            echo $dno.">>>".$recDate;die;
            $discardQuery = $this->officereportModel->discard_data($dno, $recDate);
            if($discardQuery)
            {
                echo "SUCCESSFULLY DISCARDED !!!";
            }else{
                echo "Error in deleting Office Report!!!";
            }
        }else{
            echo "Error, Please contact computer cell";
        }
    }




    public function getORDetailsListingDate($d_no,$listing_date)
    {
        $or_details=new Model_office_report_details;

        $details=$or_details->select('*')->where('diary_no',$d_no)->where('order_dt',$listing_date)->where('display','Y')->findAll();
        if(!empty($details) && (count($details)==1))
            return $details;
        else
            return false;

    }



    public function CopyOR()
    {
        $or_details=new Model_office_report_details;
        if(!empty($_POST)) {
            $diary_length=strlen($_POST['d_no']);
            $d_yr=substr($_POST['d_no'], ($diary_length-4));
            $d_no=substr($_POST['d_no'], 0, -4);
            $ORDetails = $this->getORDetailsListingDate($_POST['d_no'], $_POST['old_date']);
            $newORDetails=$this->getORDetailsListingDate($_POST['d_no'], $_POST['new_date']);
            if($newORDetails!=false)
            {
                echo "Office Report already available for new listing date.";
            }
            if ($ORDetails == false)
            {
                echo "Office Report not found for this particular date.Please enter Proper details.";
            }
            else if($newORDetails==false && $ORDetails!=false){
                $or_name=explode('.',$ORDetails[0]['office_repot_name']);
                if($or_name[1]=='pdf')
                {
                    echo 'PDF file cannot be copied.';
                    exit();
                }
// To change path on live from here
                $master_to_path = '../public/officereport';
                if (!is_dir($master_to_path)) {
                    mkdir($master_to_path);
                }

                $year_path=$master_to_path.'/'.$d_yr;
                if (!is_dir($year_path)) {
                    mkdir($year_path);
                }
                $diary_path=$year_path.'/'.$d_no;
                if (!is_dir($diary_path))
                {
                    mkdir($diary_path);

                }

                $fil_nm=trim($or_name[0]);

                $new_fil_nm=$fil_nm.time().".html";
                if (file_exists($ORDetails[0]['office_repot_name'])) {
                    //echo "file exists!!";
                    if (!copy($ORDetails[0]['office_repot_name'], $new_fil_nm))
                        echo 'Office Report file cannot be copied!';
                    else
                        echo 'Office Report file copied successfully!';
                }
                else {
                    echo 'Office Report file not found!';
                }
// to change path on live ends
                $update_array=[
                    'diary_no'=>$_POST['d_no'],
                    'office_report_id'=>$ORDetails[0]['office_report_id'],
                    'rec_dt'=>$ORDetails[0]['rec_dt'],
                    'rec_user_id'=>$ORDetails[0]['rec_user_id'],
                    'status'=>$ORDetails[0]['status'],
                    'office_repot_name'=>trim($new_fil_nm,''),
                    'order_dt'=>$_POST['new_date'],
                    'web_status'=>$ORDetails[0]['web_status'],
                    'master_id'=>$ORDetails[0]['master_id'],
                    'batch'=>$ORDetails[0]['batch'],
                    'create_modify'=>date("Y-m-d H:i:s"),
                    'updated_by'=>session()->get('login')['usercode'],
                    'updated_on'=>date("Y-m-d H:i:s"),
                    'updated_by_ip'=>getClientIP()
                ];
                $or_details->insert($update_array);
                if($or_details)echo 'Office Report Details Copied Successfully!';
            }
        }
        else {
            return view('Extension/OfficeReportViewFiles/copy_office_report');
        }
    }


    public function bulk()
    {
        return view('Extension/OfficeReportViewFiles/bulk_office_report');
    }

    public function save_office_report(){
        // echo "<pre>"; print_r($_POST); die;
        if(!empty($_POST)){
            $dataArr = $_POST;
            $data = $this->officereportModel->save_office_report($dataArr);
            echo $data;
        }
    }


}


?>