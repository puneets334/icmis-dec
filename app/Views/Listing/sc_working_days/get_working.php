<?php
if (isset($getWorkingDays) && count($getWorkingDays) > 0) {
    foreach($getWorkingDays as $row) {
        $is_nmd = (isset($row["is_nmd"])) ? $row["is_nmd"] : 0;
        $is_holiday = (isset($row["is_holiday"])) ? $row["is_holiday"] : 0;
        $holiday_description = (isset($row["holiday_description"])) ? $row["holiday_description"] : NULL;
        $nmd_dt = (!is_null($row["nmd_dt"])) ? $row["nmd_dt"] : date('d-m-Y');
        $misc_dt1 = (!is_null($row["misc_dt1"])) ? $row["misc_dt1"] : date('d-m-Y');
        $sec_list_dt = (!is_null($row["sec_list_dt"])) ? $row["sec_list_dt"] : date('d-m-Y');
        $holiday_for_registry = (isset($row["holiday_for_registry"])) ? $row["holiday_for_registry"] : 0;
        $action = base_url('Listing/SCWorkingDays/update_working_day');
        $subVal = 'UPDATE';
        $incls = '';
    }
}else{
    $action = base_url('Listing/SCWorkingDays/insert_working_day');
    $subVal = 'INSERT';
    $incls = 'btn_insert';
}

?>
        <style>
            .center1 {
                margin-left: auto;
                margin-right: auto;
                width: 40%;
                /* border: 1px solid #73AD21; */
                padding: 10px;
                text-align: left;
            }
            hn2 { 
                display: block;
                font-size: 1.2em;
                margin-top: 0.67em;
                margin-bottom: 0.67em;
                margin-left: 0;
                margin-right: 0;
                /*font-weight: bold;*/
            }
        </style>
        <script>
            $.datepicker.setDefaults({ dateFormat: "dd-mm-yy" });
        </script>
        <?php
        $attributes = 'class="col-12 col-md-6 sci_cal"';
        
        echo form_open($action, $attributes);
            echo csrf_field();
            ?>
            <div class="center1" id="dv_content1" style="width: 100%;float: left"><br>
                <table border="0" cellpadding="0" cellspacing="0" align="center">
                    <td style="text-align: left;font-weight: bold;border:1px solid white;"></td>
                    <br><hn2>Details are as below. To modify, please enter the new field values and click Update</hn2>
                    <tr><td width="3%" height="30" style="text-align: left;font-weight: bold;border:1px solid white;" ><b>Listing/Verification Date:</b></td> <td style="text-align: left;border:1px solid white;" width="30%"><input class="form-control cus-form-ctrl" type="text" size="30" id="working_date" name="working_date" value="<?= isset($_POST['is_working']) ? date('d-m-Y', strtotime($_POST['is_working'])) : ''; ?>" readonly></td></tr>
                    &nbsp <tr><td style="text-align: left;font-weight: bold;border:1px solid white;"><b>Misc/Regular Day:</b><td width="3%" height="28" style="text-align: left;border:1px solid white;">
                        <select class="form-control cus-form-ctrl" id="is_nmd"  style="width:222px ;height:22px" name="is_nmd">
                            <option value="0" <?= (isset($is_nmd) && $is_nmd == 0) ? 'selected' : ''; ?>>Misc Day</option>
                            <option value="1" <?= (isset($is_nmd) && $is_nmd == 1) ? 'selected' : ''; ?>>Regular Day</option>
                        </select>
                    </tr>
                    &nbsp <tr><td style="text-align: left;font-weight: bold;border:1px solid white;"><b>Court Holiday:</b><td width="3%" height="28" style="text-align: left;border:1px solid white;">
                        <select class="form-control cus-form-ctrl" id="is_holiday" style="width:222px ;height:22px" name="is_holiday">
                            <option value="0" <?= (isset($is_holiday) && $is_holiday == 0) ? 'selected' : ''; ?>>No</option>
                            <option value="1" <?= (isset($is_holiday) && $is_holiday == 1) ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </tr>
                    &nbsp<tr><td width="3%" height="30" style="text-align: left;font-weight: bold;border:1px solid white;"><b>Holiday Description:</b></td> <td style="text-align: left;border:1px solid white;"><input type="text" class="form-control cus-form-ctrl" size="30" id="holiday_description" name="holiday_description" value="<?= isset($holiday_description) ? $holiday_description : ''; ?>"></td></tr>
                    &nbsp<tr><td width="3%" height="30" style="text-align: left;font-weight: bold;border:1px solid white;"><b>Regular Day Date (Fresh):</b></td> <td style="text-align: left;border:1px solid white;">
                        <input type="text" size="30" id="nmd_dt" class="form-control dtppp" name="nmd_dt" value="<?= isset($nmd_dt) ? date('d-m-Y', strtotime($nmd_dt)) : ''; ?>" readonly></td></tr>
                    &nbsp<tr><td width="3%" height="30" style="text-align: left;font-weight: bold;border:1px solid white;"><b>MISC Date (Fresh):</b></td> <td style="text-align: left;border:1px solid white;"><input type="text" size="30" id="misc_dt1" class="form-control cus-form-ctrl dtppp" name="misc_dt1" value="<?= isset($misc_dt1) ? date('d-m-Y', strtotime($misc_dt1)) : ''; ?>" readonly></td></tr>
                    &nbsp<tr><td width="3%" height="30" style="text-align: left;font-weight: bold;border:1px solid white;"><b>SEC List Date: </b></td><td style="text-align: left;border:1px solid white;"><input type="text" size="30" id="sec_list_dt" class="form-control cus-form-ctrl dtppp" name="sec_list_dt" value="<?= isset($sec_list_dt) ? date('d-m-Y', strtotime($sec_list_dt)) : ''; ?>" readonly></td></tr>
                    &nbsp<tr>
                        <td width="3%" height="30" style="text-align: left;font-weight: bold;border:1px solid white;"><b>Holiday For Registry:</b>
                        <td style="text-align: left;border:1px solid white;">
                            <select class="form-control cus-form-ctrl" id="holiday_for_registry" style="width:222px ;height:22px" name="holiday_for_registry">
                                <option value="0" <?= (isset($holiday_for_registry) && $holiday_for_registry == 0) ? 'selected' : ''; ?>>No</option>
                                <option value="1" <?= (isset($holiday_for_registry) && $holiday_for_registry == 1) ? 'selected' : ''; ?>>Yes</option>
                            </select>
                    </tr>
                </table>
                <br><input type="button" name="btn_update" class="btn_update <?= $incls; ?>" value="<?= $subVal; ?>">
                <div class="result_update"></div>
            </div>
            <?php
        echo form_close();
        
?>
<style>
    .result_update {
        margin-left: 0;
        margin-right: 0;
        width: 40%;
        /* border: 1px solid #73AD21;*/
        padding: 10px;
        text-align: left;
        font-size: 1.0em;
    }
</style>

<script>
    $(".btn_update").click(async function(){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var working_date = $("#working_date").val();
        var is_nmd = $("#is_nmd").val();
        var is_holiday = $("#is_holiday").val();
        var holiday_description = $("#holiday_description").val();
        var nmd_dt = $("#nmd_dt").val();
        var misc_dt1 = $("#misc_dt1").val();
        var sec_list_dt = $("#sec_list_dt").val();
        var holiday_for_registry = $("#holiday_for_registry").val();
        if(is_holiday == 1 && holiday_description == ''){$('.result_update').html('<table width="100%" align="center"><tr><td class="class_red">Please enter holiday discription</table>'); return false; }
        else if(is_holiday == 0 && holiday_description != ''){$('.result_update').html('<table width="100%" align="center"><tr><td class="class_red">Please enter Court Holiday value as Yes to enter a Holiday Description</table>'); return false; }
        else if(nmd_dt == ""){$('.result_update').html('<table width="100%" align="center"><tr><td class="class_red">Please select Regular Day Date</table>'); return false; }
        else if(misc_dt1 == ""){$('.result_update').html('<table width="100%" align="center"><tr><td class="class_red">Please select Misc. Date</table>'); return false; }
        else if(sec_list_dt == ""){$('.result_update').html('<table width="100%" align="center"><tr><td class="class_red">Please select Sec List Date</table>'); return false; }
        var url = $('.sci_cal').attr('action');
        $.ajax({
            url: url,
            cache: false,
            async: true,
            data: {CSRF_TOKEN:csrf,working_date:working_date,is_nmd,is_nmd,is_holiday,is_holiday,holiday_description,holiday_description,nmd_dt,nmd_dt,misc_dt1:misc_dt1,sec_list_dt:sec_list_dt,holiday_for_registry:holiday_for_registry},
            beforeSend:function(){
                $('.result_update').html('<table widht="100%" align="center"><tr><td><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            type: 'POST',        
            success: async function(data, status) {
                $(".result_update").html(data);
                $(".btn_insert").hide();
                await updateCSRFTokenSync();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        await updateCSRFTokenSync();
    });

    
</script>