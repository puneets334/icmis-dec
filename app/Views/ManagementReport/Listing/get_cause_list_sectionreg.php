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
        $sec_id2 = '';
    }
    else{
        $sql_sec_name ="select section_name from master.usersection where id = $_POST[sec_id]";
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
        <H1>Cause List for  <?php echo $list_dt; ?> (<?php echo $mainhead_descri; ?>)<br><?php echo $main_supl_head; ?>  <?php echo "  Court No. ". $_POST['courtno']?></H1>

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
        

  /*  $sql = "SELECT concat(b1.title,b1.name) as pet_adv_name,concat(b2.title,b2.name) as res_adv_name, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt,
     active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark,  h.* 
FROM   sci_cmis_final.heardt h
INNER JOIN   sci_cmis_final.main m ON m.diary_no = h.diary_no
INNER JOIN   sci_cmis_final.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
INNER JOIN   sci_cmis_final.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no
LEFT JOIN brdrem br on br.diary_no=m.diary_no
  left join bar b1 on m.pet_adv_id=b1.bar_id
        left join bar b2  on m.res_adv_id=b2.bar_id
LEFT JOIN   sci_cmis_final.casetype c1 ON m.active_casetype_id = c1.casecode
$cl_print_jo
LEFT JOIN users u ON u.usercode = m.`dacode` and (u.display = 'Y' || u.display is null)
LEFT JOIN usersection us ON us.id = u.section $sec_id
WHERE 
$cl_print_jo2   
h.mainhead = '$mainhead' $main_suppl $sec_id2 and h.next_dt = '".$_POST['list_dt']."' 
$mdacode 
$lp $board_type 
and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 
AND m.diary_no IS NOT NULL AND m.c_status = 'P' $section
group by h.diary_no ORDER BY $orderby r.courtno, IF(us.section_name IS NULL, 9999, 0) ASC, us.section_name, u.name, h.brd_slno, IF(h.conn_key=h.diary_no,'0000-00-00',m.diary_no_rec_date) ASC ";
 
 */
 
        
//       echo $sql = "SELECT concat(b1.title,b1.name) as pet_adv_name,concat(b2.title,b2.name) as res_adv_name, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt,
//      active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark,  h.* 
// FROM heardt h 
// INNER JOIN main m ON m.diary_no = h.diary_no 
// INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y'
// INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no
// LEFT JOIN brdrem br on br.diary_no=m.diary_no
//   left join master.bar b1 on m.pet_adv_id=b1.bar_id
//         left join master.bar b2  on m.res_adv_id=b2.bar_id
// LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode   
// $cl_print_jo
// LEFT JOIN master.users u ON u.usercode = m.dacode and (u.display = 'Y' || u.display is null)
// LEFT JOIN master.usersection us ON us.id = u.section $sec_id
// WHERE 
// $cl_print_jo2   
// h.mainhead = '$mainhead' $main_suppl $sec_id2 and h.next_dt = '".$_POST['list_dt']."' 
// $mdacode 
// $lp $board_type 
// and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 
// AND m.diary_no IS NOT NULL AND m.c_status = 'P' $section
// group by h.diary_no ORDER BY $orderby r.courtno,brd_slno, CASE WHEN us.section_name IS NULL THEN 9999 ELSE 0 END ASC, us.section_name, u.name, h.brd_slno, IF(h.conn_key=h.diary_no,'0000-00-00',m.diary_no_rec_date) ASC ";
// die;


  $sql = "SELECT concat(b1.title,b1.name) as pet_adv_name,concat(b2.title,b2.name) as res_adv_name, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, YEAR(m.active_fil_dt) fyr, active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.* FROM heardt h INNER JOIN main m ON m.diary_no = h.diary_no INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no LEFT JOIN brdrem br on br.diary_no=m.diary_no left join master.bar b1 on m.pet_adv_id=b1.bar_id left join master.bar b2 on m.res_adv_id=b2.bar_id LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y' LEFT JOIN master.users u ON u.usercode = m.dacode and (u.display = 'Y' || u.display is null) LEFT JOIN master.usersection us ON us.id = u.section $sec_id WHERE $cl_print_jo2 h.mainhead = '$mainhead' $main_suppl $sec_id2 and h.next_dt = '".$_POST['list_dt']."' $mdacode $lp $board_type and h.main_supp_flag IN ('1', '2') AND h.roster_id > 0 AND m.diary_no IS NOT NULL AND m.c_status = 'P' $section group by h.diary_no, b1.title, b1.name, b2.title,b2.name, r.courtno, u.name, us.section_name, l.purpose, c1.short_description, m.active_fil_dt, active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date, br.remark ORDER BY $orderby r.courtno,brd_slno, CASE WHEN us.section_name IS NULL THEN 9999 ELSE 0 END ASC, us.section_name, u.name, h.brd_slno, CASE WHEN h.conn_key = h.diary_no THEN to_date('0001-01-01', 'YYYY-MM-DD') ELSE m.diary_no_rec_date END ASC";
//  die;

$query = $db->query($sql);
if ($query->getNumRows() >= 1) {
    $res = $query->getResultArray();
            ?>
            <!-- <input name="prnnt1" type="button" id="prnnt1" value="Print" > -->
            <table align="left" width="100%" border="1px;" style="font-size:15px; table-layout: fixed;">

                <tr style="background: #918788;">
                    <!-- td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td -->
                    <td width="5%" style="font-weight: bold; color: #dce38d;">Item No.</td>
                    <td width="7%" style="font-weight: bold; color: #dce38d;">Diary No</td>
                    <td width="15%" style="font-weight: bold; color: #dce38d;">Reg No.</td>
                    <td width="15%" style="font-weight: bold; color: #dce38d;">Cause Title</td>
                    <td width="15%" style="font-weight: bold; color: #dce38d;"> Advocates</td>
                   
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
                    $remark=$ro['remark'];
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    $active_fil_dt = date('d-m-Y', strtotime($ro['active_fil_dt']));
                    $conn_no = $ro['conn_key'];

                      
                    $padv='';
                    if($ro['pet_adv_name']=='') 
                    {
                        $padv='';
                    }
                    else
                    {
                        $padv=$ro['pet_adv_name']." (P) " ;
                    } 


                    $radv='';
                    if($ro['res_adv_name']=='') 
                    {
                        $radv='';
                    }
                    else
                    {
                        $radv=$ro['res_adv_name']." (R) " ;
                    }                      
                    $adv=$padv."<br>".$radv;
                     
                    
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
                        /*$fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
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

                    // $advsql = "SELECT a.*, STRING_AGG(CASE WHEN pet_res = 'R' THEN name || grp_adv ELSE NULL END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS r_n, STRING_AGG(CASE WHEN pet_res = 'P' THEN name || grp_adv ELSE NULL END, '' ORDER BY adv_type DESC, pet_res_no ASC) AS p_n, STRING_AGG(CASE WHEN pet_res = 'I' THEN name || grp_adv ELSE NULL END, '' ORDER BY adv_type DESC, pet_res_no ASC ) AS i_n FROM (SELECT a.diary_no, b.name, STRING_AGG(COALESCE(a.adv, ''), '' ORDER BY CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, adv_type DESC, pet_res_no ASC) AS grp_adv, a.pet_res, a.adv_type, pet_res_no FROM advocate a LEFT JOIN master.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no = '".$ro["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, pet_res_no) a GROUP BY diary_no, a.name, a.grp_adv, a.pet_res, a.adv_type, a.pet_res_no";

                    // $query = $db->query($advsql);
                    // if ($query->getNumRows() >= 1) {
                    //     $rowadv = $query->getRowArray();
                    //     // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                    //     $radvname=  $rowadv["r_n"];
                    //     $padvname=  $rowadv["p_n"];
                    //     $impldname = $rowadv["i_n"];
                    //     // }
                    // }

                    // pr($ro);


                    if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 )
                    {

                        if($ro['active_reg_year']!=0)
                            $ten_reg_yr = $ro['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

                        if(isset($ro['active_casetype_id']) && $ro['active_casetype_id']!=0)
                            $casetype_displ = $ro['active_casetype_id'];
                        else if($ro['casetype_id']!=0)
                            $casetype_displ = $ro['casetype_id'];

                        $section_ten_q = "SELECT dacode,section_name,name FROM master.da_case_distribution a LEFT JOIN master.users b ON usercode=dacode LEFT JOIN master.usersection c ON b.section=c.id WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ro[ref_agency_state_id]' AND a.display='Y' ";
                                

                        // $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
                        // if(mysql_num_rows($section_ten_rs)>0){
                        //     $section_ten_row = mysql_fetch_array($section_ten_rs);

                        $querysub = $db->query($section_ten_q);
                        if ($querysub->getNumRows() >= 1) {
                            $section_ten_row = $querysub->getRowArray();
                            if($ro['section_name'] =='')
                            {
                                $ro['section_name']=  $ro['dno'];
                            }
                            else
                            {
                                echo $ro['section_name']=$section_ten_row["section_name"];
                                // echo" dacode is ". $section_ten_row[dacode];
                            }
                            if($section_ten_row["dacode"] == 0)
                                $ro['name']="no dacode";

                        }
                    }
                    ?>
                    <!-- td align="left" style='vertical-align: top;'><?php // echo $sno; ?></td -->
                 <!--   <td align="left" style='vertical-align: top;'><?php //echo $ro['courtno'];-->
                        //                $q_c = "SELECT courtno from roster where id = '".$ro['roster_id']."'";
                        //                    $qc_rds = mysql_query($q_c) or die(mysql_error());
                        //                    $ros_cc = mysql_fetch_array($qc_rds);
                        //                echo $ros_cc['courtno'];
                        ?></td>-->
                    <td align="left" style='vertical-align: top;'><?php echo $ro['brd_slno']."<br>".$m_c; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td align="left" style='vertical-align: top;'><?php echo $fil_no_print."<br>Rdt ".$active_fil_dt; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $pet_name."<br/>Vs<br/>".$res_name; ?></td>
                    <td align="left" style='vertical-align: top;'><?php echo $adv; ?></td>
                   
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
    </div>



