<style>
    body {
        font-size: 12px;
    }

    th,
    td {
        padding: 5px;
        text-align: left;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php if (!file_exists($filePath)) {  ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="prnnt">
                            <div align="center" style="font-size:12px;">
                                <img src="<?= base_url('images/scilogo.png') ?>" width="50" height="80" />
                                <br />SUPREME COURT OF INDIA<br />
                                <b>SECTION LIST<br /><br />DATE OF LISTING : <?= date('d-m-Y', strtotime($list_date)) ?></b>
                            </div>

                            <table border="1">
                               
                                    <?php 
									   $psrno = "1";
										$clnochk = 0;
										$subheading_rep = "0";
										$mnhead_print_once = 1;
									    $jcd_rp = '';
									
									if (!empty($results)){ ?>
                                        <?php  $sno = 1; $output = '';
                                        foreach ($results as $row){
											 $coram = $row['coram'];
												$fix_dt = date('d-m-Y', strtotime($row['next_dt']));
												$main_supp_fl = $row['main_supp_flag'];
												$diary_no = $row['diary_no'];
												if ($mainhead == "F") {
													$retn = $row["sub_name1"];
													if ($row["sub_name2"])
														$retn .= " - " . $row["sub_name2"];
													if ($row["sub_name3"])
														$retn .= " - " . $row["sub_name3"];
													if ($row["sub_name4"])
														$retn .= " - " . $row["sub_name4"];
												} else {
													$subheading = $row["res_name"];
												}

										if ($mnhead_print_once == 1) {
											if ($mainhead == 'M' AND $subheading != "FOR JUDGEMENT" AND $subheading != "FOR ORDER") {
												if ($row['board_type'] == 'C') {
													$print_mainhead = "CHAMBER MATTERS";
												} else {
													$print_mainhead = "DRAFT LIST OF MISCELLANEOUS MATTERS";
												}
											}
											if ($mainhead == 'F')
												$print_mainhead = "REGULAR HEARING";
											if ($mainhead == 'L')
												$print_mainhead = "LOK ADALAT HEARING";
											if ($mainhead == 'S')
												$print_mainhead = "MEDIATION HEARING";
											if ($main_supp_fl == "2") {
												echo "<tr><td colspan='4' style='font-size:13px;font-weight:bold; text-decoration:underline; text-align:center;'>SUPPLEMENTARY LIST</td></tr>";
											}
											?>
											<tr>
												<th colspan="4"
													style="text-align: center; text-decoration: underline;"><?php if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") {
														echo $print_mainhead;
													} ?></th>
											</tr>
											<tr style="font-weight: bold; background-color:#cccccc;">
												<td>SNo.</td>
												<td>Case No.</td>
												<td>Petitioner / Respondent</td>
												<td>
													<?php if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") { ?>
														Petitioner/Respondent Advocate
													<?php } ?>
												</td>
											</tr>

											<?php
											$mnhead_print_once++;
										}
										
										if ($subheading != $subheading_rep) {
               							   if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") {
													echo "<tr><td colspan='4' style='font-size:12px; font-weight:bold; text-decoration:underline; text-align:center;'>" . $subheading . "</td></tr>";
													$subheading_rep = $subheading;
												}
										}
                
										if ($row['diary_no'] == $row['main_key'] OR $row['main_key'] == 0) {
											$print_srno = $psrno;
											$con_no = "0";
											$is_connected = "";
										} else if ($row['listed'] == 1) {
											$is_connected = "<span style='color:red;'>Connected</span><br/>";
										}

										$m_f_filno = $row['active_fil_no'];
										$m_f_fil_yr = $row['active_reg_year'];

										$filno_array = explode("-", $m_f_filno);
										if(count($filno_array)>2){
											if ($filno_array[1] == $filno_array[2]) {
												$fil_no_print = ltrim($filno_array[1], '0');
											} else {
												$fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
											}
										}elseif(count($filno_array)>1){
											if ($filno_array[0] == $filno_array[1]) {
												$fil_no_print = ltrim($filno_array[0], '0');
											} else {
												$fil_no_print = ltrim($filno_array[0], '0') . "-" . ltrim($filno_array[1], '0');
											}
										}else{
											$fil_no_print = ltrim($filno_array[0], '0');
										}
										
										
										if ($row['active_fil_no'] == "") {
											$comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
										}else {
											 $comlete_fil_no_prt = $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr;
										}
										
										$padvname = "";
										$radvname = "";
										$resultsadv = $model->get_adv_details($row["diary_no"]); 
										
										if (!empty($resultsadv) > 0) {
												$radvname = ''; $padvname=''; $impldname ='';
												foreach($resultsadv as $k=>$rowadvs){
												if($k==0){$kk='';}else{$kk=',';}
												   if($rowadvs["r_n"]!=''){
													   $radvname .= $kk.$rowadvs["r_n"];
												   }
												   if($rowadvs["p_n"]!=''){
												      $padvname .= $kk.$rowadvs["p_n"];
												   }
												   if($rowadvs["i_n"]!=''){
													   $impldname .= $kk.$rowadvs["i_n"];
												   }
												}
										}
										
										if ($row['pno'] == 2) {
											$pet_name = $row['pet_name'] . " AND ANR.";
										} else if ($row['pno'] > 2) {
											$pet_name = $row['pet_name'] . " AND ORS.";
										} else {
											$pet_name = $row['pet_name'];
										}
										if ($row['rno'] == 2) {
											$res_name = $row['res_name'] . " AND ANR.";
										} else if ($row['rno'] > 2) {
											$res_name = $row['res_name'] . " AND ORS.";
										} else {
											$res_name = $row['res_name'];
										}
										
										if (($row['section_name'] == null OR $row['section_name'] == '') AND $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
												if ($row['active_reg_year'] != 0)
													$ten_reg_yr = $row['active_reg_year'];
												else
													$ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

												if ($row['active_casetype_id'] != 0)
													$casetype_displ = $row['active_casetype_id'];
												else if ($row['casetype_id'] != 0)
													$casetype_displ = $row['casetype_id'];
												    $section_ten_rs =  $model->section_ten_rs_details($casetype_displ, $ten_reg_yr, $row['ref_agency_state_id']); 
												
													if (count($section_ten_rs) > 0) {
														$section_ten_row = mysql_fetch_array($section_ten_rs);
														$row['section_name'] = $section_ten_row["section_name"];
													}
										}
									if ($is_connected != '') {
												$print_srno = "";
									} else {
										$print_srno = $print_srno;
										$psrno++;
								}
								
							$output .= "<tr><td>$print_srno</td><td rowspan=2>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $row['section_name'] ."<br/>" . $row['name'] . "</td><td>" . $pet_name . "</td><td>" . str_replace(",", ", ", trim($padvname, ","));
							$output .= "</td></tr>";
							$output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
							$output .= "</td></tr>";
							$output .= "<tr><td></td><td></td><td";

							$output .= ">" . $res_name . "</td><td>" . str_replace(",", ", ", trim($radvname, ","));
							if ($impldname) {
								$output .= "<br/>" . str_replace(",", ", ", trim($impldname, ","));
							}
						$output .= "</td></tr>";
						if ($mainhead == "M" OR $mainhead == "F") {
							$output .= "<tr><td colspan='2'></td><td colspan='2' style='font-weight:bold; color:blue;'>";
							if ($row['listorder'] == '4' OR $row['listorder'] == '5')
								$output .= "{" . $row['purpose'] . " for $fix_dt } ";

							$output .= get_cl_brd_remark($diary_no) . "</td></tr>";
						}

					echo $output;
					$output = "";
			} ?>
           </table>
        <?php } else {
            echo '<p style="text-align:center;"><b>No Records Found</b></p>';
         } ?>
		 
		 <br>
        <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR/><?php date_default_timezone_set('Asia/Kolkata');
                echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
        <br>
        <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div></div></div></div>
<?php }
    else {       
            $content = file_get_contents($path_dir);
			 echo str_replace("/home/judgment/cl/scilogo.png", "scilogo.png", $content);
    }?>
	
</div>
</div>
<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
       <?php 
    if (!file_exists($filePath)) { ?>
             <button class="btn btn-primary" name="prnnt1" type="button" id="ebublish">e-Publish</button>
 <?php } else{ ?>
        <h3 class="text-success">Already Published</h3>
 <?php } ?>
        <button class="btn btn-primary" name="prnnt1" type="button" id="prnnt1">Print</button>
</div>
<section>
	