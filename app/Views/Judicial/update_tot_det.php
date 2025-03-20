<?php
	$ucode = session()->get('login')['usercode'];
 
    $stat = 1;    
	$sno_rvz = '';
    if($_REQUEST['ddlIASTAT']=='D'){
        $stat=0;
    }
?>
	<div id="sp_close" style="text-align: right;cursor: pointer" onclick="closeData()"><b><i class="fa fa-close" aria-hidden="true"></i></b></div>
	<table style="background-color: white;width:100%;text-align: left">
                <tr>
                    <td width="20%">
                        <b>  Doc Num/Year </b>    
                    </td>
                    <td>
                        <span id="sp_name"><?php echo$_REQUEST['hd_counts']?></span> / <span  id="sp_year"><?php echo $_REQUEST['hd_year'] ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Interlocutory Application</b>
                    </td>
                    <td>
                         <select class="form-control" name="m_doc1_upd" id="m_doc1_upd" style="width: 100%" onChange="showXtraDesc(this.value,this.id);" disabled="disabled" >
                                    <option value="0">Select</option>
                                    <?php
								  $res =  explode('^^', $_REQUEST['hd_ias']);     
								  for ($index = 0; $index < count($res); $index++)
								  {
									  $res1=  explode('^',$res[$index]);
									  $selected = '';
									   if($res1[0]==$_REQUEST['m_doc1']) 
										{ 
											$selected = 'selected="selected"';
										} 
									   ?>
										<option <?php echo $selected;?> value="<?php  echo $res1[0]; ?>" ><?php echo $res1[1]; ?></option>                         
								<?php }?>
 
							</select>
                    </td>
                </tr>
                <tr>
<!--                    <?php //if($_REQUEST['m_descss']=='') {  disabled="disabled" ;} ?> -->
                    <td><b>Description </b></td>
                    <td>
                          <input class="form-control" type="text" id="m_descss_upd" 
                                 name="m_descss_upd"  maxlength="50" 
                                 value="<?php echo $_REQUEST['m_descss']; ?>" style="width: 100%" <?php  if($stat==0)echo "disabled = 'true' ";?>/></td>
                </tr>
                <tr>
                    <td><b>Accused </b></td>
                    <td>
                               <?php
                                    
                  $res2=  explode('^^', $_REQUEST['hd_gtNms'])  ;    
                  $sd=1;
                  for ($index1 = 0; $index1 < count($res2); $index1++)
                    {
                      $ress=  explode('^',$res2[$index1]);
                        
                        ?>
                        <table style="width: 100%"><tr><td style="width: 10%">
                       <input type="checkbox" name="ckNm<?php echo $ress[0]; ?>" id="ckNm<?php echo $ress[0]; ?>"
                            <?php
                              if(preg_match('/^^/',$_REQUEST['hd_sp_sel_nm'] ))
                         {
                  $hd_sp_sel_nm=  explode('^^',  $_REQUEST['hd_sp_sel_nm']);
                  for ($index2 = 0; $index2 < count($hd_sp_sel_nm); $index2++)
                   {
                    $fc=explode('^',$hd_sp_sel_nm[$index2]);
                   if($fc[0]==$ress[0])
                   {
                       ?>
                              checked="checked"
                              <?php
                   }
                   }}
               else  {
                      $hd_sp_sel_nm=  explode('^',  $_REQUEST['hd_sp_sel_nm']);
                   if($fc[0]==$hd_sp_sel_nm[0])
                   {
                       ?>
                            checked="checked"   
                              <?php
                   }
                   }   
                   ?>
                           <?php  if($stat==0)echo "disabled = 'true' ";?>    />
                    </td>
                    <td>
                        <input class="form-control" type="text" name="txtNameUpd<?php echo $ress[0]; ?>" id="txtNameUpd<?php echo $ress[0]; ?>" value="<?php echo $ress[1] ?>" style="width: 100%" <?php  if($stat==0)echo "disabled = 'true' ";?>/>
                    </td>
                    </tr></table>
                    <?php
               
                         $sd++;  
                  }
?>
                        <input class="form-control" type="hidden"  name="hd_ssno" id="hd_ssno" value="<?php echo $sd; ?>"/>
                    </td>
                   
                </tr>
                <tr>
                    <td>
                        <b>IASTAT:</b>
                    </td>
                    <td>
                         <select class="form-control" name="ddlIASTAT<?php echo $sno_rvz ?>" id="ddlIASTAT<?php echo $sno_rvz ?>">
                                    <option value="P" <?php if($_REQUEST['ddlIASTAT']=='P') { ?>selected="selected" <?php ;} ?>>Pending</option>
                                    <option value="D" <?php if($_REQUEST['ddlIASTAT']=='D') { ?>selected="selected" <?php ;} ?>>Disposed</option>
                                    <!--<option value="T" <?php // if($_REQUEST['ddlIASTAT']=='T') { ?>selected="selected" <?php // ;} ?>>Transfer</option>-->
                                </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Disposed order date:</b>
                    </td>
                    <td>
                   
                     <input class="form-control" type="date" name="txt_order_dt<?php echo $sno_rvz ?> " id="txt_order_dt<?php echo $sno_rvz ?>"  maxlength="10" size="9" data-date-format="DD MMMM YYYY" value="<?php echo $_REQUEST['hd_ddate']  ?>" <?php  if($stat==0)echo "disabled = 'true' ";?>/>
                       
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Remark: :</b>
                    </td>
                    <td>
                          <input class="form-control" type="text" name="txtRematk<?php echo $sno_rvz ?>" id="txtRematk<?php echo $sno_rvz ?>" value="<?php echo $_REQUEST['txtRematk'] ?>" style="width: 100%" <?php  if($stat==0)echo "disabled = 'true' ";?>/>
                          <input type="hidden" name="hd_nature" id="hd_nature" value="<?php echo $_REQUEST['hd_nature'] ?>"/>
                          <input type="hidden" name="hd_IANAme" id="hd_IANAme" value="<?php echo $_REQUEST['hd_IANAme'] ?>"/>
                    </td>
                </tr>
            </table>
<div style="text-align: center">
     <input type="button" name="btnUpdate" class="btn btn-primary" id="btnUpdate" value="Update"/>
     <button type="button" name="closebtn" class="btn btn-danger"  onclick="closeData();" >Close</button>
</div>

 
