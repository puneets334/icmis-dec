
<style type="text/css">
        div { page-break-after:auto }

		table {
			border-collapse: collapse;
		}
		caption {
			background: #D3D3D3;
		}
		th {
			background: #A7C942;
			border: 1px solid #98BF21;
			color: #ffffff;
			font-weight: bold;
			text-align: left;
		}
		td {
			border: 1px solid #98BF21;
			text-align: left;
			font-weight: normal;
			color: #000000;
		}
		tr:nth-child(odd) {
			background: #ffffff;
		}
		tbody tr:nth-child(odd) th {
			background: #ffffff;
			color: #000000;
		}
		tr:nth-child(even) {
			background: #EAF2D3;
		}
		tbody tr:nth-child(even) th {
			background: #EAF2D3;
			color: #000000;
		}
		#target {
			width: 400px;
			height: 200px;
		}
	</style>
<input name="prnnt1" type="button" id="prnnt1" value="Print" style="margin-left: 1%;">
<div id="prnnt" style="font-size:12px;">
<table border="0" width="100%" style="font-size:12px; text-align: left; background: #A7C942;" cellspacing=0>
            <thead>
            <tr>
                <th colspan="4" style="text-align: center;">
                    SUPREME COURT OF INDIA<BR>CASE LOAD OF PRE-ADMISSION CASES TO BE LISTED BEFORE THE HON'BLE COURTS THE DAILY CAUSE LIST FOR WEEK : <?php echo date('d-m-Y', strtotime($_POST['list_dt'])); ?> AND <?php echo date('d-m-Y', strtotime($_POST['list_dt_to'])); ?>
                    <br>BREAK-UP OF CASES INCLUDED IN LIST AND NOT INCLUDED IN THE LIST BEING SURPLUS MATTERS IS AS FOLLOWS :
                   
                </th>
            </tr>
            </thead>
        </table>
    <?php 
    
    
    if (isset($spread_out_certificate)) { ?>
        <table width="100%" cellpadding='1' cellspacing='0' border='1'
            style=" border-collapse:collapse; border-color:1px solid #A7C942; font-size:11px; table-layout: fixed; margin-left: 1%;"
            cellspacing=0>
            <tr>
                <td rowspan="2" width="5%" style="text-align: center;font-weight: bold;">SNo</td>
                <td rowspan="2" width="30%" style="text-align: center;font-weight: bold;">HEAD</td>
                <td colspan="6" width="30%" style="text-align: center;font-weight: bold;">LISTED</td>
                <td colspan="6" width="30%" style="text-align: center;font-weight: bold;">NOT LISTED</td>
                <td width="5%" style="text-align: center; font-weight:bold;">TOTAL</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">Court Dt</td>
                <td style="text-align: center; font-weight: bold;">Fresh/ Fresh Adj.</td>
                <td style="text-align: center; font-weight: bold;">After Week</td>
                <td style="text-align: center; font-weight: bold;">Comp Gen Fix Dt as per scheme</td>
                <td style="text-align: center; font-weight: bold;">Comp Dt</td>
                <td style="text-align: center; font-weight: bold;">Total</td>
                <td style="text-align: center; font-weight: bold;">Court Dt</td>
                <td style="text-align: center; font-weight: bold;">Fresh/ Fresh Adj.</td>
                <td style="text-align: center; font-weight: bold;">After Week</td>
                <td style="text-align: center; font-weight: bold;">Comp Gen Fix Dt as per scheme</td>
                <td style="text-align: center; font-weight: bold;">Comp Dt</td>
                <td style="text-align: center; font-weight: bold;">Total</td>
                <td style="text-align: center; font-weight: bold;">Grand Total</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold; ">1</td>
                <td style="text-align: center; font-weight: bold; ">2</td>
                <td style="text-align: center; font-weight: bold; ">3</td>
                <td style="text-align: center; font-weight: bold; ">4</td>
                <td style="text-align: center; font-weight: bold; ">5</td>
                <td style="text-align: center; font-weight: bold;">6</td>
                <td style="text-align: center; font-weight: bold; ">7</td>
                <td style="text-align: center; font-weight: bold; ">8</td>
                <td style="text-align: center; font-weight: bold; ">9</td>
                <td style="text-align: center; font-weight: bold; ">10</td>
                <td style="text-align: center; font-weight: bold; ">11</td>
                <td style="text-align: center; font-weight: bold; ">12</td>
                <td style="text-align: center; font-weight: bold; ">13</td>
                <td style="text-align: center; font-weight: bold; ">14</td>
                <td style="text-align: center; font-weight: bold; ">15</td>
            </tr>
            <?php   
             $sno = 1;  
             $t_fd_list = 0;
             $t_frs_list = 0;
             $t_aw_list = 0;
             $t_imp_ia_list = 0;
             $t_oth_list = 0;
             $t_listed = 0;
             $t_fd_not_listed = 0;
             $t_frs_not_listed = 0;
             $t_aw_not_listed = 0;
             $t_imp_ia_not_listed = 0;
             $t_oth_not_listed = 0;
             $t_not_listed = 0;
             $gd = 0;
            foreach($spread_out_certificate as $row){   
                $sno1 = $sno % 2;
                
                if($sno == 1 OR $sno == 2){ ?> 
<tr style=" background: #ffffff;" >        
                <?php } else if($sno >= 3 AND $sno <= 10){ 
     ?> 
<tr style=" background: #d7d4d4" >        
                <?php } else { ?>
<tr style=" background: #ffffff;" >
                <?php        
                }       
      ?>
        <td align="center" style='vertical-align: top;'><?php echo $sno++; ?></td>
        <td align="center" style='text-align: left; vertical-align: top;'><?php echo $row['stagename']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['fd_list']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['frs_list']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['aw_list']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['imp_ia_list']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['oth_list']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['listed']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['fd_not_listed']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['frs_not_listed']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['aw_not_listed']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['imp_ia_not_listed']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['oth_not_listed']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['not_listed']; ?></td>
        <td align="center" style='vertical-align: top;'><?php echo $row['listed'] + $row['not_listed']; ?></td>
                </tr>
                <?php
                 $t_fd_list += $row['fd_list']; 
                 $t_frs_list += $row['frs_list']; 
                 $t_aw_list += $row['aw_list']; 
                 $t_imp_ia_list += $row['imp_ia_list']; 
                 $t_oth_list += $row['oth_list']; 
                 $t_listed += $row['listed']; 
                 $t_fd_not_listed += $row['fd_not_listed']; 
                 $t_frs_not_listed += $row['frs_not_listed']; 
                 $t_aw_not_listed += $row['aw_not_listed']; 
                 $t_imp_ia_not_listed += $row['imp_ia_not_listed']; 
                 $t_oth_not_listed += $row['oth_not_listed']; 
                 $t_not_listed += $row['not_listed']; 
                 $gd += $row['listed'] + $row['not_listed']; 

            }//END OF WHILE LOOP
            ?>
                <tr style="background: #918788;"><td colspan="2" align="right"> TOTAL </td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_fd_list; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_frs_list; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_aw_list; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_imp_ia_list; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_oth_list; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_listed; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_fd_not_listed; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_frs_not_listed; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_aw_not_listed; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_imp_ia_not_listed; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_oth_not_listed; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $t_not_listed; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;'><?php echo $gd; ?></td>
                </tr>  
                </table>

        </table>
<?php 
        } ?>
</div>
<?php if (isset($spread_out_certificate)) { 
    
    foreach($spread_out_second as $ro1){  
    ?>

    <div>
                    <br><br>
                <h3>Not Listed Reason:</h3>
                <table align="left" width="85%" border="1px;" style=" border-collapse:collapse; border-color:black; font-size:12px; table-layout: fixed;" cellspacing=0>
                    <tr>
                        <td width="22%"><b>Description</b></td>
                        <td width="13%"><b>Court Dt (Fix, MM, NW/WC)</b></td>
                        <td width="13%"><b>Fresh/ Fresh Adj.</b></td>
                        <td width="13%"><b>After Week</b></td>
                        <td width="13%"><b>Comp Gen Fix Dt as per scheme (Imp. IA)</b></td>
                        <td width="13%"><b>Comp Dt (Not Taken/Adj./Notice etc)</b></td>
                        <td width="13%"><b>Total Not Listed</b></td>
                    </tr>
                    <tr>
                        <td>Not to list till dispose of other Diary</td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_disp&purpose=f'";?> target='_blank'>
                            <?php echo $ro1['fd_depend_on_other_diary']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_disp&purpose=fr'";?> target='_blank'>
                            <?php echo $ro1['frs_depend_on_other_diary']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_disp&purpose=aw'";?> target='_blank'>
                            <?php echo $ro1['aw_depend_on_other_diary']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_disp&purpose=imp'";?> target='_blank'>
                            <?php echo $ro1['imp_ia_depend_on_other_diary']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_disp&purpose=cmp'";?> target='_blank'>
                            <?php echo $ro1['comp_depend_on_other_diary']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_disp&purpose=all'";?> target='_blank'>
                            <?php
                                echo $ro1['fd_depend_on_other_diary'] + $ro1['frs_depend_on_other_diary'] + $ro1['aw_depend_on_other_diary'] + $ro1['imp_ia_depend_on_other_diary'] + $ro1['comp_depend_on_other_diary']; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Special Bench</td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=spl&purpose=f'";?> target='_blank'>
                            <?php echo $ro1['fd_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=spl&purpose=fr'";?> target='_blank'>
                            <?php echo $ro1['frs_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=spl&purpose=aw'";?> target='_blank'>
                            <?php echo $ro1['aw_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=spl&purpose=imp'";?> target='_blank'>
                            <?php echo $ro1['imp_ia_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=spl&purpose=cmp'";?> target='_blank'>
                            <?php echo $ro1['comp_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=spl&purpose=all'";?> target='_blank'>
                            <?php echo $ro1['fd_before_jud'] + $ro1['frs_before_jud'] + $ro1['aw_before_jud'] + $ro1['comp_before_jud'] + $ro1['imp_ia_before_jud']; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>AOR Case Not to list before Judge</td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_before&purpose=f'";?> target='_blank'>
                            <?php echo $ro1['fd_not_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_before&purpose=fr'";?> target='_blank'>
                            <?php echo $ro1['frs_not_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_before&purpose=aw'";?> target='_blank'>
                            <?php echo $ro1['aw_not_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_before&purpose=imp'";?> target='_blank'>
                            <?php echo $ro1['imp_ia_not_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_before&purpose=cmp'";?> target='_blank'>
                            <?php echo $ro1['comp_not_before_jud']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=not_before&purpose=all'";?> target='_blank'>
                            <?php echo $ro1['fd_not_before_jud'] + $ro1['frs_not_before_jud'] + $ro1['aw_not_before_jud'] + $ro1['imp_ia_not_before_jud'] + $ro1['comp_not_before_jud']; ?>
                            </a>
                        </td>

                    </tr>
                    <tr>
                        <td>Defective Category</td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=defect&purpose=f'";?> target='_blank'>
                            <?php echo $ro1['fd_defect_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=defect&purpose=fr'";?> target='_blank'>
                                <?php echo $ro1['frs_defect_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=defect&purpose=aw'";?> target='_blank'>
                            <?php echo $ro1['aw_defect_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=defect&purpose=imp'";?> target='_blank'>
                            <?php echo $ro1['imp_ia_defect_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=defect&purpose=cmp'";?> target='_blank'>
                            <?php echo $ro1['comp_defect_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=defect&purpose=all'";?> target='_blank'>
                            <?php echo $ro1['fd_defect_cat'] + $ro1['frs_defect_cat'] + $ro1['aw_defect_cat'] + $ro1['imp_ia_defect_cat'] + $ro1['comp_defect_cat']; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Constitution Bench</td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=constution&purpose=f'";?> target='_blank'>
                            <?php echo $ro1['fd_const_spl_bench']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=constution&purpose=fr'";?> target='_blank'>
                            <?php echo $ro1['frs_const_spl_bench']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=constution&purpose=aw'";?> target='_blank'>
                            <?php echo $ro1['aw_const_spl_bench']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=constution&purpose=imp'";?> target='_blank'>
                                <?php echo $ro1['imp_ia_const_spl_bench']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=constution&purpose=cmp'";?> target='_blank'>
                            <?php echo $ro1['comp_const_spl_bench']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=constution&purpose=all'";?> target='_blank'>
                            <?php echo $ro1['fd_const_spl_bench'] + $ro1['frs_const_spl_bench'] + $ro1['aw_const_spl_bench'] + $ro1['imp_ia_const_spl_bench'] + $ro1['comp_const_spl_bench']; ?>
                            </a>
                        </td>

                    </tr>
                    <tr>
                        <td>Category Not Mentioned</td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=cat_not&purpose=f'";?> target='_blank'>
                            <?php echo $ro1['fd_cat_blank']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=cat_not&purpose=fr'";?> target='_blank'>
                            <?php echo $ro1['frs_cat_blank']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=cat_not&purpose=aw'";?> target='_blank'>
                            <?php echo $ro1['aw_cat_blank']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=cat_not&purpose=imp'";?> target='_blank'>
                            <?php echo $ro1['imp_ia_cat_blank']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=cat_not&purpose=cmp'";?> target='_blank'>
                            <?php echo $ro1['comp_cat_blank']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=cat_not&purpose=all'";?> target='_blank'>
                            <?php echo $ro1['fd_cat_blank'] + $ro1['frs_cat_blank'] + $ro1['aw_cat_blank'] + $ro1['imp_ia_cat_blank'] + $ro1['comp_cat_blank']; ?>
                            </a>
                        </td>

                    </tr>

                        <td>Short Category Matters</td>
                        <td>
                                <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=short&purpose=f'";?> target='_blank'>
                                <?php echo $ro1['fd_short_cat']; ?>
                                </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=short&purpose=fr'";?> target='_blank'>
                            <?php echo $ro1['frs_short_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=short&purpose=aw'";?> target='_blank'>
                            <?php echo $ro1['aw_short_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=short&purpose=imp'";?> target='_blank'>
                            <?php echo $ro1['imp_ia_short_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=short&purpose=cmp'";?> target='_blank'>
                            <?php echo $ro1['comp_short_cat']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=short&purpose=all'";?> target='_blank'>
                            <?php echo $ro1['fd_short_cat'] + $ro1['frs_short_cat'] + $ro1['aw_short_cat'] + $ro1['imp_ia_short_cat'] + $ro1['comp_short_cat']; ?>
                            </a>
                        </td>

                    </tr>
                    <tr>
                        <td>Excess Matters</td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=excess&purpose=f'";?> target='_blank'>
                            <?php echo $fd_extra_spread = $ro1['fd_tot'] - ($ro1['fd_depend_on_other_diary'] + $ro1['fd_before_jud'] + $ro1['fd_not_before_jud'] + $ro1['fd_defect_cat'] + $ro1['fd_const_spl_bench'] + $ro1['fd_cat_blank'] + $ro1['fd_short_cat']); ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=excess&purpose=fr'";?> target='_blank'>
                            <?php echo $frs_extra_spread = $ro1['frs_tot'] - ($ro1['frs_depend_on_other_diary'] + $ro1['frs_before_jud'] + $ro1['frs_not_before_jud'] + $ro1['frs_defect_cat'] + $ro1['frs_const_spl_bench'] + $ro1['frs_cat_blank'] + $ro1['frs_short_cat']); ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=excess&purpose=aw'";?> target='_blank'>
                            <?php echo $aw_extra_spread = $ro1['aw_tot'] - ($ro1['aw_depend_on_other_diary'] + $ro1['aw_before_jud'] + $ro1['aw_not_before_jud'] + $ro1['aw_defect_cat'] + $ro1['aw_const_spl_bench'] + $ro1['aw_cat_blank'] + $ro1['aw_short_cat']); ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=excess&purpose=imp'";?> target='_blank'>
                            <?php echo $imp_ia_extra_spread = $ro1['imp_ia_tot'] - ($ro1['imp_ia_depend_on_other_diary'] + $ro1['imp_ia_before_jud'] + $ro1['imp_ia_not_before_jud'] + $ro1['imp_ia_defect_cat'] + $ro1['imp_ia_const_spl_bench'] + $ro1['imp_ia_cat_blank'] + $ro1['imp_ia_short_cat']); ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=excess&purpose=cmp'";?> target='_blank'>
                            <?php echo $comp_extra_spread = $ro1['comp_tot'] - ($ro1['comp_depend_on_other_diary'] + $ro1['comp_before_jud'] + $ro1['comp_not_before_jud'] + $ro1['comp_defect_cat'] + $ro1['comp_const_spl_bench'] + $ro1['comp_cat_blank'] + $ro1['comp_short_cat']); ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=excess&purpose=all'";?> target='_blank'>
                            <?php echo $fd_extra_spread + $frs_extra_spread + $aw_extra_spread + $imp_ia_extra_spread + $comp_extra_spread; ?>
                            </a>
                        </td>

                    </tr>
                    <tr>
                        <td align="right">Total</td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=tot_tot&purpose=f'";?> target='_blank'>
                            <?php echo $ro1['fd_tot']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=tot_tot&purpose=fr'";?> target='_blank'>
                            <?php echo $ro1['frs_tot']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=tot_tot&purpose=aw'";?> target='_blank'>
                            <?php echo $ro1['aw_tot']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=tot_tot&purpose=imp'";?> target='_blank'>
                            <?php echo $ro1['imp_ia_tot']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=tot_tot&purpose=cmp'";?> target='_blank'>
                            <?php echo $ro1['comp_tot']; ?>
                            </a>
                        </td>
                        <td>
                            <a href='<?php echo "spread_out_certificate_detail?list_dt=$form_date&list_dt_to=$to_date&flag=tot_tot&purpose=all'";?> target='_blank'>
                            <?php echo $ro1['fd_tot'] + $ro1['frs_tot'] + $ro1['aw_tot'] + $ro1['imp_ia_tot'] + $ro1['comp_tot'];?>
                            </a>
                        </td>
                    </tr>
                </table>
                </div>
           

    <?php } ?>
    <br><br>

                <table align="left" width="100%" border="0px;" style=" font-size:12px;" cellspacing=0>
                <tr>
                    <td colspan="2"> <b><u>NOTE</u>:</b> </td>
                </tr>
                <tr>
                    <td width="5%">1.</td>
                    <td width="95%"><b><?php echo $t_fd_not_listed.' + '.$t_frs_not_listed.' = '.($t_fd_not_listed + $t_frs_not_listed); ?></b>
                        cases pertaining to fixed date & fresh/fresh adjourned shown against <b>Column No: 9 & 10 Row No. 14</b>, can not be listed being tied-up / constitutional bench matters due to non-availability of Bench.
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td><b><?php echo $t_aw_not_listed.' + '.$t_imp_ia_not_listed.' + '.$t_oth_not_listed.' = '.($t_aw_not_listed + $t_imp_ia_not_listed + $t_oth_not_listed); ?></b>
                        After week, Comp Gen Fix Dt as per scheme & computer generated cases, shown against <b>Column No: 11, 12 & 13 Row No. 14</b>, can not be listed due to non-availability of space, and these cases got updated automatically their next tentative listing date as per listing scheme.
                    </td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>
                        Court dated pre-admission cases shall be listed on dates assigned by the Court  (including  actual  date  notice)  and  shall  not  be  left  out.  Pre-admission matter ordered to be listed by the Hon’ble Court in week commencing / next week / after week(s) shall be treated as Court given date matter.
                    </td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>
                        Over  and  apart  from  the  fresh  and  actual  date  matters,  cases  in  which 
                        computer  generated  dates  are  given  will  also  be  listed  within  the  specified 
                        number  of  cases.    However,  if  for  any  reason  the  aggregate  number  of 
                        miscellaneous  cases  exceeds  the  computer  generated  date  matters,  they  will 
                        be deferred in suitable lots after four weeks.
                    </td>
                </tr>    
                <tr>
                    <td>5.</td>
                    <td>
                                                Not  reached/left  over FRESH  ADMISSION  MATTERS shall  be  listed  on 
                        the following miscellaneous day.  The not reached / left over after notice cases will  be  assigned  auto-generated  returnable  dates  spread  out  in  suitable  lots 
                        after four weeks. This is to ensure that the daily list / supplementary list for the 
                        following  week  does  not  get  oversized.  The  returnable  dates  of  concerned 
                        cases will be 
                        notified on the Supreme  Court official website in the  case status 
                        of  that  case  as  also  on  the  list/board  for  the  next  Court  working  day,  for  the 
                        information of the litigants and lawyers. 
                                            </td>
                                        </tr>    
                
        </table>

<?php } else {
            echo "No Records Found";
        }
?>