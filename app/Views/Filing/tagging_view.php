<?= view('header'); ?>

<!-- Main content -->
<style xmlns="http://www.w3.org/1999/html">
    #newb {
        position: fixed;
        padding: 12px;
        left: 20%;
        top: 15%;
        display: none;
        color: black;
        background-color: aliceblue;
        border: 2px solid lightslategrey;
        height: 100%;
    }

    #overlay {
        background-color: #000;
        opacity: 0.7;
        filter: alpha(opacity=70);
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?= view('Filing/filing_breadcrumb'); ?>
                    <!-- /.card-header -->
                    <?php
                    $case_group = session()->get('filing_details')['case_grp'];
                    $case_status = session()->get('filing_details')['c_status'];
                    ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <h4 class="basic_heading"> Tagging Details </h4>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('error') ?></strong>
                                        </div>

                                    <?php } ?>
                                    <?php if (session()->getFlashdata('success_msg')) : ?>
                                        <div class="alert alert-success alert-dismissible">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                        </div>
                                    <?php endif; ?>

                                    <div class="tab-content">
                                        <?php
                                        $list_before = $list_before_judge = $list_not_before = $list_not_before_judge = $status = '';
                                        if (!empty($connected_data)):
                                            foreach ($connected_data as $row1) {

                                                $row_m = $row1['diary_details'];

                                                if (isset($row_m['diary_no'])) {
                                                    $reslt_validate_verification = validate_verification($row_m['diary_no']);
                                                   
                                                }

                                                if (isset($row_m['pet_name'])) {
                                                    $p = $row_m['pet_name'];
                                                }
                                                if (isset($row_m['res_name'])) {
                                                    $r = $row_m["res_name"];
                                                }
                                                if (isset($row_m['pet_adv'])) {
                                                    $padv = $row_m['pet_adv'];
                                                }

                                                if (isset($row_m['res_adv'])) {
                                                $radv = $row_m['res_adv'];
                                                }
                                                if (isset($row_m['c_status'])) {
                                                $status = $row_m['c_status'];
                                                }

                                                if (isset($row_m['lastorder'])) {
                                                $lastorder = $row_m['lastorder'];
                                                }
                                                if (isset($row_m['ccdet'])) {
                                                $isconn = $row_m["ccdet"];
                                                }
                                                /*echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>".$isconn;
                                                    exit;*/
                                                    if (isset($row_m['connto'])) {
                                                $connto = $row_m["connto"];
                                                    }
                                                    if (isset($row_m['reg_no_display'])) {
                                                $reg_no_display_main = $row_m["reg_no_display"];
                                                    }
                                                $shead = "";
                                                $benchmain = "";

                                              
                                                $cstatus = "";
                                                switch ($status) {
                                                    case 'P':
                                                        $cstatus = "<font color='blue'>Pending</font>";
                                                        break;

                                                    case 'R':
                                                        $cstatus = "<font color='red'>Rejected</font>";
                                                        break;

                                                    case 'D':
                                                        $cstatus = "<font color='red'>Disposed</font>";
                                                        break;

                                                    case 'T':
                                                        $cstatus = "<font color='red'>Transferred</font>";
                                                        break;
                                                    default:
                                                        $cstatus = $status; // Or display a default message
                                                        break;
                                                }

                                                $padvname = $radvname = "";
                                                if (!empty($row1['party_details_diary'])) {
                                                    foreach ($row1['party_details_diary'] as $row_pty) {
                                                        if ($row_pty["pet_res"] == "P")
                                                            if ($p == "")
                                                                $p .= $row_pty["pn"];
                                                            else
                                                                $p .= ", " . $row_pty["pn"];

                                                        if ($row_pty["pet_res"] == "R")
                                                            if ($r == "")
                                                                $r .= $row_pty["pn"];
                                                            else
                                                                $r .= ", " . $row_pty["pn"];
                                                    }
                                                }

                                                if (!empty($row1['advocate_details'])) {
                                                    foreach ($row1['advocate_details'] as $row_advp) {
                                                        $tmp_advname =  "<p>&nbsp;&nbsp;";
                                                        $t_adv = $row_advp['name'];
                                                        if ($row_advp['isdead'] == 'Y')
                                                            $t_adv = "<font color=red>" . $t_adv . " (Dead / Retired / Elevated) </font>";

                                                        $tmp_advname = $tmp_advname . $t_adv . $row_advp['adv'];

                                                        $tmp_advname = $tmp_advname . "</p>";

                                                        if ($row_advp['pet_res'] == "P")
                                                            $padvname .= $tmp_advname;
                                                        if ($row_advp['pet_res'] == "R")
                                                            $radvname .= $tmp_advname;
                                                    }
                                                }

                                                // if ($status =='D') {
                                        ?>
                                                <!--   <center><b>
                                                        <font color='red' style='font-size:16px;'>Case is Disposed</font>
                                                    </b></center>-->
                                                <?php // }else{
                                                if ($reslt_validate_verification > 0 && $section != 19) { ?>
                                                    <center><b>
                                                            <font color='red' style='font-size:16px;'>Verification Pending From IB Section</font>
                                                        </b></center>
                                                <?php } else { ?>
                                                    <?php $attribute = array('class' => 'form-horizontal', 'name' => 'tagging_view', 'id' => 'tagging_view', 'autocomplete' => 'off');
                                                    echo form_open(base_url('#'), $attribute);
                                                    ?>
                                                    <input type="hidden" name="h_dno" id="h_dno" value="<?php echo substr($row_m['diary_no'], 0, -4) ?>">
                                                    <input type="hidden" name="h_dyr" id="h_dyr" value="<?php echo substr($row_m['diary_no'], -4) ?>">
                                                    <div align="center" style="background-color:mintcream; border: 1px solid #5AFFAC;">
                                                        <table bgcolor="#FBFFFD" class="table view-delts-table" width="90%" border="0" cellpadding="3" align="center">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 20%;">Diary No</th>
                                                                    <td><?php echo substr($row_m['diary_no'], 0, -4) . '/' . substr($row_m['diary_no'], -4); ?></td>
                                                                </tr>
                                                                <?php if (!empty($row_m['reg_no_display'])): ?>
                                                                    <tr>
                                                                        <th style="width: 20%;">Case No.</th>
                                                                        <td>
                                                                            <font color="blue"><?php echo $row_m['reg_no_display']; ?></font> (Reg. Dt. <?php echo $row_m['active_fil_dt']; ?>)
                                                                        </td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                                <tr>
                                                                    <th style="width: 20%;">Petitioner</th>
                                                                    <td><?php echo $p; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Respondant</th>
                                                                    <td><?php echo $r; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Petitioner Advocate(s)</th>
                                                                    <td><?php echo $padvname ?? ''; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Respondant Advocate(s)</th>
                                                                    <td><?php echo $radvname ?? ''; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Case Category</th>
                                                                    <td><?php echo $old_category_name ?? '' ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Bench</th>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <td>
                                                                        <font color="blue"><? echo $cstatus ?? ''; ?></font>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Last Order</th>
                                                                    <td>
                                                                        <font color="blue"><?php echo $lastorder ?? ''; ?></font>
                                                                    </td>
                                                                </tr>
                                                                <?php if ($row_m["c_status"] != "D") {

                                                                    if (!empty($row1['not_before'])) {
                                                                        foreach ($row1['not_before'] as $not_before) {
                                                                            if ($not_before['notbef'] == 'B') {
                                                                                $list_before = 'B';
                                                                                $list_before_judge = $not_before['jn'];
                                                                            } elseif ($not_before['notbef'] == 'N') {
                                                                                $list_not_before = 'N';
                                                                                $list_not_before_judge = $not_before['jn'];
                                                                            }
                                                                        } ?>
                                                                        <?php if (!empty($list_before) && $list_before == 'B'): ?>
                                                                            <tr>
                                                                                <th>List Before</th>
                                                                                <td>
                                                                                    <font color="green"><?php echo $list_before_judge ?? ''; ?></font>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty($list_not_before) && $list_not_before == 'N'): ?>
                                                                            <tr>
                                                                                <th>Not List Before</th>
                                                                                <td>
                                                                                    <font color="red"><?php echo $list_not_before_judge ?? ''; ?></font>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endif; ?>
                                                                <?php  }
                                                                } ?>

                                                                <?php $list_after = "";
                                                                if (!empty($row1['heardt'])) {
                                                                    foreach ($row1['heardt'] as $row_listed_after) {

                                                                        date_default_timezone_set('GMT');
                                                                        $temp = strtotime("+5 hours 30 minutes");
                                                                        if ((strtotime($row_listed_after['nd1']) > strtotime(date('Y-m-d'))) or strtotime($row_listed_after['nd1']) == strtotime(date('Y-m-d')) and (strtotime("17:00:00") - strtotime(date("H:i:s", $temp))) > 0) {
                                                                            $list_after = "YES";
                                                                            echo "<tr><th>List On</th><td><font style='font-weight:bold;color:red;'><span class='blink_me' style='font-size:20px;'>Case is listed on <font color='blue' style='font-size:20px;'>" . $row_listed_after["next_dt"] . "</font> before <font color='blue' style='font-size:20px;'>[" . stripcslashes(get_judges($row_listed_after["judges"])) . "]</font>. PLEASE DO NOT ADD/REMOVE CONNECTED / LINKED CASES.<span class='blink_me'></font></td></tr>";
                                                                        } ?>
                                                                <?php }
                                                                } ?>
                                                                <?php if ($isconn != 'NA') {
                                                                    if (!empty($row1['conct_matters'])) {

                                                                ?>
                                                                        <tr valign="top">
                                                                            <th>Connected To </th>
                                                                            <td width="100%" nowrap="">
                                                                                <div width="100%" nowrap="">
                                                                                    <?php
                                                                                    // Check if the necessary index exists and is not null
                                                                                    $reg_no_display = isset($row1['conct_matter_diary']['reg_no_display']) ? $row1['conct_matter_diary']['reg_no_display'] : 'N/A';

                                                                                    // Format the $connto string
                                                                                    $connto = "<font color='red'>Main Case </font>: DN - " . substr($connto, 0, -4) . "/" . substr($connto, -4) . "&nbsp;&nbsp;&nbsp;&nbsp;[<font color='#043fff'>" . $reg_no_display . "</font>] ";

                                                                                    // Output the $connto string
                                                                                    echo $connto;
                                                                                    ?>

                                                                                    <?php foreach ($row1['conct_matters'] as $row_oc) {
                                                                                        $connected_d = $row_oc['diary_no'];
                                                                                        $reg_no_display =  $row_oc['reg_no_display'];
                                                                                        //$t_conn_type = explode('-', $row_oc["llist"]);
                                                                                        $t_conn_type = explode('-', is_string($row_oc["llist"]) ? $row_oc["llist"] : '');
                                                                                        //$conn_type = $t_conn_type[1];
                                                                                        $conn_type = $t_conn_type[1] ?? '';
                                                                                        if ($conn_type == 'L') {
                                                                                            $t_c_l = "Linked Case";
                                                                                        } elseif ($conn_type == 'C') {
                                                                                            $t_c_l = "Connected Case";
                                                                                        } else {
                                                                                            $t_c_l = '';
                                                                                        }
                                                                                    ?>
                                                                                        <?php $connto = "<br><font color='blue'>" . $t_c_l . "</font> : " . substr($connected_d, 0, -4) . "/" . substr($connected_d, -4) . "&nbsp;&nbsp;&nbsp;&nbsp;[<font color='#043fff'>" . $reg_no_display . "</font>] ";
                                                                                        echo $connto; ?>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                <?php }
                                                                } ?>
                                                            </thead>
                                                        </table>
                                                        <br>
                                                        <br>
                                                        <?php if ($isconn != "Y") {
                                                            if ($list_after == "" and $status != 'D'): ?>
                                                                <table class="table mb-0">
                                                                    <tbody>
                                                                        <tr align="center">
                                                                            <td><button type="button" name="addconn1" id="addconn1" class="btn btn-success myModal" data-toggle="modal" data-target="#myModal">DO YOU WANT TO CONNECT / LINK A CASE</button></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            <?php endif; ?>

                                                            <?php if (!empty($row1['connected_matters'])) {
                                                                $sr_no = 0;

                                                            ?>
                                                                <table class="table" width="98%">
                                                                    <thead>
                                                                        <tr>
                                                                            <td align="center" colspan="8">
                                                                                <font color="red"><b>CONNECTED CASES / LINKED CASES</b></font>
                                                                            </td>
                                                                        </tr>
                                                                        <tr bgcolor="#F4F5F5">
                                                                            <td align="center" width="50px"><input type="checkbox" name="all" id="all" value=""></td>
                                                                            <td width="5%"><b>S. NO.</b></td>
                                                                            <td><b>Diary No.</b></td>
                                                                            <td><b>Case No.</b></td>
                                                                            <td><b>Petitioner vs. Respondant</b></td>
                                                                            <td><b>Category</b></td>
                                                                            <td align="center"><b>Status</b></td>
                                                                            <td><b>Before/Not Before</b></td>
                                                                            <td align="center"><b>List</b></td>
                                                                            <td align="center"><b>Linked/ connected</b></td>
                                                                        </tr>
                                                                        <?php
                                                                        $conncntr = 0;
                                                                        $conncntr_p = 0;
                                                                        $conncntr_d = 0;

                                                                        foreach ($row1['connected_matters'] as $row_conn) {
                                                                            $t_bfnbf = "";
                                                                            $conncntr++;
                                                                            ++$sr_no;
                                                                            $connected_d = $row_conn['diary_no'];
                                                                            $reg_no_display =  $row_conn['reg_no_display'];
                                                                            $conn_type = $row_conn['conn_type'];
                                                                            if ($row_conn['c_status'] == 'P') {
                                                                                $conncntr_p++;
                                                                                $conncntr_d_text = 'blue';
                                                                            } else if ($row_conn["c_status"] == "D") {
                                                                                $conncntr_d++;
                                                                                $conncntr_d_text = 'red';
                                                                            }

                                                                            $return_bfnbf1 = getBeforeNotBeforeData($row_conn["diary_no"]);
                                                                            $t_return_bfnbf1 = explode('^|^', $return_bfnbf1);
                                                                            if ($t_return_bfnbf1[0] != "")
                                                                                $t_bfnbf .= "<b>BEFORE</b>: <font color=green>" . $t_return_bfnbf1[0] . "</font>";
                                                                            if ($t_return_bfnbf1[1] != "")
                                                                                $t_bfnbf .= "<b>NOT BEFORE</b>: <font color=green>" . $t_return_bfnbf1[1] . "</font>";

                                                                            if ($row_conn['c_status'] == 'D') {
                                                                                $archive_flag = "_a";
                                                                            } else {
                                                                                $archive_flag = "";
                                                                            }

                                                                        ?>
                                                                            <tr>
                                                                                <td align="center"><input type="checkbox" name="ccchk<?php echo $connected_d; ?>" id="ccchk<?php echo $connected_d; ?>" value="<?php echo $connected_d; ?>"></td>
                                                                                <td><b><?php echo $sr_no; ?></b></td>
                                                                                <td><b><span id="cn<?php echo $connected_d; ?>"><?= substr($connected_d, 0, -4) . "/" . substr($connected_d, -4) ?></span></b></td>
                                                                                <td>
                                                                                    <font color="#043fff" style=" white-space: nowrap;"><?php echo $reg_no_display; ?></font>&nbsp;
                                                                                </td>
                                                                                <td><?= $row_conn['pet_name'] ?> vs.<br><?= $row_conn['res_name'] ?></td>
                                                                                <td><?php echo get_mul_category($connected_d, $archive_flag) ?></td>
                                                                                <td align="center"><b>
                                                                                        <font color="<?= $conncntr_d_text; ?>"><?= $row_conn['c_status'] ?></font>
                                                                                    </b></td>
                                                                                <td><?php echo $t_bfnbf; ?></td>
                                                                                <td align="center"><b><?= $row_conn["list"]; ?></b></td>
                                                                                <td align="center"><b><?= $row_conn["conn_type"]; ?></b></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        </thead>
                                                                </table>
                                                                <br><br>
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr align="center">
                                                                            <td>
                                                                                <?php if ($list_after == "" and $status != 'D'): ?>
                                                                                    <button type="button" name="addconn" id="addconn" class="btn btn-success myModal" data-toggle="modal" data-target="#myModal"> DO YOU WANT TO CONNECT / LINK A CASE</button>
                                                                                <?php endif; ?>
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" name="remconn" id="remconn" class="btn btn-danger" onclick="save_rec_to_main()">DELINK SELECTED CONNECTED / LINKED CASES</button>
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" name="delmain" class="btn btn-warning" id="delmain" onclick="delink_main(<?php echo $conncntr ?>,<?php echo $conncntr_p ?>,<?php echo $conncntr_d; ?>)">DELINK MAIN CASE AND KEEP THE BUNCH</button>
                                                                            </td>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                        <?php }
                                                        } ?>
                                                    </div>
                                                    <div id="newb">
                                                        <div id="newb123">

                                                        </div>
                                                        <div id="newb1" align="center">
                                                            <table border="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <input type="button" name="close1" id="close1" value="CLOSE WINDOW" onclick="return close_w(1)">
                                                                        </td>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="sh_hidden" id="sh_hidden" value="<?php echo $shead; ?>">
                                                    <input type="hidden" name="diary_no" id="diary_no" value="<?php echo $row_m['diary_no'] ?>">
                                                    <input type="hidden" name="benchm" id="benchm" value="<?php echo $benchmain; ?>">
                                                    <br><br>

                                    </div>
                                    <?php form_close(); ?>
                        <?php }
                                            }
                                        endif; ?>
                        <!-- /.tab-content -->
                                </div>
                                <div id="overlay" style="display:none;">&nbsp;</div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>


                    <!-- The Modal -->
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Link Case</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <?php echo component_html(); ?>

                                    <center> <button type="button" name="getbtn" class="btn-success btn" id="getbtn" onClick="get_case_status();" />GET</button></center>
                                    <?php form_close(); ?>
                                    <center><span id="loader"></span> </center>

                                    <div id="ccdiv" style="overflow:auto;"></div>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </div>
                    </div>





                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    $('.myModal').click(function() {
        $('#ccdiv').html('');
        $("#diary_number").val('');
        $("#case_type").val('');
        $("#case_number").val('');
    });

    function save_rec_to_main() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var url = "/Filing/Tagging/conn_case_update_to_main";

        var qte_array = new Array();
        var dno = document.getElementById("h_dno").value;
        var dyr = document.getElementById("h_dyr").value;

        var cn = "";
        $("input[type='checkbox'][name^='ccchk']").each(function() {
            var isChecked = document.getElementById($(this).attr("id")).checked;
            if (isChecked) {
                cn += $("#cn" + $(this).val()).html() + ", ";
                qte_array.push($(this).val());
            }
        });
        if (qte_array.length == 0) {
            alert("SELECT ATLEAST ONE CONNECTED CASE TO DELINK IT");
        } else {
            cn = cn.substr(0, cn.length - 2);
            if (confirm("Are you sure you want to delink \n" + cn)) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        qte: qte_array,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    success: function(msg) {
                        updateCSRFToken();
                        if (msg == "") {
                            alert("Diary No is delinked successfully.");
                            location.reload();
                        } else alert(msg);
                    },
                    error: function() {
                        updateCSRFToken();
                        alert("ERROR");
                    },
                });
            }
        }
    }

    function delink_main(ttl, ttlp, ttld) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var url = "/Filing/Tagging/conn_case_delink_main";
        var dno = document.getElementById("h_dno").value;
        var dyr = document.getElementById("h_dyr").value;
        if (confirm("Are you sure you want to DELINK main case?")) {
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    dno: dno,
                    dyr: dyr,
                    ttl: ttl,
                    ttlp: ttlp,
                    ttld: ttld,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(msg) {
                    updateCSRFToken();
                    if (msg == "") {
                        alert("DONE");
                        location.reload();
                    } else alert(msg);
                },
                error: function() {
                    updateCSRFToken();
                    alert("ERROR");
                },
            });
        }
    }

    async function save_rec(opt, cl) {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var url = "/Filing/Tagging/conn_case_update";
        var qte_array = new Array();
        var dno = document.getElementById("h_dno").value;
        var dyr = document.getElementById("h_dyr").value;
        var filnoadd = document.getElementById("fil_noadd").value;
        qte_array.push(filnoadd);
        if (opt == 1) {
            $("input[type='checkbox'][name^='ccchkadd']").each(function() {
                var isChecked = document.getElementById($(this).attr("id")).checked;
                if (isChecked) {
                    qte_array.push($(this).val());
                }
            });
        }

        if (qte_array.length == 0) {
            alert("NO ELEMENT FOUND");
        } else {
            if (confirm("Are you sure you want to Add Connected Records?")) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        qte: qte_array,
                        dno: dno,
                        dyr: dyr,
                        cl: cl,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    success: function(msg) {
                        if (msg == "") {
                            alert("Record Connected Successfully.");
                            location.reload();
                        } else if (msg == "error") {
                            alert("Diary no. not correct!!Please enter case no. again");
                            location.reload();
                        } else alert(msg);
                        //updateCSRFToken();
                    },
                    error: function() {
                        alert("ERROR");
                        //updateCSRFToken();
                    },
                });
            }
        }
    }



    async function get_case_status() {
        await updateCSRFTokenSync();
        var diaryno = "";
        var diaryyear = "";
        var cstype = "";
        var csno = "";
        var csyr = "";
        var regNum = new RegExp("^[0-9]+$");
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if ($("#search_type_c").is(":checked")) {
            cstype = $("#case_type").val();
            csno = $("#case_number").val();
            csyr = $("#case_year").val();

            if (!regNum.test(cstype)) {
                alert("Please Select Casetype");
                $("#selct1").focus();
                return false;
            }
            if (!regNum.test(csno)) {
                alert("Please Fill Case No in Numeric");
                $("#case_no1").focus();
                return false;
            }
            if (!regNum.test(csyr)) {
                alert("Please Fill Case Year in Numeric");
                $("#case_yr1").focus();
                return false;
            }
            if (csno == 0) {
                alert("Case No Can't be Zero");
                $("#case_no1").focus();
                return false;
            }
            if (csyr == 0) {
                alert("Case Year Can't be Zero");
                $("#case_yr1").focus();
                return false;
            }
        } else if ($("#search_type_d").is(":checked")) {
            diaryno = $("#diary_number").val();
            diaryyear = $("#diary_year").val();
            if (!regNum.test(diaryno)) {
                alert("Please Enter Diary No in Numeric");
                $("#dno_add_c").focus();
                return false;
            }
            if (!regNum.test(diaryyear)) {
                alert("Please Enter Diary Year in Numeric");
                $("#dyr_add_c").focus();
                return false;
            }
            if (diaryno == 0) {
                alert("Diary No Can't be Zero");
                $("#dno_add_c").focus();
                return false;
            }
            if (diaryyear == 0) {
                alert("Diary Year Can't be Zero");
                $("#dyr_add_c").focus();
                return false;
            }
        } else {
            alert("Please Select Any Option");
            return false;
        }

        var dno = document.getElementById("h_dno").value;
        var dno1 = document.getElementById("diary_number").value;
        var dyr = document.getElementById("h_dyr").value;
        var dyr1 = document.getElementById("diary_year").value;
        if (dno1 == dno && dyr == dyr1) alert("Add different case no from Main Case");
        else {
            $.ajax({
                type: "POST",
                url: "/Filing/Tagging/conn_case_status",
                beforeSend: function() {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                data: {
                    dno: dno,
                    dyr: dyr,
                    dno1: dno1,
                    dyr1: dyr1,
                    ct: cstype,
                    cn: csno,
                    cy: csyr,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(data) {
                    //updateCSRFToken();
                    $("#loader").html('');
                    $('#ccdiv').html(data);
                },
                error: function() {
                    //updateCSRFToken();
                    alert("ERROR");
                },
            });
        }
    }
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                $('.casetype_section').show();
                $('.diary_section').hide();
            } else {
                $('.casetype_section').hide();
                $('.diary_section').show();
            }
            //alert('search_type='+search_type);
        });
    });
</script>