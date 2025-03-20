<?php
    $db = \Config\Database::connect();
    $_REQUEST['sec_id'] = 0;
    $sec_id = "";
  
  $_POST['diaryNos'] = $POST['diaryNos'];
  $_POST['input_title'] = $POST['input_title'];
    $ucode = $_SESSION['login']['usercode'];
    $usertype=$_SESSION['login']['usertype'];
    $section1=$_SESSION['login']['section'];
    $sec_id = "";
  
    $diary_numbers_string_post = $_POST['diaryNos'];
    $diary_numbers_string1 = implode(',', $diary_numbers_string_post);
    $title_text = $_POST['input_title'];
    $listHeading = $_POST['listHeading'] ?? '';
    
    // print_r($diary_numbers_string1); exit;
    ?>
    <div id="prnnt" style="font-size:12px;">
        <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"> 
            <img src="<?php echo base_url()?>/images/scilogo.png" width="50px" height="80px"/><br/>            
            SUPREME COURT OF INDIA
            <br/>
        </div>
        <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
            <tr>
                <th colspan="4" style="text-align: center;">
                    <!--AS on 09042022-->
                    <!--<br>READY REGULAR HEARING MATTERS REGISTERED UPTO YEAR 2015<br><br>-->
                    <br><?php echo $listHeading  ?><br>

                    <br><?php echo $title_text  ?><br>

                </th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: left;">
                    <BR>

                </th>
            </tr>

            <?php
            $sql = "
SELECT DISTINCT 
    tentative_section(m.diary_no) AS section_name,             
    h.diary_no,   
    
    m.lastorder, 
    m.active_fil_no, 
    m.active_reg_year, 
    m.casetype_id,  
    m.active_casetype_id,  
    m.ref_agency_state_id,
    m.reg_no_display, 
    EXTRACT(YEAR FROM m.fil_dt) AS fil_year, 
    m.fil_no, 
    m.conn_key AS main_key, 
    m.fil_dt, 
    m.fil_no_fh, 
    m.reg_year_fh AS fil_year_f,
    m.mf_active, 
    m.pet_name,  
    m.res_name, 
    pno, 
    rno, 
    m.diary_no_rec_date, 
    l.purpose, 
    s.category_sc_old,
    CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER) AS diary_no_suffix,
    CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS diary_no_prefix
FROM main m
INNER JOIN heardt h ON h.diary_no = m.diary_no
INNER JOIN master.listing_purpose l ON l.code = h.listorder
LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y' 
LEFT JOIN master.submaster s ON mc.submaster_id = s.id AND s.flag = 's' AND s.display = 'Y' AND (s.category_sc_old IS NOT NULL AND s.category_sc_old != '')
LEFT JOIN case_info ci ON ci.diary_no = h.diary_no AND ci.display = 'Y'
WHERE 
    m.diary_no IN ($diary_numbers_string1)
GROUP BY 
    m.diary_no, 
    h.diary_no, 
     
   
    m.lastorder, 
    m.active_fil_no, 
    m.active_reg_year, 
    m.casetype_id,  
    m.active_casetype_id,  
    m.ref_agency_state_id,
    m.reg_no_display, 
    m.fil_no, 
    m.conn_key, 
    m.fil_dt, 
    m.fil_no_fh, 
    m.reg_year_fh, 
    m.mf_active, 
    m.pet_name,  
    m.res_name, 
    pno, 
    rno, 
    m.diary_no_rec_date, 
    l.purpose, 
    s.category_sc_old,
    diary_no_suffix, 
    diary_no_prefix
ORDER BY 
    diary_no_suffix ASC, 
    diary_no_prefix ASC
";
 
         //if(m.diary_no in ($diary_numbers_string2), 2, 999) asc,
		
          //  $res = mysql_query($sql) or die(mysql_error());
			
			 $query = $db->query($sql);
			$res = $query->getResultArray();
 
			
            $heading_priority_rep = "0";
            if (!empty($res)){
            //echo mysql_num_rows($res);
            $head2013 = 1;
            $psrno = "1";
            $clnochk = 0;
            $subheading_rep = "0";
            $mnhead_print_once = 1;
            foreach ($res as $row) {
                $diary_no = $row['diary_no'];

                if ($mnhead_print_once == 1) {
                    ?>
                    <tr style="font-weight: bold; background-color:#cccccc;">
                        <td style="width:5%;">SNo.</td>
                        <td style="width:20%;">Case No.</td>
                        <td style="width:35%;">Petitioner / Respondent</td>
                        <td style="width:40%;">Petitioner/Respondent Advocate</td>
                    </tr>
                    <?php
                    $mnhead_print_once++;
                }
             

                if ($row['reg_no_display'] == "") {
                    $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                } else {
                    $comlete_fil_no_prt = $row['reg_no_display'];
                }

                $padvname = "";
                $radvname = "";

               /*  $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'N' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) intervenor FROM 
(SELECT a.diary_no, b.name, 
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv, 
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$row["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no"; */

$advsql = "
SELECT 
    a.diary_no,
    STRING_AGG(CASE WHEN pet_res = 'R' THEN a.name || COALESCE(grp_adv, '') END, '') AS r_n,
    STRING_AGG(CASE WHEN pet_res = 'P' THEN a.name || COALESCE(grp_adv, '') END, '') AS p_n,
    STRING_AGG(CASE WHEN pet_res = 'I' THEN a.name || COALESCE(grp_adv, '') END, '') AS i_n,
    STRING_AGG(CASE WHEN pet_res = 'N' THEN a.name || COALESCE(grp_adv, '') END, '') AS intervenor
FROM (
    SELECT 
        a.diary_no, 
        b.name, 
        STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END, a.adv_type DESC, a.pet_res_no ASC) AS grp_adv, 
        a.pet_res, 
        a.adv_type, 
        a.pet_res_no
    FROM advocate a 
    LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' 
    WHERE a.diary_no = '".$row["diary_no"]."' AND a.display = 'Y' 
    GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
) a 
GROUP BY a.diary_no
";

 
				
				$query = $db->query($advsql);
			$rowadv = $query->getResultArray();
			 
                if(!empty($rowadv)) {
                   // $rowadv = mysql_fetch_array($resultsadv);
                    // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                    $radvname=  (!empty($rowadv["r_n"])) ?  strtoupper($rowadv["r_n"]) : '';
                    $padvname=  (!empty($rowadv["p_n"])) ? strtoupper($rowadv["p_n"]) : '';
                    $impldname = (!empty($rowadv["i_n"])) ? strtoupper($rowadv["i_n"]) : '';
                    $intervenorname = (!empty($rowadv["intervenor"])) ? strtoupper($rowadv["intervenor"]) : '';
                    // }
                }

                if($row['pno'] == 2){
                    $pet_name = $row['pet_name']." AND ANR.";
                }
                else if($row['pno'] > 2){
                    $pet_name = $row['pet_name']." AND ORS.";
                }
                else{
                    $pet_name = $row['pet_name'];
                }
                if($row['rno'] == 2){
                    $res_name = $row['res_name']." AND ANR.";
                }
                else if($row['rno'] > 2){
                    $res_name = $row['res_name']." AND ORS.";
                }
                else{
                    $res_name = $row['res_name'];
                }


				$is_connected = $output ='';

                $cate_old_id1 = "";

                $cate_old_id1 = '';
                $output .= "<tr style='padding-top:5px;'><td style='vertical-align: top;'>" . $psrno . "</td><td style='vertical-align: top;' rowspan=2>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $row['section_name'];
                $output .= "</td><td style='vertical-align: top;'>" . $pet_name . "</td><td style='vertical-align: top;'>" . str_replace(",", ", ", trim($padvname, ","));
                $output .= "</td></tr>";
                $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                $output .= "</td></tr>";
                $output .= "<tr><td></td><td></td><td style='vertical-align: top;'";
                $output .= ">" . $res_name . "</td><td>" . str_replace(",", ", ", trim($radvname, ","));
                $output .= "</td></tr>";
              


                $str_brdrem = get_cl_brd_remark($diary_no);
                $x60 = 150;
                $lines = explode("\n", wordwrap($str_brdrem, $x60));
                for($k=0;$k<count($lines);$k++){
                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                    $output .= $lines[$k];
                    $output .= "</td><td></td></tr>";
                }

                if($row['diary_no'] == $row['main_key']) {
                    
                  $sql2 = "SELECT tentative_section(j.diary_no) AS section_name, j.* 
FROM (
    SELECT 
        h.*, 
        active_fil_no,
        m.active_reg_year,
        m.casetype_id,
        m.active_casetype_id,
        m.ref_agency_state_id,
        m.reg_no_display,
        EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
        m.fil_no,
        m.conn_key AS main_key,
        m.fil_dt,
        m.fil_no_fh,
        m.reg_year_fh AS fil_year_f,
        m.mf_active,
        m.pet_name,
        m.res_name,
        pno,
        rno,
        m.diary_no_rec_date
    FROM (
        SELECT 
            c.diary_no AS conc_diary_no,
            m.conn_key,
            h.next_dt,
            h.mainhead,
            h.subhead,
            h.clno,
            h.brd_slno,
            h.roster_id,
            h.judges,
            h.coram,
            h.board_type,
            h.usercode,
            h.ent_dt,
            h.module_id,
            h.mainhead_n,
            h.subhead_n,
            h.main_supp_flag,
            h.listorder,
            h.tentative_cl_dt,
            m.lastorder,
            h.listed_ia,
            h.sitting_judges,
            h.list_before_remark,
            h.is_nmd,
            h.no_of_time_deleted 
        FROM heardt h
        INNER JOIN main m ON m.diary_no = h.diary_no 
        INNER JOIN conct c ON c.conn_key = CAST(m.conn_key AS bigint)   
        WHERE 
            c.list = 'Y' 
            AND m.c_status = 'P' 
            AND m.diary_no = CAST(m.conn_key AS bigint)  
            AND m.conn_key = '".$row['diary_no']."'
    ) a
    INNER JOIN main m ON a.conc_diary_no = m.diary_no
    INNER JOIN heardt h ON a.conc_diary_no = h.diary_no  
    WHERE 
        m.c_status = 'P' 
        AND CAST(m.conn_key AS bigint)  != m.diary_no 
        AND h.next_dt IS NOT NULL 
    ORDER BY m.diary_no_rec_date
) j";



			$query = $db->query($sql2);
			$res2 = $query->getResultArray();

                   // $res2 = mysql_query($sql2) or die(mysql_error());
                    if (!empty($res2)) {

                        $psrno_conc = "1";
                        foreach ($res2 as $row2) {
                            $diary_no = $row2['diary_no'];
                            if ($row2['reg_no_display'] == "") {
                                $comlete_fil_no_prt = "Diary No. " . substr_replace($row2['diary_no'], '-', -4, 0);
                            } else {
                                $comlete_fil_no_prt = $row2['reg_no_display'];
                            }
                            $padvname = "";
                            $radvname = "";
                          $advsql = "
SELECT 
    a.diary_no, 
    STRING_AGG(CASE WHEN a.pet_res = 'R' THEN a.name END, '') AS r_n,
    STRING_AGG(CASE WHEN a.pet_res = 'P' THEN a.name END, '') AS p_n,
    STRING_AGG(CASE WHEN a.pet_res = 'I' THEN a.name END, '') AS i_n,
    STRING_AGG(CASE WHEN a.pet_res = 'N' THEN a.name END, '') AS intervenor
FROM (
    SELECT 
        a.diary_no, 
        b.name, 
        STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN a.pet_res IN ('I', 'N') THEN 99 ELSE 0 END ASC, a.adv_type DESC, a.pet_res_no ASC) AS grp_adv, 
        a.pet_res, 
        a.adv_type, 
        a.pet_res_no
    FROM advocate a 
    LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' 
    WHERE a.diary_no = '".$row2["diary_no"]."' AND a.display = 'Y' 
    GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
) a
GROUP BY a.diary_no
";



                           // $resultsadv = mysql_query($advsql) or die(mysql_error());
							
							$query = $db->query($advsql);
							$rowadv = $query->getResultArray();
							
                            if(!empty($rowadv)) {
                                //$rowadv = mysql_fetch_array($resultsadv);
                                // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                                /* $radvname=  strtoupper($rowadv["r_n"]);
                                $padvname=  strtoupper($rowadv["p_n"]);
                                $impldname = strtoupper($rowadv["i_n"]);
                                $intervenorname = strtoupper($rowadv["intervenor"]); */
								
								 $radvname=  (!empty($rowadv["r_n"])) ?  strtoupper($rowadv["r_n"]) : '';
								$padvname=  (!empty($rowadv["p_n"])) ? strtoupper($rowadv["p_n"]) : '';
								$impldname = (!empty($rowadv["i_n"])) ? strtoupper($rowadv["i_n"]) : '';
								$intervenorname = (!empty($rowadv["intervenor"])) ? strtoupper($rowadv["intervenor"]) : '';
								
                                // }
                            }

                            if($row2['pno'] == 2){
                                $pet_name = $row2['pet_name']." AND ANR.";
                            }
                            else if($row2['pno'] > 2){
                                $pet_name = $row2['pet_name']." AND ORS.";
                            }
                            else{
                                $pet_name = $row2['pet_name'];
                            }
                            if($row2['rno'] == 2){
                                $res_name = $row2['res_name']." AND ANR.";
                            }
                            else if($row2['rno'] > 2){
                                $res_name = $row2['res_name']." AND ORS.";
                            }
                            else{
                                $res_name = $row2['res_name'];
                            }

                            $output .= "<tr><td>" . $psrno . '.' . $psrno_conc++ . "</td><td rowspan=2> <span style='color:red;'>Connected</span><br/> ".$comlete_fil_no_prt. "<br/>" . $row2['section_name'];
                            $output .= "</td><td>" . $pet_name . "</td><td>" . str_replace(",", ", ", trim($padvname, ","));
                            $output .= "</td></tr>";
                            $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                            $output .= "</td></tr>";
                            $output .= "<tr><td></td><td></td><td";
                            $output .= ">" . $res_name . "</td><td>" . str_replace(",", ", ", trim($radvname, ","));
                            $output .= "</td></tr>";
								
								echo $row2['diary_no'];
                            $str_brdrem = get_cl_brd_remark($row2['diary_no']) ?? '';
                            $x60 = 150;
                            $lines = explode("\n", wordwrap($str_brdrem, $x60));
                            for($k=0;$k<count($lines);$k++){
                                $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                $output .= $lines[$k];
                                $output .= "</td><td></td></tr>";
                            }
                        }
                    }
                }
                $psrno++;
                echo $output;
                $output = "";

            }//END OF WHILE LOOP

            ?>
        </table>
        <?php
        }
        else {
            echo "No Records Found";
        }

        ?>
        <br>
        <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR/><?php date_default_timezone_set('Asia/Kolkata');
                echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
        <br>
        <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
    </div>
    <!--    <div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">-->
    <!--        <input name="prnnt1" type="button" id="prnnt1" value="Print" >-->
    <!--    </div>-->
    <center></center>
	
	<?php //die;?>
    

