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
                                <h3 class="card-title">Management Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">ROGY</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label for="">Select Section</label>
                                                        <select class="form-control"  id="section_name" name="section_name">
                                                            <option value="ALL">-ALL-</option>
                                                            <?php
                                                            foreach ($data as $row_sec) {
                                                            ?>
                                                                <option value="<?php echo $row_sec['section_name']; ?>"><?php echo $row_sec['section_name']; ?></option>
                                                            <?php
                                                            } ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-6 mb-3">
                                                        <button type="button" id="get_report" class="quick-btn mt-26">SHOW DA WISE RED ORANGE GREEN REPORT</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="dv_res"></div>
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
    function CallPrint(strid)
{
 var prtContent = document.getElementById(strid);
 var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
 WinPrint.document.write(prtContent.innerHTML);
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
 //WinPrint.close();
 //prtContent.innerHTML=strOldOne;
}
       $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });

    $(document).on("click", "#get_report", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var section_name = $("#section_name").val();

        $.ajax({
            url: "<?php echo base_url('ManagementReports/DA/DA/get_da_wise_rgo');?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_res').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                section_name: section_name,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(msg_new) {
                updateCSRFToken();
                $('#dv_res').html(msg_new);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    });
</script>