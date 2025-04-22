<?= view('header') ?> 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.card-header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="container-fluid m-0 p-0">
                            <div class="row clearfix mr-1 ml-1 p-0">
                                <div class="col-12 m-0 p-0">
                                    <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                                    <div class="card">
                                        <div class="card-header bg-info text-white font-weight-bolder">Physical Hearing (With Hybrid Option)
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-row col-12">
                                                    <?php
                                                    $attributes = 'class="col-md-12"';
                                                    $action = base_url('Listing/hybrid/registry_consent_process');
                                                    echo form_open($action, $attributes);
                                                        echo csrf_field();
                                                        $crtId  = (session()->get('courtno')) ? session()->get('courtno') : '';
                                                        $list_type  = (session()->get('list_type')) ? session()->get('list_type') : '';
                                                        ?>
                                                        <div class="input-group col-sm-10 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="listtype_addon">List Type</span>
                                                            </div>
                                                            <div class="border">
                                                                <?php
                                                                if(isset($masterList) && !empty($masterList) && count($masterList) > 0) { ?>
                                                                    <select name="list_type_name" id="list_type_name" class="form-control list_type" aria-describedby="listtype_addon">
                                                                        <option value="<?= $masterList[0]['id'] ?>" <?= ($list_type == $masterList[0]['id']) ? 'selected' : ''; ?>><?= $masterList[0]['list_type_name'] ?></option>
                                                                    </select>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="courtno_addon">Court No.<span style="color:red;">*</span></span>
                                                            </div>
                                                            <div class="border col-sm-6">
                                                                <select name="list_no" id="list_no" class="form-control courtno" aria-describedby="courtno_addon">
                                                                    <option value="0">-Select-</option>
                                                                    <option value="1" <?= ($crtId == '1') ? 'selected' : ''; ?>>1</option>
                                                                    <option value="2" <?= ($crtId == '2') ? 'selected' : ''; ?>>2</option>
                                                                    <option value="3" <?= ($crtId == '3') ? 'selected' : ''; ?>>3</option>
                                                                    <option value="4" <?= ($crtId == '4') ? 'selected' : ''; ?>>4</option>
                                                                    <option value="5" <?= ($crtId == '5') ? 'selected' : ''; ?>>5</option>
                                                                    <option value="6" <?= ($crtId == '6') ? 'selected' : ''; ?>>6</option>
                                                                    <option value="7" <?= ($crtId == '7') ? 'selected' : ''; ?>>7</option>
                                                                    <option value="8" <?= ($crtId == '8') ? 'selected' : ''; ?>>8</option>
                                                                    <option value="9" <?= ($crtId == '9') ? 'selected' : ''; ?>>9</option>
                                                                    <option value="10" <?= ($crtId == '10') ? 'selected' : ''; ?>>10</option>
                                                                    <option value="11" <?= ($crtId == '11') ? 'selected' : ''; ?>>11</option>
                                                                    <option value="12" <?= ($crtId == '12') ? 'selected' : ''; ?>>12</option>
                                                                    <option value="13" <?= ($crtId == '13') ? 'selected' : ''; ?>>13</option>
                                                                    <option value="14" <?= ($crtId == '14') ? 'selected' : ''; ?>>14</option>
                                                                    <option value="15" <?= ($crtId == '15') ? 'selected' : ''; ?>>15</option>
                                                                    <option value="16" <?= ($crtId == '16') ? 'selected' : ''; ?>>16</option>
                                                                    <option value="17" <?= ($crtId == '17') ? 'selected' : ''; ?>>17</option>
                                                                    <option value="31" <?= ($crtId == '31') ? 'selected' : ''; ?>>1 (VC)</option>
                                                                    <option value="32" <?= ($crtId == '32') ? 'selected' : ''; ?>>2 (VC)</option>
                                                                    <option value="33" <?= ($crtId == '33') ? 'selected' : ''; ?>>3 (VC)</option>
                                                                    <option value="34" <?= ($crtId == '34') ? 'selected' : ''; ?>>4 (VC)</option>
                                                                    <option value="35" <?= ($crtId == '35') ? 'selected' : ''; ?>>5 (VC)</option>
                                                                    <option value="36" <?= ($crtId == '36') ? 'selected' : ''; ?>>6 (VC)</option>
                                                                    <option value="37" <?= ($crtId == '37') ? 'selected' : ''; ?>>7 (VC)</option>
                                                                    <option value="38" <?= ($crtId == '38') ? 'selected' : ''; ?>>8 (VC)</option>
                                                                    <option value="39" <?= ($crtId == '39') ? 'selected' : ''; ?>>9 (VC)</option>
                                                                    <option value="40" <?= ($crtId == '40') ? 'selected' : ''; ?>>10 (VC)</option>
                                                                    <option value="41" <?= ($crtId == '41') ? 'selected' : ''; ?>>11 (VC)</option>
                                                                    <option value="42" <?= ($crtId == '42') ? 'selected' : ''; ?>>12 (VC)</option>
                                                                    <option value="43" <?= ($crtId == '43') ? 'selected' : ''; ?>>13 (VC)</option>
                                                                    <option value="44" <?= ($crtId == '44') ? 'selected' : ''; ?>>14 (VC)</option>
                                                                    <option value="45" <?= ($crtId == '45') ? 'selected' : ''; ?>>15 (VC)</option>
                                                                    <option value="46" <?= ($crtId == '46') ? 'selected' : ''; ?>>16 (VC)</option>
                                                                    <option value="47" <?= ($crtId == '47') ? 'selected' : ''; ?>>17 (VC)</option>
                                                                    <option value="61" <?= ($crtId == '61') ? 'selected' : ''; ?>>1 (VC R1)</option>
                                                                    <option value="62" <?= ($crtId == '62') ? 'selected' : ''; ?>>2 (VC R2)</option>
                                                                    <option value="21" <?= ($crtId == '21') ? 'selected' : ''; ?>>21 (Registrar)</option>
                                                                    <option value="22" <?= ($crtId == '22') ? 'selected' : ''; ?>>22 (Registrar)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2 pl-2 mb-3">
                                                            <button id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block">Search</button>
                                                        </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-md-12 m-0 p-0" id="result"></div>
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
    </section>
    <!-- /.content -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
       
        var courtno = $('#list_no').val();
        var list_type = $('#list_type_name').val();
        if (courtno != '0' && list_type != '') {
            $(".courtno").val(courtno);
            $(".list_type").val(list_type);
            load_registry_consent_process();
        }

        $("#btn_search").click(function(){
            load_registry_consent_process();
        });

        async function load_registry_consent_process(){
            await updateCSRFTokenSync();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            $("#result").html(""); 
            $('#show_error').html("");
            var list_type = $(".list_type").val(); // Retrieve the value of the element
            var courtno = $(".courtno").val();
            if (list_type === null || list_type.length === 0) {
                $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select list type</strong></div>');
                $("#from_date").focus();
                return false;
            } else if (courtno == 0) {
                $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select court number</strong></div>');
                $("#applicant_type").focus();
                return false;
            } else {
                $.ajax({
                    url:'<?php echo base_url('Listing/hybrid/registry_consent_process'); ?>',
                    cache: false,
                    async: true,
                    data: {CSRF_TOKEN:csrf,list_type:list_type,courtno:courtno},
                    beforeSend:function(){
                        $('#result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                        $("#btn_search").html('Loading <i class="fas fa-sync fa-spin"></i>');
                    },
                    type: 'POST',
                    success: function(data, status) {
                        $("#btn_search").html('Search');
                        $("#result").html(data);
                        updateCSRFTokenSync();
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
                await updateCSRFTokenSync();
            }
        }

        $(document).on('click', '.hybrid_action', async function () {
            await updateCSRFTokenSync();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var updation_method = $(this).data('updation_method');

            if(updation_method == 'single') {
                var diary_no = $(this).data('diary_no');
                var conn_key = $(this).data('conn_key');
                var next_dt = $(this).data('next_dt');
                var from_dt = $(this).data('from_dt');
                var to_dt = $(this).data('to_dt');
                var list_type_id = $(this).data('list_type_id');
                var list_number = $(this).data('list_number');
                var list_year = $(this).data('list_year');
                var mainhead = $(this).data('mainhead');
                var board_type = $(this).data('board_type');
                var courtno = $(this).data('court_number');
                var from_time = $("#d_" + diary_no).children(".form-group").find(".from_time").val();
                var to_time = $("#d_" + diary_no).children(".form-group").find(".to_time").val();
                var ip = '<?=$clientIP?>';
                var action_content = $("#d_" + diary_no).children(".hybrid_action").html();
                var update_flag = $(this).data('update_flag');
                var update_flag_text = "";                

                if (update_flag == "P") {
                    update_flag_text = "Physical";
                }
                if (update_flag == "V") {
                    update_flag_text = "VC";
                }
                if (update_flag == "H") {
                    update_flag_text = "Hybrid";
                }
                $.ajax({
                    url: '<?php echo base_url('Listing/hybrid/registry_consent_save'); ?>',
                    cache: false,
                    async: true,
                    data: {
                        CSRF_TOKEN: csrf,
                        diary_no: diary_no,
                        conn_key: conn_key,
                        from_dt: from_dt,
                        to_dt: to_dt,
                        list_type_id: list_type_id,
                        list_number: list_number,
                        list_year: list_year,
                        mainhead: mainhead,
                        board_type: board_type,
                        courtno: courtno,
                        from_time: from_time,
                        to_time: to_time,
                        ip: ip,
                        next_dt: next_dt,
                        updation_method: updation_method,
                        update_flag: update_flag
                    },
                    beforeSend: function () {
                        $("#d_" + diary_no).children(".hybrid_action").html('Loading <i class="fas fa-sync fa-spin"></i>');
                    },
                    type: 'POST',
                    dataType: "json",
                    success: function (data, status) {
                        if (data.status == 'success') {
                            swal({
                                title: "Success!",
                                text: "Directons for " + update_flag_text + " Hearing Preserved",
                                icon: "success",
                                button: "success!"
                            });
                            
                            if (from_time === "") {
                                from_time = "Not Entered";
                            }

                            if (to_time === "") {
                                to_time = "Not Entered";
                            }
                            //$(this).removeClass( "btn-secondary" ).addClass( "btn-success" );
                            $("#d_" + diary_no).html("<b>From Time :</b> " + from_time + " <b>To Time :</b> " + to_time + "<br><span class='text-success font-weight-bolder'>Success</span>");
                        } else {
                            swal({title: "Error!", text: data.status, icon: "error", button: "error!"});
                            $("#d_" + diary_no).children(".hybrid_action").html(action_content);
                        }
                        updateCSRFTokenSync();
                    },
                    error: function (xhr) {
                        updateCSRFTokenSync();
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
                updateCSRFTokenSync();
            } // if(updation_method == 'bulk') {            
            else
            {
                var chk_count = 0; var count_success = 0; var count_error = 0;
                var update_flag = $(this).data('update_flag');
                var update_flag_text = "";

                if (update_flag == "P") {
                    update_flag_text = "Physical";
                }

                if (update_flag == "V") {
                    update_flag_text = "VC";
                }

                if (update_flag == "H") {
                    update_flag_text = "Hybrid";
                }

                $('input[type=checkbox]').each(function (){
                    if($(this).attr("name")=="chk" && $(this).is(':checked')){
                        chk_count++;
                    }
                });

                if(chk_count == 0){
                    swal({title: "Error!",text: "Atleast one case should be selected",icon: "error",button: "error!"});
                    return false;
                }
                
                swal({
                    title: "Are you sure?",
                    text: "You want to update "+chk_count+" cases for "+update_flag_text+" hearing",
                    dangerMode: true,
                    icon: "warning",
                    // type: "warning",
                    showCancelButton: true,
                    buttons: [
                        'No, cancel it!',
                        'Yes, I am sure!'
                    ],
                    confirmButtonClass: 'btn-danger',
                    // confirmButtonText: 'Yes, I am sure!',
                    // cancelButtonText: 'No, cancel it!',
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        updateCSRFTokenSync();
                        $(".result_action_loader").html('Processing <i class="fas fa-sync fa-spin"></i>');
                        $('input[type=checkbox]').each(function (){
                            // updateCSRFTokenSync();
                            // var CSRF_TOKEN = 'CSRF_TOKEN';
                            // var csrf = $("input[name='CSRF_TOKEN']").val();
                            if($(this).attr("name")=="chk" && $(this).is(':checked')){
                                var diary_no = $(this).data('diary_no');
                                var conn_key = $(this).data('conn_key');
                                var next_dt = $(this).data('next_dt');
                                var from_dt = $(this).data('from_dt');
                                var to_dt = $(this).data('to_dt');
                                var list_type_id = $(this).data('list_type_id');
                                var list_number = $(this).data('list_number');
                                var list_year = $(this).data('list_year');
                                var mainhead = $(this).data('mainhead');
                                var board_type = $(this).data('board_type');
                                var courtno = $(this).data('court_number');

                                var from_time = $(this).parents('tr').find("input[name='from_time']").val();
                                var to_time = $(this).parents('tr').find("input[name='to_time']").val();                                
                                // var from_time = "00:00:00";
                                // var to_time = "00:00:00";
                                
                                var ip = '<?=$clientIP?>';
                                //var action_content = $("#d_" + diary_no).children(".hybrid_action").html();
                                $.ajax({
                                    url: '<?php echo base_url('Listing/hybrid/registry_consent_save'); ?>',
                                    cache: false,
                                    async: true,
                                    data: {
                                        // CSRF_TOKEN: csrf,
                                        diary_no: diary_no,
                                        conn_key: conn_key,
                                        from_dt: from_dt,
                                        to_dt: to_dt,
                                        list_type_id: list_type_id,
                                        list_number: list_number,
                                        list_year: list_year,
                                        mainhead: mainhead,
                                        board_type: board_type,
                                        courtno: courtno,
                                        from_time: from_time,
                                        to_time: to_time,
                                        ip: ip,
                                        next_dt: next_dt,
                                        updation_method: updation_method,
                                        update_flag: update_flag
                                    },
                                    beforeSend: function () {
                                    // $(this).html(update_flag_text+' <i class="fas fa-sync fa-spin"></i>');
                                    },
                                    type: 'GET',
                                    dataType: "json",
                                    success: function (data, status) {
                                        if (data.status == 'success') {
                                            count_success++;
                                            $(".result_action").html("Success <span class='badge badge-secondary'>"+count_success+"</span> out of <span class='badge badge-secondary'>"+chk_count+"</span> cases");
                                            $(".result_success_count").val(count_success);
                                        }
                                        else {
                                            count_error++;
                                        }
                                    // alert(count_success);
                                    },
                                    error: function (xhr) {
                                        // updateCSRFTokenSync();
                                        alert("Error: " + xhr.status + " " + xhr.statusText);
                                    }
                                });
                            }
                        });
                        setTimeout(function() {
                            // $(".result_action_loader").html('<span class="text-success">Completed</span>');
                            var result_success_count = $('.result_success_count').val();
                            if(result_success_count > 0){
                                $(".result_action_loader").html('');
                                swal({
                                    title: "Success!",
                                    text: result_success_count+" Cases Updated Successfully",
                                    icon: "success",
                                    button: "success!"
                                },function(){
                                    location.reload();
                                });
                            } else {
                                swal({title: "Error!",text: "Not Updated",icon: "error",button: "error!"});
                            }
                        }, 1000);
                    } else {
                        swal("Cancelled", "Please try again :)", "error");
                    }
                });
                //alert(chk_count);
            }
        });

        $(document).on('click', '.delete_action', function () {
            var updation_method = $(this).data('updation_method');
            var diary_no = $(this).data('diary_no');
            var conn_key = $(this).data('conn_key');
            var ip = '<?=$clientIP?>';
            var delete_action_html = $(this).html();
            $.ajax({
                url:'registry_consent_delete.php',
                cache: false,
                async: true,
                data: {diary_no:diary_no,ip:ip,conn_key:conn_key,updation_method:updation_method},
                beforeSend:function(){
                    $("#d_"+diary_no).children(".delete_action").html('Loading <i class="fas fa-sync fa-spin"></i>');
                },
                type: 'POST',
                dataType: "json",
                success: function(data, status) {
                    if(data.status == 'success'){
                        $("#d_"+diary_no).html("<span class='text-danger font-weight-bolder'>Directions Removed</span>");
                        swal({title: "Success!",text: "Directions Removed",icon: "success",button: "success!"});
                    }
                    else{
                        swal({title: "Error!",text: data.status,icon: "error",button: "error!"});
                        $("#d_"+diary_no).children(".delete_action").html('Delete');
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });
        function chkall(e){
            var elm=e.name;
            if(document.getElementById(elm).checked) {
                $('input[type=checkbox]').each(function () {
                    if($(this).attr("name")=="chk")
                        this.checked=true;
                });
            } else {
                $('input[type=checkbox]').each(function () {
                    if($(this).attr("name")=="chk")
                        this.checked=false;
                });
            }
        }
    </script>

<?=view('sci_main_footer') ?>