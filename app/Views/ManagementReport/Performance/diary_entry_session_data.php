<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Data Entry Calc</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $action = '';
                        $attribute = 'name="searchForm" method="get" id="searchForm"';
                        echo form_open($action, $attribute);
                        csrf_token();
                        ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Date</label>
                                    <input type="text" id="fromDate" class="dtp form-control cus-form-ctrl" name="fromDate" placeholder="From Date" autocomplete="off" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Module Name</label>
                                    <select id="module_id" name="module_id" class="form-control cus-form-ctrl">
                                        <option value="">Select Module</option>';
                                        <?php
                                        if (isset($module_name) && !empty($module_name)) {
                                            foreach ($module_name as $resData) {
                                                $moduleName = str_replace('_', ' ', $resData['module_name']);
                                                echo ' <option value="' . trim($resData['id']) . '">' . strtoupper($moduleName) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Select User</label>
                                    <select id="user_id" name="user_id" class="form-control cus-form-ctrl">
                                        <option value="">Select User</option>';
                                        <?php
                                        if (isset($userSql) && !empty($userSql)) {
                                            foreach ($userSql as $userData) {
                                                echo '<option value="' . trim($userData['empid']) . '">' . strtoupper($userData['name']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1 mt-4">
                                    <input type="submit" id="searchButton" name="searchButton" value="Search">
                                </div>
                                <div class="col-md-1 mt-4">
                                    <a href="<?php echo base_url('ManagementReports/Performance/diary_entry_session_data'); ?>"><input type="button" value="Clear Search" /></a>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                        <div id="" style="margin-left: auto; margin-right: auto; width: auto; padding-top: 2%;">
                            <?php
                            $fromDate = $fromDate ?? date('d-m-Y');
                            echo '<div class="printme">';
                            $title = 'Diary Entry Module User Performance Data for Dated ' . $fromDate;
                            echo '<h1>' . $title . '</h1>';
                            echo ' <table class="table table-striped custom-table display" id="diaryTable">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Diary No.</th>
                                        <th>Cause Title</th>
                                        <th>Entry Started</th>
                                        <th>Entry Completed</th>
                                        <th>Difference (In Seconds)</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    $i = 1;
                                    $regNo = '';
                                    $total = 0;
                                    if (isset($result_array) && !empty($result_array)) {
                                        foreach ($result_array as $record) {
                                            $causeTitle = '';
                                            if (!empty($record['pet_name']) && !empty($record['res_name'])) {
                                                $causeTitle = $record['pet_name'] . '  Vs ' . $record['res_name'];
                                            } else if (!empty($record['pet_name'])) {
                                                $causeTitle = $record['pet_name'];
                                            } else if (!empty($record['res_name'])) {
                                                $causeTitle = $record['res_name'];
                                            }
                                            $regNo  = !empty($record['reg_no_display']) ? $record['reg_no_display'] . '/' . $record['diary_no'] : $record['diary_no'];
                                            $total += (strtotime($record['diary_no_rec_date']) - strtotime($record['entry_time']));
                                            echo ' <tr>
                                                <td data-key="S. No.">' . $i . '</td>
                                                <td data-key="Diary No.">' . $regNo . '</td>
                                                <td data-key="Cause Title">' . $causeTitle . '</td>
                                                <td data-key="Entry Started">' . date('d-m-Y H:i:s', strtotime($record['entry_time'])) . '</td>
                                                <td data-key="Entry Completed">' . date('d-m-Y H:i:s', strtotime($record['diary_no_rec_date'])) . '</td>
                                                <td data-key="Difference (In Seconds)">' . (strtotime($record['diary_no_rec_date']) - strtotime($record['entry_time'])) . '</td>
                                            </tr>';
                                            $i++;
                                        }
                                    }
                                echo '</tbody>
                            </table>';
                            $title .= "<br>Total Time Taken - " . date('H:i:s', mktime(0, 0, round($total))) . " (hh:mm:ss)";
                            echo '<div id="totalDiv" style="float: right; margin-right: 11px; margin-top: 15px;">Total Time Taken - ' . date('H:i:s', mktime(0, 0, round($total))) . ' (hh:mm:ss)</div>';
                            echo '</div>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).on("focus",".dtp",function(){
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth : true,
            changeYear  : true,
            yearRange : '1950:2050'
        });
    });
</script>
<script>
    $(document).ready(function() {
        var title = '<?php echo $title; ?>';
        var filename = '<?php echo $title; ?>';
        $("#diaryTable").DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'print',
                className: 'btn btn-primary glyphicon glyphicon-print',
                title: title,
                pageSize: 'A4',
                orientation: 'landscape',
                text: 'Print',
                autoWidth: false,
                columnDefs: [{
                    "width": "20px",
                    "targets": [0]
                }],
                customize: function(win) {
                    $(win.document.body).find('h1').css('font-size', '20px');
                    $(win.document.body).find('h1').css('text-align', 'left');
                    $(win.document.body).find('tab').css('width', 'auto');
                    var last = null;
                    var current = null;
                    var bod = [];
                    var css = '@page { size: landscape; }',
                        head = win.document.head || win.document.getElementsByTagName('head')[
                            0],
                        style = win.document.createElement('style');
                    style.type = 'text/css';
                    style.media = 'print';
                    if (style.styleSheet) {
                        style.styleSheet.cssText = css;
                    } else {
                        style.appendChild(win.document.createTextNode(css));
                    }
                    head.appendChild(style);
                }
            }],
            paging: true,
            ordering: false,
            info: true,
            searching: false
        });
        $("#user_id").select2();
        $("#module_id").select2();
    });
</script>