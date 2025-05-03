<?php

namespace App\Controllers\Extension;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use CodeIgniter\Model;
use App\Models\Extension\NoticesModel;

class Notices extends BaseController
{
    // public $session;
    public $notices_model;

    function __construct()
    {
        // $this->session = \Config\Services::session();
        //$this->session->start();
        date_default_timezone_set('Asia/Calcutta');
        $this->notices_model = new NoticesModel;
    }

    public function index()
    {
        return view('Extension/Notices/generate');
    }

    public function generated()
    {

        $data['noticesModel'] = $this->notices_model;
        $data['d_no'] = $this->request->getPost('d_no');
        $data['d_yr'] = $this->request->getPost('d_yr');
        $data['fno'] = $this->request->getPost('fno');
        $data['ct'] = $this->request->getPost('ct');
        $data['cn'] = $this->request->getPost('cn');
        $data['cy'] = $this->request->getPost('cy');
        $data['chk_status'] = $this->request->getPost('chk_status');

        return view('Extension/Notices/generate_view', $data);
    }

    public function add_additional_data()
    {
        extract($_POST);
        $data['noticesModel'] = $this->notices_model;
        return view('Extension/Notices/add_additional_data', $data);
    }


    public function get_send_to_type()
    {
        $dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        if ($_REQUEST['id_val'] == 2) {
            //$sql="Select id,desg from  tw_send_to where display='Y'";
            $sqlResult = is_data_from_table('master.tw_send_to', "display='Y'", "id,desg, NULL as lct_judge_desg", 'A');
        } else  if ($_REQUEST['id_val'] == 1) {
            $query = $this->db->query("SELECT advocate_id id, name || '-' || aor_code AS desg, NULL as lct_judge_desg FROM advocate a
                    LEFT JOIN master.bar b ON a.advocate_id = b.bar_id WHERE a.display = 'Y' AND diary_no = '$dairy_no' ORDER BY pet_res");
            $sqlResult = $query->getResultArray();
        } else  if ($_REQUEST['id_val'] == 3) {
            $addional_diary = '';
            //$casetype_id="Select active_casetype_id from main where diary_no = '$dairy_no'";
            //$casetype_id=  mysql_query($casetype_id) or die("Error: ".__LINE__.mysql_eror());
            //$res_casetype_id=  mysql_result($casetype_id, 0);
            $casetype_id = is_data_from_table('main', " diary_no = '$dairy_no' ", "active_casetype_id", '');
            $res_casetype_id = $casetype_id['active_casetype_id'] ?? '';

            if ($res_casetype_id == '9' || $res_casetype_id == '10' || $res_casetype_id == '25' || $res_casetype_id == '26') {
                $r_c_lct = $this->db->query("Select lct_casetype,lct_caseno,lct_caseyear from master.lowerct where 
                            diary_no = '$dairy_no' and ct_code='4' and lw_display='Y' and lct_casetype not in(9,10,25,26)");
                $r_c_lct = $query->getResultArray();
                // $r_c_lct=  mysql_query($r_c_lct) or die("Error: ".__LINE__.mysql_error());
                if (!empty($r_c_lct)) {
                    $add_diary = '';
                    // include('../extra/casetype_diary_no.php');

                    foreach ($r_c_lct as $row1) {
                        $get_diary_case_type = get_diary_case_type($row1['lct_casetype'], $row1['lct_caseno'], $row1['lct_caseyear']);
                        if ($add_diary == '')
                            $add_diary = $get_diary_case_type;
                        else
                            $add_diary = $add_diary . ',' . $get_diary_case_type;
                    }
                    $additional_diary = " or diary_no in ($add_diary)";
                }
            }
            $is_order_challenged = '';
            if ($res_casetype_id != '7' && $res_casetype_id != '8') {
                $is_order_challenged = " and  is_order_challenged = 'Y'";
            }

            $lct_judge_desg_s = '';
            $lct_judge_desg = '';
            if ($res_casetype_id == '7' || $res_casetype_id == '8') {
                $lct_judge_desg = ",lct_judge_desg";
            }
            /* echo  $sql="SELECT DISTINCT min(lower_court_id)  id, concat(
                IF (
                ct_code =3, (

                SELECT Name
                FROM state s
                WHERE s.id_no = a.l_dist
                AND display = 'Y'
                ), (

                SELECT agency_name
                FROM ref_agency_code c
                WHERE c.cmis_state_id = a.l_state
                AND c.id = a.l_dist
                AND is_deleted = 'f'
                ) ) , ' ', b.Name
                )desg $lct_judge_desg,ct_code
                FROM `lowerct` a
                JOIN state b ON a.l_state = b.id_no
                WHERE `diary_no` = '$dairy_no' $addional_diary
                AND lw_display = 'Y'
                AND b.display = 'Y' $is_order_challenged group by l_state,l_dist $lct_judge_desg "; */

            $sqlResult =   $this->notices_model->getLowerCourtDetails($dairy_no, $addional_diary, $is_order_challenged, $lct_judge_desg);
        }
        //$sql=mysql_query($sql) or die("Error: ".__LINE__.mysql_error());
        $option = '';
        $lct_judge_desg_s = '';
        if (!empty($sqlResult)) {
            foreach ($sqlResult as $row) {

                if (!empty($row['lct_judge_desg']) &&  $row['lct_judge_desg'] != '0') {
                    //require_once ('function_template.php');
                    $get_lower_court_judge = get_lower_court_judge($row['lct_judge_desg']);
                    $lct_judge_desg_s = $get_lower_court_judge;
                }
                $option .= '<option value="' . $row['id'] . '">' . $lct_judge_desg_s . $row['desg'] . '</option>';
            }
        }
        echo $option;
        die;
    }

    public function get_dynamic_cst_new()
    {
        $mode = '';
        $hd_Sendcopyto_o = $_REQUEST['hd_Sendcopyto_o'];
?>
        <div style="margin-top: 10px">
            <select name="<?php echo $_REQUEST['ddl_send_copy_typeo_id'] ?>_<?php echo $hd_Sendcopyto_o ?>"
                id="<?php echo $_REQUEST['ddl_send_copy_typeo_id'] ?>_<?php echo $hd_Sendcopyto_o ?>" onchange="get_send_to_type(this.id,this.value,'3','<?php echo $mode; ?>')">
                <?php
                echo $_REQUEST['ddl_send_copy_typeo__html'];
                ?>
            </select>
            <select name="<?php echo $_REQUEST['ddlSendCopyTo_o_id'] ?>_<?php echo $hd_Sendcopyto_o ?>"
                id="<?php echo $_REQUEST['ddlSendCopyTo_o_id'] ?>_<?php echo $hd_Sendcopyto_o ?>"
                onfocus="clear_data(this.id)" style="width: 130px;">
                <?php
                echo $_REQUEST['ddlSendCopyTo_o_html'];
                ?>
            </select>
            <select name="<?php echo $_REQUEST['ddl_cpsndto_state_o_id'] ?>_<?php echo $hd_Sendcopyto_o ?>"
                id="<?php echo $_REQUEST['ddl_cpsndto_state_o_id'] ?>_<?php echo $hd_Sendcopyto_o ?>"
                style="width: 100px" onchange="getCity(this.value,this.id,'3','<?php echo $_REQUEST['o_r_h'] ?>')">
                <?php
                echo $_REQUEST['ddl_cpsndto_state_o_html'];
                ?>
            </select>
            <select name="<?php echo $_REQUEST['ddl_cpsndto_dst_o_id'] ?>_<?php echo $hd_Sendcopyto_o ?>"
                id="<?php echo $_REQUEST['ddl_cpsndto_dst_o_id'] ?>_<?php echo $hd_Sendcopyto_o ?>"
                style="width: 100px">
                <?php
                echo $_REQUEST['ddl_cpsndto_dst_o_html'];
                ?>
            </select>
        </div>
    <?php
        die;
    }


    public function get_ck_mul_rem()
    {
        $dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $date = date('Y-m-d');
        /* $sql=  mysql_query("SELECT  cl_date , r_head FROM case_remarks_multiple
                            WHERE fil_no = '$dairy_no' and (r_head = '90'
        OR r_head = '91'
        OR r_head = '9'
        OR r_head = '10'
        OR r_head = '117'
        OR r_head = '62'
        OR r_head = '11'
        OR r_head = '60'
        OR r_head = '74'
        OR r_head = '75'
        OR r_head = '65'
        OR r_head = '2'
        OR r_head = '1'
        OR r_head = '94'
        OR r_head = '3'
        OR r_head = '4'
        OR r_head = '96'
        OR r_head = '57'
        OR r_head = '93'
        OR r_head = '59'
        ) and cl_date=(select max(cl_date) from case_remarks_multiple where diary_no = '$dairy_no')"); */

        $result =  $this->notices_model->getLatestCaseRemark($dairy_no);


        foreach ($result as $res) {

            // $sq_sql_ins=mysql_query("Select count(id) from tw_not_pen_sta where diary_no = '$dairy_no' and ck_cl_dt='$res[cl_date]' and ck_hd='$res[r_head]'");
            // $res_sq_sql_ins=mysql_result($sq_sql_ins, 0);

            $sq_sql_ins = is_data_from_table("tw_not_pen_sta", "diary_no = '$dairy_no' and ck_cl_dt='$res[cl_date]' and ck_hd='$res[r_head]'", "count(id) as total", '');
            $res_sq_sql_ins = $sq_sql_ins['total'];
            if ($res_sq_sql_ins <= 0) {
                $sql_ins =  $this->db->query("Insert Into tw_not_pen_sta (diary_no,ck_rec_dt,ck_cl_dt,ck_hd) values ('$dairy_no','$date','$res[cl_date]','$res[r_head]')");
            }
        }
        return true;
    }

    public function save_talwana()
    {
        
        $ucode = session()->get('login')['usercode'];        
        $dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $sq_ck = '';

        $year =  date('Y');
        $date =  date('Y-m-d');

        $_REQUEST['sp_nm'] = esc($_REQUEST['sp_nm']);

        $_REQUEST['sp_add'] = esc($_REQUEST['sp_add']);
       // $_REQUEST['txtNote'] = esc($_REQUEST['txtNote']);
        $_REQUEST['txtSub_nm'] = esc($_REQUEST['txtSub_nm']);
        //$_REQUEST['ddl_not_office'] = $ddl_not_office = 'NULL';

        //$txtAmount = (!empty($_REQUEST['txtAmount'])) ? $_REQUEST['txtAmount'] : 0;
        //$hd_order_date = (!empty($_REQUEST['hd_order_date'])) ? $_REQUEST['hd_order_date'] : 'NULL';


        $txtFFX = date('Y-m-d',  strtotime($_REQUEST['txtFFX']));
        // if ($txtFFX == '1970-01-01')
        // {
        //     $txtFFX = NULL;
        // }
        // else{
        //     $txtFFX = "$txtFFX";
        // }

        if ($_REQUEST['hd_new_upd'] == '0') {


           // $sql =  mysql_query("Select processid from  tw_max_process where  year='$year'") or die("Error: " . __LINE__ . mysql_error());
            //$res =  mysql_result($sql, 0);
            $res = is_data_from_table('master.tw_max_process',"year='$year'","processid",'');
            $res = ($res['processid'] ?? 0 ) + 1;

          /*   $sql_hj = "Insert Into tw_tal_del (name,address,nt_type,process_id,diary_no,rec_dt,sr_no,pet_res,amount,user_id,display,amt_wor,fixed_for,sub_tal,tal_state,tal_district,enrol_no,enrol_yr,order_dt,office_notice_rpt,individual_multiple) values 
                ('$_REQUEST[sp_nm]','$_REQUEST[sp_add]','$_REQUEST[ddl_val]'
                ,'$res','$dairy_no','$date',
                '$_REQUEST[hd_sr_no]','$_REQUEST[hd_pet_res]','$txtAmount','$ucode','Y','$_REQUEST[nm_wd]',$txtFFX,'$_REQUEST[txtSub_nm]',
                    '$_REQUEST[ddlState]','$_REQUEST[ddlCity]','$_REQUEST[hdinenroll]','$_REQUEST[hdinenrollyr]','$hd_order_date',$ddl_not_office,'$_REQUEST[individual_multiple]')";
                   */
                  $builder = $this->db->table('tw_tal_del');

                    $data = [
                        'name' => $_REQUEST['sp_nm'],
                        'address' => $_REQUEST['sp_add'],
                        'nt_type' => $_REQUEST['ddl_val'],
                        'process_id' => $res,
                        'diary_no' => $dairy_no,
                        'rec_dt' => $date, // Ensure this is in 'Y-m-d' format
                        'sr_no' => $_REQUEST['hd_sr_no'],
                        'pet_res' => $_REQUEST['hd_pet_res'],
                        'amount' => (!empty($_REQUEST['txtAmount'])) ? $_REQUEST['txtAmount'] : 0,
                        'user_id' => $ucode,
                        'display' => 'Y',
                        'amt_wor' => $_REQUEST['nm_wd'],
                        'fixed_for' => ($txtFFX == '1970-01-01') ? null : date('Y-m-d', strtotime($txtFFX)),
                        'sub_tal' => $_REQUEST['txtSub_nm'],
                        'tal_state' => $_REQUEST['ddlState'],
                        'tal_district' => $_REQUEST['ddlCity'],
                        'enrol_no' => $_REQUEST['hdinenroll'],
                        'enrol_yr' => $_REQUEST['hdinenrollyr'],
                        'order_dt' => (!empty($_REQUEST['hd_order_date'])) ? date('Y-m-d', strtotime($_REQUEST['hd_order_date'])) : null,
                        'office_notice_rpt' => (!empty($_REQUEST['ddl_not_office'])) ? $_REQUEST['ddl_not_office'] : null,
                        'individual_multiple' => $_REQUEST['individual_multiple'],
                        'print' => 0,
                    ];
                    
                   $insertResult =  $builder->insert($data);
                     
                    if (!$insertResult) {
                         $sq_ck = 0;
                } else {
                    die;
                $sql_up_max = "Update tw_max_process set processid='$res' where year='$year'";
                if (!$this->db->query($sql_up_max)) {
                    $sq_ck = 0;
                } else {
                   /* $s_ins_tw = mysql_query("Select id from tw_tal_del where diary_no ='$dairy_no' and rec_dt='$date' and display='Y' and process_id='$res' and print=0")
                        or die("Error: " . __LINE__ .  mysql_error());
                    $r_s_ins_tw = mysql_result($s_ins_tw, 0);  */

                    $s_ins_tw = is_data_from_table('tw_tal_del'," diary_no ='$dairy_no' and rec_dt='$date' and display='Y' and process_id='$res' and print=0 ","id",'');
                    $r_s_ins_tw = $s_ins_tw['id'];
                    $ex_explode = explode(',', $_REQUEST['del_ty']);
                    for ($k = 0; $k < count($ex_explode); $k++) {
                        $ex_in_exp = explode('!', $ex_explode[$k]);

                       /* $sel_dt = mysql_query("Select count(id) from tw_o_r where tw_org_id='$r_s_ins_tw'   and del_type='$ex_in_exp[0]' and display='Y'")
                            or die("Error: " . __LINE__ .  mysql_error());
                        $res_sel_dt = mysql_result($sel_dt, 0); */

                        $sel_dt = is_data_from_table('tw_o_r'," tw_org_id='$r_s_ins_tw'   and del_type='$ex_in_exp[0]' and display='Y' ","count(id) as total",'');
                        $res_sel_dt = $sel_dt['total'];

                        if ($res_sel_dt <= 0) {
                            $ins_tw = $this->db->query("Insert Into  tw_o_r (tw_org_id,del_type) values  ('$r_s_ins_tw','$ex_in_exp[0]')");
                                
                        }

                       /* $sel_tw_o_r = "Select id from tw_o_r where tw_org_id='$r_s_ins_tw' and del_type='$ex_in_exp[0]' and display='Y'";
                        $sel_tw_o_r = mysql_query($sel_tw_o_r) or die("Error: " . __LINE__ . mysql_error());
                        $res_tw_o_r = mysql_result($sel_tw_o_r, 0); */


                        $sel_tw_o_r = is_data_from_table('tw_o_r'," tw_org_id='$r_s_ins_tw'   and del_type='$ex_in_exp[0]' and display='Y' ","id",'');
                        $res_tw_o_r = $sel_tw_o_r->id;

                        $ex_send_to = explode('~', $ex_in_exp[1]);



                       /* $tw_comp_not = "Select count(id) from tw_comp_not where tw_o_r_id='$res_tw_o_r'  and copy_type='0' and display='Y'";
                        $tw_comp_not = mysql_query($tw_comp_not) or die("Error: " . __LINE__ . mysql_error());
                        $res_comp_not = mysql_result($tw_comp_not, 0); */


                        $tw_comp_not = is_data_from_table('tw_comp_not'," tw_o_r_id='$res_tw_o_r'  and copy_type='0' and display='Y' ","count(id) as total",'');
                        $res_comp_not = $tw_comp_not['total'];

                        if ($res_comp_not <= 0) {
                            $ins_comp_not = "Insert Into tw_comp_not (tw_o_r_id,tw_sn_to,copy_type,sendto_state,sendto_district,send_to_type) values 
                ('$res_tw_o_r','$ex_send_to[0]','0','$ex_send_to[1]','$ex_send_to[2]','$ex_send_to[3]')";
                            $ins_comp_not = $this->db->query($ins_comp_not);
                        } else {
                            $ins_comp_not = "Update tw_comp_not set tw_sn_to='$ex_send_to[0]',copy_type='0',
            sendto_state='$ex_send_to[1]',sendto_district='$ex_send_to[2]',send_to_type='$ex_send_to[3]' 
            where tw_o_r_id='$res_tw_o_r'  and copy_type='0' and display='Y'";
                            $ins_comp_not = $this->db->query($ins_comp_not);
                        }

                        if ($ex_in_exp[2] != '') {
                            $ex_send_to = explode('$', $ex_in_exp[2]);
                            for ($index = 0; $index < count($ex_send_to); $index++) {
                                $in_exp = explode('~', $ex_send_to[$index]);

                              /*  $tw_comp_not = "Select count(id) from tw_comp_not where tw_o_r_id='$res_tw_o_r' and  	tw_sn_to='$in_exp[0]' and copy_type='1' and display='Y'";
                                $tw_comp_not = mysql_query($tw_comp_not) or die("Error: " . __LINE__ . mysql_error());
                                $res_comp_not = mysql_result($tw_comp_not, 0); */

                                $tw_comp_not = is_data_from_table('tw_comp_not'," tw_o_r_id='$res_tw_o_r' and  	tw_sn_to='$in_exp[0]' and copy_type='1' and display='Y' ","count(id) as total",'');
                                $res_comp_not = $tw_comp_not['total'];
                                if ($res_comp_not <= 0) {
                                    $ins_comp_not = "Insert Into tw_comp_not (tw_o_r_id,tw_sn_to,copy_type,sendto_state,sendto_district,send_to_type) values 
                ('$res_tw_o_r','$in_exp[0]','1','$in_exp[1]','$in_exp[2]','$in_exp[3]')";
                                    $ins_comp_not = $this->db->query($ins_comp_not);
                                }
                            }
                        }
                    }




                    $sq_ck = 1;
                    $sq_upd_mn =  $this->db->query("Update party set state='$_REQUEST[ddlState]',city='$_REQUEST[ddlCity]' 
                    where diary_no='$dairy_no' and pet_res='$_REQUEST[hd_pet_res]' and 
                    sr_no='$_REQUEST[hd_sr_no]'");
                }
            }
        } else if ($_REQUEST['hd_new_upd'] == '1') {


            $sql_hj = "Update tw_tal_del set name='$_REQUEST[sp_nm]',address='$_REQUEST[sp_add]',nt_type='$_REQUEST[ddl_val]',
        amount='$_REQUEST[txtAmount]',user_id='$ucode',display='Y',amt_wor='$_REQUEST[nm_wd]',fixed_for='$txtFFX',
            sub_tal='$_REQUEST[txtSub_nm]',tal_state='$_REQUEST[ddlState]',tal_district='$_REQUEST[ddlCity]',
                enrol_no='$_REQUEST[hdinenroll]',enrol_yr='$_REQUEST[hdinenrollyr]',order_dt='$_REQUEST[hd_order_date]',individual_multiple='$_REQUEST[individual_multiple]' where id='$_REQUEST[hd_mn_id]'";


            if (!$this->db->query($sql_hj)) {
                $sq_ck = 0;
            } else {


                $r_s_ins_tw = $_REQUEST['hd_mn_id'];


                $ex_explode = explode(',', $_REQUEST['del_ty']);
                $del_type_del = '';
                for ($k = 0; $k < count($ex_explode); $k++) {
                    $ex_in_exp = explode('!', $ex_explode[$k]);
                    if ($del_type_del == '')
                        $del_type_del = "'" . $ex_in_exp[0] . "'";
                    else
                        $del_type_del = $del_type_del . ',' . "'" . $ex_in_exp[0] . "'";

                   /* $sel_dt = mysql_query("Select count(id) from tw_o_r where tw_org_id='$r_s_ins_tw' and del_type='$ex_in_exp[0]' and display='Y'")
                        or die("Error: " . __LINE__ .  mysql_error());
                    $res_sel_dt = mysql_result($sel_dt, 0); */

                    $sel_dt = is_data_from_table('tw_o_r'," tw_org_id='$r_s_ins_tw' and del_type='$ex_in_exp[0]' and display='Y' ","count(id) as total",'');
                    $res_sel_dt = $sel_dt['total'];
                    if ($res_sel_dt <= 0) {
                        $ins_tw = $this->db->query("Insert Into  tw_o_r (tw_org_id,del_type) values  ('$r_s_ins_tw','$ex_in_exp[0]')");
                    }

                  /*  $sel_tw_o_r = "Select id from tw_o_r where tw_org_id='$r_s_ins_tw' and del_type='$ex_in_exp[0]' and display='Y'";
                    $sel_tw_o_r = mysql_query($sel_tw_o_r) or die("Error: " . __LINE__ . mysql_error());
                    $res_tw_o_r = mysql_result($sel_tw_o_r, 0); */

                    $sel_tw_o_r = is_data_from_table('tw_o_r'," tw_org_id='$r_s_ins_tw' and del_type='$ex_in_exp[0]' and display='Y' ","id",'');
                    $res_tw_o_r = $sel_tw_o_r['id'];

                    $ex_send_to = explode('~', $ex_in_exp[1]);


                   /* $tw_comp_not = "Select count(id) from tw_comp_not where tw_o_r_id='$res_tw_o_r' and copy_type='0' and display='Y'";
                    $tw_comp_not = mysql_query($tw_comp_not) or die("Error: " . __LINE__ . mysql_error());
                    $res_comp_not = mysql_result($tw_comp_not, 0); */

                    $tw_comp_not = is_data_from_table('tw_comp_not'," tw_o_r_id='$res_tw_o_r' and copy_type='0' and display='Y' ","count(id) as total",'');
                    $res_comp_not = $tw_comp_not['total'];
                    if ($res_comp_not <= 0) {
                        $ins_comp_not = "Insert Into tw_comp_not (tw_o_r_id,tw_sn_to,copy_type,sendto_state,sendto_district,send_to_type) values 
                ('$res_tw_o_r','$ex_send_to[0]','0','$ex_send_to[1]','$ex_send_to[2]','$ex_send_to[3]')";
                        $ins_comp_not = $this->db->query($ins_comp_not);
                    } else {
                        $ins_comp_not = "Update tw_comp_not set tw_sn_to='$ex_send_to[0]',copy_type='0',
            sendto_state='$ex_send_to[1]',sendto_district='$ex_send_to[2]',send_to_type='$ex_send_to[3]' 
            where tw_o_r_id='$res_tw_o_r'  and copy_type='0' and display='Y'";
                        $ins_comp_not = $this->db->query($ins_comp_not);
                    }
                    $tot_copt_send_to = '';
                    if ($ex_in_exp[2] != '') {
                        $ex_send_to = explode('$', $ex_in_exp[2]);

                        for ($index = 0; $index < count($ex_send_to); $index++) {

                            echo $ex_send_to[$index] . '<br/>';
                            $in_exp = explode('~', $ex_send_to[$index]);

                            if ($tot_copt_send_to == '')
                                $tot_copt_send_to = $in_exp[0];
                            else
                                $tot_copt_send_to = $tot_copt_send_to . ',' . $in_exp[0];

                           /* $tw_comp_not = "Select count(id) from tw_comp_not where tw_o_r_id='$res_tw_o_r' and tw_sn_to='$in_exp[0]' and copy_type='1' and display='Y'";
                            $tw_comp_not = mysql_query($tw_comp_not) or die("Error: " . __LINE__ . mysql_error());
                            $res_comp_not = mysql_result($tw_comp_not, 0); */

                            $tw_comp_not = is_data_from_table('tw_comp_not'," tw_o_r_id='$res_tw_o_r' and tw_sn_to='$in_exp[0]' and copy_type='1' and display='Y' ","count(id) as total",'');
                            $res_comp_not = $tw_comp_not['total'];
                            if ($res_comp_not <= 0) {
                                $ins_comp_not = "Insert Into tw_comp_not (tw_o_r_id,tw_sn_to,copy_type,sendto_state,sendto_district,send_to_type) values 
                ('$res_tw_o_r','$in_exp[0]','1','$in_exp[1]','$in_exp[2]','$in_exp[3]')";
                                $ins_comp_not = $this->db->query($ins_comp_not);
                            }
                        }
                        $not_send_to_in = '';

                        if ($tot_copt_send_to != '')
                            $not_send_to_in = ' and tw_sn_to not in (' . $tot_copt_send_to . ')';
                    }
                    $upd_not_copy = "Update tw_comp_not set display='N' where tw_o_r_id='$res_tw_o_r' $not_send_to_in and copy_type='1' and display='Y'";
                    $upd_not_copy =  $this->db->query($upd_not_copy);
                }

                $dele_s = $this->db->query("Update tw_o_r set display='N' where tw_org_id='$r_s_ins_tw' and display='Y' and del_type not in($del_type_del)");



                $sq_upd_mn = "Update party set state='$_REQUEST[ddlState]',city='$_REQUEST[ddlCity]' 
                    where diary_no='$dairy_no' and pet_res='$_REQUEST[hd_pet_res]' and 
                    sr_no='$_REQUEST[hd_sr_no]'";

                if (!$this->db->query($sq_upd_mn)) {
                    $sq_ck = 0;
                } else {
                    $sq_ck = 1;
                }
            }
        }
    ?>
        <input type="hidden" name="hd_ent_suc_f" id="hd_ent_suc_f" value="<?php echo $sq_ck; ?>" />
        <input type="hidden" name="hd_new_upd" id="hd_new_upd" value="<?php echo $_REQUEST['hd_new_upd']; ?>" />
<?php
    }



    public function getCityName()
    {
        $str = $this->request->getVar('str');
        if (empty($str)) {
            echo '<option value="0">None</option>';
        } else {
            $result = $this->notices_model->getCitiesName($str);
            foreach ($result as $row) {
                echo '<option value="' . htmlspecialchars($row['id_no'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</option>';
            }
        }
        die;
    }

    public function get_dynamic_cst()
    {
        return view('Extension/Notices/generate');
    }

    public function reprint()
    {
        return view('Extension/Notices/rep_rec_tal');
    }

    public function get_rep_rec_tal()
    {
        $ucode = $_SESSION['login']['usercode'];
        $user_code = "";
        if ($ucode != 1) {
            $user_code = " and a.user_id='$ucode'";
        }
        $txtFromDate = date('Y-m-d',  strtotime($_REQUEST['txtFromDate']));
        $txtToDate = date('Y-m-d',  strtotime($_REQUEST['txtToDate']));
        $data['serve_status'] = $this->notices_model->getNoticeDetails($txtFromDate, $txtToDate, $user_code);

        return view('Extension/Notices/get_rep_rec_tal', $data);
    }


    public function pocriminal1()
    {
        $data['_REQUEST'] = $_REQUEST;
        $data['noticesModel'] = $this->notices_model;
        return view('Extension/Notices/pocriminal1', $data);
    }

    public function notice_back()
    {
        $dis_co_nm = '';
        $diary_no = $_REQUEST['fil_no'];
        $year_s = substr($diary_no, -4);
        $no_s = substr($diary_no, 0, strlen($diary_no) - 4);
        $rec_dt = $_REQUEST['dt'];
        $fil_nm = trim($_REQUEST['fil_nm']);

        $x = explode('../pdf_notices/', $fil_nm);
        $notice_path = trim($x[1], ' ');

        $result2 = "update tw_tal_del set display='N' where diary_no=$diary_no and rec_dt='$rec_dt' and notice_path='$notice_path'";
        echo $query = $this->db->query($result2);
        die;
    }


    public function save_content()
    {
        $year = substr($_REQUEST['fil_no'], -4);
        $diary_no = substr($_REQUEST['fil_no'], 0, strlen($_REQUEST['fil_no']) - 4);
        $ucode = $_SESSION['login']['usercode'];

        $user_ip = getClientIP();


        $master_path = '../';

        $path = 'pdf_notices';
        chdir($master_path);
        if (!file_exists($path))
            mkdir($path, 0755, true);
        chdir($path);
        if (!file_exists($year))
            mkdir($year);
        chdir($year);

        if (!file_exists($diary_no))
            mkdir($diary_no);
        chdir($diary_no);

        if ($_REQUEST['z_chk_status'] == 1) {
            $not_path = str_replace('../pdf_notices/', '', $_REQUEST['hd_active_filez']);
            $file_name = explode('/', $_REQUEST['hd_active_filez']);
            $file_name1 = $file_name[4];
            $sql = $this->db->query("Update  tw_tal_del set print='1',published_by=$ucode, userip='$user_ip',published_on=now() where diary_no='$_REQUEST[fil_no]' and rec_dt='$_REQUEST[dt]' and display='Y' and notice_path='$not_path'");
            $gh = fopen($file_name1, 'w');
            fwrite($gh, $_REQUEST['str']);
            fclose($gh);
        } else {
            $fil_nm = $_REQUEST['fil_no'] . '_' . $_REQUEST['dt'] . time() . ".html";

            $fil_nm1 = $year . "/" . $diary_no . "/" . $fil_nm;
            $sql = $this->db->query("Update  tw_tal_del set print='1',notice_path='$fil_nm1',published_by=$ucode, userip='$user_ip',published_on=now() where diary_no='$_REQUEST[fil_no]' and rec_dt='$_REQUEST[dt]' and display='Y' and print=0");
            $gh = fopen($fil_nm, 'w');
            fwrite($gh, $_REQUEST['str']);
            fclose($gh);
        }
    }


    public function save_pdf_html()
    {
        $cks_ids = urldecode($_REQUEST['cks_ids']);
        $id_d = $_REQUEST['id_d'];

        $year = substr($_REQUEST['fil_no'], -4);
        $diary_no = substr($_REQUEST['fil_no'], 0, strlen($_REQUEST['fil_no']) - 4);

        $master_path = '../';

        $path = 'pdf_notices';
        chdir($master_path);
        if (!file_exists($path))
            mkdir($path, 0755, true);
        chdir($path);
        if (!file_exists($year))
            mkdir($year);
        chdir($year);

        if (!file_exists($diary_no))
            mkdir($diary_no);
        chdir($diary_no);


        $ex_id_d = explode('~!@#$', $cks_ids);

        $ex_cks_ids = explode(',', $id_d);

        for ($index = 0; $index < count($ex_cks_ids); $index++) {

            $hd_full_data = $ex_id_d[$index];
            $hd_full_data =  str_replace('face="Times New Roman"', "style=\"font-family: 'Times New Roman'\"", $hd_full_data);
            $hd_full_data =  str_replace("face=\"'Kruti Dev 010'\"", "style=\"font-family: 'Kruti Dev 010'\"", $hd_full_data);
            $hd_full_data = "<!DOCTYPE html><html> <head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'></head> <body> " . $hd_full_data . " </body> </html>";


            $er = $ex_cks_ids[$index] . '.html';
            $gh = fopen($er, 'w');
            fwrite($gh, $hd_full_data);
            fclose($gh);

            $new_name = $ex_cks_ids[$index] . '.pdf';
            $new_name1 = $ex_cks_ids[$index] . '_cy.pdf';

            if (file_exists($er)) {

                exec('html2pdf -s Legal  /home/notices/' . $year . '/' . $diary_no . '/' . $er . ' ' . $new_name, $output, $return);
                if ($return) {
                    $rt = 0;
                } else {
                    $rt = 1;
                }

                if ($rt == 1) {
                    $c_date = date('Y-m-d');

                    $res_chk_notice_pnt =  $this->notices_model->getIndividualMultiple($_REQUEST['fil_no'], $c_date);

                    $path = $year . '/' . $diary_no . '/' . $_REQUEST['fil_no'] . '_' . $c_date . '.html';
                    $upd_main_no = "Update tw_tal_del set notice_path='$path' where diary_no='$_REQUEST[fil_no]'  and rec_dt='$c_date' and display='Y' and print=0";
                    $upd_main_no =  $this->db->query($upd_main_no);

                    $ex_explode =  explode('_', $er);
                    $ex_mode = explode('.html', $ex_explode[2]);



                    if ($res_chk_notice_pnt == '1') {
                        $ex_explode =  $ex_explode[0];
                        $ex_mode =  $ex_mode[0];
                        $sel_mod = is_data_from_table('tw_o_r',  " tw_org_id='$ex_explode' and del_type='$ex_mode'  and display='Y' ", " id ", '');
                        $sel_mod = !empty($sel_mod) ?  $sel_mod['id'] : '';
                    } else  if ($res_chk_notice_pnt == '2') {
                        $ext_ids = '';
                        $sel_letter =  $this->notices_model->getLetterIds($_REQUEST['fil_no']);
                        if (!empty($sel_letter)) {
                            $ids = '';
                            foreach ($sel_letter as $row) {
                                if ($ids == '')
                                    $ids = $row['id'];
                                else
                                    $ids = $ids . ',' . $row['id'];
                                $row_id = $row['id'];
                                $sel_mod_ff = is_data_from_table('tw_o_r',  " tw_org_id='$row_id'  and display='Y' ", " id ", 'A');

                                $er_ss_s = $year . '/' . $diary_no . '/' . $er;
                                if (!empty($sel_mod_ff)) {
                                    foreach ($sel_mod_ff as $row1) {
                                        $upd_mod_ff = $this->db->query("Update tw_o_r set mode_path='$er_ss_s' where id='$row1[id]'");
                                    }
                                }
                            }
                        }
                        if ($ids != '') {
                            $ext_ids = " and b.id not in($ids)";
                        }

                        $r_get_diary_modes =  $this->notices_model->getDiaryModes($ex_explode[0], $ex_mode[0], $ext_ids);

                        if (!empty($r_get_diary_modes)) {
                            $sel_mod_result =  $this->notices_model->getSelectedMode($r_get_diary_modes['diary_no'], $r_get_diary_modes['rec_dt'], $r_get_diary_modes['del_type']);
                            $sel_mod =  !(empty($sel_mod_result)) ?  $sel_mod_result['id'] : '';
                        } else {
                            $sel_mod = '';
                        }
                    }

                    $er_ss = $year . '/' . $diary_no . '/' . $er;
                    if (!empty($sel_mod)) {
                        $upd_mod = $this->db->query("Update tw_o_r set mode_path='$er_ss' where id='$sel_mod'");
                    }
                }
            }
        }
    }


    public function publish_record()
    {
        $fil_no = $_REQUEST['fil_no'];
        $dt = date('Y-m-d', strtotime($_REQUEST['dt']));
        $hd_off_notice = $_REQUEST['hd_off_notice'];
        $res_chk_data = $this->notices_model->checkDataCount($fil_no, $dt, $hd_off_notice);
        if ($res_chk_data > 0) {
            $sql = $this->db->query("Update  tw_tal_del set web_status ='1' where diary_no='$_REQUEST[fil_no]' and 
                rec_dt='$_REQUEST[dt]' and display='Y' and office_notice_rpt='$_REQUEST[hd_off_notice]'");
            if (!empty($sql)) {
                echo "Data Publish Successfully";
            } else {
                echo "Data is not Publish, Please contact to Computer cell!!";
            }
        } else {
            echo "Please save data before Publish";
        }
    }

    public function draft_record() {}
}
