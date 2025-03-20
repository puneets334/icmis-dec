<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Verify-Not Verify Loose Documents</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="POST" id="push-form" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="usercode" id="usercode" value="<?php echo $usercode; ?>" />
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="from_date">From Date</label>
                                                        <input type="text" id="from_date" value="<?php echo isset($_POST['from_date']) ? $_POST['from_date'] : ''; ?>" name="from_date" class="form-control dtp" placeholder="From Date" required="required">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="from_date">To Date</label>
                                                        <input type="text" id="to_date" value="<?php echo isset($_POST['to_date']) ? $_POST['to_date'] : ''; ?>" name="to_date" class="form-control dtp" placeholder="To Date" required="required">
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="submit" id="view" name="view" class="quick-btn mt-26">View REPORT</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                            //if(is_array($reports))
                                            if (sizeof($case_result) > 0) {
                                            ?>
                                                <div id="printable1" class="table-responsive">
                                                    <table id="example1" class="table table-striped custom-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5%;" rowspan='1'>SNo.</th>
                                                                <th style="width: 5%;" rowspan='1'>Date</th>
                                                                <!--   <th style="width: 5%;" rowspan='1'>Section</th>-->
                                                                <th style="width: 5%;" rowspan='1'>Total</th>

                                                                <th style="width: 5%;" rowspan='1'>Verfiy</th>
                                                                <th style="width: 5%;" rowspan='1'>Not Verify</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $s_no = 1;
                                                            foreach ($case_result as $result) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $s_no; ?></td>
                                                                    <td><?php echo $result['date1']; ?></td>
                                                                    <!--<td><?php /*echo $result['section_name'];*/ ?></td>-->
                                                                    <td><?php echo $result['total']; ?></td>
                                                                    <td><button class="btn btn-default" data-toggle="modal" data-target="#modal-default" onclick="get_detail('<?php echo $result['date1']; ?>','V','<?php echo $result['sec_id']; ?>','<?php echo $_POST['usercode']; ?>');"> <?php echo $result['verify']; ?></button></td>
                                                                    <td><button class="btn btn-default" data-toggle="modal" data-target="#modal-default" onclick="get_detail('<?php echo $result['date1']; ?>','N','<?php echo $result['sec_id']; ?>','<?php echo $_POST['usercode']; ?>');"> <?php echo $result['not_verify']; ?></button></td>

                                                                </tr>
                                                            <?php
                                                                $s_no++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?PHP
                                            }
                                            ?>

                                            <div class="modal fade" id="modal-default" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content" style="width: 170%; overflow-y:scroll;  position:absolute; right:-29%">
                                                        <div class="modal-header">
                                                            <button type="button" style="width:15%;float:left" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                                                            <button type="button" style="float:right" class="btn btn-danger" data-dismiss="modal">X</button>
                                                        </div>
                                                        <div class="modal-body table-responsive" id="printable">
                                                            <table width="100%" id="reportTable2" class="table table-striped custom-table">
                                                                <thead>
                                                                    <h3 style="text-align: center;"> Loose Documents Report </h3>
                                                                    <tr>
                                                                        <th>S No.</th>
                                                                        <th>Case No</th>
                                                                        <th>Section</th>
                                                                        <th>Doc No</th>
                                                                        <th>Dealing Assitant</th>
                                                                        <th>DAK Received By</th>
                                                                        <th>Entry Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                                            <button type="button" style="width:15%;float:left" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
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
            </div>
        </div>
    </div>
</section>

<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });

    function get_detail(date, flag, section, usercode) {
        $('#reportTable2 tbody').empty();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('ManagementReports/DA/DA/get_verify_Nverify_Details'); ?>",
            data: {
                date: date,
                flag: flag,
                section: section,
                usercode: usercode,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            dataType: 'json',
            type: "POST",
            success: function(data) {

                $('#reportTable2 tbody').empty();
                sno = 1;
                $.each(data, function(index) {
                    $('#reportTable2 tbody').append("<tr><td>" + sno + "</td><td>" + data[index].CaseNo + "</td><td>" + data[index].da_section + "</td><td>" + data[index].document + "-> " + data[index].docdesc + "</td><td>" + data[index].da_empid + "@ " + data[index].da_name + "</td><td>" + data[index].dak_name + "@ " + data[index].dak_empid + "</td><td>" + data[index].ent_dt + "</td></tr>");
                    sno++;
                });
                $("#modal-default").show();
            },
            error: function() {
                console.log('error');
            }
        });
    }
</script>