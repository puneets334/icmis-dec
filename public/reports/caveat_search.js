
$(document).ready(function () {

    $(document).on('change', '#ddl_st_agncy', function () {
        get_benches('0');
    });


    $(document).on('change', '#ddl_bench', function () {
        //           var org_id=$(this).attr('id');
        var idd = $(this).attr('id');

        var ddl_ref_court = $('#ddl_court').val();
        //     alert(ddl_ref_court);

        if (ddl_ref_court == '1' || ddl_ref_court == '3') {
            if (ddl_ref_court == '1')
                var chk_status = 'H';
            else
                var chk_status = 'L';
            get_lc_casetype(chk_status, idd);
        }
        else if (ddl_ref_court == '4' || ddl_ref_court == '5') {
            get_lc_casetype(chk_status, idd);
        }
    });

    $(document).on('change', '#ddl_court', function () {

        var idd = $(this).val();
        var ddl_bench = $('#ddl_bench').attr('id');

        if (idd == '4') {
            $('#ddl_st_agncy').val('490506');

            get_benches('1');
            var ddl_ref_court = $('#ddl_court').val();
            //     alert(ddl_ref_court);

            if (ddl_ref_court == '1' || ddl_ref_court == '3') {
                if (ddl_ref_court == '1')
                    var chk_status = 'H';
                else
                    var chk_status = 'L';
                get_lc_casetype(chk_status, ddl_bench);
            }
            else if (ddl_ref_court == '4' || ddl_ref_court == '5') {
                get_lc_casetype(chk_status, ddl_bench);
            }

        }else{
            get_benches('0');
        }
    });

    $(document).on('click', '#btn_submit', function () {
        var ddl_court = $('#ddl_court').val();
        var ddl_st_agncy = $('#ddl_st_agncy').val();
        var ddl_bench = $('#ddl_bench').val();
        var ddl_ref_case_type = $('#ddl_ref_case_type').val();
        var txt_ref_caseno = $('#txt_ref_caseno').val();
        var ddl_ref_caseyr = $('#ddl_ref_caseyr').val();
        var txt_order_date = $('#txt_order_date').val();
        //          alert(txt_order_date);
        if (ddl_court == '') {
            alert("Please select court");
            $('#ddl_court').focus();
            return false;
        }
        if (ddl_ref_caseyr == '') {
            alert("Please select year");
            $('#ddl_ref_caseyr').focus();
            return false;
        }
        if (ddl_st_agncy == '') {
            alert("Please select state");
            $('#ddl_st_agncy').focus();
            return false;
        }
        //$('#btn_submit').attr('disabled',true);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: base_url + '/Reports/Filing/Highcourt/get_caveat_search',
            cache: false,
            //async: true,
            data: {
                ddl_st_agncy: ddl_st_agncy, ddl_court: ddl_court, ddl_bench: ddl_bench,
                ddl_ref_case_type: ddl_ref_case_type, txt_ref_caseno: txt_ref_caseno, ddl_ref_caseyr: ddl_ref_caseyr,
                txt_order_date: txt_order_date, CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function () {
                $('#dv_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function (data, status) {
                updateCSRFToken();
                $('#dv_result').html(data);
                $('#btn_submit').attr('disabled', false);
            },
            error: function (xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });

    $(document).on('click', '#btn_left', function () {
        $('#btn_left').attr('disabled', true);
        var ddl_court = $('#ddl_court').val();
        var ddl_st_agncy = $('#ddl_st_agncy').val();
        var ddl_bench = $('#ddl_bench').val();
        var ddl_ref_case_type = $('#ddl_ref_case_type').val();
        var txt_ref_caseno = $('#txt_ref_caseno').val();
        var ddl_ref_caseyr = $('#ddl_ref_caseyr').val();
        var txt_order_date = $('#txt_order_date').val();


        var ct_count = parseInt($('#inc_count').val());
        var hd_fst = parseInt($('#hd_fst').val());
        //                alert($('#inc_count').val());
        var inc_val = parseInt($('#inc_val').val());
        var inc_tot = parseInt($('#inc_tot').val());

        var sp_frst = parseInt($('#sp_frst').html()) - inc_val;
        var inc_tot_pg = sp_frst;
        //               alert(inc_tot_pg);
        if ($('#btn_right').is(':disabled')) {
            $('#btn_right').attr('disabled', false);
        }
        //                if(hd_fst==0)
        //                    {
        //                     $('#btn_left').attr('disabled',false);
        //                   
        //                    }
        var nw_hd_fst = hd_fst - inc_val;
        $('#inc_count').val(ct_count - 1);
        if ($('#inc_count').val() == 1) {
            $('#btn_left').attr('disabled', true);
        }
        $.ajax({
            url: base_url + '/Reports/Filing/Highcourt/include_caveat',
            type: "GET",
            cache: false,
            async: true,
            beforeSend: function () {

                $('#dv_include').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            data: {
                nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1, inc_tot_pg: inc_tot_pg,
                ddl_st_agncy: ddl_st_agncy, ddl_court: ddl_court, ddl_bench: ddl_bench,
                ddl_ref_case_type: ddl_ref_case_type, txt_ref_caseno: txt_ref_caseno, ddl_ref_caseyr: ddl_ref_caseyr,
                txt_order_date: txt_order_date
            },
            success: function (data, status) {



                $('#dv_include').html(data);

                //                           alert( $('#inc_count').val());
                $('#hd_fst').val(nw_hd_fst);
                $('#sp_last').html(parseInt($('#sp_frst').html()) - 1);
                $('#sp_frst').html(parseInt($('#sp_frst').html()) - inc_val);

                if (sp_frst == 1)
                    $('#btn_left').attr('disabled', true);
                else
                    $('#btn_left').attr('disabled', false);
            },
            error: function (xhr) {
                alert("Error: " + xhr.status + ' ' + xhr.statusText);
            }
        });
    });

    $(document).on('click', '#btn_right', function () {
        //    $('#btn_right').click(function(){

        $('#btn_right').attr('disabled', true);
        var ddl_court = $('#ddl_court').val();
        var ddl_st_agncy = $('#ddl_st_agncy').val();
        var ddl_bench = $('#ddl_bench').val();
        var ddl_ref_case_type = $('#ddl_ref_case_type').val();
        var txt_ref_caseno = $('#txt_ref_caseno').val();
        var ddl_ref_caseyr = $('#ddl_ref_caseyr').val();
        var txt_order_date = $('#txt_order_date').val();
        var ct_count = parseInt($('#inc_count').val());
        var hd_fst = parseInt($('#hd_fst').val());
        var inc_val = parseInt($('#inc_val').val());

        var inc_tot = parseInt($('#inc_tot').val());
        var inc_tot_pg = parseInt($('#inc_tot_pg').val());
        //              alert(inc_tot_pg);
        if (hd_fst == 0) {
            $('#btn_left').attr('disabled', false);

        }
        var nw_hd_fst = hd_fst + inc_val;
        //                 alert(ct_count);
        //                  alert(inc_val);
        //   alert(ct_count+'@@'+hd_fst+'@@'+inc_val+'@@'+inc_tot+'@@'+inc_tot_pg+'@@'+nw_hd_fst);
        if (ct_count == inc_tot - 1) {
            $('#btn_right').attr('disabled', true);
        }
        $.ajax({
            url: base_url + '/Reports/Filing/Highcourt/include_caveat',
            type: "GET",
            cache: false,
            async: true,
            beforeSend: function () {

                $('#dv_include').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            data: {
                nw_hd_fst: nw_hd_fst, inc_val: inc_val, u_t: 1, inc_tot_pg: inc_tot_pg,
                ddl_st_agncy: ddl_st_agncy, ddl_court: ddl_court, ddl_bench: ddl_bench,
                ddl_ref_case_type: ddl_ref_case_type, txt_ref_caseno: txt_ref_caseno, ddl_ref_caseyr: ddl_ref_caseyr,
                txt_order_date: txt_order_date
            },
            success: function (data, status) {

                //                             alert(data);

                $('#dv_include').html(data);
                //                                 alert($('dv_include').html());
                $('#inc_count').val(ct_count + 1);
                //                           alert( $('#inc_count').val());
                $('#hd_fst').val(nw_hd_fst);

                $('#sp_frst').html(parseInt($('#sp_last').html()) + 1);
                var sp_last_ck = parseInt($('#sp_last').html()) + inc_val;
                var sp_nf = parseInt($('#sp_nf').html());
                //                         alert(sp_last_ck+'$$'+sp_nf);
                if (sp_last_ck <= sp_nf) {
                    $('#sp_last').html(parseInt($('#sp_last').html()) + inc_val);
                    $('#btn_right').attr('disabled', false);
                }
                else {
                    //                                  $('#sp_last').html('');
                    $('#sp_last').html(sp_nf);
                    $('#btn_right').attr('disabled', true);
                }

            },
            error: function (xhr) {
                alert("Error: " + xhr.status + ' ' + xhr.statusText);
            }
        });


    });

});
function get_benches(str) {

    var ddl_st_agncy = $('#ddl_st_agncy').val();
    var ddl_court = $('#ddl_court').val();
    $('#ddl_ref_case_type').html('<option value="">Select</option>');
    if (ddl_st_agncy != '' && ddl_court != '') {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: base_url + '/Reports/Filing/Highcourt/get_bench',
            cache: false,
            //async: true,
            data: { ddl_st_agncy: ddl_st_agncy, ddl_court: ddl_court, CSRF_TOKEN: CSRF_TOKEN_VALUE },
            //            beforeSend: function () {
            //                $('#dv_ent_z').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
            //            },
            type: 'POST',
            success: function (data, status) {
                updateCSRFToken();
                $('#ddl_bench').html(data);
                if (str == 1) {
                    $('#ddl_bench').val('10000');
                    $('#ddl_st_agncy').attr('disabled', true);
                }
                else {
                    $('#ddl_bench').val('');
                    //                               $('#ddl_st_agncy').val('')
                    $('#ddl_st_agncy').attr('disabled', false);
                }

            },
            error: function (xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
}

function get_lc_casetype(z, idd) {
    //alert("sssss:"+z);
    var ddl_st_agncy = '';
    var m_ldist = '';
    var cl_hc_dc = '';
    if (idd == 'ddl_bench') {
        ddl_st_agncy = $('#ddl_st_agncy').val();
        cl_hc_dc = $('#ddl_court').val();
    }
 
    var corttyp = z;
 
    $.ajax({
        url: base_url + "/Reports/Filing/Highcourt/get_lc_casetype?corttyp=" + corttyp + '&ddl_st_agncy=' + ddl_st_agncy + '&m_ldist=' + m_ldist + "&cl_hc_dc=" + cl_hc_dc,
        cache: false,
        //async: true,
        //data: {ddl_st_agncy: ddl_st_agncy,ddl_court:ddl_court,CSRF_TOKEN: CSRF_TOKEN_VALUE},
        //            beforeSend: function () {
        //                $('#dv_ent_z').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
        //            },
        type: 'GET',
        success: function (data, status) {
            updateCSRFToken();           
            if (idd == 'ddl_bench') {
                $('#ddl_ref_case_type').html(data);
            }

        },
        error: function (xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });




}