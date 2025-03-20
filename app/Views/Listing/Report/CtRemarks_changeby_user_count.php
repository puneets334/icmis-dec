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

    .modal-dialog {
    max-width: 100%;
    width: 95%;
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
                                    <h4 class="basic_heading">Court Remark Modify</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="push-form" method="POST" action="">
                                            <?= csrf_field() ?>
                                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="">On Date</label>
                                                        <input type="text" id="on_date" value="<?php echo isset($_POST['on_date']) ? $_POST['on_date'] : ''; ?>" name="on_date" class="form-control dtp"  required="required" readonly>


                                                        <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode']; ?>" />
                                                    </div>
                                                    <div class="col-12 text-center">
                                                        <input type="submit" d="view" name="view" class="btn btn-primary mt-5" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div>
                                            <?php
                                            if (isset($_POST['view'])) {
                                            ?>
                                                <h3 style="text-align: center;"><strong>Court Remarks Count as on Cause List Date <?= $on_date1; ?></strong></h3>
                                                <div class="table-responsive">
                                                    <table id="reportTable1" class="table table-striped custom-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5%;">SNo.</th>
                                                                <th style="width: 5%;">Updated By</th>
                                                                <th style="width: 5%;">Total Updation</th>
                                                                <th style="width: 5%;">Total Modification</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (!empty($case_result) && is_array($case_result)) {
                                                                $s_no = 1;
                                                                $t_up = 0;
                                                                $t_mod = 0;
                                                                foreach ($case_result as $result) {
                                                            ?>
                                                                    <tr>
                                                                        <td><?php echo $s_no; ?></td>
                                                                        <td style="width:50%"><?php echo $result['uid_name']; ?></td>
                                                                        <td style="width:20%"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default" onclick="get_detail('<?php echo date("Y-m-d", strtotime($_POST['on_date'])); ?>','C','<?php echo $result['uid']; ?>');"> <?php echo $result['current_total']; ?></button></td>
                                                                        <td style="width:50%"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default" onclick="get_detail('<?php echo date("Y-m-d", strtotime($_POST['on_date'])); ?>','M','<?php echo $result['uid']; ?>');"> <?php echo $result['history_total']; ?></button></td>
                                                                    </tr>
                                                                <?php
                                                                    $s_no++;
                                                                    $t_up += $result['current_total'];
                                                                    $t_mod += $result['history_total'];
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td></td>
                                                                    <td style="color:red; width:50%; font-weight: bold;">GRAND TOTAL</td>
                                                                    <td style="color:red; width:20%; font-weight:bold;"><?php echo $t_up; ?></td>
                                                                    <td style="color:red; width:20%; font-weight:bold;"><?php echo $t_mod; ?></td>
                                                                </tr>
                                                            <?php
                                                            } else {
                                                                // Display a row indicating no records were found
                                                                echo "<tr><td colspan='4' style='text-align: center; color: red;'><strong>No record found</strong></td></tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-default" style="display: none;">
                                    <div class="modal-dialog model-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <!-- <button type="button" style="width:15%;float:left" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button> -->
                                                <button type="button" style="float:right" class="btn btn-danger" data-dismiss="modal">X</button>
                                            </div>
                                            <div class="modal-body" id="printable">
                                                <h4 style="text-align: center;">Remarks Detail Updations Report </h4>
                                                <div class="table-responsive" id="reportTable">
                                                    <table id="reportTable2" class="table table-striped custom-table">
                                                        <thead>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                                                <button type="button" class="btn btn-warning" id="prnnt1">Print</button>
                                                <!-- <button type="button" style="width:15%;float:left" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button> -->
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

$(document).on("click", "#prnnt1", function()
    {
        var prtContent = $("#reportTable").html();
        
        var temp_str = prtContent;
        
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
    $(document).ready(function()
    {
        $("#reportTable1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": ["excel", "pdf"]
        });
        // $("#reportTable2").DataTable({
        //     "responsive": true,
        //     "lengthChange": false,
        //     "autoWidth": false,
        //     "dom": 'Bfrtip',
        //     "bProcessing": true,
        //     "buttons": ["excel", "pdf"]
        // });
    });

    function get_detail(cl_date, flag, usercode)
    {
       
        $('#reportTable2 tbody').empty();
        let csrfName = $("#csrf_token").attr('name');
        let csrfHash = $("#csrf_token").val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/Report/CtRemarks_user_details'); ?>",
            data: {
                cl_date: cl_date,
                flag: flag,
                usercode: usercode,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_data').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            dataType: 'json',
            type: "POST",
            success: function(data) {
            console.log("AJAX Response:", data); 
            updateCSRFToken();
            $('#reportTable2 tbody').empty();
            sno = 1;

            if (!data.success) {
                console.log("No records found.");
                return;
            }

            if (flag == 'M') {
                $('#reportTable2 thead tr').empty();
                $('#reportTable2 thead').append("<tr><th rowspan='1'>SNo.</th><th rowspan='1'>Ct No.</th><th rowspan='1'>Item No.</th><th rowspan='1'>Case No.</th><th rowspan='1'>Titled As</th><th rowspan='1'>Current Remarks</th><th rowspan='1'>Current Updated By</th><th rowspan='1'>Old Remarks</th><th rowspan='1'>Old Updated By</th></tr>");
            } else {
                $('#reportTable2 thead tr').empty();
                $('#reportTable2 thead').append("<tr><th rowspan='1'>SNo.</th><th rowspan='1'>Ct No.</th><th rowspan='1'>Item No.</th><th rowspan='1'>Case No.</th><th rowspan='1'>Titled As</th><th rowspan='1'>Current Remarks</th><th rowspan='1'>Current Updated By</th></tr>");
            }

            $.each(data.data, function(index) { // Ensure correct property
                console.log("Appending row:", data.data[index]);

                if (flag == 'C') {
                    $('#reportTable2 tbody').append("<tr><td>" + sno + "</td><td>" + data.data[index].courtno + "</td><td>" + data.data[index].brd_prnt + "</td><td>" + data.data[index].caseno + "</td><td>" + data.data[index].pet_name + " vs " + data.data[index].res_name + "</td><td>" + data.data[index].rmrk_disp + "</td><td>" + data.data[index].uid + "</td></tr>");
                } else {
                    $('#reportTable2 tbody').append("<tr><td>" + sno + "</td><td>" + data.data[index].courtno + "</td><td>" + data.data[index].brd_prnt + "</td><td>" + data.data[index].caseno + "</td><td>" + data.data[index].pet_name + " vs " + data.data[index].res_name + "</td><td>" + data.data[index].rmrk_disp + "</td><td>" + data.data[index].uid + "</td><td>" + data.data[index].old_rmrk_disp + "</td><td>" + data.data[index].old_uid + "</td></tr>");
                }
                sno++;
            });

            $("#modal-default").modal('show'); 
            },
            error: function()
            {
                updateCSRFToken();
                console.log('error');
            }
        });
    }

    



</script>