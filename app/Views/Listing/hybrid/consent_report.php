<?= view('header') ?> 
<style>
.col-3 {
    float: left;
    max-width: 20%;
}
</style>
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
                                        <div class="card-header bg-info text-white font-weight-bolder"> Report : Directions Received for Physical Hearing (With Hybrid Option) </div>
                                        <div class="card-body">
                                            <div class="row ">
                                                <div class="form-row col-12 px-2">
                                                    <?php
                                                    $attributes = 'class="col-md-12"';
                                                    $action = base_url('Listing/hybrid/consent_report_process');
                                                    echo form_open($action, $attributes);
                                                        echo csrf_field();
                                                        ?>
                                                        <div class=" px-2 col-3">
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="listtype_addon">List Type</span>
                                                                </div>
                                                                <div class="border">
                                                                    <?php
                                                                    if(isset($masterList) && !empty($masterList) && count($masterList) > 0) { ?>
                                                                        <select name="list_type_name" id="list_type_name" class="form-control list_type" aria-describedby="listtype_addon">
                                                                            <option value="<?= $masterList[0]['id'] ?>"><?= $masterList[0]['list_type_name'] ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        // pr($listDetails);
                                                        // if (isset($listDetails) && !empty($listDetails)) {
                                                            ?>
                                                            <div class=" px-2 col-3">
                                                                <div class="input-group  mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="weeklyno_addon">Weekly No.<span style="color:red;">*</span></span>
                                                                    </div>
                                                                    <select class="form-control weeklyno" aria-describedby="weeklyno_addon">
                                                                        <option value="0">-Select-</option>
                                                                        <?php 
                                                                        if (isset($listDetails) && !empty($listDetails)) {
                                                                        foreach($listDetails as $row) { ?>
                                                                            <option value="<?=$row['list_number']?>"><?=$row['list_number']?></option>
                                                                        <?php } }?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <?php // } ?>
                                                        <div class=" px-2 col-3">
                                                            <div class=" input-group  mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="weeklyyear_addon">Weekly Year<span style="color:red;">*</span></span>
                                                                </div>
                                                                <select class="form-control weeklyyear" aria-describedby="weeklyyear_addon">
                                                                    <option value="0">-Select-</option>
                                                                    <?php
                                                                    $yearplus = date('Y');
                                                                    for($i=2021;$i<=$yearplus;$i++) {
                                                                        ?>
                                                                        <option value="<?=$i?>"><?=$i?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class=" px-2 col-3">
                                                            <div class=" input-group  mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="courtno_addon">Court No.<span style="color:red;">*</span></span>
                                                                </div>
                                                                <select class="form-control courtno" aria-describedby="courtno_addon">
                                                                    <option value="0">-All-</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                    <option value="6">6</option>
                                                                    <option value="7">7</option>
                                                                    <option value="8">8</option>
                                                                    <option value="9">9</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="31">1 (VC)</option>
                                                                    <option value="32">2 (VC)</option>
                                                                    <option value="33">3 (VC)</option>
                                                                    <option value="34">4 (VC)</option>
                                                                    <option value="35">5 (VC)</option>
                                                                    <option value="36">6 (VC)</option>
                                                                    <option value="37">7 (VC)</option>
                                                                    <option value="38">8 (VC)</option>
                                                                    <option value="39">9 (VC)</option>
                                                                    <option value="40">10 (VC)</option>
                                                                    <option value="41">11 (VC)</option>
                                                                    <option value="42">12 (VC)</option>
                                                                    <option value="43">13 (VC)</option>
                                                                    <option value="44">14 (VC)</option>
                                                                    <option value="45">15 (VC)</option>
                                                                    <option value="46">16 (VC)</option>
                                                                    <option value="47">17 (VC)</option>
                                                                    <option value="61">1 (VC R1)</option>
                                                                    <option value="62">2 (VC R2)</option>
                                                                    <option value="21">21 (Registrar)</option>
                                                                    <option value="22">22 (Registrar)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class=" px-2">
                                                            <div class="pl-2 mb-3">
                                                                <button id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block">Search</button>
                                                            </div>
                                                        </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-md-12 m-0 p-0" id="result"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $("#btn_search").click(async function(){
            await updateCSRFTokenSync();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            $("#result").html(""); $('#show_error').html("");
            var list_type = $(".list_type").val();
            var weeklyno = $(".weeklyno").val();
            var weeklyyear = $(".weeklyyear").val();
            var courtno = $(".courtno").val();
            if (list_type.length == 0) {
                $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select list type</strong></div>');
                $("#from_date").focus();
                return false;
            } else if (weeklyno == 0) {
                $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select Weekly no.</strong></div>');
                $("#applicant_type").focus();
                return false;
            } else if (weeklyyear == 0) {
                $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select list year</strong></div>');
                $("#applicant_type").focus();
                return false;
            } else{
                $.ajax({
                    url:'<?php echo base_url('Listing/hybrid/consent_report_process'); ?>',
                    cache: false,
                    async: true,
                    data: {CSRF_TOKEN:csrf,list_type:list_type,weeklyno:weeklyno,weeklyyear:weeklyyear,courtno:courtno},
                    beforeSend:function(){
                        $("#btn_search").html('Loading <i class="fas fa-sync fa-spin"></i>');
                    },
                    type: 'POST',
                    success: function(data, status) {
                        $("#btn_search").html('Search');
                        $("#result").html(data);
                        updateCSRFTokenSync();
                    },
                    error: function(xhr) {
                        updateCSRFTokenSync();
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
                updateCSRFTokenSync();
            }
        });
    </script>
<?=view('sci_main_footer') ?>