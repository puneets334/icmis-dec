<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SendEmailController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $diaryno = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $sql = "select email,mobile,concat(title,' ',name) as name from master.bar b join main m on b.bar_id=m.pet_adv_id where m.diary_no='$diaryno'";
        $rs = $db->query($sql);
        $res = $rs->getRowArray();
        foreach ($res as $rw) {
            $email = $rw['email'];
            $mobile = $rw['mobile'];
            $aor_name = $rw['name'];
        }

        if ($email == '') {
            echo "<center>No email id found. Please first add e-mail id of the concerned advocate in ICMIS</center>";
            exit();
        }
        date_default_timezone_set('Asia/kolkata');
        $str = "1";
        $subject = "List of Defects in Diary no.  " . $_REQUEST['d_no'] . "/" . $_REQUEST['d_yr'] . " filed by you:-";
        $from_name = 'Supreme Court ';
        $dno = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $q_w = $db->query("SELECT a.org_id,objdesc obj_name, rm_dt,remark, mul_ent mul_ent,save_dt FROM obj_save a, objection b WHERE a.org_id = b.objcode and diary_no = '$dno' and a.display='Y' order by id");
        $output = '';
        if ($q_w->getNumRows() > 0) {
            $output = '<fieldset id="fiOD"><legend ><b>Default Details</b></legend>
                  <span id="spAddObj" style="font-size: small;text-transform: uppercase">
                  <table id="tb_nm" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5" width="100%">';
            $output .= '<tr><th>S.No.</th><th>Default</th><th>Remarks</th><th>Notification Date</th></tr>';
            $sno = 1;
            $cn_c = '';
            $rr = $q_w->getResult();
            foreach ($rr as $row1) {
                if ($cn_c == '')
                    $cn_c = $row1['org_id'];
                else
                    $cn_c = $cn_c . ',' . $row1['org_id'];
                $output .= '<tr><td class="c_vertical_align">';
                $output .= $sno;
                $output .= '</td>
                <td>
                <span id="spAddObj' . $sno . '">' . $row1['obj_name'] . '</span>';
                        $output .= '<span id="sp_hide' . $sno . '"><br/></span>
                </td>
                <td>';
                $ex_ui =  explode(',', $row1['mul_ent']);
                $r = '';
                for ($index = 0; $index < count($ex_ui); $index++) {
                    if (trim($ex_ui[$index] == '')) {
                        $r = $r . '-' . ',';
                    } else {
                        $r = $r . $ex_ui[$index] . ',';
                    }
                }
                $output .= '<span id="spRema' . $sno . '">' . $row1['remark'] . '</span>';

                $nd = $row1['save_dt'];
                if ($row1['rm_dt'] != '0000-00-00 00:00:00')
                    $rd = $row1['rm_dt'];

                else
                    $rd = "";
                $output .= "</td><td>" . $nd . "</td>";
                $output .= '</tr>';
                $sno++;
            }
            $output .= '</table></span></fieldset>';
        }
        //echo $output;
        $htmlContent = '<p>Sir/Madam,</p><p>Please Remove following defects notified in the petition filed by you. <br> Diary no -' . $_REQUEST['d_no'] . "/" . $_REQUEST['d_yr'] . ' </p>';
        $htmlContent2 = '<br><p>Regards,</p><p>Section I-B ,</p><p>Supreme Court of India.</p>';
        date_default_timezone_set('Asia/kolkata');
        $from = 'sci@nic.in';
        $cc = '';
        $to = $email;
        // $cc = 'itcell@sci.nic.in';
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";


        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // $headers .= "From: sci@nic.in" . "\r\n"."CC:".$cc ;
        $headers .= "From: sci@nic.in" . "\r\n";
        $message = $htmlContent . $output . $htmlContent2;

        // Send email
        $senders = $to . ',' . $cc;
        if (mail($to, $subject, $message, $headers)) {
            echo 'Email has sent successfully.';
            $sql = "insert into defects_notified_mails(to_sender,subject,display,usercode,created_on) values ('$senders','$subject','Y',$_SESSION[dcmis_user_idd],now())";
            $rs = $db->query($sql);
        } else {
            echo 'Email sending failed.';
        }
    }
}
