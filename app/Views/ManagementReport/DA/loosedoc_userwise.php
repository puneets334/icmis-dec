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
                                                        <button type="button" id="view" name="view" class="quick-btn mt-26">View REPORT</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="res_loader"></div>
                                            <div id="dv_res1"></div>
                                           

                                            <div class="modal" id="modal-default">
                                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" id="print" name="print" class="btn btn-primary">Print</button>
                                                                        <button type="button" style="float:right" class="btn btn-danger" data-dismiss="modal">X</button>
                                                                    </div>
                                                                    <div class="modal-body table-responsive" id="prnnt">
                                                                        <div class="reportTable"></div>
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
                                                                        <button type="button" style="width:15%;float:left" id="print" name="print"  class="btn btn-block btn-warning">Print</button>
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
    // $("#example1").DataTable({
    //     "responsive": true,
    //     "lengthChange": false,
    //     "autoWidth": false,
    //     "dom": 'Bfrtip',
    //     "bProcessing": true,
    //     "buttons": [
    //         {
    //             extend: 'excelHtml5',
    //             title: 'Verify-Not Verify Loose Documents' // Add your desired Excel heading here
    //         },
    //         {
    //             extend: 'pdfHtml5',
    //             title: 'Verify-Not Verify Loose Documents'   // Add your desired PDF heading here
    //         }
    //     ]
    // });

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });
    $(document).on("click", "#view", async function() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var usercode = $("#usercode").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        
        $.ajax({
            url: "<?php echo base_url('ManagementReports/DA/DA/get_loosedoc_verify_Nverify_data'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#res_loader').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                usercode: usercode,
                from_date: from_date,
                to_date: to_date,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(msg_new) {
               // updateCSRFToken();
                $('#res_loader').html("");    
                $('#dv_res1').html(msg_new);    
            },
            error: function(jqXHR, textStatus, errorThrown) {
               // updateCSRFToken();
                //alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
        //updateCSRFToken();
    });

    async function get_detail(date, flag, section, usercode) {
        $('#reportTable2 tbody').empty();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        await updateCSRFTokenSync();
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
            beforeSend: function() {
            $(".reportTable").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
        },
            type: "POST",
            success: function(data) {
               // updateCSRFToken();
                $(".reportTable").html('');
                $('#reportTable2 tbody').empty();
                sno = 1;
                $.each(data, function(index) {
                    $('#reportTable2 tbody').append("<tr><td>" + sno + "</td><td>" + data[index].caseno + "</td><td>" + data[index].da_section + "</td><td>" + data[index].document + "-> " + data[index].docdesc + "</td><td>" + data[index].da_empid + "@ " + data[index].da_name + "</td><td>" + data[index].dak_name + "@ " + data[index].dak_empid + "</td><td>" + data[index].ent_dt + "</td></tr>");
                    sno++;
                });
                $("#modal-default").show();
            },
            error: function() {
               // updateCSRFToken();
                console.log('error');
            }
            
        });
       // updateCSRFToken();
    }
    $(document).on("click","#print",function(){    
        var prtContent = $("#prnnt").html();
        var temp_str=prtContent;
        var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        });

   
</script>