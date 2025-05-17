
<?php if(!empty($da_defect_arr) && count($da_defect_arr)>0){  ?>

 <?php
    if($check_section_rs > 0 && $ucode != 1){ 
        $casetype = array('9', '10', '19', '20', '25', '26', '39');
        if (!in_array($result_casetype, $casetype)) {
            echo '<div style="text-align: center"><h3>Defects can be added in RP/CUR.P/CONT.P./MA</h3></div>';
            exit();
        }
        if ($da != $ucode)
        {
            echo '<div style="text-align: center"><h3>Defects can be updated by concerned Dealing Assistant</h3></div>';
            exit();
        }
        
    }
 ?>
    <fieldset id="fd_md" class="scheduler-border">
        <legend><b>Main Party Details</b></legend>
        <?php
        $result_pet =  $da_defect_arr['pet_name'];
            $result_res = $da_defect_arr['res_name'];
            $result_dt = $da_defect_arr['dt'];
            $result_pending = $da_defect_arr['c_status'];
            //$nature=mysql_result($sql_q, 0,"nature");
            $cicri = $da_defect_arr['case_grp'];
         ?>
         <style>
                .table-container {
                    width: 100%;
                    margin: 20px 0;
                    border-collapse: collapse;
                }
                .table-container td,
                .table-container th {
                    padding: 12px 15px;
                    border: 1px solid #dddddd;
                    text-align: left;
                    font-size: 14px;
                }

                .table-container th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                    color: #343a40;
                }

                .table-container td {
                    background-color: #ffffff;
                }

                .table-container tr:nth-child(even) td {
                    background-color: #f2f2f2;
                }

                @media (max-width: 768px) {

                    .table-container td,
                    .table-container th {
                        padding: 8px 10px;
                        font-size: 12px;
                    }
                }


                body {
                    font-family: Arial, sans-serif;
                    background-color: #f0f0f0;
                    padding: 20px;
                }

                .styled-fieldset {
                    border: 2px solid black;
                    border-radius: 8px;
                    background-color: #fff;
                    padding: 20px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                }

                .styled-fieldset legend {
                    font-size: 25px;
                    font-weight: bold;
                    color: black;
                }

                .search-container {
                    display: flex;
                    align-items: center;
                }

                .search-container label {
                    margin-right: 10px;
                }

                .search-container input[type="text"] {
                    width: 90%;
                    padding: 8px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    font-size: 14px;
                }

                .data-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                .data-table th,
                .data-table td {
                    padding: 10px;
                    text-align: center;
                    border: 1px solid #ddd;
                }

                .data-table th {
                    background-color: #4CAF50;
                    color: white;
                }

                .data-table tbody tr:nth-child(even) {
                    background-color: #f9f9f9;
                }

                .data-table tbody tr:hover {
                    background-color: #f1f1f1;
                    transition: background-color 0.3s;
                }

                .text-uppercase {
                    text-transform: uppercase;
                }

                .styled-button {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    margin-top: 2%;
                    font-size: 16px;
                    font-weight: bold;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    text-align: center;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
                }

                .styled-button:hover {
                    background-color: #45a049;
                }

                .remove-all-button {
                    padding: 10px 15px;
                    background-color: black;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    font-size: 14px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: background-color 0.3s ease, transform 0.2s ease;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    outline: none;
                }

                .remove-all-button:hover {
                    background-color: black;
                    transform: translateY(-2px);
                }

                .remove-all-button:active {
                    background-color: black;
                    transform: translateY(0);
                }

                @media (max-width: 600px) {
                    .remove-all-button {
                        font-size: 12px;
                    }
                }

                .close-all-button {
                    padding: 10px 15px;
                    background-color: red;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    margin-top: 1%;

                    font-size: 14px;
                    font-weight: bold;
                    cursor: pointer;
                    transition: background-color 0.3s ease, transform 0.2s ease;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    outline: none;
                }

                .close-all-button:hover {
                    background-color: black;
                    transform: translateY(-2px);
                }

                .close-all-button:active {
                    background-color: black;
                    transform: translateY(0);
                }

                @media (max-width: 600px) {
                    .close-all-button {
                        font-size: 12px;
                    }
                }
            </style>
            <input type="hidden" name="hd_ci_cri" id="hd_ci_cri" value="<?php echo $cicri; ?>" />
            <table class="table-container">
                <tr>
                    <td style="width: 10%">
                        <b> Petitioner Name </b>
                    </td>
                    <td style="width: 15%">
                        <?php echo $result_pet; ?>
                    </td>
                    <td style="width: 10%">
                        <b> Respondent Name </b>
                    </td>
                    <td style="width: 15%">
                        <?php echo $result_res; ?>
                    </td>
                    <td style="width: 8%">
                        <b> Receiving date </b>
                    </td>
                    <td style="width: 10%">
                        <?php echo $result_dt; ?>
                    </td>
                </tr>
            </table>
    </fieldset>
     <?php if ($result_pending == 'D') { ?>
            <div style="text-align: center;color: red">
                <h3>Matter is Disposed!!!!</h3>
            </div>
    <?php
        exit(0);
    } ?>
    
    <?php
    if ($sql_res == 0) {
        if ($ucode == 1 || $ucode == 1486 || $soft_copy_user == 1 || $if_chamber_listed == 1) {
            $sql_res = 1;
        } else {
            echo "<div style='text-align:center'><h3>Matter has been refiled!!!</h3></div>";
        }
    }
     ?>
    <?php if ($sql_res == 1) 
    { ?>

        <fieldset id="fiOD" >
                <legend><b>Default Details</b></legend>
                <span id="spAddObj" style="font-size: small;text-transform: uppercase">
                    <table id="tb_nm" cellpadding="5" cellspacing="5" width="100%" class="styled-fieldset">
                        <?php
                        $sno = 1;
                        $cn_c = '';

                         ?>
                        <input type="hidden" name="hdChk_num_row" id="hdChk_num_row" value="<?php echo count(@$q_w); ?>" /> 
                        <input type="hidden" name="allow_entry_in_registered_matter" id="allow_entry_in_registered_matter" value="<?php echo $allow_entry_in_registered_matter; ?>" />

                        <?php
                        if(!empty($q_w)){
                            foreach ($q_w as $row1) {
                                if ($cn_c == '')
                                    $cn_c = $row1['org_id'];
                                else
                                    $cn_c = $cn_c . ',' . $row1['org_id'];
                         ?> 
                            <tr>
                                    <!--            <td></td>-->
                                    <td class="c_vertical_align">
                                        <input id="hd_id<?php echo $sno; ?>" type="hidden" value="<?php echo $row1['org_id']; ?>" />

                                        <span id="spAddObjjjj<?php echo $sno; ?>" style="display: none">

                                            <?php echo $sno; ?>

                                        </span>
                                        <input id="chkbox_obj<?php echo $sno; ?>" type="checkbox" <?php if ($row1['rm_dt'] != '0000-00-00 00:00:00') { ?> disabled="true" <?php } ?> />
                                    </td>
                                    <td>
                                        <span id="spAddObj<?php echo $sno; ?>"><?php echo $row1['obj_name']; ?></span>

                                        <!--        <span id="rm_dt">
                                    <?php
                                                            //        if($row1['rm_dt']!='0000-00-00 00:00:00')
                                                            //        echo "<span style='color:red'>Removed On</span> ". $row1['rm_dt'];
                                    ?>
                                    </span>
                                    --> <span id="sp_hide<?php echo $sno; ?>"><br /></span>
                                    </td>
                                    <td>
                                        <span id="spRema<?php echo $sno; ?>"><?php echo $row1['remark'] ?></span>
                                    </td>
                                    <td>
                                        <span id="spRem_mula<?php echo $sno; ?>"><?php
                                                                                    $ex_ui =  explode(',', $row1['mul_ent']);
                                                                                    $r = '';
                                                                                    for ($index = 0; $index < count($ex_ui); $index++) {
                                                                                        // echo 'ererere' .$ex_ui[$index];
                                                                                        if (trim($ex_ui[$index] == '')) {


                                                                                            $r = $r . '-' . ',';
                                                                                        } else {

                                                                                            $r = $r . $ex_ui[$index] . ',';

                                                                                            // echo $row1['mul_ent'] ;
                                                                                        }
                                                                                    }

                                                                                    echo  substr($r, 0, -1);
                                                                                    ?></span>
                                    </td>
                                </tr>

                         <?php
                            $sno++;       
                            }
                        }

                         ?>

                    </table>
            
                </span>   
        
         <?php 
            $showButton = false;
            $result = $fil_trap;
            if(!empty($result) && count($result)>0){ 
                foreach ($result as $row) {
                    if ($row['remarks'] == 'FDR -> SCR') {
                            $showButton = true;
                            break;
                        }
                }
            }
            else{
                $showButton = true;
            }
            $showSmsButton = 0;
            if(!empty($obj_save_result) && count($obj_save_result)>0){
                $showSmsButton = count($obj_save_result);
            }
         ?>        
         <div style="text-align: center">
                    <?php if ($showButton): ?>
                        <input type="button" class="styled-button" name="btnModify" id="btnModify" value="Save" style="display: none" onclick="getDone1()" />
                        <br>

                        <span style="color: red">Please click SMS button after adding all defects.</span>
                        <input type="button" name="btn_sms" id="btn_sms" class="remove-all-button" value="SMS & Email" />

                    <?php endif; ?>
                    <div id="sp_sms_status" style="text-align: center"></div>
        </div>       
        </fieldset>
             <input type="hidden" name="hdTotal" id="hdTotal" value="<?php echo $sno - 1; ?>" />
            <input type="hidden" name="hd_fc" id="hd_fc" />
              <?php

            if (count($q_w) <= 0) {
             ?>
                <br />
                <span id="sp_amo" style="display: none" class="styled-button" onclick="go_other()">Add More Objection</span>

                <div style="text-align: center">
                    <input type="button" name="btnAdd" id="btnAdd" value="Save" class="styled-button" onclick="getDone()" />
                    <input type="button" name="btnClose" id="btnClose" value="Close" style="display: none" onclick="closeData()" />
                    <!--         <input type="button" name="btnRemove" id="btnRemove" value="Remove/Default" style="display: none" onclick="rem_obj();"/>-->
                </div>
            <?php 
            }else{
             ?>

             <div style="text-align: center">
                    <?php
                    $showButton = false;
                    if(!empty($fil_trap) && count($fil_trap)>0){
                        foreach ($fil_trap as $row) {
                             if ($row['remarks'] == 'FDR -> SCR') {
                                $showButton = true;
                             }
                        }
                    }
                    else {
                        $showButton = true;
                    }
                     ?>
                     <div style="text-align: center;">
                        <?php if ($showButton): ?>
                            <div id="sp_amo" onclick="go_other()" class="styled-button">
                                <b>Add More Objections</b>
                            </div>
                        <?php else: ?>
                            <p style="color: red; font-weight: bold;">Defects can not be added as matter is not refiled by the advocate!</p>
                        <?php endif; ?>
                    </div>
                    <input type="button" class="close-all-button" name="btnClose" id="btnClose" value="Close" style="display: none" onclick="closeData()" />
                    <input type="button" class="styled-button" name="btnModify" id="btnModify" value="Add/Modify" style="display: none" onclick="getDone1()" />
            </div>
               
            <?php
            }
            ?>          
            <?php
                $rt_u = 0;
                if(!empty($rw) && count($rw)>0){
                    foreach ($rw as $row2) {
                        if ($row2['rm_dt'] == '0000-00-00 00:00:00' || $sql_res == 1)
                            $rt_u = 1;
                    }
                }
                else {
                    $rt_u = 1;
                }
            ?>
            <?php if ($rt_u == 1): ?>
                 <?php
                    $rtbv = '';
                    if(empty($q_w) && count($q_w) <= 0){
                ?>
                <fieldset id="ftAO" class="styled-fieldset">
                    <legend><b>Add Default</b></legend>
                    <div>
                        <b>Search</b>&nbsp;&nbsp;&nbsp;
                        <input type="text" name="txtAuCom" id="txtAuCom" style="width: 90%" onkeyup="getRelRc(this.id,this.value)" />
                    </div><br />
                    <table id="tbData" width="100%" border="1" style="border-collapse: collapse;text-align: center;" valign="middle" class="table_tr_th_w_clr c_vertical_align" cellspacing="5" cellpadding="5">
                       
                            <tr>
                                <!--            <th >S.No</th>-->
                                <th>Check <br />To<br /> Add</th>
                                <th>Defaults</th>
                                <th>Rule</th>
                            </tr>
                             <?php
                                if(!empty($sql_obj) && is_array($sql_obj)){
                                    $sno = 1;
                                        foreach ($sql_obj as $row) {
                             ?>
                             <tr>
                                <td>
                                    <input type="checkbox" name="chkCheck_<?php echo $row['org_id'] ?>" id="chkCheck_<?php echo $row['org_id'] ?>" onclick="checkRecords(this.id)" />
                                </td>
                                <td style="text-align: justify;text-transform: uppercase">
                                    <span id="spObj_<?php echo $row['org_id'] ?>">
                                        <?php echo $row['obj_name'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span id="spRule_<?php echo $row['org_id'] ?>">
                                        <?php
                                            if ($row['ci_cri'] == 2) echo $row['rule'];
                                            else echo "-";
                                        ?>
                                    </span>
                                </td>
                            </tr>
                             

                             <?php
                                    $sno++;
                                        }
                                }
                             ?>
                    </table>
                </fieldset>
                 <?php
                        }
                ?>
            <?php                
            endif;
            ?>



    <?php } ?> 
    
<?php    
}
else{
    $j_p = 1;
    ?>
<div style="text-align: center">
            <h3>Diary No. Not Found</h3>
        </div>
 <?php   
} 
 ?>
 <input type="hidden" name="hd_kl" id="hd_kl" value="<?php echo @$j_p; ?>" />