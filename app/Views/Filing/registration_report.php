 <?= $this->extend('header') ?>
 <?= $this->section('content') ?>
     <!-- Main content -->
 <section class="content">
     <div style="text-align: center">
         <div class="dv_right">
             <input type="button" name="btn_pnt" id="btn_pnt" value="Print"/>
         </div>
     </div>
     <div id="dv_print">
         <h4 align="center">
             Supreme Court of India
         </h4>
         <div style="text-align: center">
             <h3>Diary No.- <?php echo $_REQUEST[d_no]; ?> - <?php echo $_REQUEST[d_yr]; ?></h3>
         </div>
         <?php
         $dairy_no=$_REQUEST[d_no].$_REQUEST[d_yr];
         $db = \Config\Database::connect();
         $sql = "Select fil_no,casetype_id,fil_dt,bench from main where diary_no='$dairy_no' union Select fil_no,casetype_id,fil_dt,bench from main_a where diary_no='$dairy_no'";
         $fil_no =  $db->query($sql)->getRowArray();

         $sql_ct_type = "Select short_description from casetype where casecode='$fil_no[casetype_id]' and display='Y'";
         $res_ct_typ = $db->query($sql_ct_type)->getRowArray();

         $bn_sql = "select bench_name from master_bench where display='Y' and id='$fil_no[bench]'";
         $res_bnch = $db->query($bn_sql)->getRowArray();
         ?>
         <table align="center" width="100%" cellpadding="1" cellspacing="1" class="c_vertical_align tbl_border">
             <tr>
                 <td>CASE TYPE : <strong><?php echo $res_ct_typ; ?></strong></td>
                 <td>CASE NUMBER: <strong><?php echo substr($fil_no['fil_no'], 3); ?></strong></td>
                 <td>CASE YEAR :
                     <strong><?php if (!empty(trim($fil_no['fil_dt']))) echo date('Y', strtotime($fil_no['fil_dt'])); ?></strong>
                 </td>
                 <td>Bench : <strong><?php echo $res_bnch; ?></strong></td>
             </tr>
         </table>
         <?php
         $sql = "SELECT p.sr_no, p.pet_res,p.ind_dep, p.partyname, p.sonof,p.prfhname, p.age,p.sex,p.caste, p.addr1, p.addr2,
 		p.pin, p.state, p.city,p.email, p.contact AS mobile,
 		p.deptcode,
 		(SELECT deptname  FROM  master.deptt WHERE deptcode=p.deptcode)deptname,c.skey
 	      FROM party p 
 		INNER JOIN main m ON  m.diary_no=p.diary_no  and sr_no=1 and pflag='P' and pet_res in ('P','R')
         LEFT JOIN master.casetype c ON c.casecode::text=SUBSTRING(m.fil_no,3,3)
         where m.diary_no='$dairy_no'  order by p.pet_res,p.sr_no";
         $result = $db->query($sql)->getResultArray();
         $ctr_p = 0; //for counting petining
         $ctr_r = 0; // for couting respondent
         if (sizeof($result) > 0) {
         $grp_pet_res = '';
         foreach ($result as $row) {
             ?>
             <div class="cl_center">
                 <h3><?php if ($row['pet_res'] == 'P') { ?> Petitioner <?php } else { ?> Respondent <?php } ?></h3>
             </div>
             <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                 <tr>
                     <td style="width: 15%">
                         Name
                     </td>
                     <td colspan="12">
                         <?php echo $row['partyname']; ?>
                     </td>
                 <tr>
                     <td>
                         C/o
                     </td>
                     <td colspan="12">
                         <?php
                         if ($row['sonof'] != '') {
                             echo $row['sonof'] . "/o " . $row[prfhname];
                         } ?>
                     </td>
                 </tr>
                 <tr>
                     <td>
                         Department
                     </td>
                     <td colspan="12">
                         <?php echo $row['deptname']; ?>
                     </td>
                 </tr>
                 <tr>
                     <td>
                         Address
                     </td>
                     <td colspan="12">
                         <?php
                         if ($row['addr1'] == '')
                             echo $row['addr2'];
                         else
                             echo $row['addr1'] . ', ' . $row['addr2'];
                         ?>
                     </td>
                 </tr>
                 <tr>
                     <td>
                         District
                     </td>
                     <td style="width: 250px">
                         <?php
                         $district = "Select Name from master.state where State_code='$row[state]' and District_code='$row[city]' and Sub_Dist_code=0 and Village_code=0 and display='Y'";
                         $district = $db->query($district)->getRowArray();
                         echo $district['Name'];
                         ?>
                     </td>
                     <td style="width: 60px">
                         Pincode
                     </td>
                     <td>
                         <?php echo $row['pin']; ?>
                     </td>
                     <td style="width: 50px">
                         Mobile
                     </td>
                     <td>
                         <?php echo $row['mobile']; ?>
                     </td>
                     <td style="width: 50px">
                         Gender
                     </td>
                     <td>
                         <?php echo $row['sex']; ?>
                     </td>
                     <td style="width: 40px">
                         Age
                     </td>
                     <td style="width: 50px">
                         <?php echo $row['age']; ?>
                     </td>
                     <td style="width: 60px">
                         Email Id
                     </td>
                     <td>
                         <?php echo $row['email']; ?>
                     </td>
                 </tr>
             </table>
         <?php } ?>
         <div class="cl_center"><h3>Categories</h3></div>
         <table class="table_tr_th_w_clr c_vertical_align" width="100%">
             <tr>
                 <td style="width: 15%">
                     Category
                 </td>
                 <td>
                     <?php
                     $category = "Select submaster_id ,od_cat,sub_name1,sub_name2,sub_name3,sub_name4,category_sc_old  from mul_category a 
         join master.submaster b on a.submaster_id=b.id 
         where diary_no = '$dairy_no' and a.display='Y' and b.display='Y'";
                     $category = $db->query($category)->getResultArray();
                     if (sizeof($category) > 0) {
                         $category_nm = '';
                         $mul_category = '';
                         foreach ($category as $row2) {
                             $new_nm = '';
                             if ($row2['sub_name2'])
                                 $new_nm = $new_nm . '-' . $row2['sub_name2'];
                             else if ($row2['sub_name3'])
                                 $new_nm = $new_nm . '-' . $row2['sub_name3'];
                             else if ($row2['sub_name4'])
                                 $new_nm = $new_nm . '-' . $row2['sub_name4'];
                             $category_nm = $row2['category_sc_old'] . '-' . $row2['sub_name1'] . $new_nm;
                             if ($mul_category == '') {
                                 $mul_category = $category_nm;
                             } else {
                                 $mul_category = $mul_category . ', ' . $category_nm;
                             }
                         }
                     }
                     echo $mul_category;
                     ?>
                 </td>
             </tr>
             <tr>
                 <td>
                     Act
                 </td>
                 <td>
                     <?php
                     $act = "SELECT a.act, STRING_AGG(b.section, ', ') AS section, c.act_name 
                                FROM act_main a 
                                LEFT JOIN act_section b ON a.id = b.act_id 
                                JOIN act_master c ON c.id = a.act 
                                WHERE a.diary_no = '$dairy_no' 
                                  AND a.display = 'Y' 
                                  AND b.display = 'Y' 
                                  AND c.display = 'Y' 
                                GROUP BY a.act, c.act_name;";
                     $act = $db->query($act)->getResultArray();
                     if (sizeof($act) > 0) {
                         $act_section = '';
                         foreach ($act as $row1) {
                             if ($act_section == '')
                                 $act_section = $row1['act_name'] . '-' . $row1['section'];
                             else
                                 $act_section = $act_section . ', ' . $row1['act_name'] . '-' . $row1['section'];
                         }
                     }
                     echo $act_section;
                     ?>
                 </td>
             </tr>
             <tr>
                 <td>
                     Provision of Law
                 </td>
                 <td>
                     <?php
                     $pol = "Select law from main a join  master.caselaw b on a.actcode=b.id where diary_no='$dairy_no'  and b.display='Y' union Select law from main_a a join  master.caselaw b on a.actcode=b.id where diary_no='$dairy_no'  and b.display='Y'";
                     $pol = $db->query($pol)->getRowArray();
                     echo $pol['law'];
                     ?>
                 </td>
             </tr>
         </table>
         <div class="cl_center"><h3>Petitioner Main Advocate</h3></div>
         <table class="table_tr_th_w_clr c_vertical_align" width="100%">
             <?php
             $pet_adv = "SELECT petadven, pet_adv, padvt, pet_adv_state_id, mobile, email, c.Name
 FROM main a
 JOIN bar b ON a.pet_adv_state_id = b.state_id
 AND concat( enroll_no, '/', year( enroll_date ) ) = petadven
 LEFT JOIN state c ON c.id_no = a.pet_adv_state_id
 WHERE diary_no='$dairy_no'";
             $pet_adv = "SELECT aor_code,enroll_no,year(enroll_date) enroll_date, mobile, email, c.Name,b.name adv_name FROM main a
             JOIN bar b ON a.pet_adv_id = b.bar_id  LEFT JOIN state c ON c.id_no = b.state_id WHERE diary_no='$dairy_no'";
             $pet_adv = mysql_query($pet_adv) or die("Error: " . __LINE__ . mysql_error());
             $r_pet_adv = mysql_fetch_array($pet_adv);
             ?>
             <tr>
                 <td style="width: 15%">
                     Name
                 </td>
                 <td colspan="4">
                     <?php echo $r_pet_adv[enroll_no] . '/' . $r_pet_adv[enroll_date] . ' - ' . $r_pet_adv['adv_name']; ?><?php if ($r_pet_adv[aor_code] != 0) {
                         echo 'AOR' . $r_pet_adv[aor_code];
                     } ?>
                 </td>
             </tr>
             <tr>
                 <td>
                     From State
                 </td>
                 <td style="width: 250px">
                     <?php echo $r_pet_adv[Name]; ?>
                 </td>
                 <td style="width: 70px">
                     Mobile No.
                 </td>
                 <td style="width: 100px">
                     <?php echo $r_pet_adv['mobile']; ?>
                 </td>
                 <td style="width: 70px">
                     Email id
                 </td>
                 <td>
                     <?php echo $r_pet_adv['email']; ?>
                 </td>
             </tr>
         </table>
         <div class="cl_center"><h3>Lower Court Details</h3></div>
         <table width="100%" border="1" style="border-collapse: collapse;" id="tr_id"
                class="table_tr_th_w_clr table_small_fomt">
             <thead>
             <tr>
                 <td>
                     S.No.
                 </td>
                 <td>
                     Court
                 </td>
                 <td>
                     Agency State
                 </td>
                 <td>
                     Agency Code
                 </td>
                 <td>
                     Case No.
                 </td>
                 <td>
                     Order Date
                 </td>
                 <td>
                     CNR No. /
                     Designation
                 </td>
                 <td>
                     Judge1/Judge2/Judge3
                 </td>
                 <td>
                     Description
                 </td>
                 <td>
                     Subject/Law
                 </td>
                 <td>
                     Police Station
                 </td>
                 <td>
                     Crime No./Year
                 </td>
                 <td>
                     Authority / Organisation / Impugned Order No.
                 </td>
                 <td>
                     Judgement Challanged
                 </td>
                 <td>
                     Judgement Type
                 </td>
                 <td>
                     Judgement Covered in
                 </td>
                 <td>
                     Sentence Imposed
                 </td>
                 <td>
                     Current Status
                 </td>
                 <td>
                     Period Undergone
                 </td>
                 <td>
                     Vehicle Number
                 </td>
             </tr>
             </thead>
             <?php
             $s_lw_ct = "SELECT  lct_dec_dt, lct_judge_name, lctjudname2, lctjudname3, l_dist, ct_code, l_state, Name, brief_desc desc1, sub_law usec2, lct_judge_desg,
 IF (
 ct_code =3, (
 SELECT Name
 FROM state s
 WHERE s.id_no = a.l_dist
 AND display = 'Y'
 ), (
 SELECT agency_name
 FROM ref_agency_code c
 WHERE c.cmis_state_id = a.l_state
 AND c.id = a.l_dist
 AND is_deleted = 'f'
 )
 )agency_name, crimeno, crimeyear, polstncode, (
 SELECT policestndesc
 FROM police p
 WHERE p.policestncd = a.polstncode
 AND p.display = 'Y'
 AND p.cmis_state_id = a.l_state
 AND p.cmis_district_id = a.l_dist
 )policestndesc, authdesc, l_inddep, l_orgname, l_ordchno, l_iopb, l_iopbn, l_org, lct_casetype, lct_caseno, lct_caseyear,
 IF (
 ct_code =4, (
 SELECT skey
 FROM casetype ct
 WHERE ct.display = 'Y'
 AND ct.casecode = a.lct_casetype
 ), (
 SELECT type_sname
 FROM lc_hc_casetype d
 WHERE d.lccasecode = a.lct_casetype
 AND d.display = 'Y'
 )
 )type_sname, a.lower_court_id, is_order_challenged, full_interim_flag, sentence,
 g.status , ugone_yr, ugone_mon, ugone_day,sentence_mth, judgement_covered_in, vehicle_code, vehicle_no, code,Post_name,cnr_no
 FROM lowerct a
 LEFT JOIN state b ON a.l_state = b.id_no
 AND b.display = 'Y'
 JOIN main e ON e.diary_no = a.diary_no
 LEFT JOIN authority f ON f.authcode = a.l_iopb
 AND f.display = 'Y'
 LEFT JOIN craent g ON g.lower_court_id = a.lower_court_id
 LEFT JOIN rto h ON h.id = a.vehicle_code AND h.display = 'Y'
 LEFT JOIN Post_t i ON i.Post_code = a.lct_judge_desg
 AND i.display = 'Y'
 WHERE a.diary_no = '$dairy_no'
 AND lw_display = 'Y'
 AND c_status = 'P' order by a.lower_court_id";
             $s_lw_ct = mysql_query($s_lw_ct) or die("Error: " . __LINE__ . mysql_error());
             if (mysql_num_rows($s_lw_ct) > 0) {
                 while ($row5 = mysql_fetch_array($s_lw_ct)) {
                     ?>
                     <tr>
                         <td>
                             <?php echo $c_s_c + 1; ?>
                         </td>
                         <td>
                             <?php
                             if ($row5['ct_code'] == '4')
                                 echo "Supreme Court";
                             else if ($row5['ct_code'] == '1')
                                 echo "High Court";
                             else if ($row5['ct_code'] == '3')
                                 echo "District Court";
                             else if ($row5['ct_code'] == '2')
                                 echo "Other";
                             else if ($row5['ct_code'] == '5')
                                 echo "State Agency";
                             ?>
                         </td>
                         <td>
                             <?php
                             echo $row5['Name'];
                             ?>
                         </td>
                         <td>
                             <?php
                             echo $row5['agency_name'];
                             ?>
                         </td>
                         <td>
                             <?php echo $row5['type_sname']; ?>-<?php echo $row5['lct_caseno']; ?>
                             -<?php echo $row5['lct_caseyear']; ?>
                         </td>
                         <td>
                             <?php echo date('d-m-Y', strtotime($row5['lct_dec_dt'])); ?>
                         </td>
                         <td>
                             <?php echo $row5['cnr_no']; ?> <?php if ($row5['cnr_no'] != '') { ?> / <?php } ?>
                             <?php
                             echo $row5['Post_name'];
                             ?>
                         </td>
                         <td>
                             <?php echo $row5['lct_judge_name']; ?> <?php if ($row5['lctjudname2'] != '') { ?> / <?php } ?>
                             <?php echo $row5['lctjudname2']; ?> <?php if ($row5['lctjudname3'] != '') { ?> / <?php } ?>
                             <?php echo $row5['lctjudname3']; ?>
                         </td>
                         <td>
                             <?php echo $row5['desc1'] ?>
                         </td>
                         <td>
                             <?php echo $row5['usec2'] ?>
                         </td>
                         <td>
                             <?php echo $row5['policestndesc']; ?>
                         </td>
                         <td>
                             <?php echo $row5['crimeno']; ?>/<?php echo $row5['crimeyear']; ?>
                         </td>
                         <td>
                             <?php
                             if ($row5['l_inddep'] == 'D1') {
                                 echo "State Department";
                             } else if ($row5['l_inddep'] == 'D2') {
                                 echo "Central Department";
                             } else if ($row5['l_inddep'] == 'D3') {
                                 echo "Other Organisation";
                             } else if ($row5['l_inddep'] == 'X') {
                                 echo "Xtra";
                             }
                             echo $row5['p_id_nm'];
                             ?>-<?php if ($row5['l_inddep'] == 'X') {
                                 echo $row5['l_iopbn'];
                             } else {
                                 echo $row5['authdesc'];
                             } ?> /
                             <?php echo $row5[l_orgname]; ?>/<?php echo $row5['l_ordchno'] ?>
                         </td>
                         <td>
                             <?php
                             if ($row5['is_order_challenged'] == 'Y')
                                 echo "Yes";
                             else if ($row5['is_order_challenged'] == 'N')
                                 echo "No";
                             ?>
                         </td>
                         <td>
                             <?php
                             if ($row5['full_interim_flag'] == 'I')
                                 echo 'Interim';
                             else if ($row5['full_interim_flag'] == 'F')
                                 echo 'Final';
                             else
                                 echo '-';
                             ?>
                         </td>
                         <td>
                             <?php echo $row5['judgement_covered_in']; ?>
                         </td>
                         <td>
                             <?php
                             if ($row5['sentence'] != 0) {
                                 if ($row5['sentence'] == '99')
                                     echo "Life Imprisonment";
                                 else
                                     echo $row5['sentence'] . 'Yrs';
                             }
                             ?><?php if ($row5['sentence_mth'] != 0) {
                                 echo $row5['sentence_mth'] . 'Mth';
                             } ?>
                         </td>
                         <td>
                             <?php
                             if ($row5['status'] == 'C')
                                 echo "C-Custody";
                             else if ($row5['status'] == 'B')
                                 echo "B-Bail Out";
                             else if ($row5['status'] == 'A')
                                 echo "A-Absconding";
                             else if ($row5['status'] == 'O')
                                 echo "O-Others";
                             ?>
                         </td>
                         <td>
                             <?php if ($row5['ugone_yr'] != '0') echo $row5['ugone_yr']; ?> <?php if ($row5['ugone_yr'] != '0' && $row5['ugone_yr'] != null) { ?> (Years) <?php } ?>
                             <?php if ($row5['ugone_mon'] != '0') echo $row5['ugone_mon']; ?><?php if ($row5['ugone_mon'] != '0' && $row5['ugone_mon'] != null) { ?> (Months) <?php } ?>
                             <?php if ($row5['ugone_day'] != '0') echo $row5['ugone_day']; ?><?php if ($row5['ugone_day'] != '0' && $row5['ugone_day'] != null) { ?> (Days) <?php } ?>
                         </td>
                         <td>
                             <?php echo $row5['code'] . ' '; ?><?php echo $row5['vehicle_no']; ?>
                         </td>
                     </tr>
                     <?php
                     $c_s_c++;
                 }
             }
             ?>
         </table>
         <div class="cl_center"><h3>Limitation</h3></div>
         <?php
         $sql = "SELECT lct_dec_dt, l_dist, ct_code, l_state, Name,
 IF (
 ct_code =3, (
 SELECT Name
 FROM state s
 WHERE s.id_no = a.l_dist
 AND display = 'Y'
 ), (
 SELECT agency_name
 FROM ref_agency_code c
 WHERE c.cmis_state_id = a.l_state
 AND c.id = a.l_dist
 AND is_deleted = 'f'
 )
 )agency_name, lct_casetype, lct_caseno, lct_caseyear,
 IF (
 ct_code =4, (
 SELECT skey
 FROM casetype ct
 WHERE ct.display = 'Y'
 AND ct.casecode = a.lct_casetype
 ), (
 SELECT type_sname
 FROM lc_hc_casetype d
 WHERE d.lccasecode = a.lct_casetype
 AND d.display = 'Y'
 )
 )type_sname, a.lower_court_id,limit_days,descr
 FROM lowerct a
 LEFT JOIN state b ON a.l_state = b.id_no
 AND b.display = 'Y'
 JOIN main e ON e.diary_no = a.diary_no
 LEFT JOIN case_limit cl ON cl.lowerct_id = a.lower_court_id
 AND case_lim_display = 'Y'
 WHERE a.diary_no = '$dairy_no' 
 AND lw_display = 'Y'
 AND c_status = 'P'
 AND is_order_challenged = 'Y'
 ORDER BY a.lower_court_id";
         $sql = mysql_query($sql) or die("Error: " . __LINE__ . mysql_error());
         ?>
         <table class="table_tr_th_w_clr c_vertical_align table_small_fomt" cellpadding="5" cellspacing="5" width="100%">
             <thead>
             <tr>
                 <th>
                     S.No.
                 </th>
                 <th>
                     Court
                 </th>
                 <th>
                     State
                 </th>
                 <th>
                     Bench
                 </th>
                 <th>
                     Case No.
                 </th>
                 <th>
                     Order Date
                 </th>
                 <th>
                     Petition in Time
                 </th>
                 <th>
                     Description
                 </th>
             </tr>
             </thead>
             <?php
             $sno = 0;
             while ($row = mysql_fetch_array($sql)) {
                 ?>
                 <tr>
                     <td>
                         <?php echo $sno + 1; ?>
                     </td>
                     <td>
                         <?php
                         if ($row[ct_code] == '1')
                             echo "High Court";
                         else if ($row[ct_code] == '2')
                             echo "Other";
                         else if ($row[ct_code] == '3')
                             echo "District Court";
                         else if ($row[ct_code] == '4')
                             echo "Supreme Court";
                         else if ($row[ct_code] == '5')
                             echo "State Agency";
                         ?>
                     </td>
                     <td>
                         <?php
                         echo $row['Name'];
                         ?>
                     </td>
                     <td>
                         <?php
                         echo $row['agency_name'];
                         ?>
                     </td>
                     <td>
                         <?php
                         echo $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                         ?>
                     </td>
                     <td>
                         <span id="sp_lct_dec_dt<?php echo $sno; ?>"><?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?></span>
                     </td>
                     <td>
                         <?php
                         if ($row['limit_days'] != '') {
                             if ($row['limit_days'] <= 0) {
                                 echo "Yes";
                             } else if ($row['limit_days'] > 0) {
                                 echo "No";
                             }
                         } else {
                             echo "-";
                         }
                         ?>
                     </td>
                     <td>
                         <?php echo $row['descr']; ?>
                     </td>
                 </tr>
                 <?php
                 $sno++;
             }
             ?>
         </table>
     </div>
     <div class="cl_center">
         <input type="button" name="btn_pnt" id="btn_pnt" value="Print"/>
     </div>
     <?php
     } else {
     ?>
     <div class="cl_center"><b>No Record Found</b></div>
     <?php
     }
     ?>
 </section>
 <!-- /.content -->
 <?= $this->endSection() ?>