<?php
include("../../includes/db_inc.php");
include("../common/function.php");
session_start();
$output = '';
            if(isset($_POST['mainhead']) && !empty($_POST['mainhead']) && isset($_POST['listing_dts']) && !empty($_POST['listing_dts']) &&
            isset($_POST['board_type']) && !empty($_POST['board_type']) && isset($_POST['jud_ros']) && !empty($_POST['jud_ros']) &&
                  isset($_POST['part_no']) && !empty($_POST['part_no']) && isset($_POST['main_supp_list']) && !empty($_POST['main_supp_list'])
                ) {
                $mainhead = trim($_POST['mainhead']);
                $listing_dts = date('Y-m-d',strtotime(trim($_POST['listing_dts'])));
                $board_type = trim($_POST['board_type']);
                $jud_ros = trim($_POST['jud_ros']);
                $jud_ros_arr = explode('|',$jud_ros);
                $judgeIds = !empty($jud_ros_arr[0]) ? $jud_ros_arr[0] : NULL;
                $roster_id = !empty($jud_ros_arr[1]) ? $jud_ros_arr[1] : NULL;
                $part_no = (int)trim($_POST['part_no']);
                $main_supp_list = (int)trim($_POST['main_supp_list']);
                $currentDate = date('Y-m-d');
                $list_type_id = 0;
                if(isset($mainhead) && !empty($mainhead) && isset($board_type) && !empty($board_type)){
                    if($mainhead == 'M' && $board_type == 'J'){
                        $list_type_id = 4;
                    }
                    else if($mainhead == 'M' && $board_type == 'C'){
                        $list_type_id = 5;
                    }
                    else if($mainhead == 'M' && $board_type == 'R'){
                        $list_type_id = 6;
                    }
                    else if($mainhead == 'F' && $board_type == 'J'){
                        $list_type_id = 3;
                    }
                }
                $sql = "";
                $sql = "select  id from cl_printed   where next_dt = '$listing_dts' and m_f='$mainhead' and main_supp=$main_supp_list and part=$part_no 
                        and roster_id =$roster_id and display='Y' ";
                //echo $sql; exit;
                $res_ros = mysql_query($sql) or die(mysql_error());
                if (mysql_num_rows($res_ros) > 0) {
                        echo "List Already Freezed";
                 }
                else {
                $sql = "";
                $sql = "select hy.consent,hy.entry_date,m.reg_no_display,m.pet_name,m.res_name, h.main_supp_flag,h.board_type,h.judges,h.roster_id,h.brd_slno,h.clno,h.mainhead,h.next_dt,h.conn_key,
                h.diary_no,r.courtno  from main m 
                inner join heardt h on m.diary_no = h.diary_no
                LEFT JOIN conct ct on m.diary_no=ct.diary_no and ct.list='Y'
                left join hybrid_physical_hearing_consent hy on hy.diary_no = m.diary_no and hy.from_dt = h.next_dt
                inner join roster r on h.roster_id = r.id
                where m.c_status = 'P' and (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                and h.mainhead = '$mainhead' and h.board_type = '$board_type' and h.main_supp_flag = $main_supp_list  and h.judges = '$judgeIds'
                and h.next_dt = '$listing_dts' and h.roster_id = $roster_id and h.clno = $part_no and h.brd_slno > 0 
                group by m.diary_no
                ORDER BY LENGTH(h.judges) DESC, h.next_dt,
                if(h.conn_key=h.diary_no,1,99) ASC,
                if(ct.ent_dt is not null,ct.ent_dt,999) ASC,
                cast(SUBSTRING(m.diary_no,-4) as signed) ASC,
                cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed ) ASC";
              //  echo $sql; exit;
                $res = mysql_query($sql) or die(mysql_error());
                    if(mysql_num_rows($res) > 0) {
                        $psrno = "1";
                        ?>
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th style="width: 10%"><input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall(this);">
                                    Item No.
                                </th>
                                <th style="width: 30%">Case No.</th>
                                <th style="width: 35%">Cause Title</th>
                                <th style="width: 25%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sno = 1;
                            while ($row = mysql_fetch_array($res)) { //echo '<pre>'; print_r($row); exit;
                                if($sno == 1) {
                                    $jcodes = $row['judges'];
                                    $courtno = $row['courtno'];
                                    $next_dt = date("d-m-Y", strtotime($row['next_dt']));

                                    echo "<tr><td class='p-3' colspan='3' style='font-size:15px;'><span class='text-success font-13 font-weight-bold'>";
                                    echo "<u>".f_get_judges_names_by_jcode($jcodes)."</u></span><br>";
                                    echo "<span class='font-weight-bolder'>[";

                                    echo ' List Date : <span class="badge badge-secondary">'.$next_dt.'</span>
                                    Court No. : <span class="badge badge-secondary">';
                                    if($courtno > 60){
                                        echo "VC R ".($courtno-60);
                                    }
                                    else if($courtno > 30){
                                        echo "VC ".($courtno-30);
                                    }
                                    else if($courtno > 20){
                                        echo "VC R ".($courtno-20);
                                    }
                                    else{
                                        echo $courtno;
                                    }
                                    echo "</span>";
                                    echo "]
                               </span> 
                            </td>";
                                    ?>
                                    <td class='p-3 border border-dark' colspan='3' >
                                        <button data-updation_method='bulk'
                                                data-update_flag="P"
                                                class='btn btn-info hybrid_action ' type='button'
                                                name='hybrid_action'>Physical</button>
                                        <button data-updation_method='bulk'
                                                data-update_flag="V"
                                                class='btn btn-info hybrid_action ' type='button'
                                                name='hybrid_action'>VC</button>
                                        <button data-updation_method='bulk'
                                                data-update_flag="H"
                                                class='btn btn-info hybrid_action ' type='button'
                                                name='hybrid_action'>Hybrid</button>
                                        <br>
                                        <span class="result_action"></span>
                                        <input type="hidden" class="result_success_count" >
                                        <span class="result_action_loader"></span>
                                    </td>
                                    <?php
                                    echo "</tr>";
                                }
                                $sno++;
                                if($row['diary_no'] == $row['conn_key'] OR $row['conn_key'] == 0){
                                    $print_srno = $psrno;
                                    $con_no = "0";
                                    $is_connected = "";
                                }
                                else{
                                    $is_connected = "<span style='color:red;'>Conn.</span>";
                                }
                                if($is_connected != ''){
                                    $print_srno = "";
                                }
                                else{
                                    $print_srno = $print_srno;
                                    $psrno++;
                                }
                                $count_advocates = "";
                                if($row['conn_key'] != null and $row['conn_key'] > 0) {
                                    $count_advocates = f_get_advocate_count_with_connected($row["conn_key"], $row['next_dt']);
                                }
                                else{
                                    $count_advocates = f_get_advocate_count($row["diary_no"]);
                                }
                                $conn_case_count = 0;
                                if($row['conn_key'] != null and $row['conn_key'] > 0) {
                                    $conn_case_count = f_connected_case_count_listed($row["conn_key"], $row['next_dt']);
                                }
                                ?>
                                <tr >
                                    <td><input type="checkbox" id="checkbox_<?=$sno; ?>" name="chk"
                                               data-diary_no="<?= $row['diary_no'] ?>"
                                               data-conn_key="<?= $row['conn_key'] ?>"
                                               data-next_dt="<?= $row['next_dt'] ?>"
                                               data-judges="<?= $row['judges'] ?>"
                                               data-roster_id="<?= $row['roster_id'] ?>"
                                               data-main_supp_flag="<?= $row['main_supp_flag'] ?>"
                                               data-mainhead="<?= $row['mainhead'] ?>"
                                               data-board_type="<?= $row['board_type'] ?>"
                                               data-entry_date="<?=$row['entry_date']?>"
                                               data-courtno="<?=$row['courtno']?>"
                                               data-list_type_id="<?=$list_type_id?>"
                                               data-clno="<?= $row['clno'] ?>" >
                                        <?=$print_srno.$is_connected?>
                                    </td>
                                    <td><?php
                                        echo $row['reg_no_display'].' @ '.$row['diary_no'];
                                        echo $conn_case_count > 0 ? "<br><span class='text-danger'>(Connected Cases : ".$conn_case_count.")</span>" : '';
                                        if($count_advocates > 20){
                                            echo "<br><span style='color:red;'><b>*** (More than 20 Advocates)</b></span>";
                                        }
                                        ?>

                                    </td>
                                    <td><?=$row['pet_name'].' Vs. '.$row['res_name']?></td>
                                    <td id="d_<?=$row['diary_no']?>">
                                        <?php

                                        if ($row['hearing_from_time'] == "00:00:00") {
                                            $hearing_from_time1 = "";
                                        } else {
                                            $hearing_from_time1 = $row['hearing_from_time'];
                                        }

                                        if ($row['hearing_to_time'] == "00:00:00") {
                                            $hearing_to_time1 = "";
                                        } else {
                                            $hearing_to_time1 = $row['hearing_to_time'];
                                        }


                                        ?>
                                        <div class="form-group mt-1 physical_vc_hybrid_button">

                                            <button data-updation_method='single'
                                                    data-entry_date="<?=$row['entry_date']?>"
                                                    data-diary_no="<?= $row['diary_no'] ?>"
                                                    data-conn_key="<?= $row['conn_key'] ?>"
                                                    data-next_dt="<?= $row['next_dt'] ?>"
                                                    data-roster_id="<?= $row['roster_id'] ?>"
                                                    data-judges="<?= $row['judges'] ?>"
                                                    data-main_supp_flag="<?= $row['main_supp_flag'] ?>"
                                                    data-mainhead="<?= $row['mainhead'] ?>"
                                                    data-board_type="<?= $row['board_type'] ?>"
                                                    data-clno="<?= $row['clno'] ?>"
                                                    data-courtno="<?=$row['courtno']?>"
                                                    data-list_type_id="<?=$list_type_id?>"
                                                    data-update_flag="P"
                                                    class='btn <?= ($row['consent'] == "P" && $row['is_deleted'] !=1) ? "btn-success" : "btn-secondary"?> hybrid_action ' type='button'
                                                    name='hybrid_action' id="physical_<?=$sno; ?>">Physical</button>

                                            <button data-updation_method='single'
                                                    data-entry_date="<?=$row['entry_date']?>"
                                                    data-diary_no="<?= $row['diary_no'] ?>"
                                                    data-conn_key="<?= $row['conn_key'] ?>"
                                                    data-next_dt="<?= $row['next_dt'] ?>"
                                                    data-roster_id="<?= $row['roster_id'] ?>"
                                                    data-judges="<?= $row['judges'] ?>"
                                                    data-main_supp_flag="<?= $row['main_supp_flag'] ?>"
                                                    data-mainhead="<?= $row['mainhead'] ?>"
                                                    data-board_type="<?= $row['board_type'] ?>"
                                                    data-clno="<?= $row['clno'] ?>"
                                                    data-courtno="<?=$row['courtno']?>"
                                                    data-list_type_id="<?=$list_type_id?>"
                                                    data-update_flag="V"
                                                    class='btn <?= ($row['consent'] == "V" && $row['is_deleted'] !=1) ? "btn-success" : "btn-secondary"?> hybrid_action ' type='button'
                                                    name='hybrid_action' id="vc_<?=$sno; ?>">VC</button>

                                            <button data-updation_method='single'
                                                    data-entry_date="<?=$row['entry_date']?>"
                                                    data-diary_no="<?= $row['diary_no'] ?>"
                                                    data-conn_key="<?= $row['conn_key'] ?>"
                                                    data-next_dt="<?= $row['next_dt'] ?>"
                                                    data-roster_id="<?= $row['roster_id'] ?>"
                                                    data-judges="<?= $row['judges'] ?>"
                                                    data-main_supp_flag="<?= $row['main_supp_flag'] ?>"
                                                    data-mainhead="<?= $row['mainhead'] ?>"
                                                    data-board_type="<?= $row['board_type'] ?>"
                                                    data-clno="<?= $row['clno'] ?>"
                                                    data-courtno="<?=$row['courtno']?>"
                                                    data-list_type_id="<?=$list_type_id?>"
                                                    data-update_flag="H"
                                                    class='btn <?= ($row['consent'] == "H" && $row['is_deleted'] !=1) ? "btn-success" : "btn-secondary"?> hybrid_action ' type='button'
                                                    name='hybrid_action' id="hybrid_<?=$sno; ?>">Hybrid</button>


                                        </div>
                                        <?php if (($row['consent'] == 'P' || $row['consent'] == 'V' || $row['consent'] == 'H') && ($row['is_deleted'] !=1)) {
                                            ?>
                                            <div class="form-group mt-1 physical_vc_hybrid_remove_direction">
                                                <button id="removeDirection_<?=$sno; ?>"
                                                        data-entry_date="<?=$row['entry_date']?>"
                                                        data-diary_no="<?= $row['diary_no'] ?>"
                                                        data-conn_key="<?= $row['conn_key'] ?>"
                                                        data-next_dt="<?= $row['next_dt'] ?>"
                                                        data-roster_id="<?= $row['roster_id'] ?>"
                                                        data-judges="<?= $row['judges'] ?>"
                                                        data-main_supp_flag="<?= $row['main_supp_flag'] ?>"
                                                        data-mainhead="<?= $row['mainhead'] ?>"
                                                        data-board_type="<?= $row['board_type'] ?>"
                                                        data-clno="<?= $row['clno'] ?>"
                                                        data-courtno="<?=$row['courtno']?>"
                                                        data-list_type_id="<?=$list_type_id?>"
                                                        class='btn btn-danger delete_action btn-block' type='button'
                                                        name='delete_action'>Remove Directions
                                                </button>


                                            </div>
                                            <?php
                                        }

                                        ?>
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
                }
            }
            else{
                echo "Please select mandatory fields";
            }
?>
