<?php
 
$main_details = is_data_from_table('main', "diary_no='$dairy_no'", "pet_name,res_name,c_status",'');
 
if (!empty($main_details)) {

    if (!empty($tw_chk)) {
?>
        <div align='center' style="padding-bottom: 15px;padding-top: 5px;"><span style='color: blue;font-size: 13px;font-weight: bold'><?php echo $main_details['pet_name']; ?></span> Vs <span style='color: blue;font-size: 13px;font-weight: bold'><?php echo $main_details['res_name']; ?></span></div>
        <table width="100%" class="table tbl_border c_vertical_align">
            <thead>
            <tr>
                <th colspan="12">Case(s) having Acknowledgement Pending</th>
            </tr>
            <tr>
                <th>Sr. No.</th>

                <th>Process Id /<br />Issue Date/<br />Notice Type </th>
                
                <th>To Party</th>
                <th>Address</th>
                
                <th>Delivery<br /> Type</th>
                <th>Status<br />Status Remark<br />Serve/Record Receiving Date</th>
                
                <th>Remark</th>
            </tr>
        </thead>
            <?php
            $srno = 1;
            $ck_nt_type_st = 0;
            $ck_lw_de = 0;
            $v_res = '';
            foreach ($tw_chk as $row) {


                if ($row['nt_type'] == '6' || $row['nt_type'] == '55')
                    $ck_nt_type_st = 1;



            ?>
                <tr>
                    <td><?php echo $srno; ?></td>
                    <td><span id="pid<?php echo $srno ?>"><?php echo $row['process_id']; ?></span> /<br /><span id="rdt<?php echo $srno ?>"><?php echo revertDate($row['rec_dt']); ?></span>/<br />
                        <input type="hidden" name="hd_talw_id<?php echo $srno; ?>" id="hd_talw_id<?php echo $srno; ?>" value="<?php echo $row['id']; ?>" />
                        <span id="spnottype_<?php echo $srno; ?>" onclick="show_nt(this.id)" class="sp_ytx"> <?php echo $row['nt_typ']; ?></span>
                        <?php
                        if (($row['nt_type'] == '97' || $row['nt_type'] == '98' || $row['nt_type'] == '100'
                            || $row['nt_type'] == '106' || $row['nt_type'] == '87' || $row['nt_type'] == '206' || $row['nt_type'] == '112') && $v_res != '') {
                        ?>
                            /<br />
                            <span style="color: red"><?php echo $v_res['type_sname']; ?>/<?php echo intval(substr($v_res['lct_case_no'], 3, 5)); ?>/<?php echo substr($v_res['lct_case_no'], 8, 4); ?></span>
                        <?php
                            $ck_lw_de++;
                        }
                        ?>
                        <input type="hidden" name="hd_lst_id<?php echo $srno; ?>" id="hd_lst_id<?php echo $srno; ?>" value="<?php echo $row['id'] ?>" />



                    </td>

                    <td>
                        <div style="word-wrap:break-word;width: 90px">
                            <?php
                            if ($row['name'] != '' && $row['copy_type'] == 0) {
                                echo $row['name'];
                            }
                            if ($row['name'] != '' && $row['tw_sn_to'] != 0 && $row['copy_type'] == 0) {
                                echo "<br/>Through ";
                            }
                            if ($row['tw_sn_to'] != 0) {
                                $send_to_name = send_to_name($row['send_to_type'], $row['tw_sn_to']);
                            }

                            echo $send_to_name ?? '';
                            ?>
                        </div>
                        <div style="color: red">
                            <?php
                            if ($row['copy_type'] == 1) {
                                echo "Copy";
                            }
                            ?>
                        </div>
                    </td>
                    <td>
                        <?php
                        if ($row['tw_sn_to'] == 0) {
                            $get_district = get_district($row['tal_state']);
                            $get_state = get_state($row['tal_district']);
                        } else {
                            $get_district = get_district($row['sendto_district']);

                            $get_state = get_state($row['sendto_state']);
                        }
                        echo $get_district; ?>/<br /><?php echo $get_state;
                                        ?>
                    </td>
                    <td>

                        &nbsp;&nbsp; <?php if ($row['del_type'] == 'A') {
                                            echo 'H';
                                        } else if ($row['del_type'] == 'Z') {
                                            echo 'A';
                                        } else {
                                            echo $row['del_type'];
                                        } ?>


                    </td>

                    <?php

                    if ($ck_nt_type_st == 0 ||   $row['copy_type'] == 1) {
                    ?>
                        <td>
                            <select id='status<?php echo $srno ?>' onchange="serveTypelao(this.value,<?php echo $srno ?>)">
                                <option value='0'>Select</option><?php
                                                                 
                                    $ser_q = is_data_from_table('master.tw_serve', "serve_type=0 and display='Y'", "id,name", 'A');
                                   
                                    foreach ($ser_q as $row_ser) {
                                    ?>
                                    <option value="<?php echo $row_ser['id'] ?>"><?php echo $row_ser['name'] ?></option>
                                <?php
                                                                    }
                                ?>
                            </select>
                            <br /><br />
                            <select id='sta_remark<?php echo $srno ?>'>
                                <option value=''>Select</option>
                            </select>
                            <br /><br />
                            <input type="text" id="date<?php echo $srno ?>" size="10" maxlength="10" class="dtp" readonly />
                        </td>
                    <?php } else {



                    ?>
                        <td>
                            <fieldset>
                                <legend>Lower Court</legend>
                                <?php
                                $inc_val = 0;
                                $lower_court =  $RIModel->lower_court_or($dairy_no);
                                for ($index1 = 0; $index1 < count($lower_court); $index1++) {

                                ?>
                                    <div>
                                        <input type="checkbox" name="chkhc_<?php echo $srno ?>_<?php echo $inc_val; ?>" id="chkhc_<?php echo $srno ?>_<?php echo $inc_val; ?>" class="cl_chkhc<?php echo $srno ?>" value="<?php echo $lower_court[$index1][8]; ?>" />&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $lower_court[$index1][3] . '-' . $lower_court[$index1][4] . '-' . $lower_court[$index1][5];  ?>
                                        <br /><br />
                                        <input type="text" name="txthcrmk_<?php echo $srno ?>_<?php echo $inc_val; ?>" id="txthcrmk_<?php echo $srno ?>_<?php echo $inc_val; ?>" placeholder="Remark" maxlengh='500' />
                                    </div>
                                <?php
                                    $inc_val++;
                                }
                                ?>


                            </fieldset>

                        </td>
                    <?php
                    }
                    ?>


                    <td>
                        <input type="text" name="txt_remark<?php echo $srno; ?>" id="txt_remark<?php echo $srno; ?>" />
                    </td>


                </tr>
            <?php
                $srno++;
            }
            ?>
            <input type="hidden" value="<?php echo $srno; ?>" id="total" />
            <tr>
                <th colspan="12"><input type="button" id="save" value="Save" onclick="save_record()" /></th>
            </tr>
        </table>
    <?php
    } else {
    ?>
        <div align='center'>
            <p style="color: #ff405f;font-size: 15px;font-weight: bold">Record Not Found <span style="color:black">or</span>
                Record Entered with Another User <span style="color:black">or</span> Entry Already Done</p>
        </div>
    <?php
    }
    ?>

<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103" >
       &nbsp;
    </div>
    <div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105;">
         <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()" ><b><img src="<?php echo base_url();?>/images/close_btn.png" style="width:30px;height:30px"/></b></div>
        <div  style="width: auto;background-color: white;overflow: scroll;height: 500px;margin: 40px 35px 25px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">
       </div>
        </div>
<?php

    //include ('../extra/popup.php');

} else {
?>
    <div align='center'>
        <p style="color: #ff405f;font-size: 15px;font-weight: bold">Record Not Found!!!</p>
    </div>
<?php
}