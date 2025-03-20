<?= view('header') ?>
<style>
    fieldset {
        padding: 5px;
        background-color: #F5FAFF;
        border: 1px solid #0083FF;
    }

    legend {
        background-color: #E2F1FF;
        width: 100%;
        text-align: center;
        border: 1px solid #0083FF;
        font-weight: bold;
    }

    #customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    .class_red {
        color: red;
    }

    .table3,
    .subct2,
    .subct3,
    .subct4 {
        display: none;
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
                                <h3 class="card-title">Judge Wise</h3>
                            </div>


                        </div>
                    </div>

                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div class="box-body">
                                <br>
                                <div class="form-group row">


                                    <label for="clDate" class="col-sm-2 col-form-label">Causelist Date</label>
                                    <div class="col-sm-2">
                                        <!-- <input type="text" id="clDate" name="clDate" class="form-control datepick" required placeholder="CL Date" autocomplete="off"> -->

                                        <input type="text" size="10" id="clDate" name="clDate" class="form-control ddtp" required placeholder="CL Date" autocomplete="off">
                                    </div>

                                    <!-- Hon'ble Judge Field -->
                                    <label for="judge" class="col-sm-2 col-form-label">Hon'ble Judge</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="judge" name="judge" required>
                                            <option value="">Select Judge</option>
                                            <?php foreach ($judges as $judge): ?>
                                                <option value="<?= esc($judge['jcode']) ?>"
                                                    <?= isset($judgeinitial[0]) && $judgeinitial[0] == $judge['jcode'] ? 'selected="selected"' : '' ?>>
                                                    <?= esc($judge['jcode']) ?> - <?= esc($judge['jname']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                </div>

                                <!-- Footer Buttons -->
                                <div class="box-footer">
                                    <button type="submit" style="width:15%; float:right;" id="view" name="view" class="btn btn-block btn-primary">View</button>
                                    <button type="submit" style="width:15%; float:left;" id="print" name="print" onclick="printDiv('printable');" class="btn btn-block btn-warning">Print</button>
                                </div>

                            </div> <!-- End of box-body -->

                            <!-- Placeholder for Result Sections -->

                            <div id="dv_res1"></div>

                        </div>
                    </form>


                </div>
                <div id="dv_res2" class="dv_res2"></div>



            </div>
        </div>
</section>



<script>
    // $(function() {
    //     $("#clDate").datepicker();
    // });
    var leavesOnDates = <?= next_holidays_new(); ?>;

    $(function() {
        var date = new Date();
        date.setDate(date.getDate());
        $('.ddtp').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            //startDate: date,
            todayHighlight: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050',
            datesDisabled: leavesOnDates,
            isInvalidDate: function(date) {
                return (date.day() == 0 || date.day() == 6);
            },
        });
    });

    $("#view").click(function(e) {
        e.preventDefault();
        date_wise_result();
    });

    function date_wise_result() {
        var clDate = $("#clDate").val();
        var judge = $("#judge").val();


        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: "<?php echo base_url('Listing/AdvanceListReport/judgeWiseRes/'); ?>",
            method: 'POST',
            data: {
                clDate: clDate,
                judge: judge,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(response) {
                updateCSRFToken();
                $('.dv_res2').html(response);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>