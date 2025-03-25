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

    .sp_red {
        color: red;
        cursor: pointer;
    }

    .sp_green {
        color: green;
        cursor: pointer;
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
                    sp_sp_pro: sp_sp_pro
                    //CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(data, status) {
                   // updateCSRFToken();
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
                   // updateCSRFToken();
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


    function disp_data_sin(str) {

        // var ddlOR=$('#ddlOR').val();

        if ($('#hd_d_t').val() == '0')
            var ddlOR = $('#ddlOR').val();
        else if ($('#hd_d_t').val() == '1')
            var ddlOR = $('#ddlOR_x').val();
        if (((ddlOR == 'R' || ddlOR == 'Z') && $('#rdn_not_link').is(':checked')) || ddlOR == 'A' || (ddlOR == 'O' && $('#rdn_not_link').is(':checked'))) {
            var ln_nl_val = '';
            var chk_fill_det = 0;
            var txt_bar_cd = '0';
            var ex_get_id = str.split('_');

            var ddlState_ext = '0';
            var ddl_district_ext = '0';
            if ($('#ddlState_ext' + ex_get_id[1]).length)
                ddlState_ext = $('#ddlState_ext' + ex_get_id[1]).val();
            if ($('#ddl_district_ext' + ex_get_id[1]).length)
                ddl_district_ext = $('#ddl_district_ext' + ex_get_id[1]).val();
            var ddlTehsil = $('#ddlTehsil' + ex_get_id[1]).val();
            var txtWeight = $('#txtWeight' + ex_get_id[1]).val();
            var price = $('#price_' + ex_get_id[1]).html();
            var hd_noticetype = $('#hd_noticetype' + ex_get_id[1]).val();
            var chkDispatch = $('#chkDispatch_' + ex_get_id[1]);
            //  alert(hd_noticetype);
            var ck_un_lnk = '';
            if ($('#rdn_not_link').is(':checked')) {
                ck_un_lnk = 0;
                ln_nl_val = $('#rdn_not_link').val();
            } else if ($('#rdn_link').is(':checked')) {
                ck_un_lnk = 1;
                ln_nl_val = $('#rdn_link').val();
            }
            if ($('#txt_bar_cd' + ex_get_id[1]).length)
                txt_bar_cd = $('#txt_bar_cd' + ex_get_id[1]).val();

            if (chkDispatch.is(':not(:checked)')) {
                alert("Please check Process Id");
                return false;
            }
            if ((txtWeight == '' && ck_un_lnk == 0 && (ddlOR == 'R' || ddlOR == 'Z')) ||
                ($('#txtWeight' + ex_get_id[1]).is(':not(:disabled)') && ck_un_lnk == 1 &&
                    $('#txtWeight' + ex_get_id[1]).val() == '') || ddlState_ext == '' ||
                ddl_district_ext == '' || (price == '' && ck_un_lnk == 0) ||
                (txt_bar_cd == '') ||
                ($('#txtWeight' + ex_get_id[1]).is(':not(:disabled)') && ck_un_lnk == 1 && price == '') || (txtWeight == '' && ck_un_lnk == 0 && (ddlOR == 'O'))) {
                chk_fill_det = 1;
                //if(ddlTehsil=='')
                //    $('#ddlTehsil'+ex_get_id[1]).css('background-color','red');
                if (txtWeight == '' && ck_un_lnk == 0 && (ddlOR == 'R' || ddlOR == 'Z'))
                    $('#txtWeight' + ex_get_id[1]).css('background-color', 'red');
                if ($('#txtWeight' + ex_get_id[1]).is(':not(:disabled)') && ck_un_lnk == 1 && $('#txtWeight' + ex_get_id[1]).val() == '')

                    $('#txtWeight' + ex_get_id[1]).css('background-color', 'red');
                if (ddlState_ext == '')
                    $('#ddlState_ext' + ex_get_id[1]).css('background-color', 'red');
                if (ddl_district_ext == '')
                    $('#ddl_district_ext' + ex_get_id[1]).css('background-color', 'red');
                if (price == '' && ck_un_lnk == 0)
                    $('#price_' + ex_get_id[1]).css('background-color', 'red');
                if (txt_bar_cd == '')
                    $('#txt_bar_cd' + ex_get_id[1]).css('background-color', 'red');
                if ($('#txtWeight' + ex_get_id[1]).is(':not(:disabled)') && ck_un_lnk == 1 && price == '')
                    $('#price_' + ex_get_id[1]).css('background-color', 'red');
                if (txtWeight == '' && ck_un_lnk == 0 && (ddlOR == 'O'))
                    $('#txtWeight' + ex_get_id[1]).css('background-color', 'red');

            }

            if (chk_fill_det == 1) {
                alert("Please fill detailts shown in red color");
            } else {
                $('#' + str).attr('disabled', true);
                var gus_l_nl = '';
                var txt_bar_cd = '';

                if (ln_nl_val == '1') {
                    if ($('#hd_d_t').val() == '0')
                        var ddlOR = $('#ddlOR').val();
                    else if ($('#hd_d_t').val() == '1')
                        var ddlOR = $('#ddlOR_x').val();

                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        url: "<?php echo base_url(); ?>/RI/DispatchController/get_dis_max_id",
                        type: 'GET',
                        cache: false,
                        async: false,
                        data: {
                            ddlOR: ddlOR,

                        },
                        success: function(data, status) {
                            alert(data);
                            gus_l_nl = data;
                        },
                        error: function(xhr) {
                            alert("Error:" + xhr.text + ' ' + xhr.status);
                        }
                    });
                }


                var con_chk_data = '';
                var tot_id = '';

                //  var ex_get_id=$(this).attr('id').split('_');
                if (con_chk_data == '') {
                    con_chk_data = $('#tr_sn' + ex_get_id[1]).attr('id');
                } else {
                    con_chk_data = con_chk_data + ',' + $('#tr_sn' + ex_get_id[1]).attr('id');
                }
                var hd_talw_id = $('#hd_talw_id' + ex_get_id[1]).val();
                //                    var ddlOR=$('#ddlOR').val();
                if ($('#hd_d_t').val() == '0')
                    var ddlOR = $('#ddlOR').val();
                else if ($('#hd_d_t').val() == '1')
                    var ddlOR = $('#ddlOR_x').val();

                var set_scp_nm = '';
                var chks_s_cps = '';
                var hdsto = $('#hdsto' + ex_get_id[1]).val();
                var hdsname = encodeURI($('#hdsname' + ex_get_id[1]).val());
                var hdcpsto = $('#hdcpsto' + ex_get_id[1]).val();
                var hdtot_sc = $('#hdtot_sc' + ex_get_id[1]).val();

                var ck_hdsto = $('#ck_hdsto' + ex_get_id[1]).val();
                var ck_hdcpsto = $('#ck_hdcpsto' + ex_get_id[1]).val();


                var ck_dis_m = $('#ck_dis_m' + ex_get_id[1]).val();


                if (ddlOR == 'O' || ddlOR == 'R') {
                    if (hdsto != 0 && hdsto != '') {
                        set_scp_nm = hdsto;
                        chks_s_cps = '0';
                    } else if (hdsto == '') {
                        set_scp_nm = hdcpsto;
                        chks_s_cps = '1';
                    } else if (hdsto == '0') {
                        set_scp_nm = hdsname;
                        chks_s_cps = '2';
                    }
                } else if (ddlOR == 'Z' || ddlOR == 'A') {
                    hdsto = 0;
                    set_scp_nm = hdsname;
                    chks_s_cps = '2';
                }
                if (tot_id == '') {
                    tot_id = hd_talw_id;
                } else {
                    tot_id = tot_id + '@' + hd_talw_id;
                }

                var ddlTehsil = $('#ddlTehsil' + ex_get_id[1]).val();
                var txtWeight = $('#txtWeight' + ex_get_id[1]).val();
                var price = $('#price_' + ex_get_id[1]).html();
                var txtRemdis = $('#txtRemdis_' + ex_get_id[1]).val();
                var hd_del_typo = $('#hd_del_typo' + ex_get_id[1]).val();

                var hd_ml_state = $('#hd_ml_state' + ex_get_id[1]).val();
                var hd_ml_district = $('#hd_ml_district' + ex_get_id[1]).val();
                var hd_proc_yrs = $('#hd_proc_yrs' + ex_get_id[1]).val();

                var hd_hd_rec_dt = $('#hd_hd_rec_dt' + ex_get_id[1]).val();
                var fil_hd = $('#fil_hd' + ex_get_id[1]).val();
                var spnottype = $('#spnottype_' + ex_get_id[1]).html();
                var sp_hd_noticetype = $('#sp_hd_noticetype' + ex_get_id[1]).html();
                var hd_noticetype = $('#hd_noticetype' + ex_get_id[1]).val();
                var hd_ddl_oraz = $('#hd_ddl_oraz').val();
                var hd_not_det = '';

                if ($('#' + hd_talw_id).length)
                    //                  if($('#'+hd_talw_id).length && !$('#hd_noticetype'+ex_get_id[1]).length)
                    hd_not_det = encodeURIComponent($('#' + hd_talw_id).html());
                else {
                    var hd_n_t_c = $('#hd_talw_id' + ex_get_id[1]).val() + '_' + $('#hd_noticetype' + ex_get_id[1]).val() + '_' + $('#hd_ddl_oraz').val();
                    hd_not_det = encodeURIComponent($('#' + hd_n_t_c).html());

                }
                //                    alert(hd_n_t_c);   
                //                  alert(hd_not_det);
                if (ddlOR == 'R' || ddlOR == 'Z') {
                    txt_bar_cd = $('#txt_bar_cd' + ex_get_id[1]).val();
                }
                var ddlState_ext = '0';
                var ddl_district_ext = '0';
                if ($('#ddlState_ext' + ex_get_id[1]).length)
                    ddlState_ext = $('#ddlState_ext' + ex_get_id[1]).val();
                if ($('#ddl_district_ext' + ex_get_id[1]).length)
                    ddl_district_ext = $('#ddl_district_ext' + ex_get_id[1]).val();

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
                $.ajax({
                    url: "<?php echo base_url(); ?>/RI/DispatchController/save_tw_dispatch",
                    type: 'POST',
                    async: false,
                    cache: false,
                    proccessData: false,
                    data: {
                        hd_talw_id: hd_talw_id,
                        ddlOR: ddlOR,
                        ddlTehsil: ddlTehsil,
                        txtWeight: txtWeight,
                        price: price,
                        txtRemdis: txtRemdis,
                        ln_nl_val: ln_nl_val,
                        gus_l_nl: gus_l_nl,
                        txt_bar_cd: txt_bar_cd,
                        hd_del_typo: hd_del_typo,
                        ddlState_ext: ddlState_ext,
                        ddl_district_ext: ddl_district_ext,
                        hdsto: hdsto,
                        hdsname: hdsname,
                        hdcpsto: hdcpsto,
                        hdtot_sc: hdtot_sc,
                        ck_hdsto: ck_hdsto,
                        ck_hdcpsto: ck_hdcpsto,
                        ck_dis_m: ck_dis_m,
                        hd_ml_state: hd_ml_state,
                        hd_ml_district: hd_ml_district,
                        hd_proc_yrs: hd_proc_yrs,
                        hd_hd_rec_dt: hd_hd_rec_dt,
                        fil_hd: fil_hd,
                        hd_not_det: hd_not_det,
                        spnottype: spnottype,
                        sp_hd_noticetype: sp_hd_noticetype,
                        hd_noticetype: hd_noticetype,
                        hd_ddl_oraz: hd_ddl_oraz,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    success: function(data, status) {
                        updateCSRFToken();
                        alert(data);
                        var sp_con_chk_data = con_chk_data.split(',');
                        for (var i = 0; i < sp_con_chk_data.length; i++) {
                            $('#' + sp_con_chk_data[i]).remove();
                        }
                        if (ddlOR == 'O' || ddlOR == 'R' || ddlOR == 'Z') {
                            if ($('#rdn_not_link').is(':checked')) {
                                $('#rdn_not_link').attr('checked', false);
                            } else if ($('#rdn_link').is(':checked')) {
                                $('#rdn_link').attr('checked', false);
                            }

                            $('.cl_chkbox').each(function() {
                                $(this).attr('disabled', true);
                            });
                            $('.cl_tw_weight').each(function() {
                                $(this).attr('disabled', true);
                            });
                        }
                        clear_dt();

                    },
                    error: function(xhr) {
                        updateCSRFToken();
                        alert("Error:" + xhr.text + ' ' + xhr.status);
                    }
                });



            }
        } else if ((ddlOR == 'O' || ddlOR == 'R' || ddlOR == 'Z') && $('#rdn_link').is(':checked')) {
            disp_data();
        }
    }


    // async function save_tw_dispatch() {
    //     await updateCSRFTokenSync();

    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();

    // }


    function clear_dt() {
        var date = new Date();
        $('#txtProcessId').val('');
        $('#pro_yr').val(date.getFullYear());
        $('#sp_sp_pro').html('');
        $('#dv_bn').html('');
    }



    function disp_data() {

        var ck_chk_st = 0;
        var ck_st = 0;
        var ln_nl_val = '';
        $('.cl_chkbox').each(function() {
            if ($(this).is(':checked')) {
                ck_chk_st++;
            }
        });
        if (ck_chk_st == 0) {
            alert("Please check atleast one Process Id");
        } else {
            var chk_fill_det = 0;
            var txt_bar_cd = '0';
            $('.cl_chkbox').each(function() {
                if ($(this).is(':checked')) {
                    var ex_get_id = $(this).attr('id').split('_');
                    var ddlState_ext = '0';
                    var ddl_district_ext = '0';
                    if ($('#ddlState_ext' + ex_get_id[1]).length)
                        ddlState_ext = $('#ddlState_ext' + ex_get_id[1]).val();
                    if ($('#ddl_district_ext' + ex_get_id[1]).length)
                        ddl_district_ext = $('#ddl_district_ext' + ex_get_id[1]).val();
                    var ddlTehsil = $('#ddlTehsil' + ex_get_id[1]).val();


                    var txtWeight = $('#txtWeight' + ex_get_id[1]).val();
                    var price = $('#price_' + ex_get_id[1]).html();
                    var hd_noticetype = $('#hd_noticetype' + ex_get_id[1]).val();
                    //  alert(hd_noticetype);
                    if ($('#hd_d_t').val() == '0')
                        var ddlOR = $('#ddlOR').val();
                    else if ($('#hd_d_t').val() == '1')
                        var ddlOR = $('#ddlOR_x').val();

                    var ck_un_lnk = '';
                    if ($('#rdn_not_link').is(':checked')) {
                        ck_un_lnk = 0;
                        ln_nl_val = $('#rdn_not_link').val();
                    } else if ($('#rdn_link').is(':checked')) {
                        ck_un_lnk = 1;
                        ln_nl_val = $('#rdn_link').val();
                    }
                    if ($('#txt_bar_cd' + ex_get_id[1]).length)
                        txt_bar_cd = $('#txt_bar_cd' + ex_get_id[1]).val();
                    if ((txtWeight == '' && ck_un_lnk == 0 && hd_noticetype != '51' && hd_noticetype != '52' && (ddlOR == 'R' || ddlOR == 'Z')) ||
                        ($('#txtWeight' + ex_get_id[1]).is(':not(:disabled)') && ck_un_lnk == 1 &&
                            $('#txtWeight' + ex_get_id[1]).val() == '') || ddlState_ext == '' || ddl_district_ext == '' ||
                        (price == '' && ck_un_lnk == 0) || (txt_bar_cd == '' && hd_noticetype != '51' &&
                            hd_noticetype != '52') || ($('#txtWeight' + ex_get_id[1]).is(':not(:disabled)') &&
                            ck_un_lnk == 1 && price == '') || (txtWeight == '' && ck_un_lnk == 0 && ddlOR == 'O') ||
                        (txtWeight == '' && ck_un_lnk == 0 && (ddlOR == 'R' || ddlOR == 'Z'))
                    ) {
                        chk_fill_det = 1;
                        if (ddlTehsil == '')
                            alert('hello');
                        //$('#ddlTehsil'+ex_get_id[1]).css('background-color','red');
                        if (txtWeight == '' && ck_un_lnk == 0 && hd_noticetype != '51' && hd_noticetype != '52' && (ddlOR == 'R' || ddlOR == 'Z'))
                            $('#txtWeight' + ex_get_id[1]).css('background-color', 'red');
                        if ($('#txtWeight' + ex_get_id[1]).is(':not(:disabled)') && ck_un_lnk == 1 && $('#txtWeight' + ex_get_id[1]).val() == '')

                            $('#txtWeight' + ex_get_id[1]).css('background-color', 'red');
                        if (ddlState_ext == '')
                            $('#ddlState_ext' + ex_get_id[1]).css('background-color', 'red');
                        if (ddl_district_ext == '')
                            $('#ddl_district_ext' + ex_get_id[1]).css('background-color', 'red');
                        if (price == '' && ck_un_lnk == 0)
                            $('#price_' + ex_get_id[1]).css('background-color', 'red');
                        if (txt_bar_cd == '' && hd_noticetype != '51' && hd_noticetype != '52')
                            $('#txt_bar_cd' + ex_get_id[1]).css('background-color', 'red');
                        if ($('#txtWeight' + ex_get_id[1]).is(':not(:disabled)') && ck_un_lnk == 1 && price == '')
                            $('#price_' + ex_get_id[1]).css('background-color', 'red');
                        if (txtWeight == '' && ck_un_lnk == 0 && ddlOR == 'O')
                            $('#txtWeight' + ex_get_id[1]).css('background-color', 'red');
                        if (txtWeight == '' && ck_un_lnk == 0 && (ddlOR == 'R' || ddlOR == 'Z'))
                            $('#price_' + ex_get_id[1]).css('background-color', 'red');

                    }

                }
            });

            if (chk_fill_det == 1) {
                alert("Please fill detailts shown in red color");
            } else {

                $('.cl_submit').attr('disabled', true);
                var gus_l_nl = '';
                var txt_bar_cd = '';

                if (ln_nl_val == '1') {
                    if ($('#hd_d_t').val() == '0')
                        var ddlOR = $('#ddlOR').val();
                    else if ($('#hd_d_t').val() == '1')
                        var ddlOR = $('#ddlOR_x').val();

                    $.ajax({
                        url: "<?php echo base_url(); ?>/RI/DispatchController/get_dis_max_id",
                        type: 'GET',
                        cache: false,
                        async: false,
                        data: {
                            ddlOR: ddlOR
                        },
                        success: function(data, status) {

                            gus_l_nl = data;
                        },
                        error: function() {
                            alert("Error:" + xhr.text + ' ' + xhr.status);
                        }
                    });
                }

                var con_chk_data = '';
                var tot_id = '';
                var set_scp_nm = '';
                var chks_s_cps = '';



                $('.cl_chkbox').each(function() {

                    if ($(this).is(':checked')) {
                        // alert("dfdfdf");
                        ck_st++;
                        var ex_get_id = $(this).attr('id').split('_');
                        if (con_chk_data == '') {
                            con_chk_data = $('#tr_sn' + ex_get_id[1]).attr('id');
                        } else {
                            con_chk_data = con_chk_data + ',' + $('#tr_sn' + ex_get_id[1]).attr('id');
                        }
                        var hd_talw_id = $('#hd_talw_id' + ex_get_id[1]).val();
                        // var ddlOR=$('#ddlOR').val();
                        var hdsto = $('#hdsto' + ex_get_id[1]).val();
                        var hdsname = encodeURI($('#hdsname' + ex_get_id[1]).val());
                        var hdcpsto = $('#hdcpsto' + ex_get_id[1]).val();
                        //                    alert(hdsto);
                        //                    alert(hdcpsto);

                        var ck_hdsto = $('#ck_hdsto' + ex_get_id[1]).val();
                        var ck_hdcpsto = $('#ck_hdcpsto' + ex_get_id[1]).val();
                        var ck_dis_m = $('#ck_dis_m' + ex_get_id[1]).val();

                        var hdtot_sc = $('#hdtot_sc' + ex_get_id[1]).val();
                        if ($('#hd_d_t').val() == '0')
                            var ddlOR = $('#ddlOR').val();
                        else if ($('#hd_d_t').val() == '1')
                            var ddlOR = $('#ddlOR_x').val();
                        if (ddlOR == 'O' || ddlOR == 'R') {


                            if (hdsto != 0 && hdsto != '') {
                                set_scp_nm = hdsto;
                                chks_s_cps = '0';
                            } else if (hdsto == '') {
                                set_scp_nm = hdcpsto;
                                chks_s_cps = '1';
                            } else if (hdsto == '0') {

                                set_scp_nm = hdsname;
                                chks_s_cps = '2';
                            }
                        } else if (ddlOR == 'Z' || ddlOR == 'A') {
                            hdsto = 0;
                            set_scp_nm = hdsname;
                            chks_s_cps = '2';
                        }
                        //                    alert(set_scp_nm);
                        //                    alert(chks_s_cps);
                        if ($('#hd_d_t').val() == '0')
                            var ddlOR = $('#ddlOR').val();
                        else if ($('#hd_d_t').val() == '1')
                            var ddlOR = $('#ddlOR_x').val();
                        if (tot_id == '') {
                            tot_id = hd_talw_id;
                        } else {
                            tot_id = tot_id + '@' + hd_talw_id;
                        }
                        var ddlTehsil = $('#ddlTehsil' + ex_get_id[1]).val();
                        var txtWeight = $('#txtWeight' + ex_get_id[1]).val();
                        var price = $('#price_' + ex_get_id[1]).html();
                        var txtRemdis = $('#txtRemdis_' + ex_get_id[1]).val();
                        var hd_del_typo = $('#hd_del_typo' + ex_get_id[1]).val();
                        var hd_proc_yrs = $('#hd_proc_yrs' + ex_get_id[1]).val();
                        var hd_hd_rec_dt = $('#hd_hd_rec_dt' + ex_get_id[1]).val();
                        var fil_hd = $('#fil_hd' + ex_get_id[1]).val();
                        var hd_ml_state = $('#hd_ml_state' + ex_get_id[1]).val();
                        var hd_ml_district = $('#hd_ml_district' + ex_get_id[1]).val();
                        var spnottype = $('#spnottype_' + ex_get_id[1]).html();
                        var sp_hd_noticetype = $('#sp_hd_noticetype' + ex_get_id[1]).html();

                        var hd_noticetype = $('#hd_noticetype' + ex_get_id[1]).val();
                        var hd_ddl_oraz = $('#hd_ddl_oraz').val();
                        var hd_not_det = '';

                        if ($('#' + hd_talw_id).length)
                        //                     if($('#'+hd_talw_id).length>0 && $('#hd_noticetype'+ex_get_id[1]).val<=0)
                        {
                            //                  alert('anshul');
                            hd_not_det = encodeURIComponent($('#' + hd_talw_id).html());

                        } else {
                            var hd_n_t_c = $('#hd_talw_id' + ex_get_id[1]).val() + '_' + $('#hd_noticetype' + ex_get_id[1]).val() + '_' + $('#hd_ddl_oraz').val();
                            hd_not_det = encodeURIComponent($('#' + hd_n_t_c).html());

                        }
                        //alert(hd_not_det);             
                        //                    alert(hd_not_det);
                        if (ddlOR == 'R' || ddlOR == 'Z') {
                            txt_bar_cd = $('#txt_bar_cd' + ex_get_id[1]).val();
                        }
                        var ddlState_ext = '0';
                        var ddl_district_ext = '0';
                        if ($('#ddlState_ext' + ex_get_id[1]).length)
                            ddlState_ext = $('#ddlState_ext' + ex_get_id[1]).val();
                        if ($('#ddl_district_ext' + ex_get_id[1]).length)
                            ddl_district_ext = $('#ddl_district_ext' + ex_get_id[1]).val();


                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                        $.ajax({
                            url: "<?php echo base_url(); ?>/RI/DispatchController/save_tw_dispatch",
                            type: 'POST',
                            async: false,
                            cache: false,

                            data: {
                                hd_talw_id: hd_talw_id,
                                ddlOR: ddlOR,
                                ddlTehsil: ddlTehsil,
                                txtWeight: txtWeight,
                                price: price,
                                txtRemdis: txtRemdis,
                                ln_nl_val: ln_nl_val,
                                gus_l_nl: gus_l_nl,
                                txt_bar_cd: txt_bar_cd,
                                hd_del_typo: hd_del_typo,
                                ddlState_ext: ddlState_ext,
                                ddl_district_ext: ddl_district_ext,
                                hdsto: hdsto,
                                hdsname: hdsname,
                                hdcpsto: hdcpsto,
                                hdtot_sc: hdtot_sc,
                                ck_hdsto: ck_hdsto,
                                ck_hdcpsto: ck_hdcpsto,
                                ck_dis_m: ck_dis_m,
                                hd_proc_yrs: hd_proc_yrs,
                                hd_hd_rec_dt: hd_hd_rec_dt,
                                fil_hd: fil_hd,
                                hd_ml_state: hd_ml_state,
                                hd_ml_district: hd_ml_district,
                                hd_not_det: hd_not_det,
                                spnottype: spnottype,
                                sp_hd_noticetype: sp_hd_noticetype,
                                hd_noticetype: hd_noticetype,
                                hd_ddl_oraz: hd_ddl_oraz,
                                CSRF_TOKEN: CSRF_TOKEN_VALUE
                            },
                            success: function(data, status) {
                                updateCSRFToken();
                                if (ck_st == ck_chk_st) {
                                    alert(data);
                                    show_ids(tot_id, ddlOR, set_scp_nm, chks_s_cps, ln_nl_val);

                                }
                            },
                            error: function(xhr) {
                                updateCSRFToken();
                                alert("Error:" + xhr.text + ' ' + xhr.status);
                            }
                        });
                    }
                });
            }
        }
    }

    function show_ids(tot_id, ddlOR, set_scp_nm, chks_s_cps, ln_nl_val) {
        set_scp_nm = encodeURIComponent(set_scp_nm);

        tot_id = encodeURIComponent(tot_id);

        //    alert(tot_id);
        document.getElementById('ggg').style.width = 'auto';
        document.getElementById('ggg').style.height = ' 500px';
        document.getElementById('ggg').style.overflow = 'scroll';
        //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
        document.getElementById('ggg').style.marginLeft = '18px';
        document.getElementById('ggg').style.marginRight = '18px';
        document.getElementById('ggg').style.marginBottom = '25px';
        document.getElementById('ggg').style.marginTop = '30px';

        document.getElementById('dv_sh_hd').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
        document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>';
        $.ajax({
            url: "<?php echo base_url(); ?>/RI/DispatchController/show_ids",
            type: 'GET',
            cache: false,
            async: true,
            data: {
                tot_id: tot_id,
                ddlOR: ddlOR,
                set_scp_nm: set_scp_nm,
                chks_s_cps: chks_s_cps,
                ln_nl_val: ln_nl_val
            },
            success: function(data, status) {
                $('#ggg').html(data);
            },
            error: function(xhr) {
                alert("Error:" + xhr.text + ' ' + xhr.status);
            }
        });

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