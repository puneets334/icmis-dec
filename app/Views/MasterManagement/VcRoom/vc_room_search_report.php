<?= view('header') ?>
<style>
    .list-group-item:nth-child(even) {
        background-color: #e6f2ff;
    }

    .list-group-item:nth-child(odd) {
        background-color: #F5F5F5;
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
                                <h3 class="card-title">Report</h3>
                            </div>
                        </div>
                    </div>

                    <form method="post">
                        <?= csrf_field() ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12"></div>
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="row ml-1">
                                            <div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="next_dt_addon" style="margin:7px 0;">Listing Date<span style="color:red;">*</span></span>
                                                    </div>
                                                    <input type="text" class="form-control datepick" id="next_dt" name="next_dt" placeholder="Selcet Date">
                                                </div>
                                            </div>

                                            <div>
                                                <div class="input-group mb-3">
                                                    <div>
                                                        <input id="btn_search" name="btn_search" type="button" class="btn btn-success" value="Search">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-xs-12"></div>
                                </div>
                                <div class="form-row">

                                    <div class="row col-md-12 m-0 p-0" id="result"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



<script>
    $("#btn_search").click(function() {
        var next_dt = $("#next_dt").val();
        if (next_dt === '') {
            alert('Please Select Listing Date');
            return;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $('#result').html("");
        if (next_dt.length == 0) {
            $('#next_dt').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Listing Date Required* </strong></div>');
            $("#next_dt").focus();
            return false;
        } else {

            $.ajax({
                url: '<?php echo base_url('MasterManagement/VcRoom/vcRoomGetReport'); ?>',
                cache: false,
                async: true,
                data: {
                    next_dt: next_dt,
                    CSRF_TOKEN: csrf,
                },
                beforeSend: function() {
                    $('#result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $("#result").html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    });
    $(function() {
        $("#next_dt").datepicker();
    });
</script>