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
                                <h3 class="card-title">R & I </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Notices >> Dispatch</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="processYear">Delivery Type </label>
                                                        <select class="form-control" name="ddlOR_x" id="ddlOR_x" onchange="hd_sh_ln_ntlk(this.value)">
                                                            <option value="">Select</option>
                                                            <option value="O">Ordinary</option>
                                                            <option value="R">Registry</option>
                                                            <option value="A">Humdust</option>
                                                            <option value="Z">Advocate Registry</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3" id="sp_lnk_nt" style="display:none">
                                                        <label for="rdn_lnk">Select Type </label>
                                                        <input type="radio" name="rdn_lnk" id="rdn_lnk_nt" onclick="dis_enb_add_lnk(this.id)" /> Not Link
                                                        <input type="radio" name="rdn_lnk" id="rdn_lnk" onclick="dis_enb_add_lnk(this.id)" /> Link
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="processId">Process Id</label>
                                                        <input type="number" name="txtProcessId" id="txtProcessId" class="form-control"
                                                            placeholder="Process Id" value="" size="5">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="processYear">Year</label>
                                                        <select id="pro_yr" name="pro_yr" class="form-control">
                                                            <?php
                                                            for ($i = date("Y"); $i > 1949; $i--) {
                                                                echo "<option value=" . $i . ">$i</option>";
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <!-- <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button> -->
                                                        <button type="button" name="add_ext_rec" id="add_ext_rec" class="quick-btn mt-26" value="" onclick="add_linked_ids()" style="display: none">Add Linked Id</button>
                                                        <button type="button" name="btn_pro_id" id="btn_pro_id" class="quick-btn mt-26" value="Submit" onclick="get_dt_process_id()">Submit</button>
                                                        <span id="sp_sp_pro"></span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="hd_d_t" id="hd_d_t" />
                                            </form>
                                        </div>
                                        <div id="res_loader"></div>
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
    function hd_sh_ln_ntlk(str) {

        if (str == 'O' || str == 'R' || str == 'Z') {
            $('#sp_lnk_nt').css('display', 'inline');
            $('#rdn_lnk').attr('checked', false);
            $('#rdn_lnk_nt').attr('checked', true);

        } else if (str == 'A') {
            $('#sp_lnk_nt').css('display', 'none');
            $('#rdn_lnk').attr('checked', false);
            $('#rdn_lnk_nt').prop('checked', true);
            $('#sp_sp_pro').html('');
            $('#add_ext_rec').css('display', 'none');
            $('#btn_pro_id').attr('disabled', false);

        }

    }

    function dis_enb_add_lnk(str) {
        if (str == 'rdn_lnk') {
            $('#add_ext_rec').css('display', 'inline');
            $('#btn_pro_id').attr('disabled', true);
            //            $('#dv_range_type').css('display','inline');
        } else {
            $('#sp_sp_pro').html('');
            $('#add_ext_rec').css('display', 'none');
            $('#btn_pro_id').attr('disabled', false);
            //                 $('#dv_range_type').css('display','none');
        }
    }

    function add_linked_ids() {
        var ddl_range_type = $('#ddl_range_type').val();
        if (($('#ddlOR_x').val() == 'O' || $('#ddlOR_x').val() == 'R' || $('#ddlOR_x').val() == 'Z') && ($("#rdn_lnk").is(':checked'))) {
            if ($('#sp_sp_pro').html() == '')
                $('#sp_sp_pro').append($('#txtProcessId').val() + '-' + $('#pro_yr').val());
            else
                $('#sp_sp_pro').append(',' + $('#txtProcessId').val() + '-' + $('#pro_yr').val());
            $('#btn_pro_id').attr('disabled', false);
        }
    }

    function closeData() {
        document.getElementById('ggg').scrollTop = 0;
        document.getElementById('dv_fixedFor_P').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";
        get_dt_process_id();

    }

    function get_dt_process_id() {

        $('#hd_d_t').val('1');

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var txtProcessId = $("#txtProcessId").val();
        var pro_ty = $("#pro_yr").val();
        var ddlOR_x = $("#ddlOR_x").val();
        var rd_ck_nt = '';
        var sp_sp_pro = '';
        if (ddlOR_x == '' || txtProcessId == '' || ((ddlOR_x == 'O' || ddlOR_x == 'R' || ddlOR_x == 'Z') && ($("#rdn_lnk_nt").is(':not(:checked)') &&
                $("#rdn_lnk").is(':not(:checked)')))) {
            if (ddlOR_x == '')
                alert("Please Select Delivery Type");
            else if (txtProcessId == '')
                alert("Please enter process id");
            else if ((ddlOR_x == 'O' || ddlOR_x == 'R' || ddlOR_x == 'Z') && ($("#rdn_lnk_nt").is(':not(:checked)') && $("#rdn_lnk").is(':not(:checked)')))
                alert("Please Select link or not link");
        } else {
            if ((ddlOR_x == 'O' || ddlOR_x == 'R' || ddlOR_x == 'Z') && ($("#rdn_lnk").is(':checked')))
                sp_sp_pro = $('#sp_sp_pro').html();
            if ($("#rdn_lnk_nt").is(':checked'))
                rd_ck_nt = 0;
            else if ($("#rdn_lnk").is(':checked'))
                rd_ck_nt = 1;

            $.ajax({
                url: "<?php echo base_url('RI/DispatchController/update_notice_dispatch'); ?>",
                type: 'GET',
                beforeSend: function() {
                    $("#res_loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                cache: false,
                async: false,
                data: {
                    txtProcessId: txtProcessId,
                    pro_ty: pro_ty,
                    ddlOR: ddlOR_x,
                    rd_ck_nt: rd_ck_nt,
                    sp_sp_pro: sp_sp_pro,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(data, status) {
                    updateCSRFToken();
                    $("#res_loader").html(data);
                    var ck_rd_nt = '';
                    if (rd_ck_nt == 0)
                        ck_rd_nt = 'rdn_not_link';
                    else if (rd_ck_nt == 1)
                        ck_rd_nt = 'rdn_link';
                    if (ddlOR_x == 'O' || ddlOR_x == 'R' || ddlOR_x == 'Z')
                        get_lis_notlis(ck_rd_nt);
                    $('#chkDispatch_0').prop('checked', true);

                    ena_lnk_case('chkDispatch_0');

                    //    if(ddlOR_x=='O')
                    if (ddlOR_x == 'O' || ((ddlOR_x == 'R' || ddlOR_x == 'Z') && $("#rdn_lnk").is(':checked')))
                        $('#txtWeight0').focus();
                    else if ((ddlOR_x == 'R' || ddlOR_x == 'Z') && $("#rdn_lnk_nt").is(':checked'))
                        $('#price_0').focus();
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error:" + xhr.text + ' ' + xhr.status);
                }
            });
        }
    }

    function ena_lnk_case(str) {
        var ex_vc = str.split('_');
        if ($('#rdn_link').is(':checked')) {
            var sno = 0;
            if ($('#chkDispatch_' + ex_vc[1]).is(':not(:checked)') && $('#txtWeight' + ex_vc[1]).is(':not(:disabled)')) {

                $('#txtWeight' + ex_vc[1]).attr('disabled', true);
                $('#btnsinsub_' + ex_vc[1]).attr('disabled', true);
                $('#price_' + ex_vc[1]).attr('contenteditable', false);
                $('.cl_chkbox').each(function() {
                    $(this).attr('checked', false);
                });
            } else {
                var x = $('.cl_chkbox').toArray();
                for (var i = 0; i < x.length; i++) {
                    if (($('#txtWeight' + i).is(':not(:disabled)') && $('.cl_chkbox').is(':checked'))) {
                        sno = 1;
                    }

                }
                if (sno == 0) {
                    $('#txtWeight' + ex_vc[1]).attr('disabled', false);
                    $('#price_' + ex_vc[1]).attr('contenteditable', true);
                    $('#btnsinsub_' + ex_vc[1]).attr('disabled', false);

                }
            }

        }
    }


    function checkFunction() {
        // alert("rrrr"); 
        //updateCSRFToken(); 
        var fromDate = $("#processId").val();
        var toDate = $("#processYear").val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (fromDate == "") {
            alert("Select Received From Date.");
            $("#fromDate").focus();
            return false;
        }
        if (toDate == "") {
            alert("Select Received To Date.");
            $("#toDate").focus();
            return false;
        }
        var dynamicUrl = "<?php // echo base_url('RI/DispatchController/post_update_dispatch/' . $ucode); 
                            ?>";
        $.ajax({
            url: dynamicUrl,
            type: "POST",
            data: $("#dispatchDakToRI").serialize(),
            success: function(data) {
                updateCSRFToken();
                //$('.card-title').hide();
                // $('.page-header').hide();
                $("#dataProcessId").html(data);
                // $("#dispatchDakToRI").hide(); 
            },
            error: function(xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });
    }
</script>