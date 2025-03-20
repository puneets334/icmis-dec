<?php
 
if(!empty($caveat_list))
{
	//    echo get_alert_NFM($_REQUEST['fil_no'],$_REQUEST['hd_ud']);
    $ck_ck_all=0; 
	foreach($caveat_list   as $row)
    {
		if(!empty($row))
		{
		 
			$caveat_no = $row['caveat_no'];
			$m_dtdesc11=$row['ordchdt'] ?? ''; 
			$p_id=$row['inddep'] ?? '';
			$m_orgcode=$row['org'] ?? '';
			$m_org=$row['orgname'] ?? '';
			$m_fixed=$row['fixed'] ?? '';
			$m_bail=$row['bailno'] ?? '';
			$m_fixed1=$row['fixeddet'] ?? '';
			$m_fbench=$row['bench'] ?? '';
			$m_wptyp=$row['wptype'] ?? '';
			$m_wptyp2=$row['wptype2'] ?? '';
			$m_cat=$row['category'] ?? '';
			$m_ssub=$row['ssub'] ?? '';
			$m_subcat=$row['subcat'] ?? '';
			$m_subcat1=$row['subcat1'] ?? '';
			$m_act=$row['act'] ?? '';
			$m_act1=$row['actcode'] ?? '';
			$m_impg=$row['iopb'] ?? '';
		   $m_kept=$row['caskept'] ?? '';
		   $m_lok1=$row['mlok1'] ?? '';
		   $m_lok2=$row['mlok2'] ?? '';
		   $m_fixed=$row['fixed'] ?? '';
		   $listorder=$row['listorder'] ?? '';
		   $rule_description=$row['rule_description'] ?? '';
		   $rule_code=$row['rule_code'] ?? '';
		   $sub_rule=$row['sub_rule'] ?? '';
			$rule_clause=$row['rule_clause'] ?? '';
			$case_grp=$row['case_grp'] ?? '';
			$casetype_id=$row['casetype_id'] ?? '';
			$tot_court_fee=$row['court_fee'] ?? '';
			$valuation=$row['valuation'] ?? '';
			$desc1=$row['brief_description'] ?? '';
		   $claim_amt=$row['claim_amt'] ?? '';
		   $relief= $row['relief'] ?? '';
		   $max_court_fee=$row['total_court_fee'] ?? '';
	 
		   $rm_skey='';
		  
		  if(!empty($m_dtdesc11))
		  {
			$m_dtdesc11=explode("-",$m_dtdesc11);
			$m_dtdesc1=$m_dtdesc11[2]."/".$m_dtdesc11[1]."/".$m_dtdesc11[0];
			
				if ($m_dtdesc11[2]=="00" && $m_dtdesc11[1]=="00" && $m_dtdesc11[0]=="00")
				{
				 $m_dtdesc1="";
				}
		  }else{
			  $m_dtdesc1="";
		  }
	?>
		<input type="hidden" name="lst_case" id="lst_case" value="<?php echo $casetype_id; ?>"/>
		<input type="hidden" name="hd_diary_nos" id="hd_diary_nos" value="<?php echo $caveat_no; ?>"/>
 
 
			<div width="100%">
				<div style="text-align: center">
					<b><span><?php echo $row['pet_name'];?></span></b>
					<b><span style="color: red">Vs</span></b>
					<b><span><?php echo $row['res_name'];?></span></b>
				</div>
				<div style="text-align: center;margin: 10px 0px 10px 0px">
					<b>Category</b>&nbsp;<input type="radio" name="rdn_sup_oth" id="rdn_supreme" value="S" class="cl_rdn_supreme" checked="checked"/>Supreme Court&nbsp;
					<input type="radio" name="rdn_sup_oth" id="rdn_other" value="H" class="cl_rdn_supreme"/>Other
				</div>
			 
			<table width="100%"  align="center" style="border-collapse: collapse;" cellpadding="5" cellspacing="5">
			 
			<tr>
			 
			 
			<td  colspan="2" style="width: 500px;" style="text-align: center" valign="middle">
				<br/>
				<div style="width: 100%;max-height: 200px;overflow: auto">
					<table id="tb_new" border="1" class="table table-striped table-bordered custom-table" class="rgv1 table_tr_th_w_clr"> 
				   
		  			<thead>
					<tr>
			 
						 <th>
						Check
					</th>
					<th>
					  Category Code
					</th>
					 <th>
						 Main Category
					 </th>
					 <th>
						 Sub Category 1
					</th>
					  <th>
						 Sub Category 2
					</th>
					  <th>
						 Sub Category 3
					</th>
					</tr> </thead>
				   
				  <?php
				 
				 $snos=1;
			 
					 
					 // $snos++;

				 
				/* $sql_po=  mysql_query("Select submaster_id ,od_cat,subject_description,category_description,subject_sc_old,category_sc_old ,
					sub_name1, sub_name4 , sub_name2, sub_name3,flag,subcode2_hc,subcode1_hc,subcode3_hc from mul_category_caveat a join submaster b on a.submaster_id=b.id 
					where caveat_no = '$caveat_no' and a.display='Y' and b.display='Y'") 
						or die("Error: ".__LINE__.mysql_error()); */
					
				$CaveatSubmasterList = $CaveatModel->getSubmaster($caveat_no);
				 if(!empty($CaveatSubmasterList))
				 {
					foreach($CaveatSubmasterList  as $row1)
					{
						 
						?>
							<tr id="tr_uo<?php echo $snos; ?>" >
								  <td>
									  <input type="checkbox" name="hd_chk_add<?php echo $snos; ?>" id="hd_chk_add<?php echo $snos; ?>" 
											 onclick="getDone_upd_cat(this.id);" checked="true"/>
									  <input type="hidden" name="hd_color<?php echo $snos; ?>" id="hd_color<?php echo $snos; ?>" value="<?php echo $row1['flag'] ?>"/>
								  </td>
								  <td>
								 <span class="<?php if($row1['flag']=='s'){ echo 'cl_supreme'; }else{ echo 'cl_other';}?>" >
									 <?php  
									  if($row1['flag']=='s')
											echo $row1['category_sc_old'] ; 
										else 
											echo $row1['subcode1_hc'].$row1['subcode2_hc'].$row1['subcode3_hc'];
									 ?>
								 <input id="hd_sp_c<?php echo $snos; ?>" type="hidden" value=""/>
								 <input id="hd_sp_d<?php echo $snos; ?>" type="hidden" value="<?php  echo $row1['submaster_id']  ?>"/></span>
								</td>  
							<td>
								 <span class="<?php if($row1['flag']=='s'){ echo 'cl_supreme'; }else{ echo 'cl_other';}?>" ><?php  echo $row1['sub_name1']; ?></span>
								<input id="hd_sp_a<?php echo $snos; ?>" type="hidden" value="<?php echo $row1['subcode'] ?? '' ?>"/>
							</td>
							 <td>
								  <span class="<?php if($row1['flag']=='s'){ echo 'cl_supreme'; }else{ echo 'cl_other';}?>" ><?php 
								 if($row1['sub_name4']==$row1['sub_name1'])
								 {
										echo '-';
								 }else 
									{
										if($row1['sub_name2']!='')
											echo $row1['sub_name2'];
										else if($row1['sub_name3']!='')
											echo $row1['sub_name3'];
										else if($row1['sub_name4']!='')
											echo $row1['sub_name4'];
										else 
											echo '-';
									}
								 ?></span>
								 <input id="hd_sp_b<?php echo $snos; ?>" type="hidden" value="  <?php echo $row1['subcode1'] ?? '' ?>"/>
							 </td>
							 <td>
								  <span class="<?php if($row1['flag']=='s'){ echo 'cl_supreme'; }else{ echo 'cl_other';}?>" ><?php
							  
								  if($row1['sub_name3']=='' && $row1['sub_name2']!='' )
										echo $row1['sub_name4'];
									else if( $row1['sub_name3']!='' && $row1['sub_name2']!='')
										echo $row1['sub_name3'];
									else 
										echo '-';
								 ?></span>
							 </td>
							 <td>
								  <span class="<?php if($row1['flag']=='s'){ echo 'cl_supreme'; }else{ echo 'cl_other';}?>" ><?php
								  if($row1['sub_name4']!='' && $row1['sub_name4']!='' && $row1['sub_name3']!='' && $row1['sub_name2']!='')
										echo $row1['sub_name4'];
									else 
										echo '-';
								 ?></span>
							 </td>
							 </tr>
						<?php
						$snos++;
					}
				 }
					?>
					 <input type="hidden" name="hd_ssno" id="hd_ssno" value="<?php echo $snos-1; ?>"/>
					  <input  type="hidden" name="hd_co_tot" id="hd_co_tot" value="<?php echo $snos-1; ?>"/>
				</table>
					</div>
			</td>
		 
			</tr>
			
			<tr>
				<td colspan="6" style="padding-top: 20px;">
					<b>Search Category</b> &nbsp;&nbsp;
					<input class="form-control" type="text" name="txt_search" id="txt_search" style="width: 50%" />
					<div id="sp_mul_rec" style="margin: 10px 0px 10px 0px;max-height: 200px;overflow: auto" >
						<?php
						//$_REQUEST[id_val]='S';
						//include('../scrutiny/get_categories.php');
						//include(APPPATH . 'views/scrutiny/get_categories.php');
						
						$this->include('scrutiny/get_categories');
						?>
					</div>
				</td>
			</tr>
			 
			<?php
			
			$chk_for_act_rs = $CaveatModel->actMainCaveat($caveat_no);
			 
			if(!empty($chk_for_act_rs) && count($chk_for_act_rs) == 0 )
			{
				?>
				<input type="hidden" id="kakshammoolyam" value="1"/>
				<tr id="actsec1">
					<td colspan="4">
						<span>Act</span> 
						<select class="form-control"  id="act1" style="width: 400px;"><option value="">Select</option>
						<?php
						//$sql_act = "select * from act_master where display= 'Y' order by id ASC";
						//$rs_act = mysql_query($sql_act) or die(__LINE__.'->'.mysql_error());             
						$rs_act = $CaveatModel->getActMaster();             
						foreach($rs_act as $row_act)
						{
							echo "<option value='".$row_act['id']."' >".$row_act['act_name']."</option>";
						}
						?>
						</select>
						<span>&nbsp; Section</span>
						<input type="text" id="sec_11" size="3" maxlength="3" onkeypress="return onlynumbers(event);" style="text-align: center" /> 
						<span>(</span><input type="text" id="sec_21" size="3" maxlength="3" style="text-align: center" onkeypress="return slashnot(event);"/><span>)</span>
						<span>(</span><input type="text" id="sec_31" size="3" maxlength="3" style="text-align: center" onkeypress="return slashnot(event);"/><span>)</span>
						<span>(</span><input type="text" id="sec_41" size="3" maxlength="3" style="text-align: center" onkeypress="return slashnot(event);"/><span>)</span> 
						<span>&nbsp;</span><input type="button" id="btnAddAct1" value="New Act" onclick="new_act_button()"/>
						<span>&nbsp;</span><input type="button" id="btnAddSec1" value="New Section" onclick="new_sec_button('1')"/>
					</td>
				</tr>
				<?php
			}
			else
			{
				$toal_act =1;
				if(!empty($chk_for_act_rs))
				{
					foreach($chk_for_act_rs as $act_row)
					{
						//$no_sec = explode('/', $act_row['section']);
						$no_sec = explode(',', $act_row['section']);
						for($ii=0;$ii<sizeof($no_sec);$ii++)
						{
							$sec_name = explode('(', $no_sec[$ii]);
							?>
							<tr id="actsec<?php echo $toal_act?>">
								<td colspan="4">
									<span>Act</span> 
									<select class="form-control"  id="act<?php echo $toal_act?>" style="width: 400px;"><option value="">Select</option>
									<?php
									//$sql_act = "select * from act_master where display= 'Y' order by id ASC";
									//$rs_act = mysql_query($sql_act) or die(__LINE__.'->'.mysql_error());        
									$rs_act = $CaveatModel->getActMaster(); 
									 
									foreach($rs_act as $row_act)
									{
										?>
											<option value="<?php echo $row_act['id'];?>" <?php if($row_act['id']==$act_row['act']) echo "selected";?>><?php echo $row_act['act_name'];?></option>
										<?php
									}
									?>
									</select>
									<span>&nbsp; Section </span>
									<input type="text" id="sec_1<?php echo $toal_act?>" size="3" maxlength="3" onkeypress="return onlynumbers(event);" style="text-align: center" value="<?php  echo (!empty($sec_name[0])) ? $sec_name[0] : ''?>" /> 
									<span>(</span><input type="text" id="sec_2<?php echo $toal_act?>" size="3" maxlength="3" style="text-align: center" value="<?php  echo (!empty($sec_name[1])) ? rtrim($sec_name[1],')') : ''?>" onkeypress="return slashnot(event);"/><span>)</span>
									<span>(</span><input type="text" id="sec_3<?php echo $toal_act?>" size="3" maxlength="3" style="text-align: center" value="<?php  echo (!empty($sec_name[2])) ? rtrim($sec_name[2],')') : '' ?>" onkeypress="return slashnot(event);"/><span>)</span>
									<span>(</span><input type="text" id="sec_4<?php echo $toal_act?>" size="3" maxlength="3" style="text-align: center" value="<?php  echo (!empty($sec_name[3])) ? rtrim($sec_name[3],')') : ''?>" onkeypress="return slashnot(event);"/><span>)</span> 
									<span>&nbsp;</span><input type="button" id="btnAddAct<?php echo $toal_act?>" value="New Act" onclick="new_act_button()"/>
									<span>&nbsp;</span><input type="button" id="btnAddSec<?php echo $toal_act?>" value="New Section" onclick="new_sec_button('<?php echo $toal_act?>')"/>
									<span>&nbsp;</span><input type="button" id="btnDelActSec1" value="Delete" onclick="del_act_sec('<?php echo $_REQUEST['d_no'];?>','<?php echo $_REQUEST['d_yr'];?>','<?php echo $toal_act?>')" style="background-color: #ff6666;color: white"/>
								</td>
							</tr> 
							<?php
							$toal_act++;
						}
					}
				}
				?>
				<input type="hidden" id="kakshammoolyam" value="<?php echo --$toal_act;?>"/>
				<?php 
			}
			?>

			<tr>
				<td align="left">Brief Desc.of IMPUGNED Order/Judgement/Award/Notification etc:</td>
			<td colspan="2">
				<input class="form-control"  align="left" type="text" size="80" name="order" id="order" maxlength="500" value="<?php echo $desc1;?>"/> 
			</td>
			</tr>
			 
			<tr><td align="left">Claim Amount:</td>
				<td <?php if ($m_cat==171) { ?>colspan="2"  <?php ;} else { ?>colspan="3" <?php ;} ?>>
					<input class="form-control"  type="text" style="width: 400px" name="m_camt" id="m_camt" maxlength="9" value="<?php echo $claim_amt;?>" />(Don't use comma)
			  </td>
			  </tr>
			  <tr><td  style="text-align: left">Des of Relief <br/>Claimed</td>
				<td <?php if ($m_cat==171) { ?>colspan="2"  <?php ;} else { ?>colspan="3" <?php ;} ?>>
					<input class="form-control"  type="text" style="width: 400px" name="m_relief" id="m_relief" maxlength="150" value="<?php echo $relief; ?>"/>
			  </td>
			  </tr>
			 

			<tr>
			  <td align="left">Fixed For:</td>
			  <td <?php if ($m_cat==171) { ?>colspan="2"  <?php ;} else { ?>colspan="3" <?php ;} ?>>
				  <table align="left" cellspacing="1" cellpadding="1" border="0"><tr>
						  <td style="width: 415px">
				 
				<select class="form-control"  size="1" name="m_fixed" id="m_fixed" style="width: 400px"> 
					<option value="">Select</option>
					<?php
					$fixedfor = $CaveatModel->getMasterFixedfor();
					if(!empty($fixedfor))
					{
						foreach($fixedfor as $row_fixedfor){
							?>
						<option value="<?php echo $row_fixedfor['id']; ?>" <?php if($m_fixed  == $row_fixedfor['id']) {print "selected";}?>><?php echo $row_fixedfor['fixed_for_desc']; ?></option>
								<?php
						}
					}
					?>
					 
				</select>
				</td>
			<?php 
			/* if($_REQUEST['cs_nm']=="MCRC" || $_REQUEST['cs_nm']=="mcrc" || $_REQUEST['cs_nm']=="CRA" || $_REQUEST['cs_nm']=="cra" || $_REQUEST['cs_nm']=="crr" || $_REQUEST['cs_nm']=="CRR")
			{ */
			?>
			   <td>
				   <span style="color: red">[To be filled only in MCRC-u/s 438,439 AND CRR-Juvenile u/s 53,102 AND CRA-SC/ST u/s 14A]</span>
					<select class="form-control"  size="1" name="m_bail" id="m_bail">
					   <option value="">Select Bail No.</option>
					   <option value="1" <?php if($m_bail == "1") {print "selected";}?>>1st Bail</option>
					   <option value="2" <?php if($m_bail == "2") {print "selected";}?>>2nd Bail</option>
					   <option value="3" <?php if($m_bail == "3") {print "selected";}?>>3rd Bail</option>
					   <option value="4" <?php if($m_bail == "4") {print "selected";}?>>4th Bail</option>
					   <option value="5" <?php if($m_bail == "5") {print "selected";}?>>5th Bail</option>
					   <option value="6" <?php if($m_bail == "6") {print "selected";}?>>6th Bail</option>
					   <option value="7" <?php if($m_bail == "7") {print "selected";}?>>7th Bail</option>
					   <option value="8" <?php if($m_bail == "8") {print "selected";}?>>8th Bail</option>
					   <option value="9" <?php if($m_bail == "9") {print "selected";}?>>9th Bail</option>
					 </select>
				</td>
			<?
			//}
			?>
				</tr>
			</table>
			</td>
			  </tr>
			 
			<tr>
			  <td align="left">Listable Before:</td>
			 
				 <td colspan="3">
				  <?php
					$bl_wa_rp=0; 
					$bench_master = $CaveatModel->getMasterBench();
				  ?>				  
				  <select class="form-control"  size="1" name="m_fbench" id="m_fbench" > 
					<option value="">Select</option>
					<?php
					if(!empty($bench_master))
					{
						foreach($bench_master as $row_master_bench){
							?>
						<option value="<?php echo $row_master_bench['id']; ?>" <?php if($m_fbench == $row_master_bench['id']) {print "selected";}?>><?php echo $row_master_bench['bench_name']; ?></option>
								<?php
						}
					}
					?>
					 
				   
				  </select>
				   
				</td>
			  </tr>
			   
			  <tr>
				  <td>
					  Provision of Law
				  </td>
				  <td>
					  <?php
					 // $pol="Select id,law from caselaw where display='Y' and nature='$case_grp' order by law";
					  //$pol=mysql_query($pol) or die("Error: ".__LINE__.mysql_error());
					  ?>
					  <select class="form-control"  name="ddl_pol" id="txt_pol" style="width: 520px">
						  <option value="">Select</option>
						  <?php
						  
						  $pol = $CaveatModel->getCaseLaw($case_grp);
						  if(!empty($pol))
						  {
									foreach($pol as $row5)
									{
										?>
									<option value="<?php echo $row5['id'] ?>" <?php if($m_act1==$row5['id']) { ?> selected="selected" <?php } ?>><?php echo $row5['law'] ?></option>
						  <?php
									}
						  }
						  ?>
					  </select>
				  </td>
			  </tr>
			  <?php 
			 
			    $tot_fee=0;
			  $ck_ct=0;
			  /*  $court_fee="Select court_fee,flag,security_deposit from m_court_fee where display='Y' and casetype_id='$casetype_id' and submaster_id=0 and case_law='0' AND ((
			  '$c_date'  BETWEEN from_date
				AND to_date
			  ) or (from_date<= '$c_date' and to_date= '0000-00-00'))";
			  $court_fee=mysql_query($court_fee) or die("Error: ".__LINE__.mysql_error()); */
			  
			  $court_fee= $CaveatModel->getMasterCourtFee($casetype_id);
					  if(!empty($court_fee))
					  {
						 $ck_ct=1;
						  if($m_cat==0)
							{
								$r_court_fee=$court_fee;
								if($r_court_fee['flag']=='P')
								  {
									 /*  $s_party="Select count(*) from party where diary_no = '$caveat_no'  and pflag='P' 
											  and pet_res='P'";
									  $s_party=mysql_query($s_party) or die("Error: ".__LINE__.mysql_error());
									  $res_s_party=mysql_result($s_party,0);
									   */
									  $res_s_party= $CaveatModel->getparty($caveat_no);
									  
									 $max_court_fee= $tot_fee=$res_s_party*$r_court_fee['court_fee'];
								  }						  
									else if($r_court_fee['flag']=='')
								  {
									/* $jud_challanged="Select count(lower_court_id) from lowerct where diary_no='$caveat_no' and lw_display='Y' and is_order_challenged='Y'";
									 $jud_challanged=mysql_query($jud_challanged) or die("Error: ".__LINE__.mysql_error());
									$res_jud_challanged=mysql_result($jud_challanged,0);
									 */
										$res_jud_challanged= $CaveatModel->getLowerct($caveat_no);
									
									  $max_court_fee=  $tot_fee=($r_court_fee['court_fee']*$res_jud_challanged)+$r_court_fee['security_deposit'];
								  }
							}
							else 
							  {
								 $max_court_fee= $tot_fee=$tot_court_fee;
							  }
					    }
			  
					 if($ck_ct==0)
					 {
						 $max_court_fee=$tot_fee=$tot_court_fee;
					 }
			  ?>
			  
			  <tr id="tr_val" <?php if($valuation==0) { ?> style="display: none" <?php } ?>>
				  <td>
					  Valuation
				  </td>
				  <td>
					  <input class="form-control"  type="text" name="txt_valuation" id="txt_valuation" size="8" value="<?php echo $valuation;  ?>"/>
					
				  </td>
			  </tr>
			  
			  <tr id="tr_court_fee_tot" <?php if($max_court_fee==0) { ?> style="display: none" <?php } ?>>

				  <td>
					 Total Court Fee 
				  </td>
				  <td>
					  <input class="form-control"  type="text" name="txt_court_fee_tot" id="txt_court_fee_tot" size="8" value="<?php echo $max_court_fee; ?>"/>
					
				  </td>
			  </tr>
			  
			  <tr id="tr_court_fee" <?php if($tot_fee==0) { ?> style="display: none" <?php } ?>>
			<!--  <tr>-->
				  <td>
					  Court Fee Paid
				  </td>
				  <td>
					  <input class="form-control"  type="text" name="txt_court_fee" id="txt_court_fee" size="8" value="<?php echo $tot_fee; ?>"/>
					  <input class="form-control"  type="hidden" name="hd_ck_cf_natue" id="hd_ck_cf_natue" value="<?php echo $ck_ct; ?>"/>
				  </td>
			  </tr>
			  <tr>
				  <td>
					  Sensitive Case 
				  </td>
				   <td>
					   <?php
					  /*  $sen_case="Select reason from  sensitive_cases where diary_no='$caveat_no' and display='Y'";
					   $sen_case=mysql_query($sen_case) or die("Error: ".__LINE__.mysql_error());
					    */
					   $sen_case = $CaveatModel->getSensitiveCases($caveat_no);
					   
					   $res_sen_case='';
					   if(!empty($sen_case))
					   {
						   $res_sen_case_cs = $sen_case;
						   $res_sen_case=$res_sen_case_cs['reason'];
					   }
					   ?>
					   <input type="checkbox" name="chk_sen_cs" id="chk_sen_cs" <?php if($res_sen_case!='') { ?> checked="checked"  <?php  } ?>/> &nbsp;&nbsp;
					   <input class="form-control"  type="text" name="txt_sen_case" id="txt_sen_case" style="<?php  if($res_sen_case=='') { ?> display: none;<?php } ?>width:50%" maxlength="200" value="<?php echo $res_sen_case; ?>"/>
				  </td>
			  </tr>
			 
			  <tr>
				  
				  <td>
					  <b>Search Keyword</b>&nbsp;&nbsp; <input name="txt_src_key" id="txt_src_key" style="width: 50%"  type="text">
					   <div style="margin: 10px 0px 10px 0px;max-height: 200px;overflow: auto;" id="dv_src_keyword" align="center">
						   <table class="table_tr_th_w_clr">
							   <tr>
								   <th>
									  Check
								   </th>
								   <th>
									  Keyword Description
								   </th>
							   </tr>
						   
						   <?php
						   /* $s_keyword="Select id,keyword_description from ref_keyword where is_deleted='f'";
						   $s_keyword=mysql_query($s_keyword) or die("Error: ".__LINE__.mysql_error());
						    */
						   
						   $s_keyword = $CaveatModel->getMasterKeyword();
						   $s_k=0;
						   if(!empty($s_keyword))
						   {
							   foreach ($s_keyword as $r_kw) 
							   {
								   ?>
								   <tr>
									   <td>
										   <input type="checkbox" name="chk_keyword<?php echo $s_k; ?>" id="chk_keyword<?php echo $s_k; ?>" value="<?php echo $r_kw['id']; ?>" class="cl_keyword"/>
									   </td>
										<td>
										   <span id="sp_k_des<?php echo $s_k; ?>"><?php echo $r_kw['keyword_description']; ?></span>
									   </td>
								   </tr>
							  
							   <?php
							   $s_k++;
							   }
						   }
						   ?>
						   </table>
					   </div>
				  </td>
				  <td >
					  <?php
					 /*  $keyword="Select keyword_id,keyword_description from ec_keyword a join ref_keyword b on a.keyword_id=b.id where display='Y' 
						  and diary_no = '$caveat_no'  and is_deleted='f'";
					  $keyword=mysql_query($keyword) or die("Error: ".__LINE__.mysql_error()); */
					  ?>
					  <div id="dv_sel_keyword" style="margin:36px 0px 10px 0px;max-height: 200px;overflow: auto">
						  <?php
						  
						   $keyword = $CaveatModel->getEcKeyword($caveat_no);
						  
						   $key_inc=0;
						  if(!empty($keyword) && count($keyword) > 0)
						  {
								?>
						  <table id="tb_a_keyword" class="table_tr_th_w_clr" align="center" style="width: 80%">
							<tr>
								<th style="width: 10%">
									Check
								</th>
								<th>
									Keyword Description
								</th>
							</tr>
						  <?php
							if(!empty($keyword))
							{
							  foreach ($keyword as $row2)
							  {
								  ?>
							  <tr id="tr_a_keyword<?php echo $key_inc; ?>">
									<td>
										<input type="checkbox" name="chk_a_keyword<?php echo $key_inc; ?>" id="chk_a_keyword<?php echo $key_inc; ?>"
											   value="<?php echo $row2['keyword_id']; ?>" checked="checked" class="added_keyword"/>
									</td>
									<td>
										<?php echo $row2['keyword_description']; ?>
									</td>
								</tr>
								  <?php
								  $key_inc++;
								  }
							}
								  ?>
							</table>
							<?php
							  }
							  ?>
					  </div>
					  <input type="hidden" name="hd_max_keyword" id="hd_max_keyword" value="<?php echo $key_inc; ?>"/>
					  <input type="hidden" name="hd_rem_keyword" id="hd_rem_keyword"/>
				  </td>
			  </tr>

			  <tr>
				  <td colspan="4" style="text-align: center">
					  <input class="button" type="button" onclick="sav_mul_cat();" value="SUBMIT" name="ok2" id="ok2"/>
					  
				  </td>
			  </tr>
			</table>
			</div>
<?php
		}
	}
}else{?>
	<div style="text-align: center"><b>Diary No. not found</b></div>
<?php } ?>