<?php

$ucode = $_SESSION['login']['usercode'];
/*var_dump($_POST);
exit(0);*/
extract($_POST);
$defect_cured_condition = $save_date_condition = "";
$defect_status = "All cases ";
$dates = "";
if ($status == 1) {
    $defect_status = "Still defective cases ";
    $defect_cured_condition = " and date(os.rm_dt) IS NULL";
} elseif ($status == 2) {
    $defect_status = "Defect cured cases ";
    $defect_cured_condition = " and date(os.rm_dt) IS NOT NULL";
}
if (!empty($from_date) && !empty($to_date)) {
    $dates = " notified between $from_date and $to_date";
    $save_date_condition = " and date(os.save_dt) between '" . date('Y-m-d', strtotime($from_date)) . "' and '" . date('Y-m-d', strtotime($to_date)) . "'";
}

/* 
    $sql="select org_id,concat( CAST(LEFT(m.diary_no,LENGTH(m.diary_no)-4) AS UNSIGNED),'/',CAST(RIGHT(m.diary_no, 4) AS UNSIGNED)) as diary_number,
concat(m.pet_name,' Vs. ',m.res_name) causetitle,
os.save_dt,os.rm_dt,os.remark  from obj_save os inner join main m on os.diary_no=m.diary_no where os.display='Y' 
$save_date_condition
$defect_cured_condition and org_id=$obj_type order by os.save_dt desc";
 */

$res = $FilingReportModel->getDefectTypeReport($save_date_condition, $defect_cured_condition, $obj_type);

if (!empty($res)) {
?>
    <div id="prnnt" style="text-align: center; font-size:13px;">
        <h3><?php echo $defect_status . " under " . $obj_text . " " . $dates; ?></h3>
        <div class="table-responsive">
            <table class="table table-striped custom-table" align="left" width="100%" border="0px;" style="padding: 10px; font-size:13px; table-layout: fixed;">

                <tr>
                    <td width="10%" style="font-weight: bold; color: #000;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #000;">Diary No.</td>
                    <td width="40%" style="font-weight: bold; color: #000;">Cause title</td>
                    <td width="20%" style="font-weight: bold; color: #000;">Defect Remarks</td>
                    <td width="10%" style="font-weight: bold; color: #000;">Notified date</td>
                    <td width="10%" style="font-weight: bold; color: #000;">Removed date</td>
                </tr>

                <?php
                $sno = 1;

                foreach ($res as $ro) {
                    $sno1 = $sno % 2;
                    if ($sno1 == '1') { ?>
                        <tr style="padding: 10px; background: #ececec;">
                        <?php } else { ?>
                        <tr style="padding: 10px; background: #f6e0f3;">
                        <?php
                    }
                        ?>
                        <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['diary_number'];  ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['causetitle'];  ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['remark'];  ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo date('d-m-Y h:i A', strtotime($ro['save_dt']));  ?></td>
                        <td align="left" style='vertical-align: top;'><?php echo $ro['rm_dt'] != '' ? date('d-m-Y h:i A', strtotime($ro['rm_dt'])) : '';  ?></td>
                        </tr>
                    <?php
                    $sno++;
                }
                    ?>
            </table>
        </div>
    </div>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">
<?php
} else {
    echo "No Recrods Found";
}

?>