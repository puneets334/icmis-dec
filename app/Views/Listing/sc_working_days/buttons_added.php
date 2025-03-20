<?= view('header') ?>
    <style>
        table {
            border-collapse: collapse;
            margin: 30px 0px 30px;
            background-color: #fff;
            font-size: 14px;
        }
        table tr {
            height: 40px;
        } 
        table th {
            color: #111;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            padding: 6px 1px 6px 5px;
        }
        table td, th {
            border: 1px solid #ccc;
            text-align: center;
            padding-top:8px;
            font-size:15px;
            width:150px
        }
        table tr:nth-of-type(odd) {
            background: #eee;
        }
        .border{
            border: 0px !important;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <!-- /.card-header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="container-fluid m-0 p-0">
                            <div class="row clearfix mr-1 ml-1 p-0">
                                <div class="col-12 m-0 p-0">
                                    <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                                    <div class="card">
                                        <div class="card-header bg-info text-white font-weight-bolder"> Calendar Report </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-row col-12 px-2">
                                                    <?php
                                                    $attributes = 'class="row g-3"';
                                                    // $action = base_url('Listing/hybrid/consent_report_process');
                                                    echo form_open('', $attributes);
                                                        echo csrf_field();
                                                        $leaveType = isset($_POST['form_calender'])? $_POST['form_calender'] : '';
                                                        ?>
                                                        <div class="d-inline px-2">
                                                            <div class="input-group  mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"> From Date </span>
                                                                </div>
                                                                <div class="border">
                                                                    <input class="form-control cus-form-ctrl" type="date" name="From_date" id="myDate" value="<?= (isset($_POST['From_date']) && !empty($_POST['From_date'])) ? date('Y-m-d', strtotime($_POST['From_date'])) : '' ?>" onkeydown="return false">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-inline px-2">
                                                            <div class=" input-group  mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"> To Date </span>
                                                                </div>
                                                                <div class="border">
                                                                    <input class="form-control cus-form-ctrl" type="date" name="To_date" id="myDate1" value="<?= (isset($_POST['To_date']) && !empty($_POST['To_date']))? date('Y-m-d', strtotime($_POST['To_date'])) : '' ?>" onkeydown="return false">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-inline px-2">
                                                            <div class=" input-group mb-3">
                                                                <div class="border">
                                                                    <select class="form-control cus-form-ctrl" name="form_calender">
                                                                        <option value="All" <?php if($leaveType == 'All') echo 'selected'; ?> >All</option>
                                                                        <option value="Court Working Day" <?php if($leaveType == 'Court Working Day') echo 'selected'; ?> >Court Working</option>
                                                                        <option value="Registry Working Day" <?php if($leaveType == 'Registry Working Day') echo 'selected'; ?> >Registry Working</option>
                                                                        <option value="Court Holiday" <?php if($leaveType == 'Court Holiday') echo 'selected'; ?> >Court Holiday</option>
                                                                        <option value="Registry Holiday" <?php if($leaveType == 'Registry Holiday') echo 'selected'; ?> >Registry Holiday</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-inline px-2">
                                                            <div class="pl-2 mb-3">
                                                                <button type="submit" class="btn btn-success btn-block" name="submit" value="submit">Get</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-row_ col-12 px-2">
                                                    <?php echo form_close(); ?>
                                                    <?php
                                                    if(isset($_POST['submit'])) {
                                                        if (isset($getWorkingDay) && count($getWorkingDay) > 0) {
                                                            ?>
                                                            <div id="dv_content1"  >
                                                                <div>
                                                                    <table id="tab">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>SNo</th>
                                                                                <th>Listing/Verification Dt</th>
                                                                                <th>Is Regular Day</th>
                                                                                <th>Sec List Dt</th>
                                                                                <!--<th>updated_by</th><th>updated_on</th>-->
                                                                                <th>Misc. Dt (Fresh)</th>
                                                                                <th>Regular Day Dt (Fresh)</th>
                                                                                <th>Holiday Description</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $sno=1;
                                                                            // output data of each row
                                                                            foreach ($getWorkingDay as $row) {
                                                                                    
                                                                                $temp_date = $nmd = $date_day = $miscellaneous_date = $sec_list_release_dt = '';  

                                                                                if($row["working_date"] <> '0000-00-00' || $row["working_date"] <> NULL) {
                                                                                    $temp_date = strtotime($row["working_date"]);
                                                                                    $temp_date = date('d-m-Y',$temp_date);
                                                                                    $date_day = date("l",strtotime($row["working_date"]));
                                                                                    $date_day = $temp_date.'<br> '.$date_day;
                                                                                }
                                                                                
                                                                                if($row["misc_dt1"] <> '0000-00-00' && $row["misc_dt1"] <> NULL) {
                                                                                    $miscellaneous_date = date_create($row["misc_dt1"]);
                                                                                    $miscellaneous_date = date_format($miscellaneous_date,'d-m-Y');
                                                                                }
                                                                                
                                                                                if($row["nmd_dt"] <> '0000-00-00' && $row["nmd_dt"] <> NULL){
                                                                                    $nmd = date_create($row["nmd_dt"]);
                                                                                    $nmd = date_format($nmd,'d-m-Y');
                                                                                }
                                                                                
                                                                                if($row["sec_list_dt"] <> '0000-00-00' && $row["sec_list_dt"] <> NULL) {
                                                                                    $sec_list_release_dt = date_create($row["sec_list_dt"]);
                                                                                    $sec_list_release_dt = date_format($sec_list_release_dt,'d-m-Y');
                                                                                }
                                                                                
                                                                                echo "<tr><td>" .$sno++. "</td><td>" . $date_day ."</td><td>" . $row["nmdflag"] . "</td><td>" .$sec_list_release_dt. "</td><td>" .$miscellaneous_date. "</td><td>" .$nmd. "</td><td>" . $row["holiday_description"] . "</td></tr>";
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        } else {
                                                            echo "<p>0 results</p>";
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        var filename = "<?php echo 'Calender_Report as on ' . date("d-m-Y h:i:sA");?>";
        var title = "<?php if(isset($_POST['submit']) && $_POST['submit']) { echo 'Calender Report '.$_POST['form_calender'].' From '.date('d-m-Y', strtotime($_POST['From_date'])).' To '.date('d-m-Y', strtotime($_POST['From_date'])).' as on ' . date("d-m-Y h:i:s A"); } else { echo ''; } ?>";
        $(document).ready(function () {
            $('#tab').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt',
                        filename: filename,
                        title: title,
                        text: 'Export to Excel',
                        autoFilter: true,
                        sheetName: 'Sheet1'
                    },
                    {
                        extend: 'pdf', className: 'btn btn-primary glyphicon glyphicon-file',
                        filename: filename,
                        title: title,
                        pageSize: 'A4',
                        orientation: 'landscape',
                        text: 'Save as Pdf',
                        customize: function (doc) {
                            doc.styles.title = {
                                fontSize: '18',
                                alignment: 'left'
                            },
                            doc.styles.tableBodyEven.alignment = 'center';
                            doc.styles.tableBodyOdd.alignment = 'center';
                            //doc.content[1].table.widths = [40, 355, 62, 62, 62, 62, 62, 62]; //Width of Column in PDF
                        }
                    },
                    {
                        extend: 'print', className: 'btn btn-primary glyphicon glyphicon-print',
                        title: title,
                        pageSize: 'A4',
                        text: 'Print',
                        autoWidth: false,
                        columnDefs: [
                            {"width": "40%", "targets":1},
                        ],
                        customize: function (win) {
                            $(win.document.body).find('h1').css('font-size', '20px');
                            $(win.document.body).find('h1').css('text-align', 'left');
                            $(win.document.body).find('tab').css('width', 'auto');
                            var last = null;
                            var current = null;
                            var bod = [];
                            var css = '@page { size: landscape; }',
                                head = win.document.head || win.document.getElementsByTagName('head')[0],
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
                    }
                ],
                paging: false,
                ordering: false,
                info: false,
                searching: false
            });
        });
    </script>
<?=view('sci_main_footer') ?>