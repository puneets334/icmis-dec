<?php
include("../../includes/db_inc.php");
include("../common/function.php");
session_start();
//echo '<pre>'; print_r($_POST); exit;
$output = '';
if(isset($_POST['listing_dts']) && !empty($_POST['listing_dts']) ){
    $listing_dts = date('Y-m-d',strtotime(trim($_POST['listing_dts'])));
    $list_type = trim($_POST['list_type']);
    $judge_code = trim($_POST['judge_code']);
    $court_no = trim($_POST['court_no']);
    $currentDate = date('Y-m-d');
    if(isset($list_type) && !empty($list_type) && $list_type !=0){
        if($list_type == 4){
            $mainhead_query = "and h.mainhead = 'M'";
            $board_type_query = "and h.board_type = 'J'";
        }
        else if($list_type == 3){
            $mainhead_query = "and h.mainhead = 'F'";
            $board_type_query = "and h.board_type = 'J'";
        }
        else if($list_type == 5){
            $mainhead_query = "and h.mainhead = 'M'";
            $board_type_query = "and h.board_type = 'C'";
        }
        else if($list_type == 6){
            $mainhead_query = "and h.mainhead = 'M'";
            $board_type_query = "and h.board_type = 'R'";
        }
        else{
            echo "List Type Not Defined";
            exit();
        }
    }
    if($court_no > 0){
        if($court_no < 20){
            $judge_code_query = "and (r.courtno = ".$court_no." OR r.courtno = ".($court_no+30).")";
        }
        else if($court_no >= 21){
            $judge_code_query = "and (r.courtno = ".$court_no." OR r.courtno = ".($court_no+40).")";
        }
        else{
            echo "Wrong Court Number Selected";
            exit;
        }
    }
    else if($judge_code > 0){
        $judge_code_query = "and rj.judge_id = $judge_code";
    }
    else{
        $judge_code_query = "";
    }

    $sql = "";
    $sql = "select cl.id as is_printed, m.reg_no_display,m.pet_name,m.res_name, h.main_supp_flag,h.board_type,h.judges,h.roster_id,h.brd_slno,h.clno,h.mainhead,h.next_dt,h.conn_key,
    h.diary_no,r.courtno,group_concat(advocate_id) as advocate_ids, count(distinct advocate_id) total_advocates from main m 
    inner join heardt h on m.diary_no = h.diary_no
    LEFT JOIN conct ct on m.diary_no=ct.diary_no and ct.list='Y'
    inner join roster r on h.roster_id = r.id
    inner join roster_judge rj on rj.roster_id = r.id
    inner join advocate a on m.diary_no=a.diary_no
    inner join cl_printed cl on h.next_dt = cl.next_dt AND cl.part = h.clno and h.roster_id = cl.roster_id and cl.display='Y' 
    where a.display = 'Y' and r.display = 'Y' and rj.display = 'Y' and m.c_status = 'P' and h.next_dt = '$listing_dts' $mainhead_query $board_type_query
    $judge_code_query
    and h.brd_slno > 0 
    group by m.diary_no
    ORDER BY r.courtno, h.next_dt,h.brd_slno,
    if(h.conn_key=h.diary_no,1,99) ASC,
    if(ct.ent_dt is not null,ct.ent_dt,999) ASC,
    cast(SUBSTRING(m.diary_no,-4) as signed) ASC,
    cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed ) ASC";
   // echo $sql; exit;
    $res = mysql_query($sql) or die(mysql_error());
    ?>
    <div class="row col-sm-12">
        <div class="col-sm-8">
            <h3>CASES LISTED ON <?=date('d-m-Y',strtotime(trim($_POST['listing_dts'])));?></h3>
        </div>
        <!--<div class="col-sm-4">
           Action on Entire List <button data-updation_method='bulk'
                    data-update_flag="save"
                    class='btn btn-info save_modify ' type='button'
                    name='save_modify'>Save</button>
            <button data-updation_method='bulk'
                    data-update_flag="modify"
                    class='btn btn-info save_modify ' type='button'
                    name='save_modify'>Revert</button>
            <br>
            <span class="result_action"></span>
            <input type="hidden" class="result_success_count" >
            <span class="result_action_loader"></span>
        </div>-->
    </div>

    <?php
    if(mysql_num_rows($res) > 0) {
            $psrno = "1";
            ?>
    <table class="table table-responsive">
        <thead>
        <tr>
            <th style="width: 5%">
                SNo.
            </th>
            <th style="width: 10%">
                Item No.
            </th>
            <th style="width: 25%">Case Details</th>
            <th style="width: 25%">Court Details</th>
            <th style="width: 25%">AOR/Party In Person</th>
            <th style="width: 10%">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sno = 1;
        while ($row = mysql_fetch_array($res)) { //echo '<pre>'; print_r($row); exit;


        if($row['diary_no'] == $row['conn_key'] OR $row['conn_key'] == 0 OR $row['conn_key'] == '' OR $row['conn_key'] == null){
            $print_brdslno = $row['brd_slno'];
            $con_no = "0";
            $is_connected = "";
        }
        else{
            $print_brdslno = "&nbsp;".$row["brd_slno"].".".++$con_no;
            $is_connected = "<br/><span style='color:red;'>Conn.</span>";
        }

            $count_advocates = "";
            if($row['conn_key'] != null and $row['conn_key'] > 0) {
                $count_advocates = f_get_advocate_count_with_connected($row["conn_key"], $row['next_dt']);
            }
            else{
                $count_advocates = $row["total_advocates"];
            }
            $conn_case_count = 0;
            if($row['conn_key'] == $row['diary_no']) {
                $conn_case_count = f_connected_case_count_listed($row["conn_key"], $row['next_dt']);
            }
            ?>
<tr id="tr_<?=$row['diary_no']?>" >
    <td>
        <?=$psrno++?>
    </td>
    <td>
        <!--<input type="checkbox" id="checkbox_<?/*=$sno*/?>" name="chk"
               data-diary_no="<?/*= $row['diary_no'] */?>"
               data-conn_key="<?/*= $row['conn_key'] */?>"
               data-next_dt="<?/*= $row['next_dt'] */?>"
               data-roster_id="<?/*= $row['roster_id'] */?>"
               data-main_supp_flag="<?/*= $row['main_supp_flag'] */?>"
               data-clno="<?/*= $row['clno'] */?>" >-->
        <?=$print_brdslno.$is_connected?>
    </td>
    <td>
        <?php
        echo $row['reg_no_display'].' @ '.$row['diary_no'];
        echo "<br>".$row['pet_name'].' Vs. '.$row['res_name'];
        echo $conn_case_count > 0 ? "<br><span class='text-danger'>(Connected Cases : ".$conn_case_count.")</span>" : '';
        if($count_advocates > 20){
            echo "<br><span style='color:red;'><b>*** (More than 20 Advocates)</b></span>";
        }
        ?>
    </td>
    <td>
        <?php
        $jcodes = $row['judges'];
        $courtno = $row['courtno'];
        echo f_get_judges_names_by_jcode($jcodes);
        ?>
        <br>
        <span class="badge badge-secondary">
            Court No. :
            <?php
                if($courtno > 60){
                    echo "VC R ".($courtno-60);
                }
                else if($courtno > 30){
                    echo "VC ".($courtno-30);
                }
                else if($courtno > 20){
                    echo "R ".$courtno;
                }
                else{
                    echo $courtno;
                }
        ?>
            </span>
        <span class="badge badge-secondary">
            <?php
            if($row['mainhead'] == 'F'){
                echo "Regular List";
            }
            else if($row['mainhead'] == 'M' && $row['board_type'] == 'J'){
                echo "Misc. List";
            }
            else if($row['mainhead'] == 'M' && $row['board_type'] == 'C'){
                echo "Chamber List";
            }
            else if($row['mainhead'] == 'M' && $row['board_type'] == 'R'){
                echo "Registrar List";
            }
            else{

            }
            ?>
            </span>


            <?php
            if($row['main_supp_flag'] == 2){
                echo '<span class="badge badge-secondary">Supplementary</span>';
            }
            ?>

        </td>
    <?php
    $aorData = '';
    $advocate_id = !empty($row['advocate_ids']) ? $row['advocate_ids'] : NULL;
   // $advocate_id = 584;
    $response ='';
    //PP

    //advocate
     $sqlQuery = "select c.entry_source, c.id, bar_id,name,email,mobile,aor_code,if_aor from bar b
left join consent_through_email c on c.diary_no = " . $row['diary_no'] . " and c.next_dt = '" . $row['next_dt'] . "' 
and c.roster_id = " . $row['roster_id'] . "
and c.advocate_id = b.bar_id
where c.is_deleted is null and bar_id in ($advocate_id) and if_aor = 'Y' and isdead = 'N' and if_sen = 'N' and bar_id not in (584,585,610,616,666,940) order by b.aor_code";
    $response = mysql_query($sqlQuery) or die(mysql_error());
    $aorData ='';

    if(in_array($advocate_id,array(584,585,610,616,666,940))) {
        $sql1 = "select c.entry_source, c.id, p.auto_generated_id,p.email,b.name, p.partyname,p.contact from party p 
inner join advocate a on a.diary_no = p.diary_no and a.pet_res = p.pet_res and a.pet_res_no = p.sr_no
inner join bar b on b.bar_id = a.advocate_id 
left join consent_through_email c on c.diary_no = p.diary_no and c.next_dt = '" . $row['next_dt'] . "' and c.party_id = p.auto_generated_id and c.roster_id = " . $row['roster_id'] . "

where c.is_deleted is null and a.display = 'Y' and p.pflag = 'P' and a.advocate_id in (584,585,610,616,666,940) 
and p.diary_no = " . $row['diary_no'] . " order by p.auto_generated_id;";
        //  echo $sql; exit;
        $response1 = mysql_query($sql1) or die(mysql_error());
    }

    if (mysql_num_rows($response) > 0 OR mysql_num_rows($response1) > 0) {
        $aorData .= '<div class="aorDataDiv">
    <table>
    <thead>
    <tr>
    <th width="12%"><label class=""><input type="checkbox" name="all_'.$row['diary_no'].'" id="all_'.$row['diary_no'].'"  class="aorCheckboxAll" data-diaryid="'.$row['diary_no'].'" value="ALL"/>ALL</label></th>
    
    <th>Name</th>
    <th>Email</th>
    <th>Mobile</th>
    </tr>
    </thead>
    <tbody>';
        $ctn=1;
        while ($aorPpData = mysql_fetch_array($response)) {
            if($aorPpData['id'] != null){
                $applicant_selected = "checked";
            }
            else{
                $applicant_selected = "";
            }

            if($aorPpData['entry_source'] == 2){
                $checkbox_string = "Online";
            }
            else if(strlen($aorPpData['email']) > 5 || strlen($aorPpData['mobile']) == 10) {
                $checkbox_string = '<input type="checkbox" data-applicant_type="1" data-applicant_id="' . $aorPpData['bar_id'] . '" name="' . $row['diary_no'] . '" id="' . $row['diary_no'] . '_' . $ctn . '" '.$applicant_selected.' />';
            }
            else{
                $checkbox_string = "";
            }

            $aorData .= '<tr>
    <td>'.$checkbox_string.'</td>    
    <td>' . $aorPpData['name'] . ' (AOR - ' . $aorPpData['aor_code'] . ')</td>
    <td>' . $aorPpData['email'] . '</td>
    <td>' . $aorPpData['mobile'] . '</td>
    </tr>';

                $ctn++;

        }

        while ($ppData = mysql_fetch_array($response1)) { //echo '<pre>'; print_r($ppData['partyname']); exit;
            if($ppData['id'] != null){
                $applicant_selected = "checked";
            }
            else{
                $applicant_selected = "";
            }

            if($ppData['entry_source'] == 2){
                $checkbox_string = "Online";
            }
            else if(strlen($ppData['email']) > 5 || strlen($ppData['mobile']) == 10) {
                $checkbox_string = '<input type="checkbox" data-applicant_type="2" data-applicant_id="' . $ppData['auto_generated_id'] . '" name="' . $row['diary_no'] . '" id="' . $row['diary_no'] . '_' . $ctn . '" '.$applicant_selected.' />';
            }
            else{
                $checkbox_string = "";
            }

                $aorData .= '<tr>
    <td>'.$checkbox_string.'</td>    
    <td>' . $ppData['partyname'] . ' (' . $ppData['name'] . ')</td>
    <td>' . $ppData['email'] . '</td>
    <td>' . $ppData['contact'] . '</td>
    </tr>';

            $ctn++;
        }

        $aorData .= "</tbody></table></div>";
    }


    ?>
    <td><?=$aorData?></td>
    <?php
    $aorData='';
    ?>
    <td id="d_<?=$row['diary_no']?>">
    <div class="form-group mt-1">
<?php
if($row['is_printed'] != null){
    ?>
    <span class="text-danger">List Already Published</span>
    <?php
}
?>
        <button data-updation_method='single'
                data-diary_no="<?= $row['diary_no'] ?>"
                data-conn_key="<?= $row['conn_key'] ?>"
                data-next_dt="<?= $row['next_dt'] ?>"
                data-roster_id="<?= $row['roster_id'] ?>"
                data-judges="<?= $row['judges'] ?>"
                data-clno="<?= $row['clno'] ?>"
                data-main_supp_flag="<?= $row['main_supp_flag'] ?>"
                data-action="save"
                class="btn btn-secondary btn-block save_modify" type="button" name="save_<?=$row['diary_no'] ?>" id="save_<?=$row['diary_no'] ?>">Save</button>
<br><br>
        <button data-updation_method='single'
                data-diary_no="<?= $row['diary_no'] ?>"
                data-conn_key="<?= $row['conn_key'] ?>"
                data-next_dt="<?= $row['next_dt'] ?>"
                data-roster_id="<?= $row['roster_id'] ?>"
                data-clno="<?= $row['clno'] ?>"
                data-main_supp_flag="<?= $row['main_supp_flag'] ?>"
                data-action="modify"
                class="btn btn-secondary btn-block save_modify" type="button" name="modify_<?=$row['diary_no'] ?>" id="modify_<?=$row['diary_no'] ?>">Revert</button>

    </div>

    </td>
    </tr>
    <?php
    }
    ?>
    </tbody>
    </table>
    <?php
    }
    else{
        echo "No Records Found";
        }
    //  }
    }
    else{
    echo "Please select mandatory fields";
    }
?>
<style>
    .aorDataDiv{
        border: 2px solid #ead5d5;
    }
    .ppDataDiv{
        border: 2px solid #078e7b;
    }
</style>