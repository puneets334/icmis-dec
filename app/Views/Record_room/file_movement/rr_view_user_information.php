<?= view('header') ?>

<?php
if ($jud_sel == '')
    $judge_selector = "";
else
    $judge_selector = " AND jcode = $jud_sel ";

if ($orderjud == 'N')
    $orderjud = "";
else if ($orderjud == 'Y')
    $orderjud = " jcode, ";


if ($view_sta == '-1') {
    $view_status = "";
    $view_status2 = "";
} else if ($view_sta == '0') {
    $view_status = " WHERE pa_ps LIKE '_ABS' OR to_date != '0000-00-00' OR name IS NULL";
    $view_status2 = " AND pa_ps LIKE '_ABS' OR to_date != '0000-00-00' OR name IS NULL";
} else if ($view_sta == '1') {
    $view_status = " WHERE pa_ps NOT LIKE '_ABS' AND to_date = '0000-00-00' AND name IS NOT NULL";
    $view_status2 = " AND pa_ps LIKE '_ABS' OR to_date != '0000-00-00' OR name IS NULL";
}
$auth_name = 0;

$view_rs = $model->getUsers($dept, $auth_name, $authValue, $secValue, $desg, $usercode, $cur_user_type, $judge_selector, $orderjud);
// echo "<pre>"; print_r($view_rs);die;
if (count($view_rs) > 0) {
?>
    <style>
       table{
        width: 96% !important;
        font-size: small;
       }
       form hr {
    margin: 4px 0px !important;
    border-top: 1px solid #e0e0e0;
}
    </style>
    <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">

        <table id="query_builder_report" class="query_builder_report table table-bordered table-striped">
            <?php
            if ($auth_name != '0') {
            ?>
                <tr style="text-align: center;" class="for-print">
                    <td colspan="12">REPORT FOR USERS UNDER
                        <?php echo $auth_sel_name . " as ";
                        if ($authValue == 'A') echo "APPROVAL AUTHORITY";
                        else if ($authValue == 'F') echo "FORWARDING AUTHORITY";
                        ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <th>S.No.</th>
                <th>Usercode</th>
                <?php
                if ($dept == 'ALL') {
                ?>
                    <th>Department</th>
                <?php
                }
                ?>
                <th>Section</th>
                <th>Designation</th>
                <th>User's Name</th>
                <th>Emp ID</th>
                <th>Last Login</th>
                <th class="notfor-print">Active From</th>
                <th>Status</th>
                <?php
                if ($desg == 'ALL') {
                ?>
                    <!--<th class="notfor-print">Last User Name</th>-->
                <?php
                }

                ?>
                <th>Alloted Halls</th>
                <th>DA Alloted Cases <br> (Except Halls) </th>
            </tr>
            <?php
            $sno = 1;
            foreach ($view_rs as $row) {
            ?>
                <tr>
                    <th><?php echo $sno; ?></th>
                    <td><span class="cl_manage" id="cl_manage_f<?php echo $row['usercode']; ?>"><?php echo $row['usercode']; ?></span></td>
                    <?php
                    //onclick="cl_manage_f(this.id)"
                    if ($dept == 'ALL') {
                    ?>
                        <td><?php echo $row['dept_name']; ?></td>
                    <?php
                    }
                    ?>
                    <td><?php echo $row['section_name'];
                        if ($row['isda'] == 'Y') echo " &nbsp;<span style='color:red'>[DA]</span>"; //displayUsertype($row['usertype']); 
                        ?></td>
                    <td><?php echo $row['type_name']; //$row['username'];
                        ?></td>
                    <td><?php echo $row['name'];
                        if ($row['section'] == '19') {
                            $chk_fil_t = $model->getFilTrapUsers($row['usercode']);
                            if ($chk_fil_t) {
                        ?>
                                <div style="padding: 5px;width: 40%;background-color: #e3c4f2;color: #5d593a"><?php echo $chk_fil_t['type_name']; ?></div>
                        <?php
                            }
                        }
                        ?>
                    </td>
                    <td><?php echo $row['empid']; ?></td>
                    <!-- echo revertDate_hiphen($row['log_in']); -->
                    <td><?php
                        $log_date = $model->displayLastLogin($row['usercode']);
                        if ($log_date != '0000-00-00')
                            echo date('d-M-Y', strtotime($log_date));
                        else
                            echo ''; ?>
                            </td>
                    <td class="notfor-print"><?php echo ($row['entdt'] != '' && $row['entdt'] != '0000-00-00') ? date('d-m-Y', strtotime($row['entdt'])) : ''; ?></td> 
                   
                    <td><?php
                        $display_old = 0;
                        if ($row['attend'] == 'A') {
                            $display_old = 0;
                        ?>
                            <span style="color: red">NA</span>
                        <?php
                        } else {
                            $display_old = 1;
                        ?>
                            <span style="color: green">A</span>
                        <?php
                        } ?>
                    </td>
                    <?php
                    if ($desg == 'ALL') {
                     }

                    ?>
                    <td>
                         <div id="">
                        <div class="cl_chk_case">
                            <?php
                            $chk_case = $model->checkCase_one($row['usercode']);
                            if (count($chk_case) > 0) {
                                $forCaseGroup = '';
                                foreach ($chk_case as $row_chk) {                                    
                                    $forCaseGroup =  ($row_chk['for_caseGroup'] == 'C') ? 'Civil Cases':'Criminal Cases';
                                    echo "Hall No-" . $row_chk['ref_hall_no'] . ' ( ' . $row_chk['description'] . ' )-' . $forCaseGroup . '<hr>';
                                }
                            } 
                            ?>
                        </div>
                    </div> 
                    </td>
                    <td>
                        <div id="">
                            <?php
                            $currentYear = date("Y");
                            $chk_case = $model->checkCaseDistribution($row['usercode']);
                            if (count($chk_case) > 0) {
                                foreach ($chk_case as $row_chk) {
                            ?>
                                    <div class="cl_chk_case" style="font-size: small;">
                                        <?php
                                        if ($row_chk['case_from'] == 1 && $row_chk['case_to'] == 555555  && $row_chk['caseyear_from'] == 1950 && $row_chk['caseyear_to'] == $currentYear) {
                                            echo $row_chk['short_description'] . '- ALL';
                                        } else {
                                            echo $row_chk['short_description'] . '-' . $row_chk['case_from'] . '-' . $row_chk['caseyear_from'] . '-' . $row_chk['case_to'] . '-' . $row_chk['caseyear_to'] . @$caseStage;
                                        }
                                        ?>
                                    </div>
                            <?php

                                }
                            }

                            ?>
                        </div>

                    </td>
                </tr>
            <?php
                $sno++;
            }
            ?>
            <tr style="text-align: center" class="notfor-print">
                <td colspan="13"><button onclick="get_print('result_main_um')">PRINT</button></td>
            </tr>
        </table>
    </div>
<?php
} else {
?>
    <div style="margin: 0 auto;text-align: center;margin-top: 20px;font-size: 16px;color: #ff6666">SORRY, NO RECORD FOUND!!!</div>
<?php
}
?>
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
</div>
<div id="dv_fixedFor_P" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 105">

    <div id="sar1" style="background-color: white;overflow: auto;margin: 60px 300px 30px 300px;height: 60%;">
        <div id="sp_close" style="text-align: right;cursor: pointer;float: right">
            <img src="<?= base_url('images/close_btn.png'); ?>" style="width: 20px;height: 20px;">
        </div>
        <div id="sar" style="border: 0px solid red"></div>
    </div>
</div>

<script>
    $(function() {
        $("#query_builder_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                "copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "processing": true, // Changed "bProcessing" to "processing"
            "ordering": false, // Added to disable sorting

        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
    });
</script>