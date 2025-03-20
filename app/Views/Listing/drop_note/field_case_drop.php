<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Case Drop Module</h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url('Filing/Diary'); ?>"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                    <a href="<?= base_url('Filing/Diary/search'); ?>"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                                    <a href="<?= base_url('Filing/Diary/deletion'); ?>"><button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                    <div class="card-body">
                    <table id="customers" width="100%">
                        <?php if(!empty($from_heardt)) {
                            //pr($case_details);

                            $q_next_dt = $from_heardt['next_dt'];
                            $partno = $from_heardt['clno'];
                            $brd_slno = $from_heardt['brd_slno'];
                            $mainhead = $from_heardt['mainhead'];
                            $roster_id = $from_heardt['roster_id'];
                            $jcode = $from_heardt['judges'];
                            if($from_heardt['pno'] == 2){
                                $pet_name = $from_heardt['pet_name']." AND ANR.";
                            }
                            else if($from_heardt['pno'] > 2){
                                $pet_name = $from_heardt['pet_name']." AND ORS.";
                            }
                            else{
                                $pet_name = $from_heardt['pet_name'];
                            }
                            if($from_heardt['rno'] == 2) {
                                $res_name = $from_heardt['res_name']." AND ANR.";
                            }
                            else if($from_heardt['rno'] > 2){
                                $res_name = $from_heardt['res_name']." AND ORS.";
                            }
                            else{
                                $res_name = $from_heardt['res_name'];
                            }
                            ?>
                            <tr>
                                <td>Case No.</td>
                                <td><?php echo $from_heardt['reg_no_display']; ?></td>
                            </tr>
                            <tr><td>Diary No.</td><td><?php echo substr_replace($from_heardt['diary_no'], '-', -4, 0) ?></td></tr>
                            <tr><td>Cause Title </td><td><?php echo $pet_name." Vs. ".$res_name ?></td></tr>
                            <tr><td>Listed Before Justice </td><td><?php echo f_get_judge_names($jcode) ?></td></tr>
                            <tr><td>Listed On </td><td><?php echo date('d/m/Y', strtotime($q_next_dt)) ?></td></tr>
                            <tr>
                                <td>Tentative Date </td><td><input type="text" size="10" class="dtp" name='ldates' id='ldates' value="<?php echo date('d/m/Y', strtotime($q_next_dt)); ?>" readonly /></td>
                            </tr>
                            <tr>
                            <!--<td>Tentative Date </td>
                                <td>
                                <input type="date" id="decidedDate" name="decidedDate" class="form-control datepick"  placeholder="Select Date" value="<?php //echo date('mm-dd-yyyy', strtotime($q_next_dt)); ?>"  />
                                </td>
                            </tr>-->
                            <tr>
                                <td>Reason Type </td><td>
                                <select class="ele" name="ready_not" id="ready_not">
                                    <option value="5" selected >DELETE FROM LIST AND UPDATE LATER</option>
                                    <option value="6">SEEKING DIRECTIONS (Never to be list till ready by listing section)</option>
                                    <!--<option value="0" selected>READY</option>-->
                                </select>
                                </td>
                            </tr>


                            <?php if($cl_result == 1) { ?>
                            <select style="visibility: hidden;" class="ele" name="drop_reason_select" id="drop_reason_select">
                                <option value="0" selected></option>
                            </select>
                            <tr>
                                <td>Reason </td>
                                <td>
                                    <input name="drop_rmk" type="text" id="drop_rmk" maxlength="50" size="50" placeholder="Please enter reason">
                                    <input name="is_printed" type="hidden" id="is_printed" value="Y" >
                                </td>
                            </tr>
                            <tr>
                                <td>Listing Status</td><td><font color=red>Published</font></td>
                            </tr>
                            <?php } else { ?>
                                <tr>
                                    <td>Reason</td><td>
                                    <select class="ele" name="drop_reason_select" id="drop_reason_select">
                                        <option value="0" selected>-SELECT-</option>
                                        <?php if(!empty($drop_reasons)) { ?>
                                            <?php foreach($drop_reasons as $drop_reason) { ?>
                                                <option value="<?php echo $drop_reason['id']; ?>" > <?php echo $drop_reason['reason']; ?> </option>
                                            <?php }
                                        } ?>
                                    </select>
                                    <br>
                                    <input name="drop_rmk" type="text" id="drop_rmk" value="" maxlength="50" size="50" placeholder="Please enter reason">
                                    <input name="is_printed" type="hidden" id="is_printed" value="B" >
                                </td></tr>
                                <tr><td>Listing Status</td><td>Not Published</td></tr>
                            <?php } ?>

                            <?php if($chk_drop_note == 0) { ?>
                                <tr>
                                    <td colspan='2'>
                                        <font color=red>Note : Do Not Drop, List Published, Drop Note Required before Case Drop</font>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan='2'><font color=red>Note : Drop Note Required before Case Drop</font></td>
                                </tr>
                            <?php } ?>

                        <tr><th><div id="show_fil" style="text-align: center;"></div><tr><th>
                        <tr><th><div id="di_rslt" style="text-align: center;"></div><tr><th>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input name="next_dt" type="hidden" id="next_dt" value="<?php echo isset($q_next_dt) ? $q_next_dt : ''; ?>" >
                                <input name="brd_slno" type="hidden" id="brd_slno" value="<?php echo isset($brd_slno) ? $brd_slno : ''; ?>" >
                                <input name="partno" type="hidden" id="partno" value="<?php echo isset($partno) ? $partno : ''; ?>" >
                                <input name="drop_diary" type="hidden" id="drop_diary" value="<?php echo isset($diary_number) ? $diary_number : ''; ?>" >
                                <input name="roster_id" type="hidden" id="roster_id" value="<?php echo isset($roster_id) ? $roster_id : ''; ?>" >
                                <input name="mainhead" type="hidden" id="mainhead" value="<?php echo isset($mainhead) ? $mainhead : ''; ?>" >
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                <input name="drop_btn_note" type="button" id="drop_btn_note" value="Click to Drop">
                            </td>
                        </tr>
                        <?php } else { ?>
                            <tr><th>Record Not Available/Case Not listed</th></tr>
                        <?php } ?>
                    </table>
                    </div>
                    </div>
                    <!-- Main content end -->

                </div> <!--end dv_content1-->
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<script>
    $(document).ready(function(){
        $('#ldates').datepicker({
			dateFormat: 'dd/mm/yy',
			autoclose:true,
            changeMonth : true,
            changeYear  : true,
            yearRange : '1950:2050'
		});
    });
    $(document).on("click","#drop_btn_note",function() {
        var next_dt = $("#next_dt").val();
        var brd_slno = $("#brd_slno").val();
        var partno = $("#partno").val();
        var dno = $("#drop_diary").val();
        var roster_id = $("#roster_id").val();
        var drop_reason_select = $("#drop_reason_select").val();
        var drop_rmk = $("#drop_rmk").val();
        var mainhead = $("#mainhead").val();
        var ldates = $("#ldates").val();
        var ready_not = $("#ready_not").val();
        var is_printed = $("#is_printed").val();
        reason_md_str = drop_rmk.trim();
        if(is_printed == 'B' && drop_reason_select == 0){
            alert('Please Select Reason.');
            $("#drop_reason_select").focus();
            return false;
        }
        if(reason_md_str.length<8 && (is_printed == 'Y' || (is_printed == 'B' && ready_not == 6))){
            alert('Please Entre Drop Reason with minimum 8 characters.');
            $("#drop_rmk").focus();
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#loader").html('');

        $.ajax({
                url: "<?php echo base_url('Listing/CaseDrop/drop_note_now'); ?>",
                cache: false,
                async: true,
                data: {next_dt: next_dt,brd_slno:brd_slno,dno:dno,roster_id:roster_id,drop_rmk:drop_rmk,mainhead:mainhead,ldates:ldates,ready_not:ready_not,partno:partno,is_printed:is_printed,drop_reason_select:drop_reason_select,CSRF_TOKEN:CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                type: 'POST',
                success: function(data, status){
                    $("#loader").html('');
                updateCSRFToken();
                $('#show_fil').html("");
                $('#di_rslt').html(data);
                },
                error: function(xhr){
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
    });
</script>