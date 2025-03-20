<style>
fieldset {
    padding: 5px !important;
    background-color: #F5FAFF !important;
    border: 1px solid #0083FF !important;
}

legend {
    background-color: #E2F1FF !important;
    width: 100% !important;
    text-align: center !important;
    border: 1px solid #0083FF !important;
    font-weight: bold !important;
}

.table3,
.subct2,
.subct3,
.subct4,
#res_on_off,
#resh_from_txt {
    display: none;
}

.toggle_btn {
    text-align: left;
    color: #00cc99;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
}
</style>
<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> ADVANCE ELIMINATION LIST PRINT MODULE </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="mrgT20">
                                <!--<form method="post">-->
                                    <?= csrf_field() ?>
                                    <div id="dv_content1">
                                        <div class="form-row justify-content-center">
                                            <div class="form-group col-md-3">
                                                <label for="listing_dts">Advance Elimination Dates</label>
                                                <select class="form-control" name="listing_dts" id="listing_dts">
                                                    <?php if (!empty($listing_dts)) : ?>
                                                    <option value="-1" selected>SELECT</option>
                                                    <?php foreach ($listing_dts as $date) : ?>
                                                    <option value="<?= $date['next_dt_old'] ?>">
                                                        <?= date("d-m-Y", strtotime($date['next_dt_old'])) ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <option value="-1" selected>EMPTY</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="board_type">Board Type</label>
                                                <select class="form-control" name="board_type" id="board_type">
                                                    <option value="0">-ALL-</option>
                                                    <option value="J">Court</option>
                                                    <option value="S">Single Judge</option>
                                                    <option value="C">Chamber</option>
                                                    <option value="R">Registrar</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 align-self-end">
                                                <button type="button" name="btn1" id="btn1"
                                                    class="btn btn-primary btn-block">Submit</button>
                                            </div>
                                        </div>
                                        <div id="res_loader" class="text-center mt-3"></div>
                                        <div id="dv_res1" class="mt-3"></div>
                                    </div>
                                <!--</form>-->
                    </div>                                                        
                    <!-- Main content end -->
                </div>
                <!--end dv_content1-->
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<script>
$(document).on("click", "#btn1", function() {
    get_cl_1();
});

function get_cl_1() {
    var listing_dts = $("#listing_dts").val();
    var board_type = $("#board_type").val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: "<?php echo base_url('Listing/Elimination/get_cause_list_advance_elimination'); ?>",
        cache: false,
        async: true,
        data: {
            listing_dts: listing_dts,
            board_type: board_type,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        beforeSend: function() {
            $("#dv_res1").html(
                "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>"
            );
        },
        type: 'POST',
        success: function(data, status) {
            updateCSRFToken();
            $('#dv_res1').html(data);
            if (data)
                $('#res_on_off').show();
        },
        error: function(xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

$(document).on("click", "#ebublish", function() {
    var prtContent = $("#prnnt").html();
    var listing_dts = $("#listing_dts").val();
    var board_type = $("#board_type").val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: "<?php echo base_url('Listing/Elimination/cl_print_save_advance_elimination'); ?>",
        cache: false,
        async: true,
        data: {
            list_dt: listing_dts,
            board_type: board_type,
            prtContent: prtContent,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        beforeSend: function() {
            $("#res_loader").html(
                "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>"
            );
        },
        type: 'POST',
        success: function(data, status) {
            updateCSRFToken();
            $('#res_loader').html(data.message);
            alert(data.message);
        },
        error: function(xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });

});

$(document).on("click", "#prnnt1", function() {
    var prtContent = $("#prnnt").html();
    var vac_yr = $("#vac_yr").val();
    var temp_str = prtContent;
    var WinPrint = window.open('', '',
        'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write(temp_str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
});
</script>