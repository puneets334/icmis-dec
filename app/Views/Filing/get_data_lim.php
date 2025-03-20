<?php 
$c_date=date('Y-m-d');
$diary_no = session()->get('filing_details')['diary_no'];
if(!empty($res_p_r))
{
//$res_p_r=  mysql_fetch_array($p_r);
 $ck_status=0;
 $category_code='';
 $cat='';
 $sub_cat='';
 $sub_sub_cat='';
  

if(!empty($ch_cat))
{
    $order_by=1;
    $row = $ch_cat;
        
            
            $r_chk_limi = $limitModel->getlimitation1($res_p_r['casetype_id'], $row['submaster_id'], 0);
         
            if(!empty($r_chk_limi))
            {
                $ck_status=1;
                
                if($r_chk_limi['order_by']<$order_by)
                {
                    $cat=$row['cat'];
                    $sub_cat=$row['subcat'];
                    $sub_sub_cat=$row['subcat1'];
                    $category_code=" casetype_id='$res_p_r[casetype_id]' and submaster_id='$row[submaster_id]' and case_law=0";
                    $order_by=$r_chk_limi['order_by'];
                }
            }
         
    }
    if($ck_status==0)
    {
         $ck_status=2;
      
        $res_chk_limi = $limitModel->getlimitation2($res_p_r['casetype_id'], '0', 0);
         if(!empty($res_chk_limi))
       {
           $ck_status=3;
          
           
                 $category_code=" casetype_id='$res_p_r[casetype_id]' and  submaster_id='0'
                         and case_law=0";
       }
     
    }
    if($ck_status==2)
    {
        $ck_status=5;
        
if(!empty($ch_cat))
{
    $order_by=1;    
    $row = $ch_cat;
          
            $r_chk_limi = $limitModel->getlimitation($res_p_r['casetype_id'], $row['submaster_id'], 0);
            if(!empty($r_chk_limi))
            {
                $ck_status=4;
               
                if($r_chk_limi['order_by']<$order_by)
                {
         
                    $cat_id=$row['submaster_id'];
                    $category_code=" casetype_id='0' and submaster_id='$row[submaster_id]' and case_law=0";
                    $order_by=$r_chk_limi['order_by'];
                }
            }
        
    }
    }
    
    if($ck_status==5)
    {
        $ck_status=7;  
       
        $res_chk_limi = $limitModel->getlimitation2('0', '0', $res_p_r['actcode']);
        if(!empty($res_chk_limi))
       {
           $ck_status=6; 
            $category_code=" casetype_id='0' and  submaster_id='0' and case_law='$res_p_r[actcode]'";
       }
    }
if($ck_status!=7)
{
?>
<div style="text-align: center">
    <h3><b><?php echo $res_p_r['pet_name']; ?></b> Vs <b><?php echo $res_p_r['res_name']; ?></b></h3>
</div>
<?php
$ck_fl_org=0;
 
 
    $no_rws=  isset($rw_sq) ? count($rw_sq) : 0;
 
?>
<table align="center" border="0" cellpadding='5' cellspacing='5' class="c_vertical_align" >
                <tbody>
                    <tr>
                        <th class="al_left">
                        <b>Nature of the Matter</b>
                        </th>
                        <td>
                          <?php
                        
                          $res_c_t='';
                          if($ck_status==1 || $ck_status==4)
                          {
                              if($ck_status==1)
                              {
                               
                                $res_c_t=$case_name['casename'];
                              }
                             if($cat_id!=0)
                             {
                                  
                                 $r_cat_name = $limitModel->getSubmasterDescription($cat_id); 
                                 if($res_c_t=='')
                                     $res_c_t=$r_cat_name;
                                 else
                                $res_c_t=$res_c_t.' - '.$r_cat_name;
                             }
                            

                          }
                          else if($ck_status==6)
                          {
                             
                             $res_c_t=$case_law['law'];
                          }
                          else
                          {
                              
                             $res_c_t=$case_type['casename'];
                          }
                          echo $res_c_t;
                          ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="al_left">
                        <b>Claimed From</b>
                        </th>
                        <td>
                            <?php                             
                           $o_cof='';
                           $o_c='';
                           $o_period=0;
                           $s_c_t = $limitModel->getLimitationForClaim($category_code); 
                            foreach ($s_c_t as $row1)
                            {
                               if($row1['order_cof']=='O')
                               {
                                   $o_cof='rdn_o';
                                   $o_c="Order";  
                                   $o_period=$row1['limitation'];
                               }
                               else  if($row1['order_cof']=='C')
                               {
                                   $o_cof='rdn_c';
                                     $o_c="Certificate of fitness";  
                               }
                                ?>
                            <input type="radio" name="rdn_o_cof" id="<?php echo $o_cof; ?>" value="<?php echo $row1['order_cof']; ?>"
                                    <?php if($row1['order_cof']=='O' && $no_rws==0) { ?> checked="checked" <?php } else if(!empty($rw_sq) && $rw_sq['order_cof']==$row1['order_cof']) { ?> checked="checked"  <?php  } ?> class="c_o_cof"/> 
                            <label for="<?php echo $o_cof; ?>"><?php echo $o_c; ?></label>
                            <input type="text" name="txt_order_dt<?php echo $row1['order_cof']; ?>" 
                                   id="txt_order_dt<?php echo $row1['order_cof']; ?>" maxlength="10" size="9" class="dtp"   
                                      <?php if(($row1['order_cof']!='O' && $no_rws==0 && $no_rws==0) || ($no_rws!=0 && $rw_sq['order_cof']!=$row1['order_cof']) ) { ?>  disabled="true" <?php } ?>
                                        value="<?php if($row1['order_cof']=='O' && $no_rws==0){?><?php echo $_REQUEST['sp_lct_dec_dt']; ?><?php ;} else if(!empty($rw_sq) &&  $rw_sq['order_cof']==$row1['order_cof']){ echo date('d-m-Y',strtotime($rw_sq['o_d']));}?>"/>
                            <input type="hidden" name="hd_limitation<?php echo $row1['order_cof']; ?>" id="hd_limitation<?php echo $row1['order_cof']; ?>" value="<?php echo $row1['limitation'];?>"/>
                            <?php
                            }
                         // }
                            ?>
                          
                        </td>
                    </tr>
                  
                    <tr>

                            <td><b>Period of Limitation</b>
                            </td>
                            <td>

                                <input type="text" name="climit" id="climit"  readonly="readonly" value="<?php if($no_rws==0) { echo $o_period ;} else {  echo $rw_sq['pol'] ;} ?>" size="4" disabled="true"/>
</td>
                    </tr>
                    <tr>
                       <td>
                           <?php
                           if($res_p_r['nature']==6)
                           {
                               ?>
                            <b>Date of document signed by jailer</b>
                           <?php
                            
                                $jail_dt='';
                               $r_jailer_sign_dt =  $limitModel->getJailerSignDt($diary_no);
                                if(!empty($r_jailer_sign_dt))
                                {
                                    
                                    $jail_dt= (!empty($r_jailer_sign_dt['jailer_sign_dt'])) ? date('d-m-Y',  strtotime($r_jailer_sign_dt['jailer_sign_dt'])) : '';
                                }
                           }
                           else 
                           {
                           ?>
                            <b>Date of Filing</b>
                           <?php } ?>
                        </td>
                        <td>
                            <input class="dtp" name="filing_dt" id="filing_dt" type="text" maxlength="10" size="9"  
                                   onkeyup="chkData_p(event,this.value,this.id)" onkeypress="return OnlyNumbers(event,this.id);" 
                                   value="<?php if($res_p_r['nature']==6) { if($no_rws==0) { echo $jail_dt;} else { if($rw_sq['f_d']!='' && $no_rws!=0) echo date('d-m-Y',strtotime($rw_sq['f_d'])) ; } } else { if($no_rws==0) { echo date('d-m-Y',strtotime($res_p_r['diary_no_rec_date'])); } else{ if($rw_sq['f_d']!='' && $no_rws!=0) echo date('d-m-Y',strtotime($rw_sq['f_d'])) ;}} ?>"/>
                        </td>
                    </tr>


                    

                    <tr>
                        <td>
                            <b>Copy applied on</b>
                        </td>
                        <td>
                            
                            <input class="dtp" name="copy_aply_dt" id="copy_aply_dt" type="text" maxlength="10" size="9"  onkeyup="chkData_p(event,this.value,this.id)" onkeypress="return OnlyNumbers(event,this.id);" value="<?php if(!empty($rw_sq) && $rw_sq['c_d_a'] != '' && $no_rws!=0) { echo date('d-m-Y',strtotime($rw_sq['c_d_a'])); } ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Copy ready on</b>
                        </td>
                        <td>
                            <input class="dtp" name="copy_dlvr_dt" id="copy_dlvr_dt" type="text" maxlength="10" size="9"  onkeyup="chkData_p(event,this.value,this.id)" onkeypress="return OnlyNumbers(event,this.id);" value="<?php  if(!empty($rw_sq) && $rw_sq['d_o_d']!='' && $no_rws!=0)  echo date('d-m-Y',strtotime($rw_sq['d_o_d'])) ?>"/>
                        </td>

                    </tr>
                     <tr>
                        <td>
                            <b>Attestation</b>
                        </td>
                        <td>
                            <input class="dtp" name="txt_attestation" id="txt_attestation" type="text" maxlength="10" size="9"  onkeyup="chkData_p(event,this.value,this.id)" onkeypress="return OnlyNumbers(event,this.id);" value="<?php  if(!empty($rw_sq) && $rw_sq['d_o_a']!='' && $no_rws!=0)  echo date('d-m-Y',strtotime($rw_sq['d_o_a'])) ?>"/>
                        </td>

                    </tr>

                    
             <tr style="display: <?php if($no_rws>0) { echo 'table-row'; } else { echo 'none';} ?>">
                        <th colspan="2">
                           <div style="text-align: center;margin: 10px">
                                
                                <?php echo $rw_sq['descr'] ?? '' ?>
                            </div> </th>
                                     </tr>       
 
                 
                    <tr>
                        <td colspan="4" style="text-align: center">
                            <input value="Submit" id="check" type="button" onclick="save_check()">
                             <?php
                            if($ck_fl_org==0 && $no_rws>0)
                            {
                            ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="hidden" name="hd_lim_id" id="hd_lim_id" value="<?php echo $rw_sq['id'] ?? '' ?>"/>
                           <input value="Delete" id="del_check" type="button" onclick="del_check_vb()">  
                           <?php } ?>
                        </td>
                    </tr>
                   
                </tbody></table>
            <div id="d4" width="100%" style="text-align: center"></div>  
<?php 
}
else
{
     ?>
    <div style="text-align: center;margin-top: 20px"><h3>Nature of matters not found for calculating limitation</h3></div>
<?php
}
  } 
else 
{
    ?>
    <div style="text-align: center;margin-top: 20px"><h3>No Record found</h3></div>
<?php } ?>



<script>
     $(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
    });


  function checkStat() {
    var fon_nm = document.queryCommandValue("FontName");
    //document.execCommand('styleWithCSS', false, null);
    var fon_sz = document.queryCommandValue("FontSize");
    var ital = document.queryCommandState("Italic");
    var bld = document.queryCommandState("Bold");
    var undell = document.queryCommandState("Underline");

    var addlink = document.queryCommandState("insertHTML");
  
    var jc = document.queryCommandState("JustifyCenter");
  
    var jl = document.queryCommandState("JustifyLeft");
    var jr = document.queryCommandState("JustifyRight");
    var jf = document.queryCommandState("JustifyFull");
  
    var insertUnorderedList = document.queryCommandState("insertUnorderedList");
    var insertOrderedList = document.queryCommandState("insertOrderedList");
    document.getElementById("ddlFS").value = fon_sz;
    if (ital == true)
      document.getElementById("btnItalic").style.backgroundColor = "#bbb51f";
    else document.getElementById("btnItalic").style.backgroundColor = "";
  
    if (bld == true)
      document.getElementById("btnBold").style.backgroundColor = "#bbb51f";
    else document.getElementById("btnBold").style.backgroundColor = "";
  
    if (undell == true)
      document.getElementById("btnUnderline").style.backgroundColor = "#bbb51f";
    else document.getElementById("btnUnderline").style.backgroundColor = "";

    if (addlink == true)
      document.getElementById("btnAddLink").style.backgroundColor = "#bbb51f";
    else document.getElementById("btnAddLink").style.backgroundColor = "";
  
    if (jc == true)
      document.getElementById("btnJustify").style.backgroundColor = "#bbb51f";
    else document.getElementById("btnJustify").style.backgroundColor = "";
  
    if (jl == true)
      document.getElementById("btnAliLeft").style.backgroundColor = "#bbb51f";
    else document.getElementById("btnAliLeft").style.backgroundColor = "";
  
    if (jr == true)
      document.getElementById("btnAliRight").style.backgroundColor = "#bbb51f";
    else document.getElementById("btnAliRight").style.backgroundColor = "";
    if (jf == true)
      document.getElementById("btnFull").style.backgroundColor = "#bbb51f";
    else document.getElementById("btnFull").style.backgroundColor = "";
  
    // if (insertUnorderedList == true)
    //   document.getElementById("insertUnorderedList").style.backgroundColor = "#bbb51f";
    // else
    //   document.getElementById("insertUnorderedList").style.backgroundColor = "";
  
    // if (insertOrderedList == true)
    //   document.getElementById("insertOrderedList").style.backgroundColor =
    //     "#bbb51f";
    // else document.getElementById("insertOrderedList").style.backgroundColor = "";
  
    // document.getElementById("ddlFontFamily").value = fon_nm;
    //  alert(document.getElementById('ddlFontFamily').value)    ;
    //  document.getElementById('noticecontent').focus();
  }
</script>    