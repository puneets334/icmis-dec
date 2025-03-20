<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial >> IA >> Update</h3>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
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
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <?php echo component_html(); ?>

                                    <center> <button type="submit" name="btnGetR" class="btn btn-primary" id="submit">Submit</button></center>
                                    <?php form_close(); ?>
                                    <br /><br />
                                    <center><span id="loader"></span> </center>
                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
                                    <div id="record" class="record"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $('#component_search').on('submit', function() {
            $('#record').html('');
            var search_type = $("input[name='search_type']:checked").val();
            if (search_type.length == 0) {
                alert("Please select case type");
                validationError = false;
                return false;
            }
            var diary_number = $("#diary_number").val();
            var diary_year = $('#diary_year :selected').val();

            var case_type = $('#case_type :selected').val();
            var case_number = $("#case_number").val();
            var case_year = $('#case_year :selected').val();

            if (search_type == 'D') {
                if (diary_number.length == 0) {
                    alert("Please enter diary number");
                    validationError = false;
                    return false;
                } else if (diary_year.length == 0) {
                    alert("Please select diary year");
                    validationError = false;
                    return false;
                }
            } else if (search_type == 'C') {

                if (case_type.length == 0) {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                } else if (case_number.length == 0) {
                    alert("Please enter case number");
                    validationError = false;
                    return false;
                } else if (case_year.length == 0) {
                    alert("Please select case year");
                    validationError = false;
                    return false;
                }

            }

            if ($('#component_search').valid()) {
                updateCSRFToken();
                var validateFlag = true;
                var form_data = $(this).serialize();
                if (validateFlag) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $(".form-response").html("");
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Diary/search'); ?>",
                        data: form_data,
                        beforeSend: function() {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function(data) {
                            $("#loader").html('');
                            updateCSRFToken();
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                //window.location.reload();
                                // window.location.href =resArr[1];
                                search_ia(resArr[1]);
                            } else if (resArr[0] == 3) {
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;
                }
            } else {
                return false;
            }
        });
    });

    function search_ia(url) {
        var radio = $("input[type='radio'][name='search_type']:checked").val();

        var ia_search = "<?= base_url('Judicial/IA/get_content_list') ?>";
        //alert('url'+url+'radio='+radio+'ia_search='+ia_search);
        $('#record').html('');
        $.ajax({
            type: "GET",
            url: ia_search,
            data: {
                radio: radio,
                option: 1
            },
            beforeSend: function() {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(data) {
                updateCSRFToken();
                $("#loader").html('');
                $("#record").html(data);
            },

            error: function() {
                updateCSRFToken();
                alert('Something went wrong! please contact computer cell');
            }
        });

    }

    function editrecord(str1) {

        var sc = screen.width;
        var sh = screen.height;
        document.getElementById('dv_fixedFor').style.marginLeft = '9pc';
        document.getElementById('dv_fixedFor').style.marginTop = '100px';
        var hd_ias = document.getElementById('hd_ias').value;
        var hd_gtNms = document.getElementById('hd_gtNms').value;

        document.getElementById('dv_sh_hd').style.display = 'block';
        document.getElementById('dv_fixedFor').style.display = 'block';
        var dno = document.getElementById('diaryno').value;
        //var str1=str.replace('sp_edit','');

        var m_doc1 = document.getElementById('m_doc1' + str1).value;
        var m_descss = document.getElementById('m_descss' + str1).value;
        var ddlIASTAT = document.getElementById('ddlIASTAT' + str1).value;
        var hd_sp_sel_nm = document.getElementById('hd_sp_sel_nm' + str1).value;
        var txtRematk = document.getElementById('txtRematk' + str1).value;
        var hd_counts = document.getElementById('hd_counts' + str1).value;
        var hd_year = document.getElementById('hd_year' + str1).value;
        var hd_nature = document.getElementById('hd_nature' + str1).value;
        var hd_IANAme = document.getElementById('hd_IANAme' + str1).value;
        var hd_ddate = document.getElementById('hd_ddate' + str1).value;

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            url: base_url + "/Judicial/IA/update_tot_det",
            data: {
                diaryno: dno,
                m_doc1: m_doc1,
                m_descss: m_descss,
                ddlIASTAT: ddlIASTAT,
                txtRematk: txtRematk,
                hd_counts: hd_counts,
                hd_year: hd_year,
                hd_nature: hd_nature,
                hd_IANAme: hd_IANAme,
                hd_ias: hd_ias,
                hd_gtNms: hd_gtNms,
                hd_sp_sel_nm: hd_sp_sel_nm,
                hd_ddate: hd_ddate,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(msg) {
                updateCSRFToken();
                document.getElementById('sp_mnb').innerHTML = msg;
            },
            error: function() {
                updateCSRFToken();
                alert("ERROR");
            }
        });


    }

    function closeData() {
        document.getElementById('dv_fixedFor').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";
    }

    $(document).on('click', '#btnUpdate', function() {
        //   alert("hello");
        var hd_ssno = document.getElementById('hd_ssno').value;

        var strtotal = '';
        for (var t = 1; t < hd_ssno; t++) {
            if (document.getElementById('ckNm' + t)) {
                if (document.getElementById('ckNm' + t).checked == true) {
                    var str1 = document.getElementById('ckNm' + t).id.split('ckNm');
                    if (strtotal == '')
                        strtotal = str1[1];
                    else
                        strtotal = strtotal + ',' + str1[1];
                }
            }
        }

        //    var txtFNo=document.getElementById('txtcaseno').value;
        //    var txtYear=document.getElementById('txtyear').value;
        var dno = document.getElementById('diaryno').value;
        var m_doc1 = document.getElementById('m_doc1_upd').value;
        var m_descss = document.getElementById('m_descss_upd').value;
        var ddlIASTAT = document.getElementById('ddlIASTAT').value;
        var txt_order_dt = document.getElementById('txt_order_dt').value;
        if (txt_order_dt == '' && ddlIASTAT == 'D') {
            alert('Please enter IA disposal date!!');
            document.getElementById('txt_order_dt').focus;
            return;
        }

        var txtRematk = document.getElementById('txtRematk').value;
        var hd_counts = document.getElementById('sp_name').innerHTML;
        var hd_year = document.getElementById('sp_year').innerHTML;
        var hd_nature = document.getElementById('hd_nature').value;
        //  var txtdate=document.getElementById('txt_order_dt').value;
        //  alert(txt_order_dt);
        var hd_IANAme = document.getElementById('hd_IANAme').value;
        if (m_doc1 == '0')
            alert("Please Select IA");
        else {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: "POST",
                url: base_url + "/Judicial/IA/update_ia",
                data: {
                    diaryno: dno,
                    m_doc1: m_doc1,
                    m_descss: m_descss,
                    ddlIASTAT: ddlIASTAT,
                    txtRematk: txtRematk,
                    hd_counts: hd_counts,
                    hd_year: hd_year,
                    hd_nature: hd_nature,
                    hd_IANAme: hd_IANAme,
                    strtotal: strtotal,
                    txt_order_dt: txt_order_dt,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE

                },
                success: function(msg) {
                    updateCSRFTokenSync();
                    if (msg == 1) {
                        alert(hd_counts + '/' + hd_year + ' Updated Successfully');
                        document.getElementById('dv_fixedFor').style.display = "none";
                        document.getElementById('dv_sh_hd').style.display = "none";
                        $("button[name=btnGetR]").click();
                    }

                },
                error: function() {
                    updateCSRFTokenSync();
                    alert("ERROR");
                }
            });

        }
    });
</script>