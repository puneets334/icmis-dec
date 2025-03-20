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
                                <h3 class="card-title">Court Master</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <?=view('Court/CourtMaster/courtMaster_breadcrumb'); ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2"style="background-color: #fff; border-bottom:none;">
                                <h4 class="basic_heading">Re Print</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'courtMaster', 'id' => 'courtMaster', 'autocomplete' => 'off');
                                    echo form_open('', $attribute);

                                    ?>
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            
                                            <div class="row">
                                                <div class="col-sm-12 col-md-3 mb-3">

                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input party" type="radio" id="ddl_od" name="ddl_oud" value="O">
                                                        <label for="ddl_od" class="custom-control-label">Order Date</label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input party" type="radio" id="ddl_ud" name="ddl_oud" value="U">
                                                        <label for="ddl_ud" class="custom-control-label">Uploaded Date</label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label class="" for="txt_o_frmdt">From Date</label>
                                                    <input type="text" name="txt_o_frmdt" id="txt_o_frmdt" value="<?php echo date('d-m-Y'); ?>" size="6" maxlength="10" class="form-control dtp">
                                                </div>

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label class="" for="txt_o_todt">To Date</label>
                                                    <input type="text" name="txt_o_todt" id="txt_o_todt" value="<?php echo date('d-m-Y'); ?>" size="6" maxlength="10" class="form-control dtp">
                                                </div>

                                                <div class="col-md-12">
                                                    <center><input type="button" id="btn_submit" name="btn_submit" class="btn btn-primary" onclick="get_records()" value="Submit"></center>
                                                </div>
                                            </div>
                                        </div>

                                        <hr><br>
                                        <div id="loader"></div>
                                        <div id="showData">
                                        </div>

                                        <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
                                            &nbsp;
                                        </div>
                                        <div id="dv_fixedFor_P2" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 105">
                                            <div id="sp_close2" style="text-align: right;cursor: pointer;float: right;" onclick="closeData2()">
                                                <!-- <img src="close_btn.png" style="width: 30px;height: 30px;"> -->
                                                <i style="margin-top:70px;font-size:40px;color:red;" class="fa fa-times" aria-hidden="true"></i>
                                            </div>
                                            <div id="sar1" style="margin: 0 auto; background-color: white;overflow: hidden;width: 83%;height: 91%;margin-top: 4%;margin-left: 15%;">
                                                <object style="width: 100%;height: 100%" type="application/pdf" id="ggg_object2"></object>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script type="text/javascript">
    function get_records() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        var order_upload = '';
        if ($('#ddl_od').is(':checked')) {
            order_upload = $('#ddl_od').val();
        } else if ($('#ddl_ud').is(':checked')) {
            order_upload = $('#ddl_ud').val();
        }

        var txt_o_frmdt = $('#txt_o_frmdt').val();
        var txt_o_todt = $('#txt_o_todt').val();


        if (order_upload == '' || (txt_o_frmdt == '' || txt_o_frmdt.length < 10) || (txt_o_todt == '' || txt_o_todt.length < 10)) {
            if (order_upload == '') {
                alert("Please select Order Date or Uploaded Date");
            } else if (txt_o_frmdt == '') {
                alert("Please enter from date");
            } else if (txt_o_frmdt.length < 10) {
                alert("Please enter valid from date");
            } else if (txt_o_todt == '') {
                alert("Please enter to date");
            } else if (txt_o_todt.length < 10) {
                alert("Please enter valid to date");
            }
        } else {

            $.ajax({
                url: "<?php echo base_url('Court/CourtMasterController/getReprintJO'); ?>",
                type: "get",
                beforeSend: function() {
                    $("#showData").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                data: {
                    CSRF_TOKEN: csrf,
                    order_upload: order_upload,
                    txt_o_frmdt: txt_o_frmdt,
                    txt_o_todt: txt_o_todt
                },
                success: function(result) {
                    $('#showData').html(result)
                    updateCSRFToken();
                },
                error: function() {
                    alert('Error');
                    updateCSRFToken();
                }
            });
        }
    }


    function save_upload(docid) {

        document.getElementById('dv_sh_hd').style.display = 'block';
        document.getElementById('dv_fixedFor_P2').style.display = 'block';

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 0) {
                    document.getElementById('sar1').innerHTML += "<h3>No PDF Found</h3>";
                } else {
                    document.getElementById('ggg_object2').setAttribute('data', xmlhttp.responseText);
                }

            }
        }
        var url = "/getPdfName?CSRF_TOKEN=" + csrf + "&docid=" + docid;
        var base_url = "<?php echo base_url('Court/CourtMasterController') ?>";

        var actual_link = base_url + url;

        xmlhttp.open("GET", actual_link, false);
        xmlhttp.send(null);
    }

    function closeData2() {
        $('#dv_fixedFor_P2').css("display", "none");
        $('#dv_sh_hd').css("display", "none");
    }
</script>

