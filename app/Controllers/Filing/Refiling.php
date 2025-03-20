<?php

namespace App\Controllers\Filing;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\Filing\RefilingModel;
use App\Models\Filing\DefectModel;

class Refiling extends BaseController
{
    protected $session;
    public $dModel;
    public $rModel;

    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Calcutta');
        $this->rModel = new RefilingModel();
        $this->dModel = new DefectModel();
    }


    public function index()
    {
        $data_report=[];
        $data_table=[];
        $table_data =[];
        $filing_details = session()->get('filing_details');
//        echo "<pre>";
//        print_r($filing_details);
//        die;
        if (!empty($filing_details['diary_no'])) {

            $diaryNumber = $filing_details['diary_no'];
            $petitionerName = $filing_details['pet_name'];
            $respondentName = $filing_details['res_name'];
            $date = date('d-m-Y', strtotime($filing_details['diary_no_rec_date']));
            $caseGroup = $filing_details['case_grp'];
            $fileNumber = $filing_details['fil_no'];
            $caseStatus = $filing_details['c_status'];
            $caseTypeId = $filing_details['casetype_id'];
            $daCode = $filing_details['dacode'];
            $nextdate = $total = $preCovid = $deadCoronaPeriod = $totalDelayDays = $delayDaysCal = '';
            $df = $def_notify_date = $def_rem_max_date = '';
            $returnDefectDays = [];
            $offSet = 5.5 * 60 * 60;
            $curDate = gmdate('d-m-Y g:i a', time() + $offSet);

//          CONDITION FOR DEFECTS ENTRY IN R.P./CUR.P/CONT.P/MA   ***********************************************************************

            $ucode = session()->get('login')['usercode'];
            $checkSection = $this->dModel->check_section($ucode);
//            echo "<pre>";
//            print_r($checkSection);
//            die;
            if ($checkSection > 0) {

                $caseType = array('9', '10', '19', '20', '25', '26', '39');
                if (!in_array($caseTypeId, $caseType)) {
                    $data['message'] = 'Defects can be added in RP/CUR.P/CONT.P./MA';
                    return view('Filing/refiling_messages_view', $data);
                    exit;

                }
                $da = $this->dmodel->get_da($diaryNumber);

                if ($da != $ucode) {
                    $data['message'] = 'Defects can be updated by concerned Dealing Assistant';
                    return view('Filing/refiling_messages_view', $data);
                    exit;

                }
            }

            $detailFiling = $this->rModel->get_filing_details($diaryNumber);
//             echo "<pre>";
//             print_r($detailFiling); die;
            if (!empty($detailFiling)) {
                foreach ($detailFiling as $details) {
                    $cause_title = $details['cause_title'];
                    $diary_date = $details['diary_date'];
                    $def_notify_date = $details['defect_date'];
                    $df = $details['df'];
                }
//                   echo $cause_title.">>>".$diary_date.">>>".$def_notify_date.">>".$df;die;

                if ($def_notify_date != Null) {
                    $currentDate = date('Y-m-d');

                    $getDays = $this->rModel->get_no_of_days($currentDate);
//                    print_r( $getDays);die;
                    $def_rem_max_date = date('Y-m-d', strtotime($def_notify_date . ' + ' . @$getDays['no_of_days'] . ' days'));
//                    echo $def_rem_max_date.">>>";
                    $nextdate = $this->next_date($def_rem_max_date, 1);
//                    echo $nextdate;die;
                } else {
                    $data['message'] = 'No Defects Found';
                    return view('Filing/refiling_messages_view', $data);
                }

            } else {
                $data['message'] = 'No Defects Found';
                return view('Filing/refiling_messages_view', $data);
//                exit();
            }
//            echo $def_rem_max_date.">>>>>".$nextdate;die;

//           CONDITION TO CHECK IF REFILING I.A EXIST  ******************************************************************************

            $refiling = 0;
            $offset = 5.5 * 60 * 60;
            $cur_date = gmdate('d-m-Y g:i a', time() + $offset);
            $iaCheck = $this->rModel->check_ia_exist($diaryNumber);
//            echo "<pre>";
//            print_r($iaCheck);
//            die;
            if (!empty($iaCheck)) {
                foreach ($iaCheck as $row) {

                    if ($row['doccode1'] == 226) {
                        $refiling = 1;
                        $refil_date = date('d-m-Y', strtotime($row['ent_dt']));
                        //echo date('d-m-Y', strtotime($row['ent_dt']));
                    }
                }
            }

            if ($refiling == 0) {
//                echo "tttt";
//                die;
                $chk_if_defects_exists = $this->rModel->check_if_defect_exists($diaryNumber);
//                echo "<pre>";
//                print_r($chk_if_defects_exists); die;
                if (!empty($chk_if_defects_exists)) {
                    $refil_date = date('d-m-Y', strtotime($cur_date));
                } else {
//                CONDITION TO CHECK IF DEFECTS NOT EXISTS THEN THIS BLOCK GETS EXECUTED **********************************
                    $refil_date = $this->rModel->check_refile_date($diaryNumber);
//                    echo "<pre>";
//                    print_r($refil_date);die;
                    if (!empty($refil_date)) {
                        $refil_date = $refil_date['rm_dt'];
                        $refil_date = date('d-m-Y', strtotime($refil_date));
//                        COMMENTED BELOW CODE AS PER PER TESTING OF THE THIS PART IS NOT NECESSARY BUT CAN BE USED IN FUTURE AS WRITTEN IN OL ICMIS CODE
//                        if ($refil_date == null) {
//                            $refil_date = date('d-m-Y', strtotime($cur_date));
//
//                        } else {
////                          echo date('d-m-Y', strtotime($refil_date));
//                            $refil_date = date('d-m-Y', strtotime($refil_date));
//                        }

                    } else {

                        $refil_date = date('d-m-Y', strtotime($cur_date));

                    }
                }

            }
//            echo $refil_date;die;
            $last_day_of_refiling = date('d-m-Y', strtotime($nextdate));
//            echo $last_day_of_refiling;  die;
            if (strtotime($refil_date) <= strtotime($last_day_of_refiling)) {
//                echo "FFF"; die;
                $data_report['refiling_report'] = [
                    'diaryNo'=> $diaryNumber,
                    'causeTitle'=>$cause_title,
                    'filingNo'=>$diary_date,
                    'defectNotifyDate' => $def_notify_date,
                    'refilingDate' => $refil_date,
                    'lastDayRefiling' => $last_day_of_refiling,
                    'delayInRefiling' => 'Refiling is within time',
                    'currentDate' => $cur_date,
                    'flag' => 3
                ];
//                 echo "<pre>";
//                print_r($data_report);die;

//               return view('Filing/refiling_view', $data_report);

            } else {
//                echo "EEE"; die;

                $returnDefectDays = $this->get_defect_days1($df, $refil_date, $last_day_of_refiling, $diaryNumber);

//                echo "<pre>";
//                print_r($returnDefectDays);
//                die;
//        DELAY IN REFILING IS CALCULATED BELOW*******************************************************************

//        STARTS HERE
                if (!empty($returnDefectDays)) {
                    $flag = $returnDefectDays['flag'];
                    $total = $returnDefectDays['total'];
//                    echo $flag."LLLLLLLLLLL".$total;die;
                    if ($total <= 0) {
                        $delayInRefiling = 'Refiling is within time';
                    } else {
                        if ($total < 0) {
                            $delayInRefiling = 'Refiling is within time';

                        } else {
                            $delayInRefiling = "Total Delay of " . $total . " days";
                        }
                    }

                        if ($flag == 1) {
                            $preCovid = $returnDefectDays['pre_covid'];
                            $deadCoronaPeriod = $returnDefectDays['dead_corona_period'];
                            $delayDaysCal = $returnDefectDays['delay_days_cal'];
                            $totalDelayDays = $returnDefectDays['total_delay'];

                            $data_report['refiling_report'] = [
                                'diaryNo'=> $diaryNumber,
                                'causeTitle'=>$cause_title,
                                'filingNo'=>$diary_date,
                                'defectNotifyDate' => $def_notify_date,
                                'refilingDate' => $refil_date,
                                'lastDayRefiling' => $last_day_of_refiling,
                                'delayInRefiling' => $delayInRefiling,
                                'preCovid' => $preCovid,
                                'deadCoronaPeriod' => $deadCoronaPeriod,
                                'delayDaysCal' => $delayDaysCal,
                                'totalDelay' => $totalDelayDays,
                                'currentDate' => $cur_date,
                                'flag' => $flag

                            ];
//                 echo "<pre>";
//                print_r($data_report);die;

//                            view('Filing/refiling_view', $data_report);

                        }
                        if ($flag == 2) {

                            $deadCoronaPeriod = $returnDefectDays['dead_corona_period'];
                            $delayDaysCal = $returnDefectDays['delay_days_cal'];
                            $totalDelayDays = $returnDefectDays['total_delay'];
                            $data_report['refiling_report'] = [
                                'diaryNo'=> $diaryNumber,
                                'causeTitle'=>$cause_title,
                                'filingNo'=>$diary_date,
                                'defectNotifyDate' => $def_notify_date,
                                'refilingDate' => $refil_date,
                                'lastDayRefiling' => $last_day_of_refiling,
                                'delayInRefiling' => $delayInRefiling,
                                'deadCoronaPeriod' => $deadCoronaPeriod,
                                'delayDaysCal' => $delayDaysCal,
                                'totalDelay' => $totalDelayDays,
                                'currentDate' => $cur_date,
                                'flag' => $flag

                            ];
//                 echo "<pre>";
//                print_r($data_report);die;

//                            view('Filing/refiling_view', $data_report);

                        }
                        if ($flag == 3) {
                            $total = $returnDefectDays['total'];
                            $data_report['refiling_report'] = [
                                'diaryNo'=> $diaryNumber,
                                'causeTitle'=>$cause_title,
                                'filingNo'=>$diary_date,
                                'defectNotifyDate' => $def_notify_date,
                                'refilingDate' => $refil_date,
                                'lastDayRefiling' => $last_day_of_refiling,
                                'delayInRefiling' => $delayInRefiling,
                                'currentDate' => $cur_date,
                                'flag' => $flag

                            ];
//                 echo "<pre>";
//                print_r($data_report);die;

//                            view('Filing/refiling_view', $data_report);
                        }


                }

//                   echo $delayInRefiling;die;
                }


//           CODE ENDS HERE FOR DAYS CALCULATION*************************************************************

// ****************************************************** BELOW CODE FOR DISPLAYING TABLE OF DEFECT LIST FOR REMOVAL AND LISTING ********************************************

//            CHECK FOR DEFECT FOR PARTICULAR DIARY NO **************************************************



            $defNotifyDate=$removeDate='';
            $checkDefect = $this->rModel->check_defects($diaryNumber);
//            echo "<pre>";
//            print_r($checkDefect);
//            die;
//            $defNotifyDate = $checkDefect['save_dt'];
//            $removeDate = $checkDefect['rm_dt'];
            $messageForTablePreview='';
            if(!empty($checkDefect)) {
                foreach ($checkDefect as $row) {
                    if (!empty($row)) {
                        $defNotifyDate = $row['save_dt'];
                        $removeDate = $row['rm_dt'];
                        $df = $row['df'];  // defects notification date
                    }
//                    echo $defNotifyDate.">>>".$removeDate;   die;

//                    if ($removeDate != '') {                     //THIS LINE IS CHANGED HERE IT IS EQUALS TO NULL
//                    $data['message_for_tablepreview']='No Defects Found';
//
//                        $messageForTablePreview = 'No Defects Found';
//                        echo " <center><b><span style='color: red;' >No defects found</span></b></center><br>";
//                        exit();
//
//                    }
                    if ($removeDate != null) {
                        $messageForTablePreview = 'No Defects Found';
//                        echo " <center><b><span style='color: red;' >No defects found</span></b></center><br>";
//                        exit();
                       $data_table['table_message'] = $messageForTablePreview;
//                       echo "<pre>";
//                       print_r($data_report);
//                       print_r($data_table);die;
                       return view('Filing/refiling_view',['refiling_report'=>$data_report, 'table_message'=>$data_table]);
                    }

                }
            }


//           CONDITION TO CHECK IF LOGIN USER IS SOFT PETITION USER ************************************************

            $softCopyUser = 0;
            $softCopyUserModel = $this->dModel->get_soft_copy_user($ucode);
//            print_r($softCopyUserModel);die;
            if ($softCopyUserModel) {
                $softCopyUser = 1;
            }

            // if($ucode!=217) // exempted for soft petition seat as they can remove the defects at any stage */ // user bharti saini
            // code added for checking matter is recieved in SCR from FDR or not - added on 30 june 2021
            if ($softCopyUser != 1) {

                $checkFileTrapModel = $this->rModel->check_fil_trap($diaryNumber);
//                echo "<pre>";
//                print_r($checkFileTrapModel);die;

                if (!empty($checkFileTrapModel)) {

                    if ($checkFileTrapModel['usercode'] != $ucode) {

                        $messageForTablePreview = 'Defects can be cured by ' . $checkFileTrapModel['d_to_empid'] . '-' . $checkFileTrapModel['name'];
//                            echo '<div style="text-align: center; color:red"><h3>Defects can be cured by '.$a[d_to_empid].'-'.$a[name] .'</h3></div>';
//                            exit();
                        $data_table['table_message'] = $messageForTablePreview;
//                       echo "<pre>";
//                       print_r($data_report);
//                       print_r($data_table);die;
                        return view('Filing/refiling_view',['refiling_report'=>$data_report, 'table_message'=>$data_table]);



                    }
                }else {
//                            $data['message_for_tablepreview']='Defects Cannot Be Cured As Matter Is Not Marked From File Dispatch Receive For Refiling' ;

                    $messageForTablePreview = 'Defects Cannot Be Cured As Matter Is Not Marked From File Dispatch Receive For Refiling';
                    $data_table['table_message'] = $messageForTablePreview;
//                       echo "<pre>";
//                       print_r($data_report);
//                       print_r($data_table);die;
                    return view('Filing/refiling_view',['refiling_report'=>$data_report, 'table_message'=>$data_table]);


                }

            }

            $softCopyDefect = 0;
//            echo $softCopyUser.">>>";die; 0 in dno 326882023 login2104
            if ($softCopyUser == 1) {
                $softCopyDefectQuery = $this->rModel->check_soft_defect($diaryNumber);
//                var_dump($softCopyDefectQuery);die;
                if ($softCopyDefectQuery) {
                    $softCopyDefect = 1;
                }
            }

            $currentDate = date('Y-m-d');
            $getDays = $this->rModel->get_no_of_days($currentDate);
//            var_dump($getDays);die;
            $defectMaxDate = date('Y-m-d', strtotime($defNotifyDate . ' + ' . $getDays['no_of_days'] . ' days'));
//            echo $defNotifyDate.">>>>".$def_rem_max_date;die;
            $nextDt = $this->next_date($defectMaxDate, 1);
//            echo $nextDt;    die;
            $last_day_of_refiling = $nextDt;   // HERE VALUE OF LAST DAY OF REFILING IS CHANGED AGAIN
            $data_report['lastDayRefiling'] = $last_day_of_refiling;
//     ASK MAM ABOUT THIS *********************************************************************************************************

            $varForDisplayingRemoveColumn = 0;
            $data_remove_display=[];
            $data_remove_display['remove_defect']=$varForDisplayingRemoveColumn;
//            echo $total;die;
//            echo "<pre>";
//            print_r($data_report);die;
//            Array
//            (
//                [refiling_report] => Array
//                (
//                    [defectNotifyDate] => 20-08-2023
//            [refilingDate] => 19-02-2024
//            [lastDayRefiling] => 18-09-2023
//            [delayInRefiling] => Total Delay of 154 days
//            [currentDate] => 19-02-2024 6:30 pm
//            [flag] => 3
//        )
//
//    [lastDayRefiling] => 2023-09-18  DUE TO LINE 394
//)
//            echo $softCopyDefect;die;

            if (($total > 0) && ($softCopyDefect == 0)) {

                $docDetails = $this->rModel->check_doc_details($diaryNumber);
//                echo "<pre>";
//                print_r($docDetails);die;
                if ($docDetails) {

                    $messageForTablePreview = 'IA FOR DELAY IN REFILING HAS BEEN FILED';
                    $data_table['table_message'] = $messageForTablePreview;

                } else {

                    $messageForTablePreview = 'There Is Delay Of '.$total.' Days. Please File IA For Delay In Refiling First!!!!';
                    $data_table['table_message'] = $messageForTablePreview;
                    $varForDisplayingRemoveColumn = 1;
                    $data_remove_display['remove_defect']=$varForDisplayingRemoveColumn;
                }
            } else {
                $messageForTablePreview = 'Remove Default';
                $data_table['table_message'] = $messageForTablePreview;
            }

            //BELOW CODE FOR DATA DISPLAY IN HTML TABLE

            $queryForDefectDisplay = $this->rModel->check_defect_display($diaryNumber);
//            echo "<pre>";
//            print_r($queryForDefectDisplay);die;
            if (!empty($queryForDefectDisplay)) {
                $table = $queryForDefectDisplay;
                $table_data['table_data'] = $table;

            } else {
                $table = '0';
                $table_data['table_data'] = $table;

            }

            return view('Filing/refiling_view',['refiling_report'=>$data_report, 'table_message'=>$data_table, 'table_data'=>$table_data, 'remove_defect_option'=>$data_remove_display]);



        }


    }



    public function next_date($date, $day)
    {

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

    public function is_holiday($date)
    {
        $holiday = $this->rModel->get_registry_holiday($date);
//        echo "<pre>";
//        print_r($holiday[0]);
//        die;
        if (!empty($holiday[0]))
            return 1;
        else
            return 0;
    }

    public function get_defect_days1($df, $refil_date, $last_day_of_refiling, $diary_no)
    {
//        echo $refil_date;
//        die;
        $flag = 0;
        $refil_date = date('Y-m-d', strtotime($refil_date));
        $last_day_of_refiling = date('Y-m-d', strtotime($last_day_of_refiling));

        /* FLAG VARIABLE TO UNDERSTAND WHICH PART IS RUNNING
        FLAG = 1 ---- means last day of refiling date is pre covid date which is '2020-03-06'
        FLAG = 2 ---- means last day of refiling date is dead corona period which is $last_day_of_refiling > '2020-03-07' && $last_day_of_refiling <='2022-02-28'
        FLAG = 3 ---- means last day of refiling date is greater than '2022-02-28'

        */

        $bl =$l2=$l1= 0;
//        echo $last_day_of_refiling;
//        die;

        $dataReturning = [];

        if ($last_day_of_refiling <= '2020-03-07') {
            $flag = 1;
//            echo "RRR";
//            die;
            // refiling date
            $covidDate = '2020-03-06';
            $diffDate = $this->rModel->get_date_difference($covidDate, $last_day_of_refiling);
//            print_r($diffDate);
//            die;
            if (!empty($diffDate[0]['days'])) {
                $bl = $diffDate[0]['days'];
            }
//   echo "<tr><td>Delay till 06-03-2020    * pre-covid <b>(a)</b></td><td><font color='red'><b>".date_format(date_create($last_day_of_refiling),'d-m-Y') ."  to (06-03-2020) =  ". $bl. " days </font></b></td></tr>" ;
            $dataReturning['pre_covid'] = date_format(date_create($last_day_of_refiling), 'd-m-Y') . " to (06-03-2020) = " . $bl . "days";
            if ($refil_date <= '2022-07-11') {
                $l2 = 0;
            } else {
                $l1 = 0;
                $l2 = date_diff(date_create('2022-06-01'), date_create($refil_date));;
//              echo "<pre>";print_r( $l2);die;
                $l2 = $l2->format("%R%a");
                $l2 = $l2 + 1;
                // $l2= $l2->format("%R%a days") + 1;  DAYS WORD REMOVES AS NON NUMERIC VALUE ENCOUNTERED ERROR DISPLAYS

            }
            $total = ($bl + $l1 + $l2);
//            echo $total;die;
            $dataReturning['total'] = $total;
//            echo "<tr><td>Dead(corona) Period     <b>(b)</b></td><td><font color='red'><b>(07-03-2020)  to (28-02-2022) = ".$l1 ." days </font></b></td></tr>" ;
            $dataReturning['dead_corona_period'] = "(07-03-2020) to (28-02-2022) = " . $l1 . "days";
            if ($bl == 0 && $l1 == 0) {
//                echo "<tr><td>Delay Days Calculated   <b>(c)</b></td><td><b>". date_format(date_create($last_day_of_refiling),'d-m-Y'). " to ". date_format(date_create($refil_date),'d-m-Y')." = " .$l2 . " days </b></td></tr>" ;
                $dataReturning['delay_days_cal'] = date_format(date_create($last_day_of_refiling), 'd-m-Y') . " to " . date_format(date_create($refil_date), 'd-m-Y') . " = " . $l2 . "days";
            } else {
//                echo "<tr><td>Delay Days Calculated   <b>(c)</b></td><td><b> (01-06-2022 to ". date_format(date_create($refil_date),'d-m-Y').") = " .$l2 . " days </b></td></tr>" ;
                //   echo "(01-06-2022 to ".date_format(date_create($refil_date),'d-m-Y').") = ".$l2." days";die;
                $dataReturning['delay_days_cal'] = "(01-06-2022 to " . date_format(date_create($refil_date), 'd-m-Y') . ") = " . $l2 . " days";

            }
//            echo $dataReturning['delay_days_cal'];
//            echo $total." days " ;die;
//            echo "<tr><td>total Delay   <b> [(a) + (b) + (c)] </b> <b></b></td><td><b>".$total . " days </b></td></tr>" ;
            $dataReturning['total_delay'] = $total . " days ";
            $dataReturning['flag'] = $flag;

        }
        /* end of the code for before corona times */

        /* if limiation expired in corona times */

        if ($last_day_of_refiling > '2020-03-07' && $last_day_of_refiling <= '2022-02-28')
        {
            $flag = 2;
            $l1 = 0;
            $dff='';


            if ($refil_date <= '2022-07-11') {
                $l2 = 0;
            } else {

                $dff = '2022-06-01';

                $diffDate = $this->rModel->get_date_difference($refil_date, $dff);
                if (!empty($diffDate[0]['days'])) {
                    $l2 = $diffDate[0]['days'] + 1;
                }

                $l2a = $l2;
                $last_day_of_refiling = $df;

            }
//            echo "<tr><td>Dead(corona) Period     <b>(a)</b></td><td><font color='red'><b>(07-03-2020)  to (28-02-2022) =  ". $l1. " days </font></b></td></tr>" ;
            $dataReturning['dead_corona_period'] = "(07-03-2020) to (28-02-2022) = " . $l1 . "days";
            $l2a = $l2;
            $dataReturning['delay_days_cal'] = date_format(date_create($dff), 'd-m-Y')." to ".date_format(date_create($refil_date), 'd-m-Y') . " = " . $l2 . " days";
//            echo "<tr><td>Delay Days Calculated  b>(b)</b></td><td><b>". date_format(date_create($dff),'d-m-Y'). " to ". date_format(date_create($refil_date),'d-m-Y')." = " .$l2 . " days</b></td></tr>" ;
//            echo "<tr><td>total Delay   <b> [(a)  + (b)]  </b> <b></b></td><td><b>".$l2a . " days </b></td></tr>" ;

            $dataReturning['total_delay'] = $l2a . " days";

            $total = $l2a;
            $dataReturning['total'] = $total;
            $dataReturning['flag'] = $flag;

        }

        if ($last_day_of_refiling > '2022-02-28') {
            $flag = 3;
            $l1 = 0;
//            echo ">>".$refil_date; as now its current date
            if ($refil_date <= '2022-07-11') {
                $l2 = 0;
            } else // if refiled after 11.07.2022
            {
//                echo $refil_date.">>>".$last_day_of_refiling.">>>";

                $diffDate = $this->rModel->get_date_difference($refil_date, $last_day_of_refiling);
//                echo "<pre>";
//                print_r($diffDate[0]['days']);die;
                if (!empty($diffDate[0]['days'])) {
                    $total = $diffDate[0]['days'];
                    $dataReturning['total'] = $total;
                    $dataReturning['flag'] = $flag;


                }

            }

        }
        return $dataReturning;
    }

    public function update_function()
    {
        $dataForUpdate = $_POST;
        if (!empty($dataForUpdate)) {
            $updateData = $this->rModel->defect_update_function($dataForUpdate);
        }
//        echo "<pre>";
//        print_r($updateData);
//        die;

        if ($updateData) {
            echo json_encode($updateData);
        } else {
            echo " ";
        }
    }


    //BELOW CODE IS REMOVED AFTER DISSCUSSING WITH BO SIR AND VANDANA MA'AM ----29-11-2023

//    public function listing_function()
//    {
//        $dataForUpdate = $_POST;
//        echo "<pre>";
//        print_r($dataForUpdate);
//        die;
//        if (!empty($dataForUpdate)) {
//            $listingView = $this->rModel->defect_listing_function($dataForUpdate);


//            foreach ($listingView as $data) {
//                echo "<pre>";
//                print_r(data);
//
//            }
//            die;
//            $dataForListing=array();
//            if($listingView)
//            {
//
//                echo json_encode($listingView);
//            } else {
//                echo " ";
//            }


//        }

//
//    }
//    public function refiling_listing_defect()
//    {
//
//        $data = $_REQUEST;
//        $listingViewData = $this->rModel->check_defect_display($data['dno']);
////        $listingViewData = $this->rModel->defect_listing_function($data['dno']);
//        $getIADetail = $this->rModel->get_IA_detail($data['dno']);
//        $getDataFixedFor = $this->rModel->get_data_fixedfor();
////        echo "<pre>";
////        print_r($listingViewData);
////        die;
//
//        $dataForView=array();
//        if(!empty($listingViewData))
//        {
//            $dataForView['record']=$listingViewData;
//
//        }else{
//            $dataForView['record']='No Defects Found';
//        }
//        if(!empty($getIADetail))
//        {
//                $dataForView['IA_data']=$getIADetail;
//
//        }else{
////            echo "TTERT";
//            $dataForView['IA_data']='No Record Found';
//        }
//
//        if(!empty($getDataFixedFor))
//        {
//                $dataForView['fixedFor']=$getDataFixedFor;
//
//        }else{
//            $dataForView['fixedFor']='No Record Found';
//        }
//    print_r($dataForView['IA_data']);
//        return view('Filing/refiling_listing_view',$dataForView);
//    }
//

    public function sms_btn_clicked()
    {
        $data = $_POST;
        //    echo "<pre>";
        //    print_r($data);
        //    die;

        $alreadyDefect = $this->rModel->check_if_defect_exists($data['dno']);
        $countOfDefect = count($alreadyDefect);
        $mobile = '';
        $from='Refiling';
        $templateId ='1107161234619089003';
        $textMessage = "The case filed by you with Diary No.".$data['dno']." is still defective having $countOfDefect objections. Please collect the same from Re-filing counter. - Supreme Court of India";

        $mobileParty = get_party_mobile_number($data['dno'],'P');
        $mobileAdvocate = get_advocate_mobile_number($data['dno'],'P');
    //        echo "adv=".$mobileAdvocate."  pa=".$mobileParty;
    //        die;
        $mobile = $mobileParty.",".$mobileAdvocate;

        //LINE 626 TO BE UNCOMMENTED LATER --------------------------------------------ON TESTING TIME

    //        $sendSmsFuncReturnValue =  send_sms($mobile,$textMessage,$from,$templateId);


    }

    public function obj_back_date()
    {
        if(!empty($_POST))
        {
            $dno = $_POST['dno'];
            $status = $_POST['status_case'];
            if($status === "D") {
                echo "<span style='text-align: center;color: red'><h3>Matter is Disposed!!!!</h3></span>";
                exit;
            }
            $checkDefect = $this->rModel->check_defects_refiling_bkdt($dno);
//            echo "<pre>";
//            print_r($checkDefect);
//            die;
            $sql_res = 0;

            foreach ($checkDefect as $row) {

                if ($row['rm_dt'] === null && ($row['status'] === null || $row['status'] === '0')) {
                    $sql_res = 1;
                }
             }
            if ($sql_res === 0)
            {
                echo "<span style='text-align:center;color:red'><h3>Matter has been refiled!!!</h3></span>";
                exit;
            }

            if($sql_res === 1)
            {
                $allDefect = $this->rModel->get_defect($dno);
//                echo "<pre>";
//                print_r($allDefect);
//                die;
                if(!empty($allDefect))
                {
                    $data['alldefect'] = $allDefect;
                }
//                echo "<pre>";
//                print_r($data['alldefect']);
//                die;
                $backDate = $this->rModel->check_for_back_date($dno);
//                echo "<pre>";
//                print_r($backDate[0]['rm_dt']);
//                die;
                $ucode = session()->get('login')['usercode'];
                $getUserCode = $this->rModel->get_specific_role($ucode);
                if(!empty($backDate))
                {
                    $def_rm_date = $backDate[0]['rm_dt'];
                    if ($def_rm_date === null)
                    {
//                        if ($ucode == 1 || $ucode == 1494 || $ucode == 94) {
                       if ($ucode == $getUserCode['usercode'])
                       {
                            $data['backdate'] = 'display_button_on';
                        }
                    }else{
                        $data['backdate'] = 'display_button_off';
                    }
                }
               echo view('Filing/refiling_on_back_date',$data);
                exit;
            }


        }

    }

    public function save_back_date()
    {
//        var_dump($_POST);
//        die;
        if(!empty($_POST))
        {
            $dno = $_POST['d_no'];
            $backDate = $_POST['back_date'];
            $usercode = session()->get('login')['usercode'];
            $updateDate = $this->rModel->update_refiling_date($dno,$backDate,$usercode);
//            echo "<pre>";
//            print_r($updateDate);
//            die;
            if($updateDate)
            {
                echo "<span style='text-align:center;color:red'><h4>Data Updated Successfully!!!<h4><span>";
                exit;
            }else{
                echo "<span style='text-align:center;color:red'><h4>Data Not Updated, Error....<h4><span>";
                exit;
            }

        }

    }

    public function get_and_save_data()
    {
//        echo "FFF";
//        die;
          if(!empty($_POST)) {
//              echo "YYYY";
//              die;
              $dno = $_POST['dno'];
              $usercode = session()->get('login')['usercode'];

              $status = $_POST['status_case'];
//              echo $dno.">>".$status;
//              die;

              if ($status === "D") {
                  echo "<span style='text-align: center;color: red'><h4>Matter is Disposed!!!!</h4></span>";
                  exit;
              }
//        CONDITION TO CHECK IF CASE IS LISTED OR NOT ******************************************************************

                  $check_if_listed = $this->rModel->check_if_listed($dno);
//            echo "<pre>";
//            print_r($check_if_listed);
//            die;
                  if ($check_if_listed['next_dt'] != null && $check_if_listed['next_dt'] != '')
                  {
                      echo "<div style='text-align:center;color: red'><h4>Case Is Listed. Defects cannot be added!!!!</h4></div>";
                      exit(0);
                  }
//        CONDITION TO CHECK IF CASE IS VERIFIED OR NOT ******************************************************************
                  $check_if_ver = $this->rModel->check_if_verified($dno);
//            echo "<pre>";
//            print_r($check_if_ver);
//            die;
                  if (!empty($check_if_ver))
                  {
                      echo "<div style='text-align:center;color: red'><h4>Case Is Verified. Defects cannot be added!!!!</h4></div>";
                      exit(0);
                  }

//        CONDITION TO CHECK IF CASE IS REGISTERED OR NOT ******************************************************************
                  $check_if_reg = $this->rModel->check_if_registered($dno);
//            echo "<pre>";
//            print_r($check_if_reg);
//            die;
                  if (!empty($check_if_reg)) {
                      echo "<div style='text-align:center;color: red'><h4>Case Is Registered. Defects cannot be added!!!!</h4></div>";
                      exit(0);
                  }
                  $sql_res = 0;
                  $checkDefect = $this->rModel->check_defects_refiling_bkdt($dno);
//             echo "<pre>";
//            print_r($checkDefect);
//            die;
                  if (!empty($checkDefect)) {
                      foreach ($checkDefect as $row) {

                          if ($row['rm_dt'] != null && ($row['status'] === null || $row['status'] === '0')) {
                              $sql_res = 1;

                          } elseif ($row['rm_dt'] === null && ($row['status'] === null || $row['status'] === '0')) {
                              $sql_res = 2;
                              break;
                          }
                      }
                  } else {
                      echo "<div style='text-align:center'><h4>No Defects Found!!<h4><div>";
                      exit(0);
                  }
                  if ($sql_res == 1) {
                      $allDefectRefiling = $this->rModel->get_defect_refiling($dno);
//             echo "<pre>";
//            print_r($allDefectRefiling);
//            die;
                      if (!empty($allDefectRefiling)) {
                          $data['alldefect'] = $allDefectRefiling;
                      }
//                echo "<pre>";
//                print_r($data['alldefect']);
//                die;
                      $checkForBo = $this->rModel->check_for_bo($usercode);
//                echo "<pre>";
//                print_r($checkForBo);
//                die;
//                  echo gettype($checkForBo);
                      $getUserCode = $this->rModel->get_specific_role($usercode);
//                      echo "<pre>";
//                      print_r($getUserCode['usercode']);
//                      die;

//                      if (($checkForBo > 0) || $usercode == 1 || $usercode == 1494 || $usercode == 94) {
//                      if (($checkForBo > 0) || $usercode == $getUserCode['usercode']) {
                      if(!empty($checkForBo) || !empty($getUserCode))
                      {
                          if (($checkForBo > 0) || $usercode == $getUserCode['usercode'])
                          {
                              $data['cancel_button'] = 'cancel_button_on';
                          }
                      }else {
//                              echo "FFFF";
//                              die;
                          $data['cancel_button'] = 'cancel_button_off';
                      }

                      echo view('Filing/cancel_refiling_data', $data);
                      exit;
                  } else if ($sql_res == 2) {

                      echo "<div style='text-align: center;color:red'><h4>Matter is Defective!!!!!</h4></div>";
                      exit(0);
                  }
              }else{
              echo "ERROR!!!!";
          }
        }


    public function cancel_save_data()
    {
        //              *********************ASK VANDANA MAM FOR UPDATE COLUMN blank

        if(!empty($_POST))
        {
            $dno = $_POST['dno'];
            $usercode = session()->get('login')['usercode'];
            $updateCancelRefilingDate = $this->rModel->update_cancel_refiling($dno,$usercode);
//            echo "<pre>";
//            print_r($updateDate);
//            die;
            if($updateCancelRefilingDate)
            {
                echo "<span style='text-align:center;color:red'><h4>Data Updated Successfully!!!<h4><span>";
                exit;
            }else{
                echo "<span style='text-align:center;color:red'><h4>Data Not Updated, Error....<h4><span>";
                exit;
            }

        }


    }


}


?>