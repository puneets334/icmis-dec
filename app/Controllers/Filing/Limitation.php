<?php

namespace App\Controllers\Filing;

use CodeIgniter\Controller;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;
use App\Models\LoginModel;
use App\Controllers\BaseController;

use App\Models\Filing\LimitationModel;

class Limitation extends BaseController

{

    public $diary_no;
    function __construct()
    {
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
            //$getUrl = $uri->getSegment(0).'-'.$uri->getSegment(1);
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }

    }

    public function index()
    {
        $sessionData = $this->session->get();
        $diary_no = $sessionData['filing_details']['diary_no'];
        $c_status = $sessionData['filing_details']['c_status'];
        $model = new LimitationModel();
        $data['result'] = $model->getLegalCases($diary_no);
        
        $data['res_details'] = $model->res_details($diary_no);
        $data['getcasestatus'] = $model->getcasestatus($diary_no);
        
        $data['getcasestatuswithrows'] = $model->getcasestatuswithrows($diary_no);
        $no_rws = $data['getcasestatuswithrows'];
        $data['no_rws'] = $no_rws;


        if ($data['getcasestatus'] !== null) {
            $data['fd'] = $data['getcasestatus']->f_d;
            $data['c_d_a'] = $data['getcasestatus']->c_d_a;
            $data['d_o_d'] = $data['getcasestatus']->d_o_d;
            $data['d_o_a'] = $data['getcasestatus']->d_o_a;
            $data['o_d'] = $data['getcasestatus']->o_d;
        } else {
            $data['fd'] = '';
            $data['c_d_a'] = '';
            $data['d_o_d'] = '';
            $data['d_o_a'] = '';
            $data['o_d'] = '';
        }
        $ch_cat = $model->ch_cat($diary_no);
        $data['mul_cat'] = $model->mul_cat($diary_no);
        $data['diary_no'] = $sessionData['filing_details']['diary_no'];
        $data['c_status'] = $c_status;
        //pr($data['mul_cat']);
        if (!empty($ch_cat) && is_array($ch_cat)) {
            $firstElement = reset($ch_cat);
            $submaster_id = $firstElement->submaster_id;
            $chk_limi['chk_limi'] = $model->chk_limi($submaster_id);
        } else {
        }
        $data['nature'] = $model->getNature($diary_no);
        $nature = isset($data['nature']->nature) ? $data['nature']->nature : '';
        //$data['s_c_t'] = $model->getLimitationPeriod($nature);
        /*if ($nature !== '') {
            $data['casename'] = $model->casename($nature);
            $casename = isset($data['casename']->casename) ? $data['casename']->casename : '';
        } else {
            $casename = '';
        }*/

        $condition=['diary_no'=>$diary_no, 'case_lim_display' => 'Y'];
        $existingRecord = $model->where($condition)->first();
        
        if ($existingRecord) {
            $ordernet_id = $existingRecord['id'];
            $data['insert_id'] = $ordernet_id;
        } else {
            $data['insert_id'] = 'No Record Found';
        }

        return view('Filing/get_lower_details', $data);
    }

    public function InsertAction()
    {
        $sessionData = $this->session->get();
        $diary_no = $sessionData['filing_details']['diary_no'];
        $sessionUser = $sessionData['login']['usercode'];
        $model = new LimitationModel();
        $data['getcasestatus'] = $model->getcasestatus($diary_no);
        $order_dt = isset($data['getcasestatus']->o_d) ? date('d-m-Y', strtotime($data['getcasestatus']->o_d)) : '';
        $descr = isset($data['getcasestatus']->descr) ? $data['getcasestatus']->descr  : '';
        $lowerc_t = isset($data['getcasestatus']->lowerct_id) ? $data['getcasestatus']->lowerct_id : '';
        $totalHolidaysPos = strpos($descr, "(Total Holidays)");
        $totalDaysPos = strpos($descr, "Total Days");
        $totalHolidaysStr = substr($descr, $totalHolidaysPos + strlen("(Total Holidays)"));
        $totalDaysStr = substr($descr, $totalDaysPos + strlen("Total Days"));
        $totalHolidays = ltrim(trim(substr($totalHolidaysStr, 0, strpos($totalHolidaysStr, "days"))), '=');
        $pattern = '/\d+/';
        preg_match_all($pattern, $totalDaysStr, $matches);
        $totalDaysFirst = $matches[0][0];
        $totalDaysSecondLast = $matches[0][count($matches[0]) - 2];
        $totalDaysSecond = $matches[0][1];
        $cause_title = $sessionData['filing_details']['pet_name'] . ' VS ' . $sessionData['filing_details']['res_name'];
        $data['nature'] = $model->getNature($diary_no);
        $nature = $data['nature']->nature;
        $case_nature = '0';
        $under_section = '0';
        $asdesc = "Diary No.-" . substr($diary_no, 0, strlen($diary_no) - 4) . '-' . substr($diary_no, -4) . '<br/>';
        $climit = $this->request->getVar('climit');
        $order_cof = $this->request->getVar('order_cof');
        $sid = '';
        $data['s_c_t'] = $model->getLimitationPeriod($nature);
        $filing_dt = $this->request->getVar('filing_dt') ? date('Y-m-d', strtotime($this->request->getVar('filing_dt'))) : null;
        $copy_aply_dt = $this->request->getVar('copy_aply_dt') ? date('Y-m-d', strtotime($this->request->getVar('copy_aply_dt'))) : null;
        $copy_dlvr_dt = $this->request->getVar('copy_dlvr_dt') ? date('Y-m-d', strtotime($this->request->getVar('copy_dlvr_dt'))) : null;
        $txt_attestation = $this->request->getVar('txt_attestation') ? date('Y-m-d', strtotime($this->request->getVar('txt_attestation'))) : null;
        $order_dt = $this->request->getVar('order_dt') ? date('Y-m-d', strtotime($this->request->getVar('order_dt'))) : null;

        $flg = 0;
        $i = 1;
        $cdays = 0;
        $leaves = 0;
        $f_filing_dt = date_create($filing_dt);
        if (!empty($txt_attestation) && $txt_attestation != '1970-01-01')
            $f_order_dt = date_create($txt_attestation);
        else
            $f_order_dt = date_create($order_dt);
        $diff = date_diff($f_order_dt, $f_filing_dt);
        $days = $diff->format('%R%a');
        $days =  intval(str_replace('+', '', $days));
        $ext_days = '';
        $extra_time = '';
        if ($copy_aply_dt != Null && $copy_dlvr_dt != NULL && $copy_aply_dt != '1970-01-01' && $copy_dlvr_dt != '1970-01-01') {
            $f_copy_dlvr_dt = date_create($copy_dlvr_dt);
            $f_copy_aply_dt = date_create($copy_aply_dt);
            $diff1 = date_diff($f_copy_aply_dt, $f_copy_dlvr_dt);
            $cdays = $diff1->format('%R%a');
            $cdays = ($cdays + 1);
            if (strtotime($order_dt) == strtotime($copy_aply_dt)) {

                $cdays = $cdays - 1;
            }
        }
        if ($txt_attestation != Null && $txt_attestation != NULL && $txt_attestation != '1970-01-01' && $txt_attestation != '1970-01-01') {
            $f_attestation = date_create($txt_attestation);
            $diff1 = date_diff($f_order_dt, $f_attestation);
            $cdays1 = $diff1->format('%R%a');
            $cdays1 = str_replace('+', ' ', $cdays1);
            $cdays = $cdays + $cdays1;
        }
        $cal_days = $climit + $cdays;
        if ($txt_attestation != '1970-01-01')
            $chk_tot_days = date('Y-m-d', strtotime($txt_attestation . ' + ' . $cal_days . ' days'));
        else
            $chk_tot_days = date('Y-m-d', strtotime($order_dt . ' + ' . $cal_days . ' days'));
        $next_chk_tot_days = $model->getHolidays($chk_tot_days, $filing_dt);

        // print_r($next_chk_tot_days); exit();
        $chk_dt_status = 0;
        if (strtotime($next_chk_tot_days) == strtotime($filing_dt)) {
            $chk_dt_status = 1;
        }
        $extra_time_s = '';
        if ($days > $climit && $chk_dt_status == 1) {
            $next_dt_ss = strtotime('- 1 day', strtotime($filing_dt));
            $next_dt_ss = date('Y-m-d',  $next_dt_ss);
            $next_dt_s = chksDate_vac_reg_sub($next_dt_ss, $chk_tot_days);
            $f_next_dt_s = date_create($next_dt_s);
            $diff_ndt_s = date_diff($f_next_dt_s, $f_filing_dt);
            $days_bdt_s = $diff_ndt_s->format('%R%a');
            $days_bdt_s = str_replace('+', ' ', $days_bdt_s);

            $extra_time_s = intval($days_bdt_s);
            $next_holiday = $model->chk_curr_dat_holiday($filing_dt);
            if (strtotime($next_holiday) != strtotime($filing_dt)) {
                $extra_time_s = $extra_time_s + 1;
            }
        }
        $leaves = intval($extra_time_s);
        $tcdays = $leaves;
        $asdesc = "";
        $offset = 5.5 * 60 * 60;
        $c_date = gmdate('d-m-Y g:i a', time() + $offset);
        $totdays = $days - $tcdays - $climit - (int)$cdays;
        if ($copy_dlvr_dt == '1970-01-01')
            $copy_dlvr_dt = '';
        if ($copy_aply_dt == '1970-01-01')
            $copy_aply_dt = '';
        if ($txt_attestation == '1970-01-01')
            $txt_attestation = '';
        $d_copy_dlvr_dt = '';
        $d_copy_aply_dt = '';
        if ($this->request->getVar('copy_dlvr_dt') != '' && $this->request->getVar('copy_dlvr_dt') != '')
            $d_copy_dlvr_dt = " (Copy ready on)" . $this->request->getVar('copy_dlvr_dt') . "- (Copy applied on)" . $this->request->getVar('copy_dlvr_dt');
        if ($this->request->getVar('txt_attestation') != '') {

            $f_order_dt = date_create($this->request->getVar('txt_attestation'));

            $cop_doa = "(Date of Attestation)" . $this->request->getVar('txt_attestation') . "- (Date of Filing)" . $this->request->getVar('filing_dt') . "   =" . $days . " days";  // not to use
        } else {
            $cop_doa = '(Date of Order)' . $data['getcasestatus']->o_d . '   -  (Date of Filing)' . $this->request->getVar('filing_dt') . '   =' . $days . ' days';
        }
        $day = date_diff($f_order_dt, $f_filing_dt);
        $day = $day->format('%R%a');
        $asdesc .= $cop_doa . $d_copy_dlvr_dt;
        $asdesc .= ' (Total Holidays)= ' . $leaves . ' days ' . $ext_days . '  Total Days =' . $days . '  -' . $cdays . '   -' . $leaves . '-' . $climit . '   = ' . $totdays . ' DAYS ';
        $totaldays = $days - $tcdays - $climit - $cdays;

        $d1 = clone $f_order_dt;
        //echo "ist   ".$f_order_dt->format('Y-m-d');
        date_add($d1, date_interval_create_from_date_string("$climit days"));
        //echo "/n 2nd   ".$f_order_dt->format('Y-m-d');
        // echo "/n initial".$d1->format('Y-m-d'); exit(0);
        $cv_days = '';
        $limit_end = date_format($d1, "Y-m-d");
        if (date_create($limit_end) >= date_create('2020-03-08') && date_create($limit_end) <= date_create('2022-02-28')) {
            if ($f_order_dt < date_create('2020-03-08')) {
                if ($f_filing_dt <= date_create('2022-07-11')) {
                    // echo "preeti = ".$totaldays;
                    $corona_start_date = date_create('2020-03-07');
                    $order_days = date_diff($f_order_dt, $corona_start_date);
                    $cv_days =  $order_days->format('%R%a') + 1;
                    $totaldays = ($cv_days) - $climit;
                    echo "<tr><td> * Pre Covid Delay days <b>(J)</b><br> " . $_REQUEST['order_dt'] . " to 06.03.2020</td><td>  <font color=blue>" . $cv_days .  " days </font> </td></tr>";
                    echo "<tr><td> * Covid Relief <b> [DEAD PERIOD]  <b><br> (08-03-2020 to 28-02-2022)<br>** not considered while calculating limitation</td><td>  722 days </td></tr>";


                    //$asdesc.="<br>Limitation period of 133 days ie from 01-03-2022 to ".$_REQUEST['filing_dt'].' Added';
                } else {
                    $corona_start_date = date_create('2020-03-07');
                    $order_days = date_diff($f_order_dt, $corona_start_date);
                    $cv_days =  $order_days->format('%R%a') + 1;
                    $after_corona_date = date_create('2022-03-01');
                    $after_corona_days = date_diff($after_corona_date, $f_filing_dt);
                    $after_corona_days =  $after_corona_days->format('%R%a') + 1;
                    $totaldays = ($cv_days + $after_corona_days) - $climit;
                    echo "<tr><td> * Covid Relief <b> [DEAD PERIOD]  <b><br> (08-03-2020 to 28-02-2022)<br>** not considered while calculating limitation</td><td>  722 days </td></tr>";

                    $asdesc .= "<br> * Corona benefit of 722 days ie from 08-03-2020 to 28-02-2022 is given <br>";
                }
            }
            if ($f_order_dt >= date_create('2020-03-08') && $f_order_dt <= date_create('2022-02-28')) {
                echo "<tr><td> * Covid Relief <b> [DEAD PERIOD]  <b><br> (08-03-2020 to 28-02-2022)<br>** not considered while calculating limitation</td><td>  722 days </td></tr>";
                $asdesc .= "<br> * Corona benefit of 722 days ie from 08-03-2020 to 28-02-2022 is given <br>";
                if ($f_filing_dt >= date_create('2022-03-01') && $f_filing_dt <= date_create('2022-07-11')) {
                    echo "<tr><td>Limitation period <br> <b>(" . $_REQUEST['filing_dt'] . ' to  01-03-2022 ) <br> * considered while caulculating the limitation</b></td><td> 133 days</td></tr>';
                    $asdesc .= "<br>Limitation period <br> of 133 days ie from " . $_REQUEST['filing_dt'] . ' 01-03-2022  </td><td>133 DAYS </td> <br>';
                    $totaldays = 0;
                } else {   // if limitation expired before 28 feburary then              
                    $days_diff = date_diff(date_create('2022-03-01'), $f_filing_dt);
                    $cv_days =  $days_diff->format('%R%a');
                    echo "cv days = " . $cv_days;
                    $totaldays = ($cv_days + 1) - $climit;
                    echo "c limit is " . $climit;
                    echo "<tr><td> Days Calculated <b>(J)</b> :<br> <b><br> <br</td><td> " . $totaldays . "  </td></tr>";
                }
            }
        }
        $asdesc1 = "Diary No.-" . substr($diary_no, 0, strlen($diary_no) - 4) . '-' . substr($diary_no, -4) . '<br/>';
        $descr = $asdesc1 . $asdesc . ' - '  . ' - Limitation Period for this case is = ' . $climit . ' days ';
        $data = [
            'climit' => $climit,
            'descr' => $descr,
            'case_nature' =>  $case_nature,
            'under_section' => $under_section,
            'o_s' => $sid,
            'pol' => $climit,
            'o_d' => $order_dt,
            'f_d' => $filing_dt,
            'c_d_a' => $copy_aply_dt,
            'd_o_d' => $copy_dlvr_dt,
            'd_o_a' => $txt_attestation,
            'case_lim_display' => 'Y',
            'diary_no' => $diary_no,
            'lowerct_id' => $lowerc_t,
            'order_cof' => $order_cof,
            'case_lmt_user' => $sessionUser,
            'case_lmt_ent_dt' => date("Y-m-d H:i:s"),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP(),
            'cause_title' => $cause_title,
            'order_dt' => $order_dt,
            'totalHolidays' => $totalHolidays,
            'days' => $days,
            'cdays' => $cdays,
            'totaldays' => $totaldays,
            'limit_days' => $totaldays,
            'f_order_dt' => $f_order_dt,
            'cv_days' => $cv_days,
        ];

        $model = new LimitationModel();
        $existingRecord = $model->where('diary_no', $diary_no)->first();
        if ($existingRecord) {
            $model->update($existingRecord['id'], $data);
            $ordernet_id = $existingRecord['id'];
        } else {
            $ordernet_id = $model->insertCaseLimit($data);
        }


        $data['insert_id'] = $ordernet_id;
        $data['diary_no'] = $diary_no;
        $data['result'] = $model->getLegalCases($diary_no);
        echo json_encode($data);
        die;
    }

    public function del_limit()
    {
        $hd_lim_id = $this->request->getPost('insert_id');

        $model = new LimitationModel();

        $success = $model->updateCaseLimit($hd_lim_id);
        if ($success) {
            echo "Data Updated Successfully";
        } else {
            echo "Error: Unable to update data";
        }
    }

    
    public function get_data_lim()
    {
        $data['limitModel'] = $model = new LimitationModel();
        $sessionData = $this->session->get();
        $diary_no = $sessionData['filing_details']['diary_no'];
        
        $c_status = $sessionData['filing_details']['c_status'];

        $hd_lower_id = $this->request->getPost('hd_lower_id');
        $sp_lct_dec_dt = $this->request->getPost('sp_lct_dec_dt');
        $sp_from_court = $this->request->getPost('sp_from_court');
        $sp_name = $this->request->getPost('sp_name');
        $sp_case_no = $this->request->getPost('sp_case_no');


        $data['res_p_r'] = $model->res_details($diary_no);
        
        $data['ch_cat'] = $model->getSubmasterId($diary_no);
        
        /*foreach($data['ch_cat'] as $row){
            $submaster_id = isset($row['submaster_id']) ? $row['submaster_id'] : '0';
            $data['r_chk_limi'] = $model->getlimitation($data['res_p_r']['casetype_id'], $submaster_id, 0);
        }*/
        //pr($data['r_chk_limi']);
            $submaster_id = isset($data['ch_cat']['submaster_id']) ? $data['ch_cat']['submaster_id'] : '0';
            $data['r_chk_limi'] = $model->getlimitation($data['res_p_r']['casetype_id'], $submaster_id, 0);
            //pr($data['r_chk_limi']);

        $data['res_chk_limi'] = $model->getlimitation2($data['res_p_r']['casetype_id'], '0', 0);
        //pr($data['res_chk_limi'] );
        $data['a_chk_limi'] = $model->getlimitation('0', $submaster_id, 0);
        $data['b_chk_limi']='';
        if(!empty($data['res_p_r']['actcode'])) {
            $data['b_chk_limi'] = $model->getlimitation('0', '0', $data['res_p_r']['actcode']);
        }
    
        $no_rws = $model->getcasestatuswithrows($diary_no);
        
        //$data['no_rws'] = $no_rws;
        $data['rw_sq'] = $model->getCaseLimit($diary_no, $hd_lower_id);
        
        $data['case_name'] = $model->getCaseName($data['res_p_r']['casetype_id']);
        $data['case_law'] = $model->getCaseLow($data['res_p_r']['actcode']);
        $data['case_type'] = $model->getCaseType($data['res_p_r']['casetype_id']);

        return view('Filing/get_data_lim', $data);
    }
    

    public function checkdays()
    {
        //$d_no = $this->request->getGet('d_no');
        //$d_yr = $this->request->getGet('d_yr');
        $model = new LimitationModel();
        $sessionData = $this->session->get();
        $diary_no = $sessionData['filing_details']['diary_no'];
        $c_status = $sessionData['filing_details']['c_status'];

        $data['order_dt'] = $this->request->getGet('order_dt');
        $data['filing_dt'] = $this->request->getGet('filing_dt');
        $data['copy_aply_dt'] = $this->request->getGet('copy_aply_dt');
        $data['copy_dlvr_dt'] = $this->request->getGet('copy_dlvr_dt');
        $data['txt_attestation'] = $this->request->getGet('txt_attestation');
        
        $climit = $this->request->getGet('climit');
        //$climit = 1;
        $v_val = $this->request->getGet('v_val');
        $hd_l_c_id = $this->request->getGet('hd_l_c_id');


        $order_dt = date('Y-m-d',  strtotime($data['order_dt']));
        $filing_dt = date('Y-m-d',  strtotime($data['filing_dt']));
        $copy_aply_dt = date('Y-m-d',  strtotime($data['copy_aply_dt']));
        $copy_dlvr_dt = date('Y-m-d',  strtotime($data['copy_dlvr_dt']));
        $txt_attestation = date('Y-m-d',  strtotime($data['txt_attestation']));

        $data['res_p_r_s'] = $model->res_details($diary_no);

        $flg = 0;
        $i = 1;
        $cdays = 0;
        $leaves = 0;

        $f_filing_dt = date_create($filing_dt);
        if ($txt_attestation != '1970-01-01') {
            $f_order_dt = date_create($txt_attestation);
        } else {
            $f_order_dt = date_create($order_dt);
        }


        $diff = date_diff($f_order_dt, $f_filing_dt);
        $data['days'] = $days = $diff->days;
        //$days= $diff->format('%R%a');
        //$days=  intval(str_replace('+','', $days));
        $ext_days = '';
        $extra_time = '';

        if ($copy_aply_dt != Null && $copy_dlvr_dt != NULL && $copy_aply_dt != '1970-01-01' && $copy_dlvr_dt != '1970-01-01') {
            $f_copy_dlvr_dt = date_create($copy_dlvr_dt);
            $f_copy_aply_dt = date_create($copy_aply_dt);
            $diff1 = date_diff($f_copy_aply_dt, $f_copy_dlvr_dt);
            $cdays = $diff1->format('%R%a');
            $cdays = ($cdays + 1);

            if (strtotime($order_dt) == strtotime($copy_aply_dt)) {
                $cdays = $cdays - 1;
            }
        }

        if ($txt_attestation != Null && $txt_attestation != NULL && $txt_attestation != '1970-01-01' && $txt_attestation != '1970-01-01') {
            $f_attestation = date_create($txt_attestation);
            $diff1 = date_diff($f_order_dt, $f_attestation);
            $cdays1 = $diff1->format('%R%a');
            $cdays1 = str_replace('+', ' ', $cdays1);
            $cdays = $cdays + $cdays1;
        }
        
        $cal_days = $climit + $cdays;

        if ($txt_attestation != '1970-01-01') {
            $chk_tot_days = date('Y-m-d', strtotime($txt_attestation . ' + ' . $cal_days . ' days'));
        } else {
            $chk_tot_days = date('Y-m-d', strtotime($order_dt . ' + ' . $cal_days . ' days'));
        }
            
        $next_chk_tot_days = helper_chksDate_vac_reg_add($chk_tot_days, $filing_dt);
        $chk_dt_status = 0;
        if (strtotime($next_chk_tot_days) == strtotime($filing_dt)) {
            $chk_dt_status = 1;
        }
    
        $extra_time_s = 0;
        if ($days > $climit && $chk_dt_status == 1) {
            $next_dt_ss = strtotime('- 1 day', strtotime($filing_dt));
            $next_dt_ss = date('Y-m-d',  $next_dt_ss);

            $next_dt_s = helper_chksDate_vac_reg_sub($next_dt_ss, $chk_tot_days);
            $f_next_dt_s = date_create($next_dt_s);
            $diff_ndt_s = date_diff($f_next_dt_s, $f_filing_dt);
            $days_bdt_s = $diff_ndt_s->format('%R%a');
            $days_bdt_s = str_replace('+', ' ', $days_bdt_s);

            $extra_time_s = intval($days_bdt_s);
            $next_holiday = chk_curr_dat_holiday($filing_dt);
            if (strtotime($next_holiday) != strtotime($filing_dt)) {
                $extra_time_s = $extra_time_s + 1;
            }
        }

        $leaves = intval($extra_time_s);
        $tcdays = $leaves;

        $asdesc = "";
        $offset = 5.5 * 60 * 60;
        $cur_date = gmdate('d-m-Y g:i a', time() + $offset);
        $asdesc= "Diary No.-".substr( $diary_no, 0, strlen( $diary_no ) -4 ).'-'.substr( $diary_no , -4 ).'<br/>' ;

        $data['cur_date'] = $cur_date;
        $data['diary_no'] = $diary_no;
        $data['cdays'] = $cdays;
        $data['leaves'] = $leaves;
        $data['climit'] = $climit;
        $data['tcdays'] = $tcdays;

        $data['f_filing_dt'] = $f_filing_dt;
        $data['ext_days'] = $ext_days;



        $totdays=$days - $tcdays - $climit - (int)$cdays;
        if ($copy_dlvr_dt == '1970-01-01')
            $copy_dlvr_dt = '';
        if ($copy_aply_dt == '1970-01-01')
            $copy_aply_dt = '';
        if ($txt_attestation == '1970-01-01')
            $txt_attestation = '';
        $d_copy_dlvr_dt = '';
        $d_copy_aply_dt = '';
        if ($copy_dlvr_dt != '' && $copy_aply_dt != '')
        $d_copy_dlvr_dt = " (Copy ready on)" . $copy_dlvr_dt . "- (Copy applied on)" . $copy_aply_dt;


        if($txt_attestation!=''){
           $f_order_dt=date_create($txt_attestation);   
           $cop_doa="(Date of Attestation)".$txt_attestation."- (Date of Filing)".$filing_dt."   =".$days." days";  // not to use
        } else {
           $cop_doa = '(Date of Order)' . $order_dt . '   -  (Date of Filing)' . $filing_dt . '   =' . $days . ' days';
        }
        /* conditions due to covid */

        $day=date_diff($f_order_dt,$f_filing_dt);
        $day= $day->format('%R%a');

        $asdesc.=$cop_doa.$d_copy_dlvr_dt;
        $asdesc.=' (Total Holidays)= '.$leaves.' days '.$ext_days.'  Total Days ='.$days.'  -'.$cdays.'   -'.$leaves.'-'.$climit.'   = '.$totdays.' DAYS ';

        $totaldays = $days - $tcdays - $climit - $cdays;
        
        $descr = '';
        
        if($totaldays <= 0) {
            $descr="<U>PETITION HAS BEEN FILED WITHIN".' '.$climit.' '."DAYS".' '."PETITION IS WITHIN TIME</U>";
        }
        $descr=$asdesc.' - '.$descr.' - Limitation Period for this case is = '.$climit.' days ';
        $caseid=0;
        $sid=0;

        $caseLimit = $model->getCaseLimit($diary_no, $hd_l_c_id);
        $insertArray = [
            'limit_days' => $totaldays,
            'descr' => $descr,
            'case_nature' => $caseid,
            'under_section' => $sid,
            'pol' => $climit,
            'o_d' => $order_dt,
            'f_d' => $filing_dt,
            'c_d_a' => $copy_aply_dt,
            'd_o_d' => $copy_dlvr_dt,
            'case_lim_display' => 'Y',
            'diary_no' => $diary_no,
            'lowerct_id' => $hd_l_c_id,
            'order_cof' => $v_val,
            'd_o_a' => $txt_attestation,
            'case_lmt_user' => $sessionData['login']['usercode'],
            'case_lmt_ent_dt' => date("Y-m-d H:i:s"),
            'o_s' => '',
        ];
        if($caseLimit <= 0) {
            insert('case_limit', $insertArray);
        } else {
            $condition = ['diary_no' => $diary_no, 'lowerct_id' => $hd_l_c_id, 'case_lim_display' => 'Y'];
            update('case_limit', $insertArray, $condition);
        }

        return view('Filing/checkdays', $data);
    }
}
