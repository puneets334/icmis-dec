<style type="text/css">
    .cl_off_rop {
        color: blue;
    }

    .cl_off_rop:hover {
        cursor: pointer;
    }
</style>
<?php

if (!empty($results)) {
?>
    <table width="100%" class="table table_tr_th_w_clr custom-table">
        <thead>
            <tr>
                <th>
                    Causelist No.
                </th>
                <th>
                    Case No.
                </th>
                <th>
                    Petitioner / Respondent
                </th>
                <th>
                    Office Report
                </th>
                <th>
                    ROP
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sno = 1;
            $chk_roster = '';

            foreach ($results as $row) {

                $diary_no = $row['diary_no'];

                if ($chk_roster != $row['roster_id']) {
            ?>
                    <tr>
                        <td colspan="5">
                            <?php

                            $j_name = '';
                            /* $judges="Select jname from roster_judge a join judge b on a.judge_id=b.jcode
	 where roster_id='$row[roster_id]' and  a.display='Y' and b.display='Y'";
            $judges=  mysql_query($judges) or die("Error: ".__LINE__.mysql_error()); */
                            $judges = $ReportModel->getRosterJudge($row['roster_id']);
                            foreach ($judges as $row1) {
                                if ($j_name == '')
                                    $j_name = $row1['jname'];
                                else
                                    $j_name = $j_name . ', ' . $row1['jname'];
                            }
                            ?>
                            <div style="width: 100%;color: red;text-align: center;display: inline"><?php echo $j_name;  ?></div> <span>Dated: <?php echo date('d-m-Y',  strtotime($row['next_dt']))  ?></span>
                            <?php

                            $chk_roster = $row['roster_id'];
                            ?>
                        </td>
                    </tr>
                <?php } ?>

                <tr>

                    <td>
                        <?php echo $row['brd_slno'];  ?>
                        <?php
                        /*  $section="Select section_name from users a join usersection b on a.section=b.id where 
                     usercode='$row[dacode]' and  a.display='Y' and  b.display='Y'";
            $section=  mysql_query($section) or die("Error: ".__LINE__.mysql_error()); */

                        $section = $ReportModel->getSectionName($row['dacode']);
                        $res_section =  $section['section_name'] ?? '';
                        ?>

                    </td>
                    <td>
                        <?php if ($row['reg_no_display'] == '')  echo substr($row['diary_no'], 0, -4) . '-' .  substr($row['diary_no'], -4);
                        else echo $row['reg_no_display'];  ?>
                        <div style="color: blue">
                            <?php echo $res_section; ?>
                        </div>
                    </td>
                    <td>
                        <div><?php echo $row['pet_name'];  ?></div>
                        <div><i>Versus</i></div>
                        <div><?php echo $row['res_name'];  ?></div>
                    </td>
                    <td>
                        <?php
                        /*  $office_report="Select office_repot_name, order_dt from office_report_details where display='Y' and 
                   web_status=1 and diary_no='$row[diary_no]' order by order_dt desc,rec_dt desc limit 0,1" ;
          $office_report=  mysql_query($office_report) or die("Error: ".__LINE__.mysql_error()); */

                        $row3 = is_data_from_table(
                            'office_report_details',
                            "display='Y' and web_status=1 and diary_no='$diary_no' order by order_dt desc,rec_dt desc",
                            'office_repot_name, order_dt ',
                            null
                        );
                        //pr($row3);
                        if (!empty($row3)) {
                                $order_dt = $row3['order_dt'] ?? null; 
                                $order_dt ? date('d-m-Y', strtotime($order_dt)) : '';
                            
                        ?>
                            <span class="cl_off_rop" id="<?php echo 'officereport/' . substr($row['diary_no'], -4) . '/' . substr($row['diary_no'], 0, -4) . '/' . $row3['office_repot_name'];  ?>"><?php echo $order_dt; ?></span>
                            <?php
                            // }
                        } else {

                            /*    $rop="Select  jm pdfname,dated orderdate from tempo where diary_no=$diary_no and  jt='or' order by  dated desc limit 0,1" ;
          $rop=  mysql_query($rop) or die("Error: ".__LINE__.mysql_error()); */

                            $row_rop = is_data_from_table('tempo',  " diary_no=$diary_no and jt='or' order by dated desc", ' jm AS pdfname,dated  AS orderdate ', null);

                            if (!empty($row_rop)) {
                                //foreach ($rop as $row_rop) {
                            ?>
                                <span class="cl_off_rop" id="<?php echo 'judgment/' . $row_rop['pdfname'];  ?>"><?php echo date('d-m-Y',  strtotime($row_rop['orderdate'])); ?></span>
                        <?php
                                //}
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        /*  $rop="Select pdfname,orderdate from ordernet where diary_no='$row[diary_no]' and display='Y' order by 
                orderdate desc,ent_dt desc limit 0,1" ;
          $rop=  mysql_query($rop) or die("Error: ".__LINE__.mysql_error()); */


                        $row_rop = is_data_from_table('ordernet',  " diary_no=$diary_no and display='Y' order by orderdate desc,ent_dt desc ", ' pdfname,orderdate ', null);

                        if (!empty($row_rop)) {
                            // while ($row_rop = mysql_fetch_array($rop)) {
                        ?>
                            <span class="cl_off_rop" id="<?php echo 'jud_ord_html_pdf/' . $row_rop['pdfname'];  ?>"><?php echo date('d-m-Y',  strtotime($row_rop['orderdate'])); ?></span>
                            <?php
                            //}
                        } else {
                            /* $rop="Select  jm pdfname,dated orderdate from tempo where diary_no='$row[diary_no]' and  	
                     jt='rop' order by 
                dated desc limit 0,1" ;
          $rop=  mysql_query($rop) or die("Error: ".__LINE__.mysql_error()); */
                            $row_rop = is_data_from_table('tempo',  " diary_no=$diary_no and jt='rop' order by dated desc ", ' jm pdfname,dated orderdate ', null);

                            if (!empty($row_rop)) {
                                //while ($row_rop = mysql_fetch_array($rop)) {
                            ?>
                                <span class="cl_off_rop" id="<?php echo 'judgment/' . $row_rop['pdfname'];  ?>"><?php echo date('d-m-Y',  strtotime($row_rop['orderdate'])); ?></span>
                        <?php
                                //}
                            } else {
                            }
                        }

                        //          else 
                        //          {
                        //              
                        //          }
                        ?>
                    </td>

                </tr>
            <?php

                $sno++;
            }
            ?>
        </tbody>
    </table>
    <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
        &nbsp;
    </div>
    <div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105">
        <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()"><b><img src="../../images/close_btn.png" style="width:30px;height:30px" /></b></div>

        <div style="width: auto;background-color: white;overflow: hidden;height: 550px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;width: 100%" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">
            <object id="ob_shw" style="width: 100%;height: 550px" type="application/pdf"></object>

        </div>



    </div>

    <div id="dv_sh_hd1" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
        &nbsp;
    </div>
    <div id="dv_fixedFor_P1" style="position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105">
        <div id="sp_close1" style="text-align: right;cursor: pointer;width: 40px;float: right;margin-right: 30px;" onclick="closeData1()"><b><img src="../../images/close_btn.png" style="width:30px;height:30px" /></b></div>

        <div style="width: auto;background-color: white;overflow: hidden;height: 550px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;width: 100%" id="ggg1" onkeypress="return  nb(event)" onmouseup="checkStat()">


        </div>



    </div>
<?php
    //include ('../extra/popup.php');
    //include ('../extra/popup_pdf_html.php');
} else {
?>
    <div style="text-align: center"><b>No Record Found</b></div>
<?php
}

?>