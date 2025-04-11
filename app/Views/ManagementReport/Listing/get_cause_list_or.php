<?= view('header') ?>
<?php

if(isset($_POST['mainhead']) && (isset($_POST['list_dt']) && ($_POST['list_dt'] !=  0 && $_POST['list_dt'] != -1))  )
{
    $list_dt = date('d-m-Y', strtotime($_POST['list_dt']));

    $main_supl_head = '';
    $mainhead = $_POST['mainhead'];
    if($mainhead == 'M'){
        $mainhead_descri = "Miscellaneous Hearing";
    }
    if($mainhead == 'F'){
        $mainhead_descri = "Regular Hearing";
    }
    if($mainhead == 'L'){
        $mainhead_descri = "Lok Adalat";
    }

    if($_POST['lp'] == "all"){
        $lp = "";
    }
    else{
        $lp = "and h.listorder = '".$_POST['lp']."'";
    }

    if($_POST['main_suppl'] == "0"){
        $main_suppl = "";
    }
    else{
        $main_suppl = "AND h.main_supp_flag = '".$_POST['main_suppl']."'";
        if($_POST['main_suppl'] == "1"){
            $main_supl_head = "Main List";
        }
        if($_POST['main_suppl'] == "2"){
            $main_supl_head = "Supplimentary List";
        }
    }

    if($_POST['courtno'] == "0"){
        $court_no = "";
    }
    else{
        $court_no = "AND r.courtno = '".$_POST['courtno']."'";
    }

    if($_POST['board_type'] == "0"){
        $board_type = "";
    }
    else{
        // $board_type = "AND h.board_type = '".$_POST['board_type']."'";
        $board_type = "AND h.board_type = '".$_POST['board_type']."'";
    }
    // echo $board_type;
    if(isset($_POST['orderby']) && $_POST['orderby'] == "1"){
        $orderby = "r.courtno, ";
    }
    else if(isset($_POST['orderby']) && $_POST['orderby'] == "2"){
        $orderby = "us.id, ";
    }
    else{
        $orderby = "";
    }

    if($_POST['sec_id'] == "0"){
        $sec_id = "";
        $sec_id2 = "";
    }
    else{
        $sql_sec_name ="select section_name from usersection where id = $_POST[sec_id]";
        // $sql_sec_name;
        // $re_sec=mysql_query($sql_sec_name) or die(mysql_error());
        $re_sec = $db->query($sql_sec_name);
        $rp = $re_sec->getRowArray();
        // $rp = mysql_fetch_array($re_sec);
        $sec_name= $rp['section_name'];
        $sec_id = " and (us.id ='".$_POST['sec_id']."'  or tentative_section(h.diary_no) = '$sec_name' )";
        $sec_id2 = "AND us.id is not null";
    }



    ?>
    <input name="prnnt1" type="button" id="prnnt1" value="Print" >
    <div id="prnnt" style="text-align: center; font-size:10px;">
        <H1>Cause List for  <?php echo $list_dt; ?> (<?php echo $mainhead_descri; ?>)<br><?php echo $main_supl_head; ?>  <?php if($_POST['courtno']=='0') echo "All Court"; else echo "  Court No. ". $_POST['courtno']?></H1>

        <?php
        $ucode     = session()->get('login')['usercode'];
        $usertype  = session()->get('login')['usertype'];
        $section1  = session()->get('login')['section'];

        // $ucode = $_SESSION['dcmis_user_idd'];
        // $usertype=$_SESSION['dcmis_usertype'];
        // $section1=$_SESSION['dcmis_section'];


        $sql_sec_name ="select section_name from master.usersection where id = $section1";
        
        // $re_sec=mysql_query($sql_sec_name) or die(mysql_error());
        // $rp = mysql_fetch_array($re_sec);
        $re_sec = $db->query($sql_sec_name);
        $rp = $re_sec->getRowArray();
        
        $sec_name= $rp['section_name'];
        
        // echo "section name is ".$sec_name;
        if($usertype == '14' and $section!=77 and $ucode != 2516)
        {
            $sq_u = "SELECT GROUP_CONCAT(u2.usercode) as allda FROM master.users u LEFT JOIN master.users u2 ON u2.section = u.section WHERE u.display = 'Y' AND u.usercode = '$ucode' group by u2.section";
            //"<br>";
            // $re_u=mysql_query($sq_u) or die(mysql_error());
            // $ro_u = mysql_fetch_array($re_u);
            $re_u = $db->query($sq_u);
            $ro_u = $re_u->getRowArray();
            $all_da = $ro_u['allda'];
            // echo $all_da;
            $mdacode = "AND (m.dacode IN ($all_da)  or m.dacode=0)";
        }

        else if ($usertype == '17' OR $usertype == '50' OR $usertype == '51'){
            $mdacode = "AND m.dacode = '$ucode'";
        }
        else{
            $mdacode = "";
        }
        if($ucode == '1' OR $usertype==3 or $ucode == 2516)
        {
            $mdacode = "";
            // $cl_print_jo = "";
            // $cl_print_jo2 = "";
            $cl_print_jo = "LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'";
            $cl_print_jo2 = "p.id IS NOT NULL AND ";

        }
        else{

            $cl_print_jo = "LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'";
            $cl_print_jo2 = "p.id IS NOT NULL AND ";

        }

        if($ucode == '1' OR $usertype==3 or $ucode == 2516)
        {
            $section='';
        }
        else
        {
            //  echo "dfjasdfj";
            $section= "and (us.id='$section1' or tentative_section(h.diary_no) = '$sec_name' )";
            //ech "section is ".$section;
        }        


        //    echo $sql = "select aa.courtno, count(*) total,count(if(is_uploaded='NotUplodaded',1,null))not_uploaded,count(if(is_uploaded='Uploaded',1,null)) uploaded from (SELECT concat(b1.title,b1.name) as pet_adv_name,concat(b2.title,b2.name) as res_adv_name, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark,  h.*,if(od.web_status is null or od.web_status='' OR od.web_status = '0','NotUplodaded','Uploaded') is_uploaded FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN   master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no LEFT JOIN brdrem br on br.diary_no=m.diary_no left join master.bar b1 on m.pet_adv_id=b1.bar_id left join master.bar b2  on m.res_adv_id=b2.bar_id LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode $cl_print_jo LEFT JOIN master.users u ON u.usercode = m.dacode and (u.display = 'Y' || u.display is null) LEFT JOIN master.usersection us ON us.id = u.section $sec_id  left join office_report_details od on h.diary_no=od.diary_no and h.next_dt=od.order_dt WHERE $cl_print_jo2 h.mainhead = '$mainhead' $main_suppl $sec_id2 and h.next_dt = '".$_POST['list_dt']."' $mdacode $lp $board_type and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' $section and (od.display='Y' or od.display is null or od.display='') group by h.diary_no ORDER BY $orderby r.courtno,brd_slno, IF(us.section_name IS NULL, 9999, 0) ASC, us.section_name, u.name, h.brd_slno, IF(h.conn_key=h.diary_no,'0000-00-00',m.diary_no_rec_date) ASC)aa group by aa.courtno";

           $sql = "SELECT aa.courtno, count(*) total, count(CASE WHEN is_uploaded = 'NotUplodaded' THEN 1 ELSE null END) AS not_uploaded, count(CASE WHEN is_uploaded = 'Uploaded' THEN 1 ELSE null END) AS uploaded FROM (SELECT concat(b1.title, b1.name) as pet_adv_name, concat(b2.title,b2.name) as res_adv_name, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.*, CASE WHEN od.web_status IS NULL OR od.web_status::text = '' OR od.web_status::text = '0' THEN 'NotUplodaded' ELSE 'Uploaded' END AS is_uploaded FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no LEFT JOIN brdrem br on br.diary_no=m.diary_no left join master.bar b1 on m.pet_adv_id=b1.bar_id left join master.bar b2 on m.res_adv_id=b2.bar_id LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode $cl_print_jo LEFT JOIN master.users u ON u.usercode = m.dacode and (u.display = 'Y' || u.display is null) LEFT JOIN master.usersection us ON us.id = u.section $sec_id left join office_report_details od on h.diary_no=od.diary_no and h.next_dt=od.order_dt WHERE $cl_print_jo2 h.mainhead = '$mainhead' $main_suppl $sec_id2 and h.next_dt = '".$_POST['list_dt']."' $mdacode $lp $board_type and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' $section and (od.display='Y' or od.display is null or od.display='') group by h.diary_no, b1.title, b1.name, b2.title, b2.name, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt, active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, remark, od.web_status ORDER BY $orderby r.courtno, brd_slno, CASE WHEN us.section_name IS NULL THEN 9999 ELSE 0 END ASC, us.section_name, u.name, h.brd_slno, CASE WHEN h.conn_key = h.diary_no THEN NULL ELSE m.diary_no_rec_date END ASC) aa GROUP BY aa.courtno";
// die;
        // $res=mysql_query($sql);

        $query = $db->query($sql);
        
        if ($query->getNumRows() >= 1) {
            $res = $query->getResultArray();
        // } else {
        //     return [];
        // }

        // if(mysql_num_rows($res)>0)
        // {

            // echo $dataquery="SELECT group_concat(a.diary_no) total FROM (select h.diary_no FROM heardt h INNER JOIN   main m ON m.diary_no = h.diary_no INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no LEFT JOIN brdrem br on br.diary_no=m.diary_no left join master.bar b1 on m.pet_adv_id=b1.bar_id left join master.bar b2  on m.res_adv_id=b2.bar_id LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode $cl_print_jo LEFT JOIN master.users u ON u.usercode = m.dacode and (u.display = 'Y' || u.display is null) LEFT JOIN master.usersection us ON us.id = u.section $sec_id left join office_report_details od on h.diary_no=od.diary_no and h.next_dt=od.order_dt WHERE $cl_print_jo2 h.mainhead = '$mainhead' $main_suppl $sec_id2 and h.next_dt = '".$_POST['list_dt']."' $mdacode $lp $board_type and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' $section and (od.display='Y' or od.display is null or od.display='') group by h.diary_no ORDER BY $orderby r.courtno,brd_slno, IF(us.section_name IS NULL, 9999, 0) ASC, us.section_name, u.name, h.brd_slno, IF(h.conn_key=h.diary_no,'0000-00-00',m.diary_no_rec_date) ASC)a";

            $dataquery = "SELECT string_agg(a.diary_no::text, ',') AS total FROM (SELECT h.diary_no FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no LEFT JOIN brdrem br ON br.diary_no = m.diary_no LEFT JOIN master.bar b1 ON m.pet_adv_id = b1.bar_id LEFT JOIN master.bar b2 ON m.res_adv_id = b2.bar_id LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode $cl_print_jo LEFT JOIN master.users u ON u.usercode = m.dacode AND (u.display = 'Y' OR u.display IS NULL) LEFT JOIN master.usersection us ON us.id = u.section $sec_id LEFT JOIN office_report_details od ON h.diary_no = od.diary_no AND h.next_dt = od.order_dt WHERE $cl_print_jo2 h.mainhead = '$mainhead' $main_suppl $sec_id2 AND h.next_dt = '".$_POST['list_dt']."' $mdacode $lp $board_type AND h.main_supp_flag IN ('1', '2') AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' $section AND (od.display = 'Y' OR od.display IS NULL OR od.display = '') GROUP BY h.diary_no, r.courtno, brd_slno, us.section_name, u.name, m.diary_no_rec_date, h.conn_key ORDER BY $orderby r.courtno, brd_slno, CASE WHEN us.section_name IS NULL THEN 9999 ELSE 0 END ASC, us.section_name, u.name, h.brd_slno, CASE WHEN h.conn_key = h.diary_no THEN NULL ELSE m.diary_no_rec_date::text END ASC) a";

            // $dataquery=mysql_query($dataquery);
            $dataquery = $db->query($dataquery);
            
        // if ($dataquery->getNumRows() >= 1) {
            // return $query->getResultArray();
            //   $row=mysql_fetch_array($dataquery);
            $row = $dataquery->getRowArray();
            $diarynolist = $row['total'];
            ?>
            <table  width="100%" border="1px;" style="font-size:15px; table-layout: fixed;" >

                <tr style="background: #918788;">
                    <!-- td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td -->
                    <td width="5%" style="font-weight: bold; color: #dce38d;text-align:center;">Court No.</td>
                    <td width="7%" style="font-weight: bold; color: #dce38d;text-align:center;">Total Items</td>
                    <td width="15%" style="font-weight: bold; color: #dce38d;text-align:center;">Total- OR Publised</td>
                    <td width="15%" style="font-weight: bold; color: #dce38d;text-align:center;">Total- OR Not Publised</td>


                    <!--  <td width="5%" style="font-weight: bold; color: #dce38d;">Section Name</td>
                      <td width="10%" style="font-weight: bold; color: #dce38d;">DA Name</td>
                      <td width="20%" style="font-weight: bold; color: #dce38d;">Statutory Info.</td>
                      <td width="7%" style="font-weight: bold; color: #dce38d;">Listed Before</td>
                      <td width="8%" style="font-weight: bold; color: #dce38d;">Purpose</td>
                      <td width="10%" style="font-weight: bold; color: #dce38d;">Trap</td>
                 --> </tr>
                <?php
                $sno = 1;

                // while($ro = mysql_fetch_array($res)){
                foreach ($res as $ro) {
                   ?>
                    <td align="left" style='vertical-align: top;'><?php echo $ro['courtno']?></td>

                    <td align="left" style='vertical-align: top;'><?php echo $ro['total']; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $ro['uploaded']; ?></td>
                    <!--<td align="left" style='vertical-align: top;'><?php /*echo $ro['not_uploaded']; */?></td>-->
                    <td align="left" style='vertical-align: top;'> <?php echo "<span style='font-weight: bold;color: red;cursor:pointer;vertical-align: top;' id='doc_$ro[courtno]_$_POST[list_dt]_$diarynolist' >".$ro['not_uploaded']."</span>"; ?></td>






                    </tr>
                    <?php
                    $sno++;
                }
                ?>
            </table>
            <?php
        }
        else{
            echo "No Recrods Found";
        }
    }
    
        ?>
        <BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>
    </div>

    <div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
        <!--<input name="prnnt1" type="button" id="ebublish" value="e-Publish" >-->
        <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">
<!--    <input name="sh4" type="button" id="sh4" onClick="toggle_note4(this.id);" value="Header Note">
    <input name="sh5" type="button" id="sh5" onClick="toggle_note5(this.id);" value="Footer Note">
    <input name="sh3" type="button" id="sh3" onClick="toggle_note3(this.id);" value="Drop Note">    -->
</span>
        
    </div>




