<?php
// include("../../includes/db_inc.php");
// include("../common/function.php");
// session_start();
extract($_POST);
$_SESSION['session_hybrid_court_no'] = $courtno;
$_SESSION['session_hybrid_list_type'] = $list_type;
if(count($freezeData) > 0 ) {
    echo "List Already Freezed";
    exit();
} else {
    // for weekly list : list_type = 1 //(year(curdate()) = weekly_year OR (year(curdate()) + 1) = weekly_year)
    if(count($weekelyList) > 0) 
    {
        $psrno = "1";
        ?>
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th style="min-width: 50%; max-width:100%;"><input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall(this);">Item No.</th>
                    <th style="min-width: 30%; max-width:100%;">Case No.</th>
                    <th style="min-width: 35%; max-width:100%;">Cause Title</th>
                    <th style="min-width: 25%; max-width:100%;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                if(isset($weekelyList) && !empty($weekelyList)) {
                    foreach ($weekelyList as $row) {
                        if($sno == 1) {
                            $jcodes = $row['judges_code'];
                            $courtno = $row['courtno'];
                            if ($list_type == 1) {
                                $list_date_dmy = date("d-m-Y", strtotime($row['from_dt']))." to ".date("d-m-Y", strtotime($row['to_dt']));
                            } else {
                                $list_date_dmy = date("d-m-Y", strtotime($row['from_dt']));
                            }
                            echo "<tr><td class='p-3' colspan='3' style='font-size:15px;'><span class='text-success font-13 font-weight-bold'>";
                            echo "<u>".f_get_judges_names_by_jcode($jcodes)."</u></span><br>";
                            echo "<span class='font-weight-bolder'>[";
                            echo "List Type : ";
                            if($list_type == 1) {
                                echo '<span class="badge badge-secondary">Weekly</span>';
                            }
                            echo ' List Date : <span class="badge badge-secondary">'.$list_date_dmy.'</span>, Court No. : <span class="badge badge-secondary">';
                            if($courtno > 60) {
                                echo "VC ".($courtno-60);
                            } else if($courtno > 30) {
                                echo "VC ".($courtno-30);
                            } else {
                                echo $courtno;
                            }
                            echo "</span>] </span> </td>";
                            ?>
                            <td class='p-3 border border-dark wSet' colspan='' >
                                <button data-updation_method='bulk' data-update_flag="P" class='btn btn-info hybrid_action' type='button' name='hybrid_action'>Physical</button>
                                <button data-updation_method='bulk' data-update_flag="V" class='btn btn-info hybrid_action' type='button' name='hybrid_action'>VC</button>
                                <button data-updation_method='bulk' data-update_flag="H" class='btn btn-info hybrid_action ' type='button' name='hybrid_action'>Hybrid</button>
                                <br>
                                <span class="result_action"></span>
                                <input type="hidden" class="result_success_count" >
                                <span class="result_action_loader"></span>
                            </td>
                            <?php
                            echo "</tr>";
                        }
                        $sno++;
                        if($row['diary_no'] == $row['conn_key'] OR $row['conn_key'] == 0) {
                            $print_srno = $psrno;
                            $con_no = "0";
                            $is_connected = "";
                        } else {
                            $is_connected = "<span style='color:red;'>Conn.</span>";
                        }
                        if($is_connected != '') {
                            $print_srno = "";
                        } else {
                            $print_srno = $print_srno;
                            $psrno++;
                        }
                        $count_advocates = "";
                        if($row['conn_key'] != null and $row['conn_key'] > 0) {
                            $count_advocates = f_get_advocate_count_with_connected($row["conn_key"], $row['next_dt']);
                        } else {
                            $count_advocates = f_get_advocate_count($row["diary_no"]);
                        }
                        $conn_case_count = 0;
                        if($row['conn_key'] != null and $row['conn_key'] > 0) {
                            $conn_case_count = f_connected_case_count_listed($row["conn_key"], $row['next_dt']);

                        }
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" id="chkeeed" name="chk" data-diary_no="<?= $row['diary_no'] ?>" data-conn_key="<?= $row['conn_key'] ?>" data-next_dt="<?= $row['next_dt'] ?>" data-from_dt="<?= $row['from_dt'] ?>" data-to_dt="<?= $row['to_dt'] ?>" data-list_type_id="<?= $list_type ?>" data-list_number="<?= $row['weekly_no'] ?>" data-list_year="<?= $row['weekly_year'] ?>" data-mainhead="<?= $row['mainhead'] ?>" data-board_type="<?= $row['board_type'] ?>" data-court_number="<?= $row['courtno'] ?>">
                                <?=$print_srno.$is_connected?>
                            </td>
                            <td>
                                <?php
                                echo $row['reg_no_display'].' @ '.$row['diary_no'];
                                echo $conn_case_count > 0 ? "<br><span class='text-danger'>(Connected Cases : ".$conn_case_count.")</span>" : '';
                                if($count_advocates > 20) {
                                    echo "<br><span style='color:red;'><b>*** (More than 20 Advocates)</b></span>";
                                }
                                ?>
                            </td>
                            <td><?=$row['pet_name'].' Vs. '.$row['res_name']?></td>
                            <td id="d_<?=$row['diary_no']?>">
                                <?php
                                /* if($count_advocates > 20){
                                    echo "<span style='color:red;'><b>*** (More than 20 Advocates)</b></span>";
                                } else {*/
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
                                    <div class="form-group mt-1">
                                        <input type="time" class="from_time" name="from_time" value="<?= $hearing_from_time1 ?>" min="10:00" max="18:00" style="min-width: 115px !important;">
                                        <input type="time" class="to_time" name="to_time" value="<?= $hearing_to_time1 ?>" min="10:00" max="18:00" style="min-width: 115px !important;">
                                    </div>
                                    <div class="form-group mt-1">
                                        <button data-updation_method='single' data-diary_no="<?= $row['diary_no'] ?>" data-conn_key="<?= $row['conn_key'] ?>" data-next_dt="<?= $row['next_dt'] ?>" data-from_dt="<?= $row['from_dt'] ?>" data-to_dt="<?= $row['to_dt'] ?>" data-list_type_id="<?= $list_type ?>" data-list_number="<?= $row['weekly_no'] ?>" data-list_year="<?= $row['weekly_year'] ?>" data-mainhead="<?= $row['mainhead'] ?>" data-board_type="<?= $row['board_type'] ?>" data-court_number="<?= $row['courtno'] ?>" data-update_flag="P" class='btn <?= $row['consent'] == "P" ? "btn-success" : "btn-secondary"?> hybrid_action' type='button' name='hybrid_action'>Physical</button>

                                        <button data-updation_method='single' data-diary_no="<?= $row['diary_no'] ?>" data-conn_key="<?= $row['conn_key'] ?>" data-next_dt="<?= $row['next_dt'] ?>" data-from_dt="<?= $row['from_dt'] ?>" data-to_dt="<?= $row['to_dt'] ?>" data-list_type_id="<?= $list_type ?>" data-list_number="<?= $row['weekly_no'] ?>" data-list_year="<?= $row['weekly_year'] ?>" data-mainhead="<?= $row['mainhead'] ?>" data-board_type="<?= $row['board_type'] ?>" data-court_number="<?= $row['courtno'] ?>" data-update_flag="V" class='btn <?= $row['consent'] == "V" ? "btn-success" : "btn-secondary"?> hybrid_action ' type='button' name='hybrid_action'>VC</button>
                                        
                                        <button data-updation_method='single' data-diary_no="<?= $row['diary_no'] ?>" data-conn_key="<?= $row['conn_key'] ?>" data-next_dt="<?= $row['next_dt'] ?>" data-from_dt="<?= $row['from_dt'] ?>" data-to_dt="<?= $row['to_dt'] ?>" data-list_type_id="<?= $list_type ?>" data-list_number="<?= $row['weekly_no'] ?>" data-list_year="<?= $row['weekly_year'] ?>" data-mainhead="<?= $row['mainhead'] ?>" data-board_type="<?= $row['board_type'] ?>" data-court_number="<?= $row['courtno'] ?>" data-update_flag="H" class='btn <?= $row['consent'] == "H" ? "btn-success" : "btn-secondary"?> hybrid_action ' type='button' name='hybrid_action'>Hybrid</button>
                                    </div>
                                    <?php if ($row['consent'] == 'P' || $row['consent'] == 'V' || $row['consent'] == 'H') { ?>
                                        <div class="form-group mt-1">
                                            <button data-updation_method='single' data-diary_no="<?= $row['diary_no'] ?>" data-conn_key="<?= $row['conn_key'] ?>" class='btn btn-danger delete_action btn-block' type='button' name='delete_action'> Remove Directions </button>
                                        </div>
                                        <?php
                                    }
                                /*  }*/
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <?php
    } else {
        echo "No Records Found";
    }
}
?>