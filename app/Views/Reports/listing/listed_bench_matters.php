<?= view('header') ?>
<style>
    #reportTable2_filter label {
        margin-right: 50px;
    }

    #display_records h3 {
        color: #072c76 !important;
    }

    #display_records h3:hover {
        text-decoration: underline !important;
        color: #3670db !important;
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
                                <h3 class="card-title"> Listing Stats </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="container">
                                <?php $formAction = "Reports/Listing/ListedBenchStats/listed_bench_matters";
                                $attribute = array('class' => 'form-horizontal', 'name' => 'listing_stats', 'id' => 'listing_stats', 'autocomplete' => 'off');
                                echo form_open(base_url($formAction), $attribute);
                                ?>
                                <div class="form-group col-sm-12">
                                    <label for="">Miscellaneous</label>
                                    <input type="radio" name="mainhead" value='M' required <?php echo (isset($mainhead) && ($mainhead == 'M')) ? 'checked' : '' ?>>
                                    <label for="">Regular</label>
                                    <input type="radio" name="mainhead" value='F' required <?php echo (isset($mainhead) && ($mainhead == 'F')) ? 'checked' : '' ?>>
                                    <label for="">All</label>
                                    <input type="radio" name="mainhead" value='M,F' required <?php echo (isset($mainhead) && ($mainhead == 'M,F')) ? 'checked' : '' ?>>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="">Court</label>
                                    <input type="radio" name="board_type" value='J' required <?php echo (isset($mainhead) && ($board_type == 'J')) ? 'checked' : '' ?>>
                                    <label for="">Single judge</label>
                                    <input type="radio" name="board_type" value='S' required <?php echo (isset($mainhead) && ($board_type == 'S')) ? 'checked' : '' ?>>
                                    <label for="">Registrar</label>
                                    <input type="radio" name="board_type" value='R' required <?php echo (isset($mainhead) && ($board_type == 'R')) ? 'checked' : '' ?>>
                                    <label for="">Chamber</label>
                                    <input type="radio" name="board_type" value='C' required <?php echo (isset($mainhead) && ($board_type == 'C')) ? 'checked' : '' ?>>
                                    <label for="">All</label>
                                    <input type="radio" name="board_type" value='J,S,R,C' required <?php echo (isset($mainhead) && ($board_type == 'J,S,R,C')) ? 'checked' : '' ?>>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="">Listed from</label>
                                    <input type="text" class="form-control list_date" name="from_dt" required placeholder="From Date..." value="<?php echo isset($from_dt) ? $from_dt : ''; ?>" autocomplete="off">
                                    <label for="">Listed to</label>
                                    <input type="text" class="form-control list_date" name="to_dt" required placeholder="To Date..." value="<?php echo isset($to_dt) ? $to_dt : ''; ?>" autocomplete="off">
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="">Category</label>
                                    <select name="category" class="form-control">
                                        <option value="">Select category</option>
                                        <?php foreach ($categories as $arr) { ?>
                                            <option value="<?php echo $arr['id']; ?>" <?php echo (isset($category) && $category == $arr['id']) ? 'selected' : '' ?>><?php echo $arr['sub_name1'] . ' - ' . $arr['sub_name4']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="">Benches</label>
                                    <select name="benches" class="form-control" required>
                                        <option value="all" <?php echo (isset($benches) && $benches == 'all') ? 'selected' : '' ?>>All</option>
                                        <?php
                                        $benchesArr = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17'];
                                        foreach ($benchesArr as $val):
                                        ?>
                                            <option value="<?php echo $val; ?>" <?php echo (isset($benches) && $benches == $val) ? 'selected' : '' ?>><?php echo $val; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-12">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                </div>
                                <?php echo form_close(); ?>
                                <input type="hidden" id="mainhead" name="mainhead" value="<?php echo isset($mainhead) ? $mainhead : '' ?>">
                                <input type="hidden" id="board_type" name="board_type" value="<?php echo isset($board_type) ? $board_type : '' ?>">
                                <input type="hidden" id="from_dt" name="from_dt" value="<?php echo isset($from_dt) ? $from_dt : '' ?>">
                                <input type="hidden" id="to_dt" name="to_dt" value="<?php echo isset($to_dt) ? $to_dt : '' ?>">
                                <input type="hidden" id="category" name="category" value="<?php echo isset($category) ? $category : '' ?>">
                                <input type="hidden" id="benches" name="benches" value="<?php echo isset($benches) ? $benches : '' ?>">
                                <input type="hidden" id="tbl_sub" name="tbl_sub" value="sub">
                                <center id="total_rows"><?php echo isset($total_rows) ? $total_rows : ''; ?></center>
                                <div id="result_dis"></div>
                                <div class="panel">
                                    <table id="reportTable2" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Case no</th>
                                                <th>Cause title</th>
                                                <th>Listed date</th>
                                                <th>Mainhead</th>
                                                <th>Board type</th>
                                                <th>Bench Count</th>
                                                <th>Category</th>
                                                <th>Case status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main content end -->
                </div> <!--end dv_content1-->
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<script>
    $(function() {
        $('.list_date').datepicker({
            autoclose: true,
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    $(document).ready(function() {
        $('#reportTable2').hide();
    });


    $(document).on('click', '#display_records', function() {
        $('#reportTable2').show();
        $('#total_rows').empty();
        var title = function() {
            return 'Listing Stats';
        }

        $('#reportTable2').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'csv',
                    title: title,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                        stripHtml: true
                    }
                },
                {
                    extend: 'excel',
                    title: title,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                        stripHtml: true
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: title,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                        stripHtml: true
                    }
                },
                {
                    extend: 'print',
                    title: title,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                        stripHtml: true
                    }
                }
            ]
        });

        //DataTable END
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?= base_url('Reports/Listing/ListedBenchStats/listed_bench_matters_action'); ?>",
            cache: false,
            async: true,
            data: {
                from_dt: $("#from_dt").val(),
                to_dt: $("#to_dt").val(),
                mainhead: $("#mainhead").val(),
                board_type: $("#board_type").val(),
                category: $("#category").val(),
                benches: $("#benches").val(),
                tbl_sub: $("#tbl_sub").val(),
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                updateCSRFToken();
                $('#result_dis').html('<table widht="100%" align="center"><tr><td><img src="../../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(Resultdata, status) {
                updateCSRFToken();
                var rdata = JSON.parse(Resultdata);
                console.log(rdata);
                $("#reportTable2").DataTable().clear();
                var length = Object.entries(rdata).length;

                for (var i = 0; i < length; i++) {
                    $('#reportTable2').dataTable().fnAddData([
                        rdata[i].SNO,
                        rdata[i].Case_NO,
                        rdata[i].Cause_Title,
                        rdata[i].Listed_Date,
                        rdata[i].Mainhead,
                        rdata[i].Board_Type,
                        rdata[i].Bench_Count,
                        rdata[i].Category,
                        rdata[i].Case_Status
                    ]);
                }
                $("#result_dis").html('');

            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }); //END OF BTN_RPT_SEARCH()..
</script>