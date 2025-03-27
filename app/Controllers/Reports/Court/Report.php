<?php

namespace App\Controllers\Reports\Court;
use App\Controllers\BaseController;
use App\Models\Reports\Court\ReportModel;
use App\Models\Common\Dropdown_list_model;

class Report extends BaseController
{
    public $Dropdown_list_model;

    public $ReportModel;
    function __construct()
    {
         ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
         $this->Dropdown_list_model= new Dropdown_list_model();
         $this->ReportModel= new ReportModel();
    }

    public function index(){
        return view('Reports/court/report');
    }
    public function get_search_view(){
        $type = $_REQUEST['type'];
        if(!empty($type)){
            $type =  (int)$type;
             switch($type){
                case 1: //Paperless Court
                   // $data['casetype']=get_from_table_json('casetype');
                    return view('Reports/court/paperless_court_search_view');
                    break;
                case 2: //Part Head
                    $judge = get_from_table_json('judge');
                    foreach($judge as $key => $value):

                        if($value['is_retired']=='N'){
                            $judge_list[] = $value;
                        }
                    endforeach;
                    $data['usertype'] = 1;
                    $data['judge'] = $judge_list;
                   return view('Reports/court/part_heard_search_view',$data);
                    break;
                case 3: //Daily Disposal Remarks
                    return view('Reports/court/daily_disposal_search_view');
                    break;
                case 4: //Gist Module
                    return view('Reports/court/gist_module_search_view');
                    break;
                case 5: //matters_disposed_through_mentioning_search
                    //print_r($role);exit;
                   // $data['judge'] = $this->ReportModel->get_judges_list_current();
                    return view('Reports/court/matters_disposed_through_mentioning_search_view');
                    break;
                case 6: //Final Disposal Matter
                   // $data['judge'] = $this->ReportModel->get_judges_list_current();
                    return view('Reports/court/final_disposal_matters_search_view');
                    break;
                case 7: //Fixed Date Matters
                    $data['judge'] = $this->ReportModel->get_judges_list_current();
                    return view('Reports/court/fixed_date_matters_search_view',$data);
                    break;
                 case 8: //Cause List with OR
                     $data['listing_dates'] = $this->ReportModel->getListingDates();
                     return view('Reports/court/cause_list_with_OR_search_view',$data);
                     break;
                 case 9: //Appearance Search
                     return view('Reports/court/appearance_search_view');
                     break;
                 case 10: //Reports master
                     return view('Reports/court/upload_search_view');
                     break;
                 case 11: //Vernacular Judgments Report
                     return view('Reports/court/vernacular_judgments_report_search_view');
                     break;
                 case 12: //CAV
                     $judge = get_from_table_json('judge');
                     foreach($judge as $key => $value):

                         if($value['is_retired']=='N'){
                             $judge_list[] = $value;
                         }
                     endforeach;
                     $data['usertype'] = 1;
                     $data['judge'] = $judge_list;
                     return view('Reports/court/cav_search_view',$data);
                     break;
                case 13: //ROP NOT Uploaded
                    $data['judge'] = $this->ReportModel->get_judges_list_current();
                    return view('Reports/court/rop_not_uploaded',$data);
                    break;

                default:

            }
        }


    }

    

   /* public function paperless_court(){
        $ReportModel = new ReportModel();
        $data['cause_list_date'] = $this->request->getPost('cause_list_date');
        $data['courtno'] = $this->request->getPost('courtno');
        $data['formdata'] = $this->request->getPost();
        // print_r($data);exit;
        if (!empty($data)) {

            $rosterData= $ReportModel->get_rosterid($data);
            $rosterIds = [];
            foreach ($rosterData as $item) {
                $rosterIds[] = $item->roster_id;
            }
            $rosterIdsString = implode(',', $rosterIds);

            $data['rosterID'] = $rosterIdsString;
            if(!empty($rosterData)) {
                $data['report_title'] = 'Paperless Court ';
                $data['ppl_court'] = $ReportModel->getpaperless_court($data);

            }
            return view('Reports/court/get_content_paperless_court', $data); exit;
        }
    } */
    public function part_heard_search(){
        $ReportModel = new ReportModel();
        $data['formdata'] = $this->request->getPost();
        $data['judge'] = $this->request->getPost('judge');
        $data['mr'] = $this->request->getPost('mr');
        $data['report_type'] = $this->request->getPost('report_type');
        $data['report_title'] = 'Details of Part heard';
       if ($data['judge']!=''){
            $data['dataPartHeard']= $ReportModel->getPartHeard($data);
       }
       if ($data['judge']!=0){
            $data['getJname']= $ReportModel->getJname($data['judge']);
            $data['Jname'] = $data['getJname'][0]['jname'];
       }
       return view('Reports/court/get_content_part_heard',$data);exit;
            
    }
    public function daily_disposal_remarks(){
        $ReportModel = new ReportModel();

        $data['formdata'] = $this->request->getPost();
        $disposalon_date = date('Y-m-d',strtotime($this->request->getPost('on_date')));
          $data['report_title'] = 'Details of Daily Disposal Remarks';
        if (!empty($disposalon_date)){
            $data['dataDisposalRemarks']= $ReportModel->getDisposalRemarks($disposalon_date);
        }
        $data['disposalon_date'] = $disposalon_date;
       return view('Reports/court/get_content_daily_disposal_remarks',$data);exit;

    }
    public function gist_module_search(){
        $ReportModel = new ReportModel();

        $data['formdata'] = $this->request->getPost();
        $data['listing_dts'] = date('Y-m-d',strtotime($this->request->getPost('listing_dts')));
        $data['courtno'] = $this->request->getPost('courtno');
        $data['mainhead'] = $this->request->getPost('mainhead');
        $data['report_title'] = 'Details of Gist Module Data';

        $board_type = $this->request->getPost('board_type');
        $main_suppl = $this->request->getPost('main_suppl');

        $data['board_type'] = $board_type;

        $max_gist_last_read_datetime = $ReportModel->max_gist_last_read_datetime($data);
        $data['max_date'] = $max_gist_last_read_datetime[0]['max_date'];
        if (!empty($data['courtno'])){
            $data['dataGistModule']= $ReportModel->getGistModule($data,$board_type,$main_suppl);
        }
        return view('Reports/court/get_content_gist_module',$data);exit;

    }
    public function cav_search(){
        $ReportModel = new ReportModel();
        $data['formdata'] = $this->request->getPost();
        $data['judge'] = $this->request->getPost('judge');
        $data['name'] = $this->request->getPost('judge_name');
        $data['report_title'] = 'Details of CAV Search';
        if ($data['judge']!=''){
            $data['dataCAV']= $ReportModel->getCAV($data);
        }
        return view('Reports/court/get_content_cav',$data);exit;

    }
    public function matters_disposed_through_mm(){
        $ReportModel = new ReportModel();
        $data['formdata'] = $this->request->getPost();
        $data['from_date'] = date('Y-m-d',strtotime($this->request->getPost('from_date')));
        $data['to_date'] = date('Y-m-d',strtotime($this->request->getPost('to_date')));
        $data['courtno'] = $this->request->getPost('courtno');
        $data['report_title'] = 'Details of MDTM Search with Selected Filter';
      //  $data['judge'] = get_from_table_json('judge');
        $data['mdtmReport'] = $ReportModel->getmm_disposed($data);
        return view('Reports/court/get_content_matters_disposed_through_mm',$data);exit;

    }
    public function final_disposal_matters(){

        $ReportModel = new ReportModel();
        $judge = $this->request->getPost('judge');
        if (!empty($judge)){
            $data['dataFDM']= $ReportModel->getFinalDisposalMatters($judge);
        }

        $data['formdata'] = $this->request->getPost();
        $data['report_title'] = 'Details of File Trap Search with Selected Filter';
        return view('Reports/court/get_content_final_disposal_matters',$data);exit;

    }
    public function fixed_date_matters(){
        $ReportModel = new ReportModel();
        $data['report_type'] = $this->request->getPost('report_type');
        $judge = $this->request->getPost('judge');

        $data['judges_data'] = is_data_from_table('master.judge', ['jcode' => $judge,'display'=>'Y','jtype'=>'J'], 'jname, jcode','R');
        if($data['report_type'] == 1){
            $heading1 = " Misc. Matters ";
        } else if ($data['report_type']  == 2){
            $heading1 = " NMD Matters ";
        }else{
            $heading1 = " Regular Hearing Matters ";
        }
        $data['judge_id'] = $judge;
        $data['fdmReport'] = array();
        $data['heading'] = $heading1;
        if (!empty($data['report_type'])){
            $data['fdmReport']= $ReportModel->getFixedDateMatters($judge,$data['report_type']);
            $data['report_title'] = 'Reports Fixed Date Matters';
            //print_r($data);exit;
        }
      return view('Reports/court/get_content_fixed_date_matters',$data);exit;
            
    }
    public function cause_list_with_or(){
        $ReportModel = new ReportModel();
        $data['formdata'] = $this->request->getPost();
        $data['listing_date'] = date('Y-m-d',strtotime($this->request->getPost('listing_date')));
        $data['board_type'] = $this->request->getPost('board_type');
        $data['courtno'] = $this->request->getPost('courtno');
        $data['main_suppl'] = $this->request->getPost('main_suppl');
        $data['mr'] = $this->request->getPost('mr');
        $main_supl_head = $mainhead_descri =  '';
        if ($data['main_suppl']== "1") {
            $main_supl_head = "Main List";
        }
        if ($data['main_suppl'] == "2") {
            $main_supl_head = "Supplimentary List";
        }

        if ($data['mr'] == 'M') {
            $mainhead_descri = "Miscellaneous Hearing";
        }
        if ($data['mr'] == 'F') {
            $mainhead_descri = "Regular Hearing";
        }
        if($data['listing_date'] != "-1"){
            $listing_date = date('d-m-Y', strtotime($data['listing_date']));
        }else{
            $listing_date = date('d-m-Y');
        }

        $data['dataCauseListwithor']= $ReportModel->getcauseListWithOR($data);
        $data['report_title'] = 'Cause List for Dated '.$listing_date. '('.$mainhead_descri.')<br>'.$main_supl_head ;
           // print_r($data);exit;

        return view('Reports/court/get_content_cause_list_withor',$data);exit;

    }


    public function appearance_search(){
        $ReportModel = new ReportModel();
        $data['listing_dts'] = date('Y-m-d',strtotime($this->request->getPost('listing_dts')));
        $data['courtno'] = $this->request->getPost('courtno');

        if (!empty($data['listing_dts'])){
            $data['appearanceSearchData']= $ReportModel->getAppearanceSearchReport($data);
            $data['report_title'] = 'Appearance Search';
           //  print_r($data);exit;
        }
        return view('Reports/court/get_content_appearance_search_report',$data);exit;

    }

    public function vernacular_judgments_report(){
        $ReportModel = new ReportModel();
        $data['from_date'] = date('Y-m-d',strtotime($this->request->getPost('from_date')));
        $data['to_date'] = date('Y-m-d',strtotime($this->request->getPost('to_date')));
        $judge = $this->request->getPost('judge');
        if (!empty($data['from_date'])){
            $data['vernacularjudgmentData']= $ReportModel->getvernacularJudgmentsReport($data);
            $data['report_title'] = 'Reports Fixed Date Matters';
            // print_r($data);exit;
        }
        return view('Reports/court/get_content_vernacular_judgments_report',$data);exit;

    }

    public function judge_coram_cases_detail_get_nsh()
    {
        $data['app_name']='';
        $jcd=$this->request->getGet('jcd');
        $flag=$this->request->getGet('flag');
        $list_dt=date('Y-m-d',strtotime($this->request->getGet('list_dt')));
        $misc_nmd=$this->request->getGet('misc_nmd');
        $judge_name = is_data_from_table('master.judge',['jcode'=>$jcd],'jname','R');

        $msc_nmd_q = "";

        if ($misc_nmd == 1) {
            $mainhead = "M";
            $heading1 = " Misc. Matters ";
            $nmd_flag = 0;
            $subhead_qry = " AND h.subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816) ";
        } else if ($misc_nmd == 2) {
            $mainhead = "M";
            $heading1 = " NMD Matters ";
            $nmd_flag = 1;
            $subhead_qry = " AND h.subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816) ";
        } else {
            $mainhead = "F";
            $heading1 = " Regular Hearing Matters ";
            $nmd_flag = 1;
            $subhead_qry = "  ";
        }

        if ($flag == 'f') {
            // $subquert1 = " AND (listorder = 4) ";
            $headnote1 = " Court Dated Cases ";
        }
        if ($flag == 'all') {
            $subquert1 = " ";
            $headnote1 = " ";
        }

        if ($list_dt == 0) {
            $sub_list_dt = " AND next_dt >= curdate() ";
            $headnote_date = " ";
        } else {
            $sub_list_dt = " AND next_dt = '$list_dt'";
            $headnote_date = " List On " . date('d-m-Y', strtotime($list_dt));
        }
            $judge_coram_report=$this->ReportModel->judge_coram_cases_detail_get_nsh($nmd_flag,$mainhead,$jcd,$sub_list_dt,$msc_nmd_q,$subhead_qry);
            $data['judge_coram_result']=$judge_coram_report;
            $data['title'] = $judge_name['jname'] . ", " . $headnote1 . ", " . $heading1.", Ready To List".$headnote_date;
            //$data['dt_title'] = str_replace("'",$judge_name['jname']). ", " . $headnote1 . ", " . $heading1.", Ready To List".$headnote_date;

            return view('Reports/court/judge_coram_cases_detail_view', $data);

    }

    public function getROPNotUploaded(){
       
        $data['fromDate']= $_POST['causelistFromDate'];
        $data['toDate']= $_POST['causelistToDate'];
        $data['judgeName']= $_POST['pJudge'];

        $causelistFromDate = date('Y-m-d',strtotime($_POST['causelistFromDate']));
        $causelistToDate = date('Y-m-d',strtotime($_POST['causelistToDate']));
        $pJudge = $_POST['pJudge'];

        $data['ReportModel'] = new ReportModel();

         $data['caseList']=$this->ReportModel->ropNotUploaded($causelistFromDate,$causelistToDate,$pJudge);
         return view('Reports/court/ropNotuploadedCases', $data);
     }



     public function get_cl_date_judges()
     {

  
        $ucode = $_SESSION['login']['usercode'];
        $icmis_user_jcode = $_SESSION['login']['jcode'];
        $dcmis_section = $_SESSION['login']['section'];
        $judge_code = '';
        if($_REQUEST['flag'] == 'court'){
            if($icmis_user_jcode > 0 and $ucode != 1){
                $judge_code = "and t3.jcode = $icmis_user_jcode";
                $select_display_none = "display:none;";
            }
            else{
                $selectOption = "<option value=''>select</option>";
            }
        }
        if($_REQUEST['flag'] == 'reader'){
            if($dcmis_section == 62){
                $judge_code = "and (t1.courtno = 21 OR t1.courtno = 61 )";
                $select_display_none = "display:none;";
            }
            else if($dcmis_section == 81){
                echo $judge_code = "and (t1.courtno = 22 OR t1.courtno = 62 )";
                $select_display_none = "display:none;";
            }
            else{
                $selectOption = "<option value=''>select</option>";
            }
        }
 
        $dtd = date('Y-m-d', strtotime($_REQUEST['dtd']));
       /* $sql_reg="SELECT distinct t1.courtno, concat(t3.jname,' ',t3.first_name,' ',t3.sur_name) jname
        FROM master.roster t1
        INNER JOIN master.roster_judge t2 ON t1.id = t2.roster_id
        INNER JOIN master.judge t3 ON t3.jcode = t2.judge_id
        LEFT JOIN cl_printed cp on cp.next_dt = '$dtd' and cp.roster_id = t1.id and cp.display = 'Y'
        WHERE cp.next_dt is not null and '$dtd' >= t1.from_date
            AND t1.to_date IS NULL
            AND t3.jtype = 'R' $judge_code
            AND t3.is_retired = 'N'
            AND t1.display = 'Y'
            AND t2.display = 'Y'
        ORDER BY t3.jcode";
        $results_query = $this->db->query($sql_reg);
        $results_reg = $results_query->getResultArray(); */
        $results_reg =  $this->ReportModel->getRegisteredJudges($dtd, $judge_code);
        if (!empty($results_reg)) {
            echo $selectOption;
            foreach ($results_reg as $row_reg) {
                $judge_name = $row_reg["jname"];
                echo '<option value="' . $row_reg["courtno"].'">' . str_replace("\\", "", $row_reg["jname"]) . '</option>';
            }
        }
     }


     public function get_title()
     {
        $ucode = $_SESSION['login']['usercode'];
        $courtno= $_POST['courtno'];
        
        $judge_code = "and r.courtno = $courtno ";
        
        $dtd= date('Y-m-d', strtotime($_POST['dtd']));
        if($courtno > 0){
            /* $sql = "SELECT r.id, GROUP_CONCAT(distinct j.jcode ORDER BY j.judge_seniority) jcd, 
            GROUP_CONCAT(distinct j.jname ORDER BY j.judge_seniority SEPARATOR ', ') jnm, j.first_name, j.sur_name, title,
                            r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session FROM roster r 
                            LEFT JOIN roster_bench rb ON rb.id = r.bench_id 
                            LEFT JOIN master_bench mb ON mb.id = rb.bench_id
                            LEFT JOIN roster_judge rj ON rj.roster_id = r.id 
                            LEFT JOIN judge j on j.jcode = rj.judge_id
                            LEFT JOIN cl_printed cp on cp.next_dt = '$dtd' and cp.roster_id = r.id and cp.display = 'Y' 
                            WHERE cp.next_dt is not null and j.is_retired != 'Y' and j.display  = 'Y' 
                            and rj.display = 'Y' and rb.display = 'Y' and mb.display = 'Y' 
                            and r.display = 'Y' $judge_code group by r.id
                            ORDER BY r.id, j.judge_seniority";
            $res = mysql_query($sql) or die(mysql_error()); */

            $res = $this->ReportModel->getRosterDetails($dtd, $judge_code);

            if (!empty($res)){
                $row = $res;

                if ($row['courtno'] == 21) {
                    $court = "Registrar Court No. 1";
                    $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
                }
                else if ($row['courtno'] == 61) {
                    $court = "Registrar Virtual Court No. 1";
                    $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
                }
                else if ($row['courtno'] == 22) {
                    $court = "Registrar Court No. 2";
                    $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
                }
                else if ($row['courtno'] == 62) {
                    $coudiary_nort = "Registrar Virtual Court No. 2";
                    $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
                } else {
                    $court = "Court No. " . $row['courtno'];
                    $judge_name = $row['jnm'];
                }
                ?>
                <p style="font-size: 1.2vw; padding-top: 2px;"><?php echo $court . ' @ ' . $judge_name;
                    ?>
                    <span style="font-size: 0.7vw; color: #009acd; ">List Of Business For <?php echo date('l', strtotime($_POST['dtd'])) . ' The ' . date('jS F, Y', strtotime($_POST['dtd'])); ?></span>
                </p> 
                <?php
            }
            else {
                echo "Not Found !...";
            }
        }
     }


     public function get_item_nos()
     {

       
        $crt = $_REQUEST['courtno'];
        $dtd = $_REQUEST['dtd'];
        if($crt > 0) {
     
            $mf = "M";
     
            $msg = "";
            //$tdt = explode("-", $dtd);
            //$tdt1 = $tdt[2] . "-" . $tdt[1] . "-" . $tdt[0];
            $tdt1  = date('Y-m-d',strtotime($dtd));
            $printFrm = 0;
            ///=====Not Show If Cause List Not Print
            $pr_mf = $mf;
            $sql_t = "";
            $ttt = 0;

            if ($crt != '') {
                if ($mf == 'M') {
                    $stg = 1;
                } else if ($mf == 'F') {
                    $stg = 2;
                }
                
               // $t_cn = " and `courtno` = '" . $crt . "' AND if(to_date = '0000-00-00', to_date = '0000-00-00', '" . $tdt1 . "' BETWEEN from_date AND to_date) ";
               $t_cn = " AND courtno = '" . $crt . "'  AND (to_date IS NULL OR '" . $tdt1 . "' BETWEEN from_date AND to_date)";
 
                $sql_ro = $this->ReportModel->getRosterJudgeDetails($stg, $t_cn);
              
                $result = '';
                if(!empty($sql_ro))
                {
                    foreach($sql_ro as $res) {
                        if ($result == '')
                            $result .= $res['roster_id'];
                        else
                            $result .= "," . $res['roster_id'];
                    }
                }
               // pr($result);
                $r_status = '';
                $whereStatus = "";
                if ($r_status == 'A') {
                    $whereStatus = '';
                } else if ($r_status == 'P') {
                    $whereStatus = " and m.c_status='P'";
                } else if ($r_status == 'D') {
                    $whereStatus = " and m.c_status='D'";
                }

              

        $results10 = $this->ReportModel->getCaseDetails($tdt1, $mf, $result, $whereStatus);
               
            }

            
            //$results10 = mysql_query($sql_t) or die(mysql_error());
            $jc = "";
            $chk_var = 0;
            $not_avail = "";
            if (!empty($results10)) {
                ?>
                <div class="list-group list-group-mine">
                <?php
                $chk_var = 1;
                $con_no = 0;
                $odd_even = 1;
                foreach ($results10 as $row10) {
                    $t_diary_no = $row10['diary_no'];
                    $t_next_dt = $row10['next_dt'];
                    $t_list_status = $row10['list_status'];
                    $t_reg_no_display = $row10['reg_no_display'];
                    $caseno = $row10["case_no"] . " / " . $row10["year"];
                    if ($row10['diary_no'] == $row10['conn_key'] OR $row10['conn_key'] == 0) {
                        $print_brdslno = $row10['brd_slno'];
                        $con_no = "1";
                    } else {
                        $print_brdslno = $row10["brd_slno"] . "." . $con_no++;
                    }
                    if ($t_list_status == 'DELETED') {
                        $is_deleted = "style='background-color: #ff0000; color:black;'";
                        $is_disable = "disabled";
                    } else {
                        $is_deleted = "";
                        $is_disable = "";
                    }

                    if ($odd_even % 2 == 0) {
                        /*$style_colr = "#99ddff";*/
                        $style_colr = "list-group-item-info";
                    } else {
                        /*$style_colr = "#b3e6ff";*/
                        $style_colr = "list-group-item-primary";
                    }
                    $odd_even++;
                    $display_board_val1 = $crt . ':' . $mf . ':' . $tdt1 . ':' . str_replace(" - ", " ", $caseno) . ':' . str_replace(":", "&nbsp;", str_replace(" & ", " and ", $row10["pet_name"] . ' Vs ' . $row10["res_name"])) . ':' . $row10["brd_slno"];
                    $display_board_val2 = $row10["judges"];


                    ?>

                    <!--style=""-->
                    <div style="padding-bottom: 1px; padding-top: 1px;" onclick="item_no_function(this)" class="item_no list-group-item <?= $is_disable; ?>"
                        data-displayboardval1="<?= $display_board_val1; ?>"
                        data-displayboardval2="<?= $display_board_val2; ?>" data-dno="<?= $t_diary_no; ?>"
                        data-conn_key="<?= $row10["conn_key"] ?>"
                        data-listdt="<?= $t_next_dt; ?>">
                        <div class="row"<?= $is_deleted; ?> >

                            <div class="column_item1"><span style="font-size:0.9vw;"><?= $print_brdslno; ?></span></div>
                            <!--style="color:#4B0082;"-->
                            <div class="column_item4"><span style="font-size:0.9vw;">
                    <?php

                    if ($row10['reg_no_display']) {
                        echo $row10['reg_no_display'] . ' <br> DNO. ';
                    } else {
                        echo $row10['short_description'] . " .. DNO. ";
                    }
                    echo substr_replace($row10['diary_no'], '-', -4, 0);
                   
                    echo '</span><br><span style="font-size:0.6vw;">' . $row10['pet_name'] . ' <font color="#006400">Vs.</font> ' . $row10['res_name'] . '</span>';

                    ?> 
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                </div>
                <?php
            } else
                echo 'No Records Found';
            
        }
     }


     public function get_right_panel_data_row2()
     {
        
        $diary_no = $_POST['diary_no'];
        $list_dt = $_POST['listdt'];

        $sql_o = "select * from office_report_details o where o.diary_no = $diary_no and o.order_dt = '$list_dt' and o.display='Y' and o.web_status=1";
        $res_o=$this->db->query($sql_o);
        $row_o = $res_o->getRowArray();
        if(!empty($row_o)){
            //$row_o = mysql_fetch_array($res_o);
            $split_or_path = explode("_",$row_o['office_repot_name']);
            $or_gen_dt = date('d-m-Y H:i:s', strtotime($row_o['rec_dt']));
            $or_address = "http://XXXX/supreme_court/officereport/". $split_or_path[1]."/".$split_or_path[0]."/".$row_o['office_repot_name']."#zoom=FitV";
            $or_address_for_path = "http://XXXX/supreme_court/officereport/". $split_or_path[1]."/".$split_or_path[0]."/".$row_o['office_repot_name'];
            $path_info = pathinfo($or_address_for_path);
            if($path_info['extension'] == 'html'){
                $obj_type = "text/html";
            }
            else{
                $obj_type = "application/pdf";
            }
            ?>
            <div style="text-align: left; padding:0px;">
                <p style="font-size: 1.2vw; color: #4169E1;">Office Report</p>
                <div class="embed-responsive" style="padding-bottom: 97%;">
                    <object class="embed-responsive-item" data="<?=$or_address;?>" type="<?=$obj_type;?>" internalinstanceid="9" title="" >
                        <p>Your browser isn't supporting embedded pdf files. You can download the file
                            <a href="<?=$or_address;?>">here</a>.</p>
                    </object>
                </div>
            </div>
            <?php
        }
     }

     public function get_gist_details()
     {
        $data['ReportModel'] = new ReportModel();
        return view('Reports/court/get_gist_details', $data);
     }


}
