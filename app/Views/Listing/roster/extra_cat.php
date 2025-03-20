<style>
.c_vertical_align td {
  vertical-align: top;
}
</style>

<?php
$res_sql = $roastData[0];
?>

<input type="hidden" name="hd_ros_id" id="hd_ros_id" value="<?php echo $btnroster; ?>"/>
<input type="hidden" name="hd_m_f_l_m" id="hd_m_f_l_m" value="<?php echo $res_sql['m_f']; ?>"/>
<?php
// $stageData          = $stageData[0];
$res_sql_not        = $stageData['stage_code'];
$res_priority       = $stageData['priority'];
$res_stage_nature   = $stageData['stage_nature'];
$res_case_type      = $stageData['case_type'];
$res_submaster_id   = $stageData['submaster_id'];
$res_b_n            = $stageData['b_n'];

?>
<input type="hidden" name="hd_res_priority" id="hd_res_priority" value="<?php echo $res_priority; ?>"/>

<br/>
<?php
    {
    $datas['case_type'] = $case_type;
    $datas['sub_master'] = $sub_master;
    echo view('Listing/roster/get_mot_details1', $datas);
?>
    <div style="text-align: center;width: 90%;margin: auto" >
    <b>Categories already  alloted</b>

    <table width="100%" id="tb_cat_id" class="table_tr_th_w_clr c_vertical_align">
    <?php
    $sno=1;
    $ex_res_sql_not=  explode(',', $res_sql_not);
    $ex_priorityt=  explode(',', $res_priority);
    $ex_stage_nature=explode(',', $res_stage_nature);
    $ex_case_type=explode(',', $res_case_type);
    $ex_b_n=explode(',', $res_b_n);
    $ex_res_submaster_id=explode(',', $res_submaster_id);

    for ($index = 0; $index < count($ex_res_sql_not); $index++)
    {
        $ex_b_x='';
        if($ex_b_n[$index]=='N')
            $ex_b_x='Not Before';
        else if($ex_b_n[$index]=='B')
            $ex_b_x='Before';
        
        $case_nat_nm='';
        if($ex_stage_nature[$index]=='C')
            $case_nat_nm='Civil';
        else  if($ex_stage_nature[$index]=='R')
            $case_nat_nm='Criminal';
        else  if($ex_stage_nature[$index]=='W')
            $case_nat_nm='Writ';
        else 
            $case_nat_nm='-';

        $res_sql_ck_cas = $thiss->getCasetypeKey($ex_case_type[$index]);

        if(!empty($res_sql_ck_cas)){
            $res_sql_ck_cas_ty = $res_sql_ck_cas[0]['skey'];
        }else{
            $res_sql_ck_cas_ty = '-';
        }
        

        $subcode = $thiss->getSubMaster($ex_res_submaster_id[$index]);

        if(!empty($subcode)){
            $res_subcode = $subcode[0];
            $sub_name1 = $res_subcode['sub_name1'];
            $sub_name2 = $res_subcode['sub_name2'];
            $sub_name3 = $res_subcode['sub_name3'];
            $sub_name4 = $res_subcode['sub_name4'];
        }else{
            $sub_name1 = '';
            $sub_name2 = '';
            $sub_name3 = '';
            $sub_name4 = '';
        }

    ?>
    <tr>
        <td>
            <span id="sps_<?php echo $sno; ?>"><?php echo $sno; ?></span>
        </td>
        <td>
            <span id="spscn_<?php echo $sno; ?>"><?php echo $case_nat_nm; ?></span>
            <input type="hidden" name="hd_sp_fs<?php echo $sno; ?>" id="hd_sp_fs<?php echo $sno; ?>" value="<?php echo $ex_stage_nature[$index]; ?>"/>
          <input type="hidden" name="hd_sp_gs<?php echo $sno; ?>" id="hd_sp_gs<?php echo $sno; ?>" value="<?php echo $ex_priorityt[$index]; ?>"/>
        </td>
        <td>
            <span id="spscy_<?php echo $sno; ?>"><?php echo $res_sql_ck_cas_ty; ?></span>
             <input type="hidden" name="hd_sp_as<?php echo $sno; ?>" id="hd_sp_as<?php echo $sno; ?>" value="<?php echo $ex_case_type[$index]; ?>"/>
        </td>
        
        <td>
        <?php
        $sq_mn_cat = $thiss->getSubHeading($ex_res_sql_not[$index]);
        
        if(!empty($sq_mn_cat)){
            $stagename = $sq_mn_cat[0]['stagename'];
        }else{
            $stagename = '-';
        }
        ?>
        <span id="sp_nms<?php echo $sno; ?>"><?php echo $stagename; ?></span>
        <input type="hidden" name="hd_sp_bs<?php echo $sno; ?>" id="hd_sp_bs<?php echo $sno; ?>" value="<?php echo $ex_res_sql_not[$index]; ?>"/>
        </td>
        
        <td>
            <span style="color: red"><?php echo $ex_b_x; ?></span><br/>
            <span id="spscat1_<?php echo $sno; ?>"><?php echo $sub_name1; ?></span><?php if($sub_name2 != '') { ?><br/> <?php } ?>
            <span id="spscat2_<?php echo $sno; ?>"><?php echo $sub_name2; ?></span><?php if($sub_name3 != '') { ?><br/> <?php } ?>
            <span id="spscat3_<?php echo $sno; ?>"><?php echo $sub_name3; ?></span><br/>
            <span id="spscat4_<?php echo $sno; ?>"><?php echo $sub_name4; ?></span>
            <input type="hidden" name="hd_sp_cs<?php echo $sno; ?>" id="hd_sp_cs<?php echo $sno; ?>" value="<?php echo $ex_res_submaster_id[$index]; ?>"/>
        </td>
    </tr>
    <?php
    $sno++;
    } ?>
</table>
    <br/><br/>
    <div style="text-align: center">
        <input type="button" name="btn_save_ex_party" id="btn_save_ex_party" class="btn btn-primary" value="Save" onclick="save_ex_party_sd()"/>
    &nbsp;&nbsp;&nbsp;
              
    </div>
    <div style="text-align: center" id="dv_rss">
        
   </div>
    </div>
<input type="hidden" name="hd_tootal" id="hd_tootal" value="<?php echo $sno; ?>"/>
<input type="hidden" name="hd_pr_nm_vals" id="hd_pr_nm_vals" />

<?php

if($res_sql['bench_id']=='1')
{
   $ddlBench='S' ;
}
 else
     {
       $ddlBench='D' ;
}
?>
<div align="center" style="float: right;width:55%">
                
<?php
     }
?>

<br/><br/>
 
</div>
<input type="hidden" name="btn_final_roster" id="btn_final_roster" value="<?php echo $btnroster; ?>"/>
