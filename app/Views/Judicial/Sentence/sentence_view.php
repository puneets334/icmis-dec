<?= view('header') ?>

    <style>
        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
        .form-control {
            height: calc(28px + 2px) !important;
            padding: 0.1rem 0.8rem !important;
        }
        .form-group {
            margin-bottom: 1px !important;
        }
        .col-form-label {
            margin-bottom: 0;
            line-height: 1.5;
            padding-top: calc(0.22rem + 2px)!important;
            padding-bottom: calc(0.22rem + 2px)!important;
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
                                    <h3 class="card-title">Judicial >> Sentence Status >> Add/Update</h3>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                        <?php
                                        $attribute = array('class' => 'form-horizontal','name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                        echo form_open(base_url('#'), $attribute);

                                        echo component_html();
                                        ?>
                                        <center> <button type="submit" class="btn btn-primary" id="submit">Search</button></center>
                                        <?php form_close();?>
                                        <br/>
                                        <div id="record" class="record"></div>

                                        <!--start Court,state,bench and case type tab details-->
                                        <div class="div_court_state_bench_casetype" style="display: none;">
                                            <hr/>
                                            <input type="hidden" name="sentence_diary_number" id="sentence_diary_number" class="form-control">
                                            <input type="hidden" name="sentence_period_id" id="sentence_period_id" class="form-control">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Court<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_court" id="ddl_court" class="form-control" onchange="get_state_list();">

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">State <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_st_agncy" id="ddl_st_agncy" class="form-control" onchange="get_bench_list();">
                                                                <option value="">Select State</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Court Bench <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_bench" id="ddl_bench" class="form-control" onchange="get_tot_cases_list();">
                                                                <option value="" title="Select">Select Court Bench</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Case No. <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_case_no" id="ddl_case_no" class="form-control" onchange="get_tot_accused_list();">
                                                                <option value="" title="Select">Select Court Bench</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Accused <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_tot_accused" id="ddl_tot_accused" class="form-control" onchange="actionAccused(this.value)">
                                                                <option value="" title="Select">Select</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <br/>
                                            <center><div class="final_submit"></div></center>

                                        </div>
                                        <!--end Court,state,bench and case type tab details-->

                                        <br/>
                                        <div id="get_details"></div>
                                        <div id="get_sentence_undergone_list"></div>


                                        <!--sms alert-->
                                        <br/><br/>
                                        <center><span id="loader"></span> </center>
                                        <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                        </span>
                                        <br/><br/>
                                        <!--end alert-->


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
            $('#component_search').on('submit', function () {
                var search_type = $("input[name='search_type']:checked").val();
                if (search_type.length == 0) {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                }
                var diary_number = $("#diary_number").val();
                var diary_year =$('#diary_year :selected').val();

                var case_type =$('#case_type :selected').val();
                var case_number = $("#case_number").val();
                var case_year =$('#case_year :selected').val();

                if (search_type=='D') {
                    if (diary_number.length == 0) {
                        alert("Please enter diary number");
                        validationError = false;
                        return false;
                    }else if (diary_year.length == 0) {
                        alert("Please select diary year");
                        validationError = false;
                        return false;
                    }
                }else if (search_type=='C') {

                    if (case_type.length == 0) {
                        alert("Please select case type");
                        validationError = false;
                        return false;
                    }else if (case_number.length == 0) {
                        alert("Please enter case number");
                        validationError = false;
                        return false;
                    }else if (case_year.length == 0) {
                        alert("Please select case year");
                        validationError = false;
                        return false;
                    }

                }

                if ($('#component_search').valid()) {
                    var validateFlag = true;
                    var form_data = $(this).serialize();
                    if(validateFlag){
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $('.alert-error').hide(); $(".form-response").html("");
                        $("#loader").html('');
                        $(".final_submit").html('');
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('Filing/Diary/search'); ?>",
                            data: form_data,
                            beforeSend: function () {
                                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                            },
                            success: function (data) {
                                $("#loader").html('');
                                updateCSRFToken();
                                var resArr = data.split('@@@');
                                if (resArr[0] == 1) {
                                    //window.location.reload();
                                    // window.location.href =resArr[1];
                                    //alert('diary='+resArr[1]);
                                    get_sentence(resArr[1]);
                                } else if (resArr[0] == 3) {
                                    $('.div_court_state_bench_casetype').hide();
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
        function get_sentence() {
            $('.div_court_state_bench_casetype').hide();
            var select="<option value='' title='Select'>---Select---</option>";
            $('#ddl_court').html(select);
            $('#ddl_st_agncy').html(select);
            $('#ddl_bench').html(select);
            $('#ddl_case_no').html(select);
            $('#ddl_tot_accused').html(select);
            $(".final_submit").html('');
            $("#get_details").html('');
            $('#sentence_period_id').val('');
            $("#get_sentence_undergone_list").html('');


            var radio = $("input[type='radio'][name='search_type']:checked").val();

            var ia_search = "<?=base_url('Judicial/Sentence/Sentence/get_sentence')?>";
            $('#loader').html('');
            $(".final_submit").html('');
            $.ajax({
                type: "GET",
                url: ia_search,
                data:{radio: radio,option: 1},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    // updateCSRFToken();
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#loader').html('');
                        $('.div_court_state_bench_casetype').show();
                        $('#sentence_diary_number').val(resArr[1]);
                        get_from_court_by_diary_no(resArr[1]);
                        //$("#ddl_court").html(resArr[1]);
                    } else if (resArr[0] == 3) {
                        $('.div_court_state_bench_casetype').hide();
                        $("#loader").html(resArr[1]);
                    }

                },

                error: function () {
                    //updateCSRFToken();
                    alert('Something went wrong! please contact computer cell');
                }
            });

        }


        function get_from_court_by_diary_no(diary_no) {
            $('#loader').html(''); $(".final_submit").html('');
            var radio = $("input[type='radio'][name='search_type']:checked").val();
            $.ajax({
                type: "GET",
                url: "<?=base_url('Judicial/Sentence/Sentence/get_from_court_by_diary_no')?>",
                data:{radio: radio,ddl_court:'',diary_no: diary_no},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $('#loader').html('');
                    $("#ddl_court").html(data);
                },
                error: function () {
                    //updateCSRFToken();
                    alert('Something went wrong! please contact computer cell');
                }
            });

        }
        function get_state_list() {
            $(".final_submit").html('');
            var diary_no =$('#sentence_diary_number').val();
            var ddl_court =$('#ddl_court :selected').val();
            get_state_name(ddl_court,diary_no,'');
            //alert('ddl_court='+ ddl_court+'diary_no='+diary_no);
        }
        function get_bench_list() {
            $(".final_submit").html('');
            var diary_no =$('#sentence_diary_number').val();
            var ddl_court =$('#ddl_court :selected').val();
            var ddl_st_agncy =$('#ddl_st_agncy :selected').val();
            //alert('ddl_court='+ ddl_court+'diary_no='+diary_no+'ddl_st_agncy='+ddl_st_agncy);
            get_index_bench(ddl_court,diary_no,ddl_st_agncy);
        }
        function get_tot_cases_list(){
            $(".final_submit").html('');
            var diary_no =$('#sentence_diary_number').val();
            var ddl_court =$('#ddl_court :selected').val();
            var ddl_st_agncy =$('#ddl_st_agncy :selected').val();
            var ddl_bench =$('#ddl_bench :selected').val();
            //alert('ddl_court='+ ddl_court+'diary_no='+diary_no+'ddl_st_agncy='+ddl_st_agncy+'ddl_bench='+ddl_bench);
            get_tot_cases(ddl_court,diary_no,ddl_st_agncy,ddl_bench);
        }
        function get_tot_accused_list(){
            $(".final_submit").html('');
            var diary_no =$('#sentence_diary_number').val();
            var ddl_court =$('#ddl_court :selected').val();
            var ddl_st_agncy =$('#ddl_st_agncy :selected').val();
            var ddl_bench =$('#ddl_bench :selected').val();
            var ddl_case_no =$('#ddl_case_no :selected').val();
            //alert('ddl_court='+ ddl_court+'diary_no='+diary_no+'ddl_st_agncy='+ddl_st_agncy+'ddl_bench='+ddl_bench+'ddl_case_no='+ddl_case_no);
            get_tot_accused(ddl_court,diary_no,ddl_st_agncy,ddl_bench,ddl_case_no);
        }
        function actionAccused(type){

            if (type.length != 0){
                $(".final_submit").html('<div class="btn btn-primary final_submit" onclick="get_details();">Submit</div>');
            }else{
                $(".final_submit").html('');
            }
        }
        function get_state_name(ddl_court,diary_no,ddl_st_agncy) {
            $('#loader').html('');
            var radio = $("input[type='radio'][name='search_type']:checked").val();
            $.ajax({
                type: "GET",
                url: "<?=base_url('Judicial/Sentence/Sentence/get_state_name')?>",
                data:{radio: radio,ddl_court:ddl_court,diary_no: diary_no,ddl_st_agncy:ddl_st_agncy},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $('#loader').html('');
                    $("#ddl_st_agncy").html(data);
                },
                error: function () {
                    //updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });

        }
        function get_index_bench(ddl_court,diary_no,ddl_st_agncy) {
            $('#loader').html('');
            var radio = $("input[type='radio'][name='search_type']:checked").val();
            $.ajax({
                type: "GET",
                url: "<?=base_url('Judicial/Sentence/Sentence/get_index_bench')?>",
                data:{radio: radio,ddl_court:ddl_court,diary_no: diary_no,ddl_st_agncy:ddl_st_agncy},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $('#loader').html('');
                    $("#ddl_bench").html(data);
                },
                error: function () {
                    //updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });

        }
        function get_tot_cases(ddl_court,diary_no,ddl_st_agncy,ddl_bench) {
            $('#loader').html('');
            var radio = $("input[type='radio'][name='search_type']:checked").val();
            $.ajax({
                type: "GET",
                url: "<?=base_url('Judicial/Sentence/Sentence/get_tot_cases')?>",
                data:{radio: radio,ddl_court:ddl_court,diary_no: diary_no,ddl_st_agncy:ddl_st_agncy,ddl_bench:ddl_bench},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $('#loader').html('');
                    $("#ddl_case_no").html(data);
                },
                error: function () {
                    //updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });

        }
        function get_tot_accused(ddl_court,diary_no,ddl_st_agncy,ddl_bench,ddl_case_no){
            $('#loader').html('');
            var radio = $("input[type='radio'][name='search_type']:checked").val();
            $.ajax({
                type: "GET",
                url: "<?=base_url('Judicial/Sentence/Sentence/get_tot_accused')?>",
                data:{radio: radio,ddl_court:ddl_court,diary_no: diary_no,ddl_st_agncy:ddl_st_agncy,ddl_bench:ddl_bench,ddl_case_no:ddl_case_no},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $('#loader').html('');
                    $("#ddl_tot_accused").html(data);
                },
                error: function () {
                    //updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });
        }
        function get_details(){

            var ddl_court =$('#ddl_court :selected').val();
            var ddl_st_agncy =$('#ddl_st_agncy :selected').val();
            var ddl_bench =$('#ddl_bench :selected').val();

            var ddl_case_no =$('#ddl_case_no :selected').val();
            var ddl_tot_accused =$('#ddl_tot_accused :selected').val();
            var diary_no =$('#sentence_diary_number').val();

            $('#loader').html('');
            var radio = $("input[type='radio'][name='search_type']:checked").val();
            $.ajax({
                type: "GET",
                url: "<?=base_url('Judicial/Sentence/Sentence/get_details')?>",
                data:{radio: radio,diary_no: diary_no,ddl_case_no:ddl_case_no,ddl_tot_accused:ddl_tot_accused},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $('#loader').html('');
                    var resArr = data.split('@@@');
                    $("#get_details").html(resArr[2]);
                    $('#sentence_period_id').val(resArr[1]);
                    get_sentence_undergone_list();
                },
                error: function () {
                    //updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });
        }


        function get_sentence_undergone_list(){
            $('#loader').html('');
            var sentence_period_id = $("#sentence_period_id").val();
            $.ajax({
                type: "GET",
                url: "<?=base_url('Judicial/Sentence/Sentence/get_sentence_undergone_list')?>",
                data:{sentence_period_id:sentence_period_id},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $('#loader').html('');
                    $("#get_sentence_undergone_list").html(data);
                },
                error: function () {
                    //updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });
        }

        function delete_sentence_undergone(id)
        {
            var r = confirm("Are you Sure, Record to be Delete.");
            if(r == true){
            var sentence_undergone_id=id;
            $('#loader').html('');
            $.ajax({
                type: "GET",
                url: "<?=base_url('Judicial/Sentence/Sentence/delete_sentence_undergone')?>",
                data:{sentence_undergone_id:sentence_undergone_id},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $('#loader').html('');
                    get_details();
                    //get_sentence_undergone_list();
                },
                error: function () {
                    //updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });
}
        }

        function check()
        {
            var s=document.getElementById('m_status').value;
            if(s=='U')
            {
                document.getElementById('m_sent2').value='';
                document.getElementById('m_sent2_mon').value='';
                document.getElementById('m_sent2').disabled =true;
                document.getElementById('m_sent2_mon').disabled =true;
            }else{
                document.getElementById('m_sent2').disabled =false;
                document.getElementById('m_sent2_mon').disabled =false;
            }
        }
        function btn_add()
        {
            var sentence_period_id = $("#sentence_period_id").val();
            var diary_no =$('#sentence_diary_number').val();
            var m_status = $('#m_status').val();
            var txt_frm_dt = $('#txt_frm_dt').val();
            var txt_to_dt = $('#txt_to_dt').val();
            var cnt_rw = $('#dv_add_det tr').length;
            var ddl_case_no = $('#ddl_case_no').val();
            var m_sent2 = $('#m_sent2').val();
            var m_sent2_mon = $('#m_sent2_mon').val();
            var ddl_tot_accused = $('#ddl_tot_accused').val();
            var remarks = $('#remarks').val();

            if ((m_sent2 == '') && (m_status != 'U')) {
                alert("Please select Year");
                return false;
            }
            if ((m_sent2_mon == '') && (m_status != 'U')) {
                alert("Please select Month");
                return false;
            }
            if (m_status == '') {
                alert("Please select Bail/Cusdoty status");
                return false;
            }
            if (txt_frm_dt == '') {
                alert("Please enter From Date");
                return false;
            }
            var period_under = '';
            for (var j = 1; j < cnt_rw; j++) {
                var sp_m_status = $('#sp_m_status' + j).html();
                var sp_txt_frm_dt = $('#sp_txt_frm_dt' + j).html();
                var sp_txt_to_dt = $('#sp_txt_to_dt' + j).html();

                if (sp_m_status == m_status && sp_txt_frm_dt == txt_frm_dt && sp_txt_to_dt == txt_to_dt) {
                    alert("Record already selected");
                    return false;
                }
                if (period_under == ''){
                    period_under = sp_txt_frm_dt + '@' + sp_txt_to_dt;
                }else{
                    period_under = period_under + ',' + sp_txt_frm_dt + '@' + sp_txt_to_dt;
                }
            }

            var sp_judgement_dt = $('#sp_judgement_dt').html();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?=base_url('Judicial/Sentence/Sentence/add_details')?>",
                data: {
                    sentence_period_id:sentence_period_id,
                    diary_no: diary_no,
                    m_status: m_status,
                    txt_frm_dt: txt_frm_dt,
                    txt_to_dt: txt_to_dt,
                    cnt_rw: cnt_rw,
                    ddl_case_no: ddl_case_no,
                    ddl_tot_accused: ddl_tot_accused,
                    sp_judgement_dt: sp_judgement_dt,
                    m_sent2: m_sent2,
                    m_sent2_mon: m_sent2_mon,
                    period_under: period_under,
                    remarks: remarks,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    updateCSRFToken();
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $('#btn_save_rec').show();
                        $('#loader').html('');
                        $("#dv_add_det").append(resArr[1]);
                    } else if (resArr[0] == 2) {
                        $('#btn_save_rec').show();
                        $('#loader').html('');
                        $("#get_sentence_undergone_list").html(resArr[1]);
                        setTimeout(function() {
                            btn_save_rec();
                        }, 3000);

                    } else if (resArr[0] == 3) {
                        $("#loader").html(resArr[1]);
                    }
                },
                error: function () {
                    updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });

        }
        $(document).on('click','#btn_save_rec',function(){
            btn_save_rec();
        });
        function btn_save_rec()
        {
            //alert('btn_save_rec='+btn_save_rec);
            var diary_no =$('#sentence_diary_number').val();
            var m_status = $('#m_status').val();
            var txt_frm_dt = $('#txt_frm_dt').val();
            var txt_to_dt = $('#txt_to_dt').val();
            var dv_add_det= $('#dv_add_det tr').length;
            var ddl_case_no = $('#ddl_case_no').val();
            var m_sent2 = $('#m_sent2').val();
            var m_sent2_mon = $('#m_sent2_mon').val();
            var ddl_tot_accused = $('#ddl_tot_accused').val();
            var remarks = $('#remarks').val();

            var period_under='';
            for(var i=1;i<dv_add_det;i++)
            {
                var sp_m_status=$('#sp_m_status'+i).html();
                var sp_txt_frm_dt=$('#sp_txt_frm_dt'+i).html();
                var sp_txt_to_dt=$('#sp_txt_to_dt'+i).html();
                var hd_ped_ungone=$('#hd_ped_ungone'+i).val();
                var remarks=$('#remarks').val();
                if(period_under==''){
                    period_under=sp_m_status+'@'+sp_txt_frm_dt+'@'+sp_txt_to_dt+'@'+hd_ped_ungone;
                }else{
                    period_under=period_under+','+sp_m_status+'@'+sp_txt_frm_dt+'@'+sp_txt_to_dt+'@'+hd_ped_ungone;
                }
            }


            var sp_judgement_dt = $('#sp_judgement_dt').html();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?=base_url('Judicial/Sentence/Sentence/add_period_undergone')?>",
                data: {
                    diary_no: diary_no,
                    m_status: m_status,
                    txt_frm_dt: txt_frm_dt,
                    txt_to_dt: txt_to_dt,
                    cnt_rw: dv_add_det,
                    ddl_case_no: ddl_case_no,
                    ddl_tot_accused: ddl_tot_accused,
                    sp_judgement_dt: sp_judgement_dt,
                    m_sent2: m_sent2,
                    m_sent2_mon: m_sent2_mon,
                    period_under: period_under,
                    remarks: remarks,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    updateCSRFToken();
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        $("#loader").html(resArr[1]);
                        alert('Data Inserted Successfully');
                        get_sentence_undergone_list();
                    } else if (resArr[0] == 3) {
                        $("#loader").html(resArr[1]);
                    }
                },
                error: function () {
                    updateCSRFToken();
                    //alert('Something went wrong! please contact computer cell');
                }
            });

        }


    </script>