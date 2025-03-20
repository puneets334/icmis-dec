<div class="active tab-pane" id="Fil_Trap">
    <?php
    $attribute = array('class' => 'form-horizontal fil_trap_search_form','name' => 'fil_trap_search_form', 'id' => 'fil_trap_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary">
                <div class="card-body">
                  <?php echo component_court_html('H');?>
                    <div class="row ">
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input"  type="radio" name="incompleteandcompletematter" value="dd" <?php if(!empty($formdata['incompleteandcompletematter'])){ if($formdata['incompleteandcompletematter'] == 'cv') {echo 'checked';} }?>>
                                <label class="form-check-label">Diary-Diary</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" checked type="radio" name="incompleteandcompletematter" value="cc" <?php if(!empty($formdata['incompleteandcompletematter'])){ if($formdata['incompleteandcompletematter'] == 'cm') {echo 'checked';} }?>>
                                <label class="form-check-label">Caveat-Caveat</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="incompleteandcompletematter" value="c" <?php if(!empty($formdata['incompleteandcompletematter'])){ if($formdata['incompleteandcompletematter'] == 'im') {echo 'checked';} }?>>
                                <label class="form-check-label">Caveat</label>
                            </div>
                        </div>

                    </div>


                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <input type="button" name="fil_trap_search" id="fil_trap_search"  class="fil_trap_search btn btn-primary" value="Search">
                            <input type="reset" name="reset_search" id="reset_search"  class="reset_search btn btn-primary" value="Reset">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--/.col (right) -->
    <div id="get_content_pagination"></div>
    <br/>
    <div id="dv_include"></div>
    <?= form_close();?>
</div>
<!-- /.Fil_Trap -->

<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $("#dateSelection").hide();
    $("#diary_search").hide();
    //var radio_selected=$('input[name="incompleteandcompletematter"]:checked').val();
    //radioChangeEvents(radio_selected);
    var validationError = false;
    $('#fil_trap_search').on('click', function () {
       var radio_selected=$('input[name="incompleteandcompletematter"]:checked').val();
        var ddl_court=$('#ddl_court').val();
        var ddl_st_agncy=$('#ddl_st_agncy').val();
        var ddl_bench=$('#ddl_bench').val();
        var ddl_nature=$('#ddl_nature').val();
        var txt_ref_caseno=$('#txt_ref_caseno').val();
        var ddl_ref_caseyr=$('#ddl_ref_caseyr').val();
        var txt_order_date=$('#txt_order_date').val();
        if(radio_selected.length == 0) {
            alert("At least one input radio required for the selected Report type");
            validationError = false;
            return false;
        }
        if(ddl_court.length == 0)
        {
            alert("Please select court");
            $('#ddl_court').focus();
            validationError = false;
            return false;
        }
        if(ddl_st_agncy.length == 0)
        {
            alert("Please select state");
            $('#ddl_st_agncy').focus();
            validationError = false;
            return false;
        }
        if(ddl_ref_caseyr.length == 0)
        {
            alert("Please select year");
            $('#ddl_ref_caseyr').focus();
            validationError = false;
            return false;
        }

        if (radio_selected == 'dd') {
            var url="";
            alert("Under Development");
            validationError = false;
            return false;
        }else if (radio_selected == 'cc') {
            var url="<?php echo base_url('Reports/Court/Caveat/get_caveat_to_caveat'); ?>";
        }else if (radio_selected == 'c') {
            var url="<?php echo base_url('Reports/Court/Caveat/get_caveat_lower_court_total_count'); ?>";
        } else {
            alert("At least one input radio required for the selected Report type");
            validationError = false;
            return false;
        }
        if(!(validationError)) {
            var form_data = $("#fil_trap_search_form").serialize();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('.alert-error').hide();
            $("#get_content_pagination").html('');
            $("#dv_include").html('');
            $.ajax({
                type: "POST",
                url: url,
                data: form_data,
                beforeSend: function () {
                    $('.fil_trap_search').val('Please wait...');
                    $('.fil_trap_search').prop('disabled', true);
                },
                success: function (data) {
                    $('.fil_trap_search').prop('disabled', false);
                    $('.fil_trap_search').val('Search');

                    if (radio_selected == 'dd') {

                        $("#dv_include").html('');

                    }else if (radio_selected == 'cc') {

                        $("#dv_include").html(data);

                    }else if (radio_selected == 'c') {

                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $("#get_content_pagination").html(resArr[2]);
                            $("#dv_include").html(resArr[3]);
                         }else if (resArr[0] == 3) {
                            $("#dv_include").html(resArr[1]);
                       }

                       // get_content_caveat_lower_court_details();
                    }
                    updateCSRFToken();
                },
                error: function () {
                    updateCSRFToken();
                }

            });
            return false;
        } else {
            return false;
        }
    });

    function get_content_caveat_lower_court_details(action_type){
        var ddl_court=$('#ddl_court').val();
        var ddl_st_agncy=$('#ddl_st_agncy').val();
        var ddl_bench=$('#ddl_bench').val();
        var ddl_nature=$('#ddl_nature').val();
        var txt_ref_caseno=$('#txt_ref_caseno').val();
        var ddl_ref_caseyr=$('#ddl_ref_caseyr').val();
        var txt_order_date=$('#txt_order_date').val();
        var total_count=$('#total_count').val();
       // alert('action_type='+action_type);
        if (action_type=='L') {
            $('#btn_left').attr('disabled',true);
            var ct_count=parseInt($('#inc_count').val());
            var hd_fst=parseInt($('#hd_fst').val());
            var inc_val=parseInt($('#inc_val').val());
            var inc_tot=parseInt($('#inc_tot').val());
            var sp_frst=parseInt($('#sp_frst').html())-inc_val;
            var inc_tot_pg=sp_frst;
            if($('#btn_right').is(':disabled'))
            {
                $('#btn_right').attr('disabled',false);
            }
            var nw_hd_fst=hd_fst-inc_val;
            $('#inc_count').val(ct_count-1);
            if($('#inc_count').val()==1)
            {
                $('#btn_left').attr('disabled',true);
            }
        }else if (action_type=='R'){
            var ct_count=parseInt($('#inc_count').val());
            var hd_fst=parseInt($('#hd_fst').val());
            var inc_val=parseInt($('#inc_val').val());
            var inc_tot=parseInt($('#inc_tot').val());
            var inc_tot_pg=parseInt($('#inc_tot_pg').val());
            if(hd_fst==0)
            {
                $('#btn_left').attr('disabled',false);
            }
            var nw_hd_fst=hd_fst+inc_val;
            if(ct_count==inc_tot-1)
            {
                $('#btn_right').attr('disabled',true);
            }
        }


        var url="<?php echo base_url('Reports/Court/Caveat/get_content_caveat_lower_court_details'); ?>";
       // var form_data = $("#fil_trap_search_form").serialize();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.alert-error').hide();
        $("#dv_include").html('');
        $.ajax({
            type: "GET",
            url: url,
            data:{hd_fst:nw_hd_fst,inc_val:inc_val,u_t:1,inc_tot_pg:inc_tot_pg,
                ddl_st_agncy: ddl_st_agncy,ddl_court:ddl_court,ddl_bench:ddl_bench,
                ddl_nature:ddl_nature,txt_ref_caseno:txt_ref_caseno,ddl_ref_caseyr:ddl_ref_caseyr,
                txt_order_date:txt_order_date,action_type:action_type,total_count:total_count},
            beforeSend: function () {
                $("#dv_include").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function (data) {
                if (action_type=='L') {
                    $('#dv_include').html(data);
                    $('#hd_fst').val(nw_hd_fst);
                    $('#sp_last').html(parseInt($('#sp_frst').html())-1);
                    $('#sp_frst').html(parseInt($('#sp_frst').html())-inc_val);
                    if(sp_frst==1){
                        $('#btn_left').attr('disabled',true);
                    }else{
                        $('#btn_left').attr('disabled',false);
                    }

                }else if (action_type=='R'){
                    $('#dv_include').html(data);
                    $('#inc_count').val(ct_count+1);
                    $('#hd_fst').val(nw_hd_fst);

                    $('#sp_frst').html(parseInt($('#sp_last').html())+1);
                    var sp_last_ck= parseInt($('#sp_last').html())+inc_val;
                    var sp_nf = parseInt($('#sp_nf').html());
                    if(sp_last_ck<=sp_nf)
                    {
                        $('#sp_last').html(parseInt($('#sp_last').html())+inc_val);
                        $('#btn_right').attr('disabled',false);
                    }
                    else
                    {
                        $('#sp_last').html(sp_nf);
                        $('#btn_right').attr('disabled',true);
                    }
                }
                updateCSRFToken();
            },
            error: function () {
                updateCSRFToken();
            }

        });
    }

   /* $(document).ready(function() {
        $('input:radio').change(function() {
            var radio_selected=$('input[name="incompleteandcompletematter"]:checked').val();
            radioChangeEvents(radio_selected);
        });
    });

    function radioChangeEvents(radio_selected=null)
    {
        if(radio_selected=='cv')
        {
            $("#dateSelection").hide();
            $("#diary_search").show();
        }
        else if(radio_selected=='cm')
        {
            $("#dateSelection").show();
            $("#diary_search").hide();
        }
        else
        {
            $("#diary_search,#dateSelection input,select").each(function() {
                this.value = "";
            })

            $("#dateSelection").hide();
            $("#diary_search").hide();
        }
    }*/


</script>

