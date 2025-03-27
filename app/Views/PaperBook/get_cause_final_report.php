<?php
 
 $ucode = session()->get('login')['usercode'];

    // $sql_utype="select usertype from users where usercode=$ucode";
     $sql_utype = is_data_from_table('master.users', " usercode=$ucode ", 'usertype','');
     $rs_utype=mysql_query($sql_utype);
      $row_type=mysql_fetch_array($rs_utype);


     $utype=$row_type['usertype'];
  

    if(($ucode !=1) && ($utype!=14))
    {
         $user_code=" and a.user_id='$ucode'";
    }
     pr($_REQUEST);
     $cl_date= trim($_REQUEST['cl_date']);
     $date1 = strtotime($cl_date);
     $list_type=trim($_REQUEST['list_type']);
     $sorting=trim($_REQUEST['sort']);
     if($sorting=='diary_no')

      {
      
      $sorting= " cast(substr(a.diary_no, -4) as unsigned),cast(substr(a.diary_no, 1, length( a.diary_no ) -4 )as unsigned)  ";

      }
      if($sorting=='docnum')
      {
          $sorting= "docyear,docnum";
      }
      if($sorting=='active_fil_no')
      {
        $sorting="active_casetype_id,active_reg_year,active_fil_no";
      }
 

    if($list_type==1)
    {
        if(($ucode !=1) && ($utype!=14))            
        {
              $sql_matters="select group_concat(casetype_id,caseyear) as ct from godown_user_allocation where usercode=$ucode ";
            $rs_matters=mysql_query($sql_matters);

            $row_matters=mysql_fetch_array($rs_matters);
            $ct=$row_matters[ct];
          
        }

       
    if(($ucode ==1) || ($ucode == 630))  // for super user and avadhesh sir (employee code 3719)
        {       

                 $serve_status="SELECT a.* from (select r.courtno,docyear,docnum,concat(docnum,'/',docyear) as IA,reg_no_display, active_casetype_id,u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode  LEFT JOIN docdetails ON m.diary_no = docdetails.diary_no LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date' AND  subhead in(811,812) and h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and iastat='P' group by h.diary_no) a   ORDER BY $sorting ASC";

        }
        else if($utype==14)
        {          
               $serve_status="SELECT a.* from (select r.courtno,docyear,docnum,concat(docnum,'/',docyear) as IA,reg_no_display, active_casetype_id,u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode  LEFT JOIN docdetails ON m.diary_no = docdetails.diary_no LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date' AND  subhead  in(811,812) and h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and iastat='P' group by h.diary_no )a    ORDER BY $sorting ASC";


        }
        else
        {
             $serve_status="select a.* from (SELECT r.courtno,reg_no_display,docnum,docyear,concat(docnum,'/',docyear)as IA, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN docdetails on m.diary_no=docdetails.diary_no LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date' AND h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and iastat='P' and subhead in (811,812) and concat(active_casetype_id,active_reg_year) in ($ct)) a  ORDER BY  $sorting ASC";

    }
    
        $serve_status=  mysql_query($serve_status) or die("Error: ".__LINE__.mysql_error()); 

        if(mysql_num_rows($serve_status)>0) {

            ?>
 <div id ="r">
<CENTER> List of  matters <b>Fresh</b> for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER><input type="button" onclick="printDiv('r')" value="print " />

            <BR>
            <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">

                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">IA No.</td>
                     
                </tr>

                <?php

                $sno = 1;

                while($ro = mysql_fetch_array($serve_status)){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '0000-00-00 00:00:00')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{
                       
                        $fil_no_print = $ro['reg_no_display'];
                    }


              $purpose = $ro['purpose'];
              $IA=$ro['IA'];



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                    $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n FROM
(SELECT a.diary_no, b.name,
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                    $resultsadv = mysql_query($advsql) or die(mysql_error());
                    if(mysql_num_rows($resultsadv) > 0) {
                        $rowadv = mysql_fetch_array($resultsadv);
                      
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];

                        $section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
LEFT JOIN users b ON usercode=dacode
LEFT JOIN usersection c ON b.section=c.id
WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ro[ref_agency_state_id]' AND a.display='Y' ";
                        $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
                        if(mysql_num_rows($section_ten_rs)>0){
                            $section_ten_row = mysql_fetch_array($section_ten_rs);
                            $ro['section_name']=$section_ten_row["section_name"];
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                         <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
                      <td align="left" style='vertical-align: top;'><?php echo $IA; ?></td>
       
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
</div>
        <?php
    }


    if($list_type==2)
    {
        if(($ucode !=1) && ($utype!=14))
            
        {
              $sql_matters="select group_concat(casetype_id,caseyear) as ct from godown_user_allocation where usercode=$ucode ";
            $rs_matters=mysql_query($sql_matters);

            $row_matters=mysql_fetch_array($rs_matters);
            $ct=$row_matters['ct'];
        
        }

      
    if(($ucode ==1))
        {
        //echo" super user query";

                 $serve_status="SELECT a.* from (select r.courtno,docyear,docnum,concat(docnum,'/',docyear) as IA,reg_no_display, active_casetype_id,u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode  LEFT JOIN docdetails ON m.diary_no = docdetails.diary_no LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date' AND  subhead not in(811,812) and h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and iastat='P' group by h.diary_no) a   ORDER BY $sorting ASC";

        }
        else if($utype==14)
        {
           // echo" query for diary matters";
               $serve_status="SELECT a.* from (select r.courtno,docyear,docnum,concat(docnum,'/',docyear) as IA,reg_no_display, active_casetype_id,u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode  LEFT JOIN docdetails ON m.diary_no = docdetails.diary_no LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date' AND  subhead not in(811,812) and h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and iastat='P' group by h.diary_no )a    ORDER BY $sorting ASC";


        }
        else
        {
             $serve_status="select a.* from (SELECT r.courtno,reg_no_display,docnum,docyear,concat(docnum,'/',docyear)as IA, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN docdetails on m.diary_no=docdetails.diary_no LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date' AND h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and iastat='P' and subhead not in (811,812) and concat(active_casetype_id,active_reg_year) in ($ct)) a  ORDER BY  $sorting ASC";

    }//echo $serve_status="SELECT l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name,  casetype_id, reg_no_display,ref_agency_state_id, diary_no_rec_date,remark, h.* FROM sci_cmis_final.heardt h INNER JOIN sci_cmis_final.main m ON m.diary_no = h.diary_no INNER JOIN sci_cmis_final.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN sci_cmis_final.roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN sci_cmis_final.casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M' and  h.next_dt = '$cl_date' AND h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' group by h.diary_no ORDER BY r.courtno, IF(us.section_name IS NULL, 9999, 0) ASC, us.section_name, u.name, h.brd_slno, IF(h.conn_key=h.diary_no,'0000-00-00',m.diary_no_rec_date) ASC;";
        //  $serve_status="select main.diary_no,reg_no_display,concat(pet_name ,' VS ',res_name) as Cause_Title , next_dt,name,tentative_section(main.diary_no) as Section,active_casetype_id,casetype_id,active_reg_year,diary_no_rec_date  from main join heardt on main.diary_no=heardt.diary_no left join users on main.dacode=users.usercode where next_dt='2018-07-04' and (coram <>''and coram <>0) and brd_slno <> 0 and main_supp_flag in(1,2)  and main.diary_no not in(select diary_no from drop_note where cl_date ='2018-07-04')";
        $serve_status=  mysql_query($serve_status) or die("Error: ".__LINE__.mysql_error());
//echo " the usercode is ".$ucode;

        if(mysql_num_rows($serve_status)>0) {

            ?>
 <div id ="r">
<CENTER> List of  matters <b>except fresh</b> for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER><input type="button" onclick="printDiv('r')" value="print " />

            <BR>
            <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">

                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">IA No.</td>
                     <!--<td width="20%" style="font-weight: bold; color: #dce38d;">Purpose.</td>-->
                </tr>

                <?php

                $sno = 1;

                while($ro = mysql_fetch_array($serve_status)){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '0000-00-00 00:00:00')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{
                        /*                $fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
                                        if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                                            $fil_no_print .= "-".ltrim($filno_array[2], '0');
                                        $fil_no_print .= "/".$ro['active_reg_year'];*/
                        $fil_no_print = $ro['reg_no_display'];
                    }


              $purpose = $ro['purpose'];
              $IA=$ro['IA'];



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                    $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n FROM
(SELECT a.diary_no, b.name,
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                    $resultsadv = mysql_query($advsql) or die(mysql_error());
                    if(mysql_num_rows($resultsadv) > 0) {
                        $rowadv = mysql_fetch_array($resultsadv);
                        // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];

                        $section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
LEFT JOIN users b ON usercode=dacode
LEFT JOIN usersection c ON b.section=c.id
WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ro[ref_agency_state_id]' AND a.display='Y' ";
                        $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
                        if(mysql_num_rows($section_ten_rs)>0){
                            $section_ten_row = mysql_fetch_array($section_ten_rs);
                            $ro['section_name']=$section_ten_row["section_name"];
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                         <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
                      <td align="left" style='vertical-align: top;'><?php echo $IA; ?></td>
       <!--  <td align="left" style='vertical-align: top;'><?php // echo $purpose; ?></td>-->
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
</div>
        <?php
    }


    if($list_type==3)
    {
        if(($ucode !=1) && ($utype!=14))
            // echo " fresh Civil matters list to be generated.";
        {
             $sql_matters="select group_concat(casetype_id) as ct from godown_user_allocation where usercode=$ucode and casetype_id not in(1,3,5,7,11,13,23,32,34,40,9,19,25) and caseyear=YEAR(now())";
            $rs_matters=mysql_query($sql_matters);

            $row_matters=mysql_fetch_array($rs_matters);
            $ct=$row_matters[ct];
            if($ct==null)

            {
                echo " NO Fresh  Matters  ";
                exit();

            }
        }

        //  echo " the casetypes are ".$ct;
// if($ucode==1)
  //   {

         $serve_status="select a.* from (SELECT r.courtno,reg_no_display,active_casetype_id, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M' and h.next_dt = '$cl_date' AND h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' group by h.diary_no) a ORDER BY $sorting ASC";

//}
//else
//{
           //   $serve_status="select a.* from (SELECT r.courtno,reg_no_display, active_casetype_id,u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND  (active_casetype_id is null or active_casetype_id =0) and (active_reg_year=0 or active_reg_year is null) and l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date' AND  h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' group by h.diary_no) a  ORDER BY $sorting ASC";

//}
//else
//{

//}
////// $serve_status="SELECT l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name,  casetype_id, reg_no_display,ref_agency_state_id, diary_no_rec_date,remark, h.* FROM sci_cmis_final.heardt h INNER JOIN sci_cmis_final.main m ON m.diary_no = h.diary_no INNER JOIN sci_cmis_final.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN sci_cmis_final.roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN sci_cmis_final.casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M' and  h.next_dt = '$cl_date' AND h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' group by h.diary_no ORDER BY r.courtno, IF(us.section_name IS NULL, 9999, 0) ASC, us.section_name, u.name, h.brd_slno, IF(h.conn_key=h.diary_no,'0000-00-00',m.diary_no_rec_date) ASC;";
        //  $serve_status="select main.diary_no,reg_no_display,concat(pet_name ,' VS ',res_name) as Cause_Title , next_dt,name,tentative_section(main.diary_no) as Section,active_casetype_id,casetype_id,active_reg_year,diary_no_rec_date  from main join heardt on main.diary_no=heardt.diary_no left join users on main.dacode=users.usercode where next_dt='2018-07-04' and (coram <>''and coram <>0) and brd_slno <> 0 and main_supp_flag in(1,2)  and main.diary_no not in(select diary_no from drop_note where cl_date ='2018-07-04')";
        $serve_status=  mysql_query($serve_status) or die("Error: ".__LINE__.mysql_error());
//echo " the usercode is ".$ucode;

        if(mysql_num_rows($serve_status)>0) {

            ?>
 <div id ="r">
 <CENTER> Consolidated List of  matters  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER><input type="button" onclick="printDiv('r')" value="print " />

            <BR>
            <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">

                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                     <!--<td width="20%" style="font-weight: bold; color: #dce38d;">Purpose.</td>-->
                </tr>

                <?php

                $sno = 1;

                while($ro = mysql_fetch_array($serve_status)){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '0000-00-00 00:00:00')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{
                        /*                $fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
                                        if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                                            $fil_no_print .= "-".ltrim($filno_array[2], '0');
                                        $fil_no_print .= "/".$ro['active_reg_year'];*/
                        $fil_no_print = $ro['reg_no_display'];
                    }


              $purpose = $ro['purpose'];



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                    $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n FROM
(SELECT a.diary_no, b.name,
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                    $resultsadv = mysql_query($advsql) or die(mysql_error());
                    if(mysql_num_rows($resultsadv) > 0) {
                        $rowadv = mysql_fetch_array($resultsadv);
                        // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];

                        $section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
LEFT JOIN users b ON usercode=dacode
LEFT JOIN usersection c ON b.section=c.id
WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ro[ref_agency_state_id]' AND a.display='Y' ";
                        $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
                        if(mysql_num_rows($section_ten_rs)>0){
                            $section_ten_row = mysql_fetch_array($section_ten_rs);
                            $ro['section_name']=$section_ten_row["section_name"];
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                         <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
         <!--<td align="left" style='vertical-align: top;'><?php //echo $purpose; ?></td>-->
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
</div>
        <?php
    }


    if($list_type==4)
    {
        if(($ucode !=1) && ($utype!=14))
            // echo " fresh Civil matters list to be generated.";
        {
             $sql_matters="select group_concat(casetype_id) as ct from godown_user_allocation where usercode=$ucode and casetype_id not in(1,3,5,7,11,13,23,32,34,40,9,19,25) and caseyear=YEAR(now())";
            $rs_matters=mysql_query($sql_matters);

            $row_matters=mysql_fetch_array($rs_matters);
            $ct=$row_matters[ct];
            if($ct==null)

            {
                echo " NO Fresh  Matters  ";
                exit();

            }
        }
        else
        {
             if($ma==1)

                  {
                     $ct='2,4,6,8,12,14,33,35,41,10,20,26,39';
                   }
              else
               {

                    $ct='2,4,6,8,12,14,33,35,41,39';
               }
        }
        //  echo " the casetypes are ".$ct;

             $serve_status="select a.* from (SELECT r.courtno,reg_no_display, u.name,active_casetype_id, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date'   AND active_casetype_id in (9,10,25,26,19,20) and h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and subhead not in (811,812) group by h.diary_no) a  ORDER BY $sorting ASC";

    //echo $serve_status="SELECT l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name,  casetype_id, reg_no_display,ref_agency_state_id, diary_no_rec_date,remark, h.* FROM sci_cmis_final.heardt h INNER JOIN sci_cmis_final.main m ON m.diary_no = h.diary_no INNER JOIN sci_cmis_final.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN sci_cmis_final.roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN sci_cmis_final.casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M' and  h.next_dt = '$cl_date' AND h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' group by h.diary_no ORDER BY r.courtno, IF(us.section_name IS NULL, 9999, 0) ASC, us.section_name, u.name, h.brd_slno, IF(h.conn_key=h.diary_no,'0000-00-00',m.diary_no_rec_date) ASC;";
        //  $serve_status="select main.diary_no,reg_no_display,concat(pet_name ,' VS ',res_name) as Cause_Title , next_dt,name,tentative_section(main.diary_no) as Section,active_casetype_id,casetype_id,active_reg_year,diary_no_rec_date  from main join heardt on main.diary_no=heardt.diary_no left join users on main.dacode=users.usercode where next_dt='2018-07-04' and (coram <>''and coram <>0) and brd_slno <> 0 and main_supp_flag in(1,2)  and main.diary_no not in(select diary_no from drop_note where cl_date ='2018-07-04')";
        $serve_status=  mysql_query($serve_status) or die("Error: ".__LINE__.mysql_error());
//echo " the usercode is ".$ucode;

        if(mysql_num_rows($serve_status)>0) {

            ?>
  <div id ="r">
<CENTER> Consolidated List of <b> Review/Curative/Contempt matters</b>  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER><input type="button" onclick="printDiv('r')" value="print " />

            <BR>
            <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">

                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                   <!--  <td width="20%" style="font-weight: bold; color: #dce38d;">Purpose.</td>-->
                </tr>

                <?php

                $sno = 1;

                while($ro = mysql_fetch_array($serve_status)){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '0000-00-00 00:00:00')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{
                        /*                $fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
                                        if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                                            $fil_no_print .= "-".ltrim($filno_array[2], '0');
                                        $fil_no_print .= "/".$ro['active_reg_year'];*/
                        $fil_no_print = $ro['reg_no_display'];
                    }


              $purpose = $ro['purpose'];



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                    $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n FROM
(SELECT a.diary_no, b.name,
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                    $resultsadv = mysql_query($advsql) or die(mysql_error());
                    if(mysql_num_rows($resultsadv) > 0) {
                        $rowadv = mysql_fetch_array($resultsadv);
                        // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];

                        $section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
LEFT JOIN users b ON usercode=dacode
LEFT JOIN usersection c ON b.section=c.id
WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ro[ref_agency_state_id]' AND a.display='Y' ";
                        $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
                        if(mysql_num_rows($section_ten_rs)>0){
                            $section_ten_row = mysql_fetch_array($section_ten_rs);
                            $ro['section_name']=$section_ten_row["section_name"];
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                         <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
        <!-- <td align="left" style='vertical-align: top;'><?php //echo $purpose; ?></td>-->
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
</div>
        <?php
    }   // end of if


    if($list_type==5)
    {

        //  echo " the casetypes are ".$ct;

         $serve_status="select a.* from (SELECT r.courtno,reg_no_display, active_casetype_id,u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date'   and h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and concat(active_casetype_id,active_reg_year) not in (11999,12000,12001,12002,12003,12004,12005,12006,12007,12008,12009,12010,12011,12012,12013,12015,12016,12018,12014,12017,22015,72015,82015,52015,62015,22016,72016,82016,52016,62016,22018,72018,82018,52018,62018,21950,21951,21952,21953,21954,21955,21956,21957,21958,21959,21960,21961,21962,21963,21964,21965,21966,21967,21968,21969,21970,21971,21972,21973,21974,21975,21976,21977,21978,21979,21980,21981,21982,21983,21984,21985,21986,21987,21988,21989,21990,21991,21992,21993,21994,21995,21996,21997,21998,21999,22000,22001,22002,22003,22004,22005,22006,22007,22008,22009,22010,22011,22012,22013,22014,22017,71950,71951,71952,71953,71954,71955,71956,71957,71958,71959,71960,71961,71962,71963,71964,71965,71966,71967,71968,71969,71970,71971,71972,71973,71974,71975,71976,71977,71978,71979,71980,71981,71982,71983,71984,71985,71986,71987,71988,71989,71990,71991,71992,71993,71994,71995,71996,71997,71998,71999,72000,72001,72002,72003,72004,72005,72006,72007,72008,72009,72010,72011,72012,72013,72014,72017,81950,81951,81952,81953,81954,81955,81956,81957,81958,81959,81960,81961,81962,81963,81964,81965,81966,81967,81968,81969,81970,81971,81972,81973,81974,81975,81976,81977,81978,81979,81980,81981,81982,81983,81984,81985,81986,81987,81988,81989,81990,81991,81992,81993,81994,81995,81996,81997,81998,81999,82000,82001,82002,82003,82004,82005,82006,82007,82008,82009,82010,82011,82012,82013,82014,82017,51950,51951,51952,51953,51954,51955,51956,51957,51958,51959,51960,51961,51962,51963,51964,51965,51966,51967,51968,51969,51970,51971,51972,51973,51974,51975,51976,51977,51978,51979,51980,51981,51982,51983,51984,51985,51986,51987,51988,51989,51990,51991,51992,51993,51994,51995,51996,51997,51998,51999,52000,52001,52002,52003,52004,52005,52006,52007,52008,52009,52010,52011,52012,52013,52014,52017,61950,61951,61952,61953,61954,61955,61956,61957,61958,61959,61960,61961,61962,61963,61964,61965,61966,61967,61968,61969,61970,61971,61972,61973,61974,61975,61976,61977,61978,61979,61980,61981,61982,61983,61984,61985,61986,61987,61988,61989,61990,61991,61992,61993,61994,61995,61996,61997,61998,61999,62000,62001,62002,62003,62004,62005,62006,62007,62008,62009,62010,62011,62012,62013,62014,62017,02018,02017,02017,02018,111950,111951,111952,111953,111954,111955,111956,111957,111958,111959,111960,111961,111962,111963,111964,111965,111966,111967,111968,111969,111970,111971,111972,111973,111974,111975,111976,111977,111978,111979,111980,111981,111982,111983,111984,111985,111986,111987,111988,111989,111990,111991,111992,111993,111994,111995,111996,111997,111998,111999,112000,112001,112002,112003,112004,112005,112006,112007,112008,112009,112010,112011,112012,112013,112014,112015,112016,112017,112018,121950,121951,121952,121953,121954,121955,121956,121957,121958,121959,121960,121961,121962,121963,121964,121965,121966,121967,121968,121969,121970,121971,121972,121973,121974,121975,121976,121977,121978,121979,121980,121981,121982,121983,121984,121985,121986,121987,121988,121989,121990,121991,121992,121993,121994,121995,121996,121997,121998,121999,122000,122001,122002,122003,122004,122005,122006,122007,122008,122009,122010,122011,122012,122013,122014,122015,122016,122017,122018,31950,31951,31952,31953,31954,31955,31956,31957,31958,31959,31960,31961,31962,31963,31964,31965,31966,31967,31968,31969,31970,31971,31972,31973,31974,31975,31976,31977,31978,31979,31980,31981,31982,31983,31984,31985,31986,31987,31988,31989,31990,31991,31992,31993,31994,31995,31996,31997,31998,31999,32000,32001,32002,32003,32004,32005,32006,32007,32008,32009,32010,32011,32012,32013,32014,32015,32016,32017,32018,41950,41951,41952,41953,41954,41955,41956,41957,41958,41959,41960,41961,41962,41963,41964,41965,41966,41967,41968,41969,41970,41971,41972,41973,41974,41975,41976,41977,41978,41979,41980,41981,41982,41983,41984,41985,41986,41987,41988,41989,41990,41991,41992,41993,41994,41995,41996,41997,41998,41999,42000,42001,42002,42003,42004,42005,42006,42007,42008,42009,42010,42011,42012,42013,42014,42015,42016,42017,42018,131950,131951,131952,131953,131954,131955,131956,131957,131958,131959,131960,131961,131962,131963,131964,131965,131966,131967,131968,131969,131970,131971,131972,131973,131974,131975,131976,131977,131978,131979,131980,131981,131982,131983,131984,131985,131986,131987,131988,131989,131990,131991,131992,131993,131994,131995,131996,131997,131998,131999,132000,132001,132002,132003,132004,132005,132006,132007,132008,132009,132010,132011,132012,132013,132014,132015,132016,132017,132018,392018)
  and ( fil_no is not null or fil_no <> '') and active_casetype_id not in (9,10,19,20,25,26) group by h.diary_no) a  ORDER BY $sorting ASC";

        //echo $serve_status="SELECT l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name,  casetype_id, reg_no_display,ref_agency_state_id, diary_no_rec_date,remark, h.* FROM sci_cmis_final.heardt h INNER JOIN sci_cmis_final.main m ON m.diary_no = h.diary_no INNER JOIN sci_cmis_final.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN sci_cmis_final.roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN sci_cmis_final.casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M' and  h.next_dt = '$cl_date' AND h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' group by h.diary_no ORDER BY r.courtno, IF(us.section_name IS NULL, 9999, 0) ASC, us.section_name, u.name, h.brd_slno, IF(h.conn_key=h.diary_no,'0000-00-00',m.diary_no_rec_date) ASC;";
        //  $serve_status="select main.diary_no,reg_no_display,concat(pet_name ,' VS ',res_name) as Cause_Title , next_dt,name,tentative_section(main.diary_no) as Section,active_casetype_id,casetype_id,active_reg_year,diary_no_rec_date  from main join heardt on main.diary_no=heardt.diary_no left join users on main.dacode=users.usercode where next_dt='2018-07-04' and (coram <>''and coram <>0) and brd_slno <> 0 and main_supp_flag in(1,2)  and main.diary_no not in(select diary_no from drop_note where cl_date ='2018-07-04')";
        $serve_status=  mysql_query($serve_status) or die("Error: ".__LINE__.mysql_error());
//echo " the usercode is ".$ucode;

        if(mysql_num_rows($serve_status)>0) {

            ?>
<input type="button" onclick="printDiv('r')" value="print " />
          <div id ="r">
              <CENTER> Consolidated List of  <b>Unallocated matters </b>  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>

            <BR>
            <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

                <tr style="background: #918788;">

                    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>
                    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="20%" style="font-weight: bold; color: #dce38d;">Registration No.</td>
                    <!--  <td width="20%" style="font-weight: bold; color: #dce38d;">Purpose.</td>-->
                </tr>

                <?php

                $sno = 1;

                while($ro = mysql_fetch_array($serve_status)){
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    if($ro['active_fil_dt'] != '0000-00-00 00:00:00')
                        $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
                    else
                        $active_fil_dt = "";
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){

                        $fil_no_print = "Unregistred";
                    }
                    else{
                        /*                $fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
                                        if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                                            $fil_no_print .= "-".ltrim($filno_array[2], '0');
                                        $fil_no_print .= "/".$ro['active_reg_year'];*/
                        $fil_no_print = $ro['reg_no_display'];
                    }


                    $purpose = $ro['purpose'];



                    if($sno1 == '1'){ ?>
                        <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
                    <?php } else { ?>
                        <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
                        <?php
                    }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";
                    $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n FROM
(SELECT a.diary_no, b.name,
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                    $resultsadv = mysql_query($advsql) or die(mysql_error());
                    if(mysql_num_rows($resultsadv) > 0) {
                        $rowadv = mysql_fetch_array($resultsadv);
                        // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                        // }
                    }


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if($ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];

                        $section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
LEFT JOIN users b ON usercode=dacode
LEFT JOIN usersection c ON b.section=c.id
WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ro[ref_agency_state_id]' AND a.display='Y' ";
                        $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
                        if(mysql_num_rows($section_ten_rs)>0){
                            $section_ten_row = mysql_fetch_array($section_ten_rs);
                            $ro['section_name']=$section_ten_row["section_name"];
                        }
                    }
                    ?>
                    <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print; ?></td>
                    <!-- <td align="left" style='vertical-align: top;'><?php //echo $purpose; ?></td>-->
                    </tr>

                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Records Found To Display!!!";
        }
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
        </div>
        </div>
        <?php
    }
//    code for other diary matters


     if($list_type==6)
     {
      //  echo "diary_civil_matters";
     // echo " fresh Crminal matters list to be generated.";



         // echo $serve_status="SELECT j.* FROM (SELECT  p.id AS is_printed, r.courtno, us.id, ifnull(u.name,tentative_da(m.diary_no)) as name, ifnull(us.section_name,tentative_section(m.diary_no)) as section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, reg_no_display, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y' LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` AND u.display = 'Y' LEFT JOIN usersection us ON us.id = u.section WHERE  h.mainhead = 'M'  and casetype_id in(1,3,5,7,11,13,15,17,19,21,22,23,24,25,27,32,34,40,31)  and h.next_dt = '$cl_date' AND  (active_reg_year='' or active_reg_year is null ) AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND m.c_status = 'P' AND h.clno > 0 AND h.brd_slno > 0 AND h.roster_id > 0 AND m.diary_no IS NOT NULL group by h.diary_no) j LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND j.next_dt != l.next_dt AND l.judges != 0 AND l.judges IS NOT NULL AND l.brd_slno > 0 AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL ORDER BY courtno, IF(j.section_name IS NULL, 9999, 0) ASC, j.section_name, j.name, j.brd_slno, IF(j.conn_key=j.diary_no,'0000-00-00',diary_no_rec_date) ASC ";
         //  $serve_status="select main.diary_no,reg_no_display,concat(pet_name ,' VS ',res_name) as Cause_Title , next_dt,name,tentative_section(main.diary_no) as Section,active_casetype_id,casetype_id,active_reg_year,diary_no_rec_date  from main join heardt on main.diary_no=heardt.diary_no left join users on main.dacode=users.usercode where next_dt='2018-07-04' and (coram <>''and coram <>0) and brd_slno <> 0 and main_supp_flag in(1,2)  and main.diary_no not in(select diary_no from drop_note where cl_date ='2018-07-04')";


     //   $serve_status="SELECT j.* FROM (SELECT p.id AS is_printed,docnum,docyear,casecode ,r.courtno, us.id, ifnull(u.name,tentative_da(m.diary_no)) as name, ifnull(us.section_name,tentative_section(m.diary_no)) as section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, reg_no_display, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y' LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode left join docdetails on h.diary_no=docdetails.diary_no and iastat='P' and doccode=8 LEFT JOIN users u ON u.usercode = m.`dacode` AND u.display = 'Y' LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M' and casetype_id in($ct) and h.next_dt = '$cl_date' AND (active_reg_year='' or active_reg_year is null ) AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND m.c_status = 'P' AND h.clno > 0 AND h.brd_slno > 0  AND board_type='J' AND h.roster_id > 0 AND m.diary_no IS NOT NULL group by h.diary_no) j LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND j.next_dt != l.next_dt AND l.judges != 0 AND l.judges IS NOT NULL AND l.brd_slno > 0 AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL order by docnum asc ";
   $serve_status="select a. * from (SELECT r.courtno,reg_no_display,u.name,us.section_name,
    l.purpose,docnum,docyear,
    c1.short_description,
    YEAR(m.active_fil_dt) fyr,
    active_reg_year,
    active_fil_dt,
    active_fil_no,
    m.pet_name,
    m.res_name,
    m.pno,
    m.rno,
    casetype_id,
    ref_agency_state_id,
    diary_no_rec_date,

    h.*
FROM
    heardt h
        INNER JOIN
    main m ON m.diary_no = h.diary_no
        INNER JOIN
    listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
        INNER JOIN
    roster r ON r.id = h.roster_id AND r.display = 'Y'
        LEFT JOIN
    brdrem br ON br.diary_no = m.diary_no
        left join docdetails on h.diary_no=docdetails.diary_no
        and iastat='P' and doccode=8
        LEFT JOIN
    casetype c1 ON m.active_casetype_id = c1.casecode
        LEFT JOIN
    users u ON u.usercode = m.`dacode`
        AND (u.display = 'Y' || u.display IS NULL)
        LEFT JOIN
    usersection us ON us.id = u.section
WHERE
    h.mainhead = 'M'
        AND h.next_dt = '2018-08-17'
        AND h.board_type = 'J'
        AND (h.main_supp_flag = 1
        OR h.main_supp_flag = 2)
        AND h.roster_id > 0
        AND m.diary_no IS NOT NULL
        AND m.c_status = 'P' and case_grp='C'
        AND (fil_no = '' OR fil_no IS NULL)
        AND subhead IN (811,812)
GROUP BY h.diary_no) a
ORDER BY $sorting ASC";
          // echo $serve_status="SELECT r.courtno,reg_no_display, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date'   AND active_casetype_id in (9,10,25,26,19,20) and h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and subhead not in (811,812) group by h.diary_no ORDER BY $sorting ASC";

         $serve_status=  mysql_query($serve_status) or die("Error: ".__LINE__.mysql_error());
        //echo " the usercode is ".$ucode;

         if(mysql_num_rows($serve_status)>0)
           {
             ?>

                    <input type="button" onclick="printDiv('r')" value="print " />

                       <div id ="r">
                       <CENTER> Consolidated List of  <b>Diary Matters- civil </b>  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>


                       <BR>
             <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

<tr style="background: #918788;">

    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>

    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
    <td width="20%" style="font-weight: bold; color: #dce38d;">IA. No.</td>

</tr>

<?php

      $sno = 1;

        while($ro = mysql_fetch_array($serve_status)){
            $sno1 = $sno % 2;
            $dno = $ro['diary_no'];
            $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
            if($ro['active_fil_dt'] != '0000-00-00 00:00:00')
                $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
            else
                $active_fil_dt = "";
            $conn_no = $ro['conn_key'];
            $m_c = "";
            if($conn_no == $dno){
                $m_c = "Main";
            }
            if($conn_no != $dno AND $conn_no > 0){
                $m_c = "Conn.";
            }
            $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
            $filno_array = explode("-",$ro['active_fil_no']);

            if(empty($ro['reg_no_display'])){

                $fil_no_print = "Unregistred";
            }
            else{
/*                $fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
                if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                    $fil_no_print .= "-".ltrim($filno_array[2], '0');
                $fil_no_print .= "/".$ro['active_reg_year'];*/
                $fil_no_print = $ro['reg_no_display'];
            }


           if($sno1 == '1'){ ?>
            <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
            <?php } else { ?>
            <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
            <?php
            }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                     $padvname = ""; $radvname = ""; $impldname= "";
 $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n FROM
(SELECT a.diary_no, b.name,
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                    $resultsadv = mysql_query($advsql) or die(mysql_error());
                    if(mysql_num_rows($resultsadv) > 0) {
                        $rowadv = mysql_fetch_array($resultsadv);
                       // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                                  $radvname=  $rowadv["r_n"];
                                  $padvname=  $rowadv["p_n"];
                                  $impldname = $rowadv["i_n"];
                       // }
                    }


 if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                    if($ro['active_reg_year']!=0)
    $ten_reg_yr = $ro['active_reg_year'];
else
    $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

if($ro['active_casetype_id']!=0)
    $casetype_displ = $ro['active_casetype_id'];
else if($ro['casetype_id']!=0)
    $casetype_displ = $ro['casetype_id'];

$section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
LEFT JOIN users b ON usercode=dacode
LEFT JOIN usersection c ON b.section=c.id
WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ro[ref_agency_state_id]' AND a.display='Y' ";
$section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
if(mysql_num_rows($section_ten_rs)>0){
    $section_ten_row = mysql_fetch_array($section_ten_rs);
    $ro['section_name']=$section_ten_row["section_name"];
}
}




            ?>
                <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->

            <td align="left" style='vertical-align: top;'><?php echo $ro[docnum]."-".$ro[docyear] ?></td>

            </tr>

    <?php
            $sno++;
        }
        ?>
    </table>
                <?php
    }
    else{
        echo "No Records Found";
    }
    ?>
<BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
</div>
</div>

<?php
}
if($list_type==7)
     {
      //  echo "diary_civil_matters";
     // echo " fresh Crminal matters list to be generated.";



         // echo $serve_status="SELECT j.* FROM (SELECT  p.id AS is_printed, r.courtno, us.id, ifnull(u.name,tentative_da(m.diary_no)) as name, ifnull(us.section_name,tentative_section(m.diary_no)) as section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, reg_no_display, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y' LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` AND u.display = 'Y' LEFT JOIN usersection us ON us.id = u.section WHERE  h.mainhead = 'M'  and casetype_id in(1,3,5,7,11,13,15,17,19,21,22,23,24,25,27,32,34,40,31)  and h.next_dt = '$cl_date' AND  (active_reg_year='' or active_reg_year is null ) AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND m.c_status = 'P' AND h.clno > 0 AND h.brd_slno > 0 AND h.roster_id > 0 AND m.diary_no IS NOT NULL group by h.diary_no) j LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND j.next_dt != l.next_dt AND l.judges != 0 AND l.judges IS NOT NULL AND l.brd_slno > 0 AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL ORDER BY courtno, IF(j.section_name IS NULL, 9999, 0) ASC, j.section_name, j.name, j.brd_slno, IF(j.conn_key=j.diary_no,'0000-00-00',diary_no_rec_date) ASC ";
         //  $serve_status="select main.diary_no,reg_no_display,concat(pet_name ,' VS ',res_name) as Cause_Title , next_dt,name,tentative_section(main.diary_no) as Section,active_casetype_id,casetype_id,active_reg_year,diary_no_rec_date  from main join heardt on main.diary_no=heardt.diary_no left join users on main.dacode=users.usercode where next_dt='2018-07-04' and (coram <>''and coram <>0) and brd_slno <> 0 and main_supp_flag in(1,2)  and main.diary_no not in(select diary_no from drop_note where cl_date ='2018-07-04')";


     //   $serve_status="SELECT j.* FROM (SELECT p.id AS is_printed,docnum,docyear,casecode ,r.courtno, us.id, ifnull(u.name,tentative_da(m.diary_no)) as name, ifnull(us.section_name,tentative_section(m.diary_no)) as section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, reg_no_display, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y' LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode left join docdetails on h.diary_no=docdetails.diary_no and iastat='P' and doccode=8 LEFT JOIN users u ON u.usercode = m.`dacode` AND u.display = 'Y' LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M' and casetype_id in($ct) and h.next_dt = '$cl_date' AND (active_reg_year='' or active_reg_year is null ) AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND m.c_status = 'P' AND h.clno > 0 AND h.brd_slno > 0  AND board_type='J' AND h.roster_id > 0 AND m.diary_no IS NOT NULL group by h.diary_no) j LEFT JOIN last_heardt l ON j.diary_no = l.diary_no AND j.next_dt != l.next_dt AND l.judges != 0 AND l.judges IS NOT NULL AND l.brd_slno > 0 AND (l.bench_flag = '' OR l.bench_flag IS NULL) WHERE l.diary_no IS NULL order by docnum asc ";
    $serve_status="select a.* from (SELECT r.courtno,reg_no_display,u.name,us.section_name,
    l.purpose,docnum,docyear,
    c1.short_description,
    YEAR(m.active_fil_dt) fyr,
    active_reg_year,
    active_fil_dt,
    active_fil_no,
    m.pet_name,
    m.res_name,
    m.pno,
    m.rno,
    casetype_id,
    ref_agency_state_id,
    diary_no_rec_date,

    h.*
FROM
    heardt h
        INNER JOIN
    main m ON m.diary_no = h.diary_no
        INNER JOIN
    listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
        INNER JOIN
    roster r ON r.id = h.roster_id AND r.display = 'Y'
        LEFT JOIN
    brdrem br ON br.diary_no = m.diary_no
        left join docdetails on h.diary_no=docdetails.diary_no
        and iastat='P' and doccode=8
        LEFT JOIN
    casetype c1 ON m.active_casetype_id = c1.casecode
        LEFT JOIN
    users u ON u.usercode = m.`dacode`
        AND (u.display = 'Y' || u.display IS NULL)
        LEFT JOIN
    usersection us ON us.id = u.section
WHERE
    h.mainhead = 'M'
        AND h.next_dt = '2018-08-17'
        AND h.board_type = 'J'
        AND (h.main_supp_flag = 1
        OR h.main_supp_flag = 2)
        AND h.roster_id > 0
        AND m.diary_no IS NOT NULL
        AND m.c_status = 'P' and case_grp='R'
        AND (fil_no = '' OR fil_no IS NULL)
        AND subhead IN (811,812)
GROUP BY h.diary_no) a
ORDER BY $sorting ASC";
          // echo $serve_status="SELECT r.courtno,reg_no_display, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN roster r ON r.id = h.roster_id AND r.display = 'Y' LEFT JOIN brdrem br on br.diary_no=m.diary_no LEFT JOIN casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null) LEFT JOIN usersection us ON us.id = u.section WHERE h.mainhead = 'M'  and h.next_dt = '$cl_date'   AND active_casetype_id in (9,10,25,26,19,20) and h.board_type = 'J' and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' and subhead not in (811,812) group by h.diary_no ORDER BY $sorting ASC";

         $serve_status=  mysql_query($serve_status) or die("Error: ".__LINE__.mysql_error());
        //echo " the usercode is ".$ucode;

         if(mysql_num_rows($serve_status)>0)
           {
             ?>

                   <input type="button" onclick="printDiv('r')" value="print " />

                       <div id ="r">
                         <CENTER> Consolidated List of  <b>Diary Matters- criminal </b>  for  Causelist Dated :  <?php echo   date('d-m-Y', $date1); ?> <br>   </CENTER>


                       <BR>
             <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">

<tr style="background: #918788;">

    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>

    <td width="10%" style="font-weight: bold; color: #dce38d;">Diary No</td>
    <td width="20%" style="font-weight: bold; color: #dce38d;">IA. No.</td>

</tr>

<?php

      $sno = 1;

        while($ro = mysql_fetch_array($serve_status)){
            $sno1 = $sno % 2;
            $dno = $ro['diary_no'];
            $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
            if($ro['active_fil_dt'] != '0000-00-00 00:00:00')
                $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
            else
                $active_fil_dt = "";
            $conn_no = $ro['conn_key'];
            $m_c = "";
            if($conn_no == $dno){
                $m_c = "Main";
            }
            if($conn_no != $dno AND $conn_no > 0){
                $m_c = "Conn.";
            }
            $coram = $ro['coram'];
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
            $filno_array = explode("-",$ro['active_fil_no']);

            if(empty($ro['reg_no_display'])){

                $fil_no_print = "Unregistred";
            }
            else{
/*                $fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
                if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                    $fil_no_print .= "-".ltrim($filno_array[2], '0');
                $fil_no_print .= "/".$ro['active_reg_year'];*/
                $fil_no_print = $ro['reg_no_display'];
            }


           if($sno1 == '1'){ ?>
            <tr style=" background: #ececec;" id="<?php echo $dno; ?>">
            <?php } else { ?>
            <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
            <?php
            }


                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){
                        $res_name = $ro['res_name']." AND ANR.";
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                     $padvname = ""; $radvname = ""; $impldname= "";
 $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n FROM
(SELECT a.diary_no, b.name,
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res = 'I', 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                    $resultsadv = mysql_query($advsql) or die(mysql_error());
                    if(mysql_num_rows($resultsadv) > 0) {
                        $rowadv = mysql_fetch_array($resultsadv);
                       // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                                  $radvname=  $rowadv["r_n"];
                                  $padvname=  $rowadv["p_n"];
                                  $impldname = $rowadv["i_n"];
                       // }
                    }


 if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){
                    if($ro['active_reg_year']!=0)
    $ten_reg_yr = $ro['active_reg_year'];
else
    $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

if($ro['active_casetype_id']!=0)
    $casetype_displ = $ro['active_casetype_id'];
else if($ro['casetype_id']!=0)
    $casetype_displ = $ro['casetype_id'];

$section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
LEFT JOIN users b ON usercode=dacode
LEFT JOIN usersection c ON b.section=c.id
WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ro[ref_agency_state_id]' AND a.display='Y' ";
$section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
if(mysql_num_rows($section_ten_rs)>0){
    $section_ten_row = mysql_fetch_array($section_ten_rs);
    $ro['section_name']=$section_ten_row["section_name"];
}
}




            ?>
                <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->

            <td align="left" style='vertical-align: top;'><?php echo $ro[docnum]."-".$ro[docyear] ?></td>

            </tr>

    <?php
            $sno++;
        }
        ?>
    </table>
                <?php
    }
    else{
        echo "No Records Found";
    }
    ?>
<BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
</div>
</div>

<?php
}
 
?>
