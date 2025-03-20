<?php

namespace CodeIgniter\Validation;

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use App\Models\Filing\ScrutinyReportModel;

class ScrutinyReport extends BaseController
{
  protected $session;
  protected $form_validation;
  protected $ScrutinyReportModel;

  public function __construct()
  {
    $this->ScrutinyReportModel = new ScrutinyReportModel();
    $this->form_validation = \Config\Services::validation();
    $this->session = \Config\Services::session();
  }

  public function index()
  {

    $data['diary_year'] = $_SESSION['filing_details']['diary_year'];
    $data['diary_no'] = $_SESSION['filing_details']['diary_number'];
    return view('Filing/ScrutinyReportView', $data);
  }

  public function get_lower_report()
  {

    $dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
    $order_date = '';
    $transfer_petition = '';
    $transfer_state = '';
    $this->navigate_diary($dairy_no);

    $res_p_r = $this->ScrutinyReportModel->diary_data($dairy_no);

    if ($res_p_r['c_status'] == 'D') { ?>
      <div style="text-align: center;color: red;font-weight: bold"><b>Cannot Register matter as Matter is Disposed</b></div>
      <?php exit();
    }

    $order_date = $this->ScrutinyReportModel->order_dt_data($dairy_no);

    if($res_p_r['fil_no']!=null || $res_p_r['fil_no']!="")
    {
        if((intval(substr($res_p_r['fil_no'],0,2))!=13)&&(intval(substr($res_p_r['fil_no'],0,2))!=14)&&(intval(substr($res_p_r['fil_no'],0,2))!=15)&&(intval(substr($res_p_r['fil_no'],0,2))!=16)&&(intval(substr($res_p_r['fil_no'],0,2))!=31)) {
            ?>
            <div style="text-align: center"><b>Registration already done</b></div>
            <?php exit();
        }
    }
    $category = $this->ScrutinyReportModel->diary_category($dairy_no);
    if(empty($category)){
        ?>
        <div style="text-align: center"><b>Category not updated!!!</b></div>
        <?php exit();
    }

    $res_casetype_added = $this->ScrutinyReportModel->casetype_added($res_p_r['casetype_id']);
    ?>
    <div class="cl_center" style="color: red;font-weight: bold"><?php echo "Is ".$res_casetype_added." ?";  ?>
        <input type='checkbox' name='casetype' id='casetype' value='$res_p_r[casetype_id]' required><br>
    </div>
    <?php

    echo "<input type=hidden value='$res_p_r[casetype_id]' id='hd_casetype_id'>";
    pr(72);
    if($res_p_r['casetype_id']=='5' || $res_p_r['casetype_id']=='6' || $res_p_r['casetype_id']=='17'
        || $res_p_r['casetype_id']=='24' || $res_p_r['casetype_id']=='32' || $res_p_r['casetype_id']=='33' || $res_p_r['casetype_id']=='34'
        || $res_p_r['casetype_id']=='35' || $res_p_r['casetype_id']=='27' || $res_p_r['casetype_id']=='40' || $res_p_r['casetype_id']=='41')
    {
        $ck_def="Select count(id) from obj_save where diary_no = '$dairy_no'  and display='Y' and
              rm_dt='0000-00-00 00:00:00'";
        $ck_def=mysql_query($ck_def) or die("Error: ".__LINE__.mysql_error());
        $res_ck_def=mysql_result($ck_def,0);
        if($res_ck_def>0)
        {
            ?>
            <div style="text-align: center"><b>Please remove defects before generating Registration No.</b></div>
            <?php
        }


  }
}

  public function navigate_diary($dno)
  {

    $res = $this->ScrutinyReportModel->navigate_diary_data($dno);

    foreach ($res as $ro) {
      $filno_array = explode("-", $ro['active_fil_no']);

      if (empty($filno_array[0])) {
        $fil_no_print = "Unreg.";
      } else {
        $fil_no_print = $ro['short_description'] . "/" . ltrim($filno_array[1], '0');
        if (!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
          $fil_no_print .= "-" . ltrim($filno_array[2], '0');
        $fil_no_print .= "/" . $ro['active_reg_year'];
      }
      if ($ro['c_status'] == "P") {
        $cstatus = "Pending";
      } else {
        $cstatus = "Disposed";
      }

      $_SESSION['session_c_status'] = $cstatus;
      $_SESSION['session_pet_name'] = $ro['pet_name'];
      $_SESSION['session_res_name'] = $ro['res_name'];
      $_SESSION['session_lastorder'] = $ro['lastorder'];
      $_SESSION['session_diary_recv_dt'] = date('d-m-Y H:i:s', strtotime($ro['diary_no_rec_date'] ?? ''));
      $_SESSION['session_active_fil_dt '] = date('d-m-Y H:i:s', strtotime($ro['active_fil_dt'] ?? ''));
      $_SESSION['session_diary_no'] = substr($dno, 0, -4);
      $_SESSION['session_diary_yr'] = substr($dno, -4);
      $_SESSION['session_active_reg_no'] = $fil_no_print;
    }
  }
}
