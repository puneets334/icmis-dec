<div class="container">
    <div class="card-body">
        <div class="card-header text-center">
            Case Details
        </div>
        <div class="card-body text-center">
            <div class="mb-4">
            <?php
            if (isset($pno)) {
                if ($pno == 2) {
                    $pet_name .= " AND ANR.";
                } elseif ($pno > 2) {
                    $pet_name .= " AND ORS.";
                }
            }

            if (isset($rno)) {
                if ($rno == 2) {
                    $res_name .= " AND ANR.";
                } elseif ($rno > 2) {
                    $res_name .= " AND ORS.";
                }
            }
            
            //echo isset($pet_name) ? "<p>" . $pet_name . " Vs.</p>" : '';
            //echo isset($res_name) ? "<p>" . $res_name . "</p>" : '';
            echo isset($pet_name) ?  $pet_name . " Vs. " : '';
            echo isset($res_name) ?  $res_name  : '';
            echo "<br/>Listed Before " . $judge_names ;

            if (isset($next_dt) && !is_null($next_dt)) {
                echo "<br/>Listed On " . date('d-m-Y', strtotime($next_dt));
            } else {
                echo "<br/>Listed On - Not available";
            }
            ?>
            </div>
            <form>
                <?php csrf_field() ?>
                <input type="hidden" name='ldates' id='ldates' value="<?php echo isset($next_dt) && !is_null($next_dt) ? date('d-m-Y', strtotime($next_dt)) : ''; ?>" readonly />

                <?php
                if ($chk_drop_note == 0) {
                    echo "<div class='error-message class_red'>Do Not Drop, Advance List Published, Drop Note Required before Case Drop</div>";
                    $ucode = session()->get('login')['usercode'];

                    if ($ucode != 1) {
                        echo "<div class='error-message class_red'>YOU ARE NOT AUTHORISED</div>";
                        exit();
                    }
                ?>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <input name="next_dt" type="hidden" id="next_dt" value="<?php echo $next_dt; ?>">
                    <input name="brd_slno" type="hidden" id="brd_slno" value="<?php echo $brd_slno; ?>">
                    <input name="partno" type="hidden" id="partno" value="<?php echo $partno; ?>">
                    <input name="drop_diary" type="hidden" id="drop_diary" value="<?php echo $dno; ?>">
                    <input name="roster_id" type="hidden" id="roster_id" value="<?php echo $jcode; ?>">

                    <div class="form-group">
                        <label for="drop_rmk">Drop Remark (Max 75 characters):</label>
                        <input name="drop_rmk" type="text" id="drop_rmk" maxlength="75" size="75" class="form-control">
                    </div>

                    <button name="drop_btn_note" type="button" id="drop_btn_note" class="btn btn-danger">Click to Drop</button>
                <?php
                } else {
                ?>
                    <button name="drop_btn" type="button" id="drop_btn" class="btn btn-danger mt-10">Click to Drop</button>
                    <input name="drop_diary" type="hidden" id="drop_diary" value="<?php echo $dno; ?>">
                    <input name="next_dt" type="hidden" id="next_dt" value="<?php echo $next_dt; ?>">
                <?php
                }
                ?>
            </form>
        </div>
    </div>
</div>




<script>
    $(document).ready(function() {
        $(document).on("click", "#drop_btn", function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#di_rslt').html("");
            var dno = $("#drop_diary").val();
            var ldates = $("#ldates").val();
            $.ajax({
                url: "<?php echo base_url('Listing/DropNoteAdvance/caseDropNow'); ?>",
                //cache: false,
                //async: true,
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    dno: dno,
                    ldates: ldates
                },
                beforeSend: function(xhr) {
                    $("#report_result").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                type: 'POST',
                success: function(response) {
                    updateCSRFToken();
                    $('#show_fil').html("");
                    $('#report_result').html(response.message || response.error);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });

        // Function for the second button click
        $(document).on("click", "#drop_btn_note", function() {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var next_dt = $("#next_dt").val();
            var brd_slno = $("#brd_slno").val();
            var partno = $("#partno").val();
            var dno = $("#drop_diary").val();
            var roster_id = $("#roster_id").val();
            var drop_rmk = $("#drop_rmk").val();
            var mainhead = 'M';
            var ldates = $("#ldates").val();

            if (drop_rmk == "") {
                alert("Drop Note Required.");
                return false;
            }

            $.ajax({
                url: "<?php echo base_url('Listing/DropNoteAdvance/dropNoteNow/'); ?>",

                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    next_dt: next_dt,
                    brd_slno: brd_slno,
                    dno: dno,
                    roster_id: roster_id,
                    drop_rmk: drop_rmk,
                    mainhead: mainhead,
                    ldates: ldates,
                    partno: partno
                },
                beforeSend: function(xhr) {
                    $("#report_result").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                type: 'POST',
                success: function(response) {
                    updateCSRFToken();
                    $('#show_fil').html("");
                    $('#report_result').html(response.message || response.error);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });
    });
</script>