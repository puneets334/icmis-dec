<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Original Records</h3>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card" id="alert_header" style="display: none;">
                                <div class="card-body">
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong id="alert_message"></strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php }  ?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <?php echo component_html(); ?>
                                    <center> <button type="button" class="btn btn-primary" id="submit" onClick="callGetDetailsNew();">Get Details</button></center>
                                    <?php form_close(); ?>


                                </div>
                                <div class="card-footer">
                                    <div id="result"></div>
                                    <div id="dv_res1"> </div>
                                    <div id="dv_res2"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="loader"></div>
<script>
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function showErrorAlert(message) {
        $('#alert_message').text(message);
        $('#alert_header').fadeIn();
        setTimeout(function() {
            $('#alert_header').fadeOut();
        }, 5000);
    }

    async function callGetDetailsNew() {
       await updateCSRFTokenSync();
        var diary_number = $('#diary_number').val();
        var diary_year = $('#diary_year').val();
        var case_type = $('#case_type').val();
        var case_number = $('#case_number').val();
        var case_year = $('#case_year').val();
        var option_list = $('input:radio[name=search_type]:checked').val();
        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();

        const regex = /^\d+$/;

        var validationError = true; // Flag to track validation state
        $(".invalid-feedback").remove(); // Remove any previous error messages
        $(".is-invalid").removeClass('is-invalid border-danger'); // Reset error style

        // Validation for option_list
        if (option_list === 'D') {
            if (!diary_number) {
                $("#diary_number").addClass('is-invalid').after("<div class='invalid-feedback'>Please enter Diary No.</div>");
                validationError = false;
            } else if (!regex.test(diary_number)) {
                $("#diary_number").addClass('is-invalid').after("<div class='invalid-feedback'>Diary No. must be a number.</div>");
                validationError = false;
            }

            if (!diary_year) {
                $("#diary_year").addClass('is-invalid').after("<div class='invalid-feedback'>Please enter Diary Year.</div>");
                validationError = false;
            }
        } else if (option_list === 'C') { // Changed from 'elseif' to 'else if' and adjusted condition
            if (!case_type) {
                $("#case_type").addClass('is-invalid').after("<div class='invalid-feedback'>Please select case type.</div>");
                validationError = false;
            }
            if (!case_number) {
                $("#case_number").addClass('is-invalid').after("<div class='invalid-feedback'>Please enter case number.</div>");
                validationError = false;
            } else if (!regex.test(case_number)) {
                $("#case_number").addClass('is-invalid').after("<div class='invalid-feedback'>Case No. must be a number.</div>");
                validationError = false;
            }
            if (!case_year) {
                $("#case_year").addClass('is-invalid').after("<div class='invalid-feedback'>Please select case year.</div>");
                validationError = false;
            }
        } else {
            showErrorAlert("Something went wrong. Please try again later.");
            return; // Exit function if validation fails
        }
        $('#submit').prop('disabled', true);
        // Proceed if validation is successful
        if (validationError) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Judicial/OriginalRecord/HcOr/getIndexingReport'); ?>",
                data: {
                    'u_t': 0,
                    'diary_number': diary_number,
                    'diary_year': diary_year,
                    'case_type': case_type,
                    'case_number': case_number,
                    'case_year': case_year,
                    'option_list': option_list,
                    '<?= csrf_token() ?>': csrfToken
                },
                beforeSend: function() {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    $("#result").html(result);
                    $('#tblCasesForUploading').DataTable({
                        "bSort": false,
                        "bPaginate": false,
                        "bLengthChange": false,
                        "bInfo": false
                    });
                    $("#loader").html('');
                    $('#submit').prop('disabled', false);
                    updateCSRFTokenSync();
                },
                error: function() {
                    updateCSRFTokenSync();
                    $('#submit').prop('disabled', false);
                    alert('Something went wrong. Please try again later.');
                    return false;
                }
            });
        }
    }
    $(document).ready(function() {
        // debugger;
        $('input:radio[name=rd_slct]').click(
            function() {
                if ($(this).is(':checked') && $(this).val() == 'hc') {
                    $('#d_no').val('');
                    $('#d_year').val("");

                }
            });

        $("#radiodn").click(function() {
            $("#d_no").removeProp('disabled');
            $("#d_year").removeProp('disabled');
            $("#selct").prop('disabled', true);
            $("#case_no").prop('disabled', true);
            $("#case_yr").prop('disabled', true);
            $("#selct").val("-1");
            $("#case_no").val("");
            $("#case_yr").val("");
        });

        $("#radioct").click(function() {
            $("#d_no").prop('disabled', true);
            $("#d_year").prop('disabled', true);
            $("#d_no").val("");
            $("#d_year").val("");
            $("#selct").removeProp('disabled');
            $("#case_no").removeProp('disabled');
            $("#case_yr").removeProp('disabled');
        });

        $(document).on('click', '.cl_diary_no', function() {
            //    var    
            var val_id = $(this).html();
            var sp_id = val_id.split('-');
            var diary_no = sp_id[0] + sp_id[1];
            var idd = $(this).attr('id');
            var sp_id = idd.split('sp_diary_no');

            $('#hd_cnt_no').val(sp_id[1]);
            get_ent_details(diary_no);

        });
        $(document).on('click', '.sp_verify', function() {
            // debugger;
            var val_id = $(this).attr('id');
            var sp_id = val_id.split('-');
            var diary_no = sp_id[1] + sp_id[2];
            verify_nverify(diary_no);

        });
        $(document).on('click', '.sp_details', function() {
            var idd = $(this).attr('id');
            var sp_idd = idd.split('sp_d_');
            var sp_diary_no = $('#sp_diary_no' + sp_idd[1]).html();
            var sp_d_no = sp_diary_no.split('-');
            var diary_no = sp_d_no[0] + sp_d_no[1];
            get_ent_details(diary_no);
        });
        //Old Function 
        // $(document).on('click', '.cl_hover,.cl_hover1', function() {
        //     var cl_class = $(this).attr('class');
        //     var idd = $(this).attr('id');
        //     //alert($(this).attr('class'));
        //     if (cl_class == 'cl_hover') {
        //         var sp_spshow = idd.split('spshow_');
        //         var hdpdf_name = $('#hdpdf_name_' + sp_spshow[1]).val();
        //     } else {
        //         var sp_spshow = idd.split('spshows_');
        //         var hdpdf_name = $('#hdpdf_names_' + sp_spshow[1]).val();
        //     }

        //     var str = decodeURIComponent(hdpdf_name);
        //     console.log(str);
        //     document.getElementById('ob_shw').scrollTop = 0;
        //     document.getElementById('ggg1').scrollTop = 0;
        //     document.getElementById('ggg1').style.width = 'auto';
        //     document.getElementById('ggg1').style.height = ' 550px';
        //     document.getElementById('ggg1').style.overflow = 'hidden';

        //     document.getElementById('ggg1').style.marginLeft = '18px';
        //     document.getElementById('ggg1').style.marginRight = '18px';
        //     document.getElementById('ggg1').style.marginBottom = '25px';
        //     document.getElementById('ggg1').style.marginTop = '20px';

        //     document.getElementById('dv_sh_hd1').style.display = 'block';
        //     document.getElementById('dv_fixedFor_P1').style.display = 'block';
        //     document.getElementById('dv_fixedFor_P1').style.marginTop = '3px';

        //     document.getElementById('ob_shw').setAttribute('data', str);
        // });
        //New optimize function by Codewithvj
        $(document).on('click', '.cl_hover, .cl_hover1', function() {
            var cl_class = $(this).attr('class');
            var idd = $(this).attr('id');
            //alert('Class: ' + cl_class + '\nID: ' + idd);

            var hdpdf_name = '';
            if ($(this).hasClass('cl_hover')) {
                var sp_spshow = idd.split('spshow_');
                hdpdf_name = $('#hdpdf_name_' + sp_spshow[1]).val();
            } else if ($(this).hasClass('cl_hover1')) {
                var sp_spshow = idd.split('spshows_');
                hdpdf_name = $('#hdpdf_names_' + sp_spshow[1]).val();
            }

            //console.log('Hidden PDF Name (Encoded):', hdpdf_name);

            var decodedStr = decodeURIComponent(hdpdf_name || '');
            //console.log('Hidden PDF Name (Decoded):', decodedStr);

            $('#ob_shw').scrollTop(0).attr('data', decodedStr);

            $('#ggg1').css({
                width: 'auto',
                height: '550px',
                overflow: 'hidden',
                marginLeft: '18px',
                marginRight: '18px',
                marginBottom: '25px',
                marginTop: '20px'
            });

            $('#dv_sh_hd1, #dv_fixedFor_P1').css('display', 'block');
            $('#dv_fixedFor_P1').css('marginTop', '3px');
        });
        //old case status Function to check 
        // $(document).on('click', '.cl_c_diary', function() {
        //     var d_no = $(this).html();
        //     var sp_d_no = d_no.split('-');
        //     var idd = $(this).attr('id');
        //     //var sp_id=idd.split('sp_c_diary');
        //     //var hd_diary_no=$('#hd_link'+sp_id[1]).val();
        //     var d_yr = sp_d_no[1];
        //     var d_no = sp_d_no[0];
        //     //$('#hd_diary_nos').val(hd_diary_no);
        //     document.getElementById('ggg').style.width = 'auto';
        //     document.getElementById('ggg').style.height = ' 500px';
        //     document.getElementById('ggg').style.overflow = 'scroll';

        //     document.getElementById('ggg').style.marginLeft = '18px';
        //     document.getElementById('ggg').style.marginRight = '18px';
        //     document.getElementById('ggg').style.marginBottom = '25px';
        //     document.getElementById('ggg').style.marginTop = '30px';
        //     document.getElementById('dv_sh_hd').style.display = 'block';
        //     document.getElementById('dv_fixedFor_P').style.display = 'block';
        //     document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
        //     $.ajax({
        //         url: '../case_status/case_status_process.php',
        //         cache: false,
        //         async: true,
        //         data: {
        //             d_no: d_no,
        //             d_yr: d_yr
        //         },
        //         beforeSend: function() {
        //             // $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
        //             $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
        //         },
        //         type: 'POST',
        //         success: function(data, status) {

        //             $('#ggg').html(data);
        //             add_button();
        //         },
        //         error: function(xhr) {
        //             alert("Error: " + xhr.status + " " + xhr.statusText);
        //         }

        //     });
        // });
        //New case status 
        $(document).on('click', '.cl_c_diary', function() {
            // Extract diary number and year
            var d_no = $(this).html();
            var sp_d_no = d_no.split('-');
            var d_yr = sp_d_no[1];
            var diary_no = sp_d_no[0];
            // Style adjustments for #ggg
            $('#ggg').css({
                width: 'auto',
                height: '500px',
                overflow: 'scroll',
                marginLeft: '18px',
                marginRight: '18px',
                marginBottom: '25px',
                marginTop: '30px'
            });

            // Display UI elements
            $('#dv_sh_hd').css('display', 'block');
            $('#dv_fixedFor_P').css({
                display: 'block',
                marginTop: '3px'
            });
            const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();
            // AJAX request
            $.ajax({
                url: '<?php echo  base_url('Common/Case_status/case_status_by_diaryno') ?>',
                cache: false,
                async: true,
                type: 'GET',
                data: {
                    diaryno: diary_no+d_yr,
                    d_yr: d_yr,
                    '<?= csrf_token() ?>': csrfToken
                },
                beforeSend: function() {
                    // Show loading indicator
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(data, status) {
                    $("#loader").html('');
                    $('#ggg').html(data);
                    //add_button();
                    updateCSRFToken();
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });


        // $(document).on('click', '[id^=accordion] a', function(event) {
        //     var accname = $(this).attr('data-parent');

        //     if (typeof $(this).attr('data-parent') !== "undefined") {
        //         //var collapse_element = event.target;
        //         var url = "";
        //         //    alert(collapse_element);
        //         var href = this.hash;
        //         var depId = href.replace("#collapse", "");
        //         var accname1 = accname.replace("#accordion", "");
        //         var acccnt = accname1 * 100;
        //         var diaryno = document.getElementById('diaryno' + accname1).value;
        //         if (depId != (acccnt + 1)) {
        //             if (depId == (acccnt + 2)) url = "../case_status/get_earlier_court.php";
        //             if (depId == (acccnt + 3)) url = "../case_status/get_connected.php";
        //             if (depId == (acccnt + 4)) url = "../case_status/get_listings.php";
        //             if (depId == (acccnt + 5)) url = "../case_status/get_ia.php";
        //             //    if(depId==6) url="get_earlier_court.php";
        //             if (depId == (acccnt + 6)) url = "../case_status/get_court_fees.php";
        //             if (depId == (acccnt + 7)) url = "../case_status/get_notices.php";
        //             if (depId == (acccnt + 8)) url = "../case_status/get_default.php";
        //             if (depId == (acccnt + 9)) url = "../case_status/get_judgement_order.php";
        //             if (depId == (acccnt + 10)) url = "../case_status/get_adjustment.php";
        //             if (depId == (acccnt + 11)) url = "../case_status/get_mention_memo.php";
        //             if (depId == (acccnt + 12)) url = "../case_status/get_restore.php";
        //             if (depId == (acccnt + 13)) url = "../case_status/get_drop.php";
        //             if (depId == (acccnt + 14)) url = "../case_status/get_appearance.php";
        //             if (depId == (acccnt + 15)) url = "../case_status/get_office_report.php";
        //             if (depId == (acccnt + 16)) url = "../case_status/get_similarities.php";

        //             // var dataString = 'depId='+ depId + '&do=getDepUsers';
        //             $.ajax({
        //                     type: 'POST',
        //                     url: url,
        //                     beforeSend: function(xhr) {
        //                         $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
        //                     },
        //                     data: {
        //                         diaryno: diaryno
        //                     }
        //                 })
        //                 .done(function(msg) {
        //                     $("#result" + depId).html(msg);
        //                 })
        //                 .fail(function() {
        //                     alert("ERROR, Please Contact Server Room");
        //                 });
        //         }
        //     }
        // });

        $(document).on('change', '#ddl_st_agncy,#ddl_court', function() {
            get_benches('0');
        });


        $(document).on('change', '#ddl_bench', function() {
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
            } else if (ddl_ref_court == '4' || ddl_ref_court == '5') {
                get_lc_casetype(chk_status, idd);
            }
        });

        $(document).on('change', '#ddl_court', function() {

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
                } else if (ddl_ref_court == '4' || ddl_ref_court == '5') {
                    get_lc_casetype(chk_status, ddl_bench);
                }

            }
        });

    });


    function get_ent_details(diary_no) {
        //   debugger;
        $('#hd_fil_no').val(diary_no);
        $('#ggg').css({
            width: 'auto',
            height: '550px',
            overflow: 'scroll',
            marginLeft: '18px',
            marginRight: '18px',
            marginBottom: '25px',
            marginTop: '20px'
        });

        $('#dv_sh_hd').css('display', 'block');
        $('#dv_fixedFor_P').css({
            display: 'block',
            marginTop: '3px'
        });

        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();
        $.ajax({
            url: "<?php echo base_url('Judicial/OriginalRecord/HcOr/getEntryDetailsReport'); ?>",
            type: 'GET',
            cache: false,
            data: {
                diary_no: diary_no,
                '<?= csrf_token() ?>': csrfToken
            },
            beforeSend: function() {
                $('#ggg').html('<table width="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"></td></tr></table>');

            },
            success: function(data, status) {
                //alert(data);
                $('#ggg').html(data);
                updateCSRFToken();

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + ' ' + xhr.statusText);
                updateCSRFToken();
            }
        });
    }

    function verify_nverify(diary_no) {
        //debugger;
        //alert(diary_no);
        var d_no = diary_no;
        // document.getElementById('ggg').style.width = 'auto';
        // document.getElementById('ggg').style.height = ' 500px';
        // document.getElementById('ggg').style.overflow = 'scroll';

        // document.getElementById('ggg').style.marginLeft = '18px';
        // document.getElementById('ggg').style.marginRight = '18px';
        // document.getElementById('ggg').style.marginBottom = '25px';
        // document.getElementById('ggg').style.marginTop = '20px';
        // document.getElementById('dv_sh_hd').style.display = 'block';
        // document.getElementById('dv_fixedFor_P').style.display = 'block';
        // document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
        const ggg = document.getElementById('ggg');
        const dv_sh_hd = document.getElementById('dv_sh_hd');
        const dv_fixedFor_P = document.getElementById('dv_fixedFor_P');

        // Apply styles to #ggg
        Object.assign(ggg.style, {
            width: 'auto',
            height: '500px',
            overflow: 'scroll',
            marginLeft: '18px',
            marginRight: '18px',
            marginBottom: '25px',
            marginTop: '20px'
        });

        // Set display and margin styles for other elements
        dv_sh_hd.style.display = 'block';
        Object.assign(dv_fixedFor_P.style, {
            display: 'block',
            marginTop: '3px'
        });

        //$('#sub').attr('disabled', true);
        $.ajax({
            url: 'verify_nverify.php',
            type: "GET",
            cache: false,
            async: true,
            beforeSend: function() {
                $('#ggg').html('<div style="text-align: center"><img src="<?php echo base_url('images/load.gif'); ?>"><div>');
            },
            data: {
                d_no: d_no,

            },
            success: function(data, status) {
                //debugger;
                //                           alert(data);
                //alert(status);
                $('#sub').attr('disabled', false);
                $('#ggg').html(data);

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + ' ' + xhr.statusText);
            }
        });

    }

    function closeData() {
        document.getElementById('ggg').scrollTop = 0;

        document.getElementById('dv_fixedFor_P').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";
    }

    function closeData1() {
        document.getElementById('ggg1').scrollTop = 0;

        document.getElementById('dv_fixedFor_P1').style.display = "none";
        document.getElementById('dv_sh_hd1').style.display = "none";

        //       document.getElementById('sp_close').style.display='none';
    }

    function getbtn_left() {
        //debugger;
        $('#btn_left').attr('disabled', true);
        var d_no = $('#d_no').val();
        var d_year = $('#d_year').val();
        var ct = $('#selct').val();
        var cn = $('#case_no').val();
        var cy = $('#case_yr').val();
        var lct_caseno = $('#txt_ref_caseno').val();
        var lct_casetype = $('#ddl_ref_case_type').val();
        var lct_caseyear = $('#ddl_ref_caseyr').val();

        var ct_count = parseInt($('#inc_count').val());
        var hd_fst = parseInt($('#hd_fst').val());
        //                alert($('#inc_count').val());
        //var hd_lst=parseInt($('#hd_lst').val());
        var inc_val = parseInt($('#inc_val').val());
        var inc_tot = parseInt($('#inc_tot').val());

        var sp_frst = parseInt($('#sp_frst').html()) - inc_val;
        var hd_lst = parseInt($('#sp_frst').html() - 1);

        var inc_tot_pg = sp_frst;
        // alert(inc_tot_pg);
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
            url: 'get_indexing_hc_dc_report.php',
            type: "GET",
            cache: false,
            async: true,
            beforeSend: function() {

                //$("#dv_include").html('');
                $('#result').html('');

                $('#result').html('<table width="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"></td></tr></table>');

            },
            data: {
                nw_hd_fst: nw_hd_fst,
                inc_val: inc_val,
                u_t: 1,
                inc_tot_pg: inc_tot_pg,
                d_no: d_no,
                d_year: d_year,
                ct: ct,
                cn: cn,
                cy: cy,
                lct_caseno: lct_caseno,
                lct_casetype: lct_casetype,
                lct_caseyear: lct_caseyear

            },
            success: function(data, status) {

                //debugger;

                $('#result').html(data);

                //                           alert( $('#inc_count').val());
                $('#hd_fst').val(nw_hd_fst);
                // $('#sp_last').html(parseInt($('#sp_frst').html())-1);
                $('#sp_last').html(hd_lst);
                $('#sp_frst').html(parseInt(sp_frst));

                if (sp_frst == 1)
                    $('#btn_left').attr('disabled', true);
                else
                    $('#btn_left').attr('disabled', false);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + ' ' + xhr.statusText);
            }
        });
    }

    function getbtn_right() {
        // debugger;
        $('#btn_right').attr('disabled', true);

        var d_no = $('#d_no').val();
        var d_year = $('#d_year').val();
        var ct = $('#selct').val();
        var cn = $('#case_no').val();
        var cy = $('#case_yr').val();
        var lct_caseno = $('#txt_ref_caseno').val();
        var lct_casetype = $('#ddl_ref_case_type').val();
        var lct_caseyear = $('#ddl_ref_caseyr').val();
        var ct_count = parseInt($('#inc_count').val());
        var hd_fst = parseInt($('#hd_fst').val());
        var hd_lst = parseInt($('#hd_lst').val());
        var inc_val = parseInt($('#inc_val').val());

        var inc_tot = parseInt($('#inc_tot').val());
        var inc_tot_pg = parseInt($('#inc_tot_pg').val());
        // alert(inc_tot_pg);
        if (hd_fst == 0) {
            $('#btn_left').attr('disabled', false);

        }
        var nw_hd_fst = hd_fst + inc_val;
        //                 alert(ct_count);
        //                  alert(inc_val);
        //alert(ct_count+'@@'+hd_fst+'@@'+inc_val+'@@'+inc_tot+'@@'+inc_tot_pg+'@@'+nw_hd_fst);
        if (ct_count == inc_tot - 1) {
            $('#btn_right').attr('disabled', true);
        }
        $.ajax({
            url: 'get_indexing_hc_dc_report.php',
            type: "GET",
            cache: false,
            async: true,
            beforeSend: function() {
                //$('#dv_include').html('');
                $('#result').html('');

                $('#result').html('<table widht="100%" align="center"><tr><td><img src="<?php echo base_url('images/load.gif'); ?>"></td></tr></table>');
            },
            data: {
                nw_hd_fst: nw_hd_fst,
                inc_val: inc_val,
                u_t: 1,
                inc_tot_pg: inc_tot_pg,
                d_no: d_no,
                d_year: d_year,
                ct: ct,
                cn: cn,
                cy: cy,
                lct_caseno: lct_caseno,
                lct_casetype: lct_casetype,
                lct_caseyear: lct_caseyear
            },
            success: function(data, status) {

                //                             alert(data);
                //  debugger;

                $('#result').html(data);
                //                                 alert($('dv_include').html());
                $('#inc_count').val(ct_count + 1);
                //                           alert( $('#inc_count').val());
                $('#hd_fst').val(nw_hd_fst);
                $('#hd_lst').val(nw_hd_fst + inc_val)
                //$('#sp_frst').html(parseInt($('#hd_lst').html())+1);
                $('#sp_frst').html(parseInt($('#hd_fst').val()) + 1);
                var sp_last_ck = parseInt($('#hd_lst').val());
                // $('#hd_lst').val(sp_last_ck);
                var sp_nf = parseInt($('#sp_nf').html());
                //                         alert(sp_last_ck+'$$'+sp_nf);
                if (sp_last_ck <= sp_nf) {
                    //$('#sp_last').html(parseInt($('#sp_last').html())+inc_val);
                    $('#sp_last').html($('#hd_lst').val());
                    $('#btn_right').attr('disabled', false);
                } else {
                    //                                  $('#sp_last').html('');
                    $('#sp_last').html(sp_nf);
                    $('#btn_right').attr('disabled', true);
                }

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + ' ' + xhr.statusText);
            }
        });
    }

    function rd_onchange() {
        if ($("input[name='rd_slct']:checked").val() == 'hc') {
            $('#d_no').val('');
            $('#d_year').val('');
        } else {
            $('#ddl_ref_case_type').val('');
            $('#txt_ref_caseno').val('');
            $('#ddl_ref_caseyr').val('');
        }

    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function validate() {
        if ($("input[name='rd_slct']:checked").val() == 'hc') {

            if ($('#ddl_ref_case_type').val() == '') {
                alert('Please select case type');
                $('#ddl_ref_case_type').focus();
                return false;
            } else if ($('#txt_ref_caseno').val() == '') {
                $('#txt_ref_caseno').focus();
                alert('Please enter case number');
                return false;
            } else if ($('#ddl_ref_caseyr').val() == '') {
                alert('Please select case year');
                $('#ddl_ref_caseyr').focus();
                return false;
            } else
                return true;
        } else {
            if ($("input[name='rdbtn_select']:checked").val() == 'diary') {
                if ($('#d_no').val() == '') {
                    alert('Please enter Diary No.');
                    $('#d_no').focus();
                    return false;
                } else if ($('#d_year').val() == '') {
                    alert('Please enter Diary Year.');
                    $('#d_year').focus();
                    return false;
                } else
                    return true;
            } else if ($("input[name='rdbtn_select']:checked").val() == 'case') {
                if ($('#selct').val() == '') {
                    alert('Please select Case type.');
                    $('#selct').focus();
                    return false;
                } else if ($('#case_no').val() == '') {
                    alert('Please enter Case Number.');
                    $('#case_no').focus();
                    return false;
                } else if ($('#case_yr').val() == '') {
                    alert('Please select Case Year.');
                    $('#case_yr').focus();
                    return false;
                } else
                    return true;
            }
        }


    }

    function doReOpen() {
        diaryNo = $('#hdn_diary_no').val();
        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();
        var conf = confirm('Do you really want to re-open this case for updation.');
        if (conf) {
            $.ajax({
                url: "<?php echo base_url('Judicial/OriginalRecord/HcOr/updateReOpenforEdit'); ?>",
                type: "POST",
                cache: false,
                async: true,
                beforeSend: function() {
                    $('#result').html('<div style="text-align: center"><img src="<?php echo base_url('images/load.gif'); ?>"><div>');
                },
                data: {
                    diaryNo: diaryNo,
                    '<?= csrf_token() ?>': csrfToken
                },
                success: function(data, status) {
                    updateCSRFTokenSync();
                    $('#result').html('');
                    if (data.status === 'success') {
                        alert(data.message); // Success message
                        // alert("Now High court user can edit original record details in thid case.");
                    } else {
                        alert(data.message); // Error message
                    }
                    callGetDetailsNew();
                },
                error: function(xhr) {
                    updateCSRFTokenSync();
                    alert("Error: " + xhr.status + ' ' + xhr.statusText);
                }
            });
        } else {
            return false;
        }
    }


    function save_verify() {
        //debugger;
        var conf = confirm('Do you want to insert the record.');
        if (conf) {
            //if(validate_submit()==true) {

            var d_no = $('#diary_no').val();
            var d_year = $('#diary_year').val();
            /*var index_hc_dc_id = $('#index_hc_dc_id').val();*/
            var remarks = $('#txt_remark').val();
            var is_verify = $('#slct_verify').val();
            /*$('#sub').attr('disabled', true);*/
            const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();
            $.ajax({
                url: "<?php echo base_url('Judicial/OriginalRecord/HcOr/saveRecord'); ?>",
                type: "POST",
                cache: false,
                async: true,
                beforeSend: function() {
                    $('#result').html('<div style="text-align: center"><img src="<?php echo base_url('images/load.gif'); ?>"><div>');
                },
                data: {
                    d_no: d_no,
                    d_year: d_year,
                    remarks: remarks,
                    is_verify: is_verify,
                    '<?= csrf_token() ?>': csrfToken
                },
                success: function(data, status) {
                    //alert(data);

                    $('#result').html('');
                    if (data.status === 'success') {
                        alert(data.message); // Success message
                        //("Successfully Verified.");
                    } else {
                        alert(data.message); // Error message
                    }
                    callGetDetailsNew();

                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + ' ' + xhr.statusText);
                }
            });
            // }
        }
    }
</script>