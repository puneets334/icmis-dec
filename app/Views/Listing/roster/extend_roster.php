<?php
// $row = $roster_data[0];
$row = $roster_data;
$cur_ddt = date('Y-m-d', strtotime(' +1 day'));
$next_court_work_day = date("d-m-Y", strtotime(next_court_working_date($cur_ddt)));
?>
<input type="hidden" name="hd_ex_r_id" id="hd_ex_r_id" value="<?php echo $reqData['btnroster']; ?>"/>
<table align="center" style="margin-top: 30px" id="tb_nmsz" class="c_vertical_align tbl_border" cellpadding="5" cellspacing="5">
<tr>
                     <td>
                            Effected From :
                    </td>
                     <td>
                            Session :
                    </td>
                    <td colspan="2">
                         Timing :
                    </td>
                    <td>
                        No. of Cases :
                    </td>
                     <td>
                       Delete
                    </td>
                </tr>
                <tr id="row_del_addz1">
                    
                    <td>
                     
                        <input type="text" name="from_dtz1" id="from_dtz1" class="dtpn" maxsize="10" value="<?= $next_court_work_day; ?>" autocomplete="on" size="9" readonly/>   
                        <div id="dv_add_dtsz"></div>
                      
                        <input type="hidden" name="hd_from_dtz" id="hd_from_dtz" value="1"/>
                    </td>
                    <td style="vertical-align: top">
                        <select name="sessz1" id="sessz1" >
                        <option value="">-Select-</option>
                        <option value="Whole Day" <?php if($row['session']=='Whole Day') { ?>  selected="selected" <?php } ?>>Whole Day</option>
                        <option value="Before Lunch" <?php if($row['session']=='Before Lunch') { ?>  selected="selected" <?php } ?>>Before Lunch</option>                        
                        <option value="After Lunch" <?php if($row['session']=='After Lunch') { ?>  selected="selected" <?php } ?>>After Lunch</option>            
                        <option value="After Regular Bench" <?php if($row['session']=='After Regular Bench') { ?>  selected="selected" <?php } ?>>After Regular Bench</option>
                         <option value="After DB" <?php if($row['session']=='After DB') { ?>  selected="selected" <?php } ?>>After DB</option>
                     <option value="After SPL. DB" <?php if($row['session']=='After DB') { ?>  selected="selected" <?php } ?>>After SPL. DB</option>
                        </select>
                          <div id="dv_add_sesz" ></div>
                    </td>
                    <?php
                    $sp_frm_time=  explode(':', $row['frm_time']);
                    $ex_f_t=  explode(' ', $sp_frm_time[1]);
                   
                    ?>
                    
                    <td style="vertical-align: top" colspan="2">
                         <select name="ddl_hrsz1" id="ddl_hrsz1" onchange="set_min(this.value,this.id)">
                         <option value="">Select</option>
                         <?php
                         for($j=1;$j<=12;$j++)
                         {
                             ?>
                         <option value="<?php echo $j; ?>" <?php if($sp_frm_time[0]==$j) { ?> selected="selected" <?php } ?>><?php echo $j; ?></option>
                         <?php
                         }
                         ?>
                     </select> &nbsp;<b> : </b>&nbsp;
                     
                     <select name="ddl_minz1" id="ddl_minz1" <?php if($row['frm_time']=='') { ?>  disabled="true" <?php } ?>>
                         <option value="">Select</option>
                         <?php
                         for($k=0;$k<=60;$k++)
                         {
                            if(strlen($k)==1)
                            {
                                $k='0'.$k;
                            }
                             ?>
                         <option value="<?php echo $k; ?>" <?php if($ex_f_t[0]==$k) { ?> selected="selected" <?php } ?>><?php echo $k; ?></option>
                         <?php
                         }
                         ?>
                     </select>
                     <select name="ddl_am_pmz1" id="ddl_am_pmz1" <?php if($row['frm_time']=='') { ?>  disabled="true" <?php } ?>>
                         <option value="">Select</option>
                         <option value="AM" <?php if($ex_f_t[1]=='AM') { ?> selected="selected" <?php } ?>>AM</option>
                         <option value="PM" <?php if($ex_f_t[1]=='PM') { ?> selected="selected" <?php } ?>>PM</option>
                     </select>
                     
                       <div id="dv_timingz" ></div>
                    </td>
                    
                    <td style="vertical-align: top">
                        <input type="text" name="txt_no_casez1" id="txt_no_casez1" size="4" 
                        onkeypress="return OnlyNumbersTalwana(event,this.id)" maxlength="4" value="<?php echo $row['tot_cases'] ?>"/>
                      <div id="dv_txt_no_casez" ></div>
                    </td>
                     <td style="vertical-align: top">
                         <div style="margin-top: 20px"></div>
                      <div id="dv_deletez" ></div>
                    </td>
                </tr>
                
                <tr id="tr_insert_rowsz"></tr>
                <tr>
                    <td colspan="5" style="text-align: center">
                          <span id="sp_addz" class="btn btn-secondary" onclick="ad_textboxz()" >Add</span>
                    </td>
                </tr>
                </table>
<div style="text-align: center;margin-top: 20px">
    <input type="button" name="btn_ext_su" id="btn_ext_su" class="btn btn-primary" value="Submit" onclick="save_ext_rec()"/>
     <input type="hidden" name="hd_sp_bn_r" id="hd_sp_bn_r" value="<?php echo $reqData['sp_bn_r']; ?>"/> 
    
</div>
<b><div id="dv_ext_s" style="text-align: center"></div></b>
<script>
$(document).ready(function() {
   $('.dtpn').datepicker({
       format: 'dd-mm-yyyy',
       todayHighlight: true,
       autoclose: true,
       changeMonth: true,
       changeYear: true,
       yearRange: '1950:2050'

   });
});
</script>