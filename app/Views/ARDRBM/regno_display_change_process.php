<?php
if ($option == 1) {
    
    if ($radio == 2) {
        $get_dno = $model_ia->searchDiary($ctype, $cno, $cyr);
        if ($get_dno) {
            $diary_no = $get_dno['dn'] . $get_dno['dy'];
        } else {
            $get_dno = $model_ia->getSearchDiary($ctype, $cno, $cyr);
            if ($get_dno) {
                $diary_no = $get_dno['dn'] . $get_dno['dy'];
            }
        }
    }
    elseif($radio == 'C' && !empty($ctype) && !empty($cno) && !empty($cyr) ){
        $get_dno = $model_ia->searchDiary($ctype, $cno, $cyr);
        if ($get_dno) {
            $diary_no = $get_dno['dn'] . $get_dno['dy'];
        } else {
            $get_dno = $model_ia->getSearchDiary($ctype, $cno, $cyr);
            if ($get_dno) {
                $diary_no = $get_dno['dn'] . $get_dno['dy'];
            }
        }   
    } else {
        $diary_no = $dno . $dyr;
        $get_dno['dn'] = $dno;
        $get_dno['dy'] = $dyr;
    }

    
    if ($diary_no) {
        $dno_data = $model_ia->getDiaryData($diary_no);
        $reg_no = $dno_data['reg_no_display'];
    }
    if ($dno_data) {
?>
        <br />
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-8">
                <p class="pdiv1">Diary No. : <?php echo $get_dno['dn'] . '/' . $get_dno['dy'] ?></p>
                <p class="pdiv1">Cause Title : <?= $dno_data['cause_title'] ?></p>
            </div>
            <script>
                var choice = "<?php echo $radio; ?>";

                var dno = "<?php echo $get_dno['dn']; ?>";
                var dyr = "<?php echo $get_dno['dy']; ?>";
                //alert(choice + dno + dyr);
                if (choice == 2) {
                    $('#dno').val(dno);
                    $('#dyr').val(dyr);
                }
            </script>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-7">
                <label for="regno" class="pdiv1">Registration No. Display&nbsp&nbsp&nbsp</label>
                <input type="text" id="regno" disabled class="regno" value="<?= $dno_data['reg_no_display']; ?>" style="width:60%;">
            </div>
            <?php

            ?>
            <div class="col-sm-1" id="edit_btn">
                <button type="button" id="edit_fld" style="margin-top:12px;" class="form-control btn btn-primary" onclick="edit_field(1)">Edit
            </div>
            <div class="col-sm-1" id="update_btn" style="display:none;">
                <button type="button" id="update_fld" style="margin-top:12px;" class="form-control btn btn-primary" onclick="update_data()">Update
            </div>
            <div class="col-sm-1" id="cancel_btn" style="display:none;">
                <button type="button" id="cancel_fld" style="margin-top:12px;" class="form-control btn btn-danger" onclick="edit_field(2)">Cancel
            </div>
        </div>
    <?php


    } else { ?>
        <script>
            var choice = "<?php echo $radio; ?>";
            var dyr = "<?= date('Y'); ?>";
            if (choice == 2) {
                $('#dno').val('');
                $('#dyr').val(dyr);
            }
        </script>
        <div class="alert alert-danger">
            <strong>Fail!</strong> Diary No. or Case No. doesn't exist .
        </div>

    <?php }
} elseif ($option == 2) {
    // pr($dno);die;
    $updateSuccess = $model_ia->updateRegistrationNumber($dno, $regno);
    if ($updateSuccess) { ?>
        <div class="alert alert-success">
            <strong>Success!</strong> Registration Number display updated Successfully.
        </div>
    <?php } else { ?>
        <div class="alert alert-danger">
            <strong>Fail!</strong> Updation Failed.
        </div>
<?php }
}
?>
<style>
    .pdiv1 {
        font-size: 18px;
    }

    .pdiv2 {
        font-size: 20px;
        font-weight: bolder;
        text-underline: black;
    }
</style>

<script>
    function edit_field(id) {
        if (id == 1) {
            $('#regno').prop('disabled', false);
            $('#edit_btn').hide();
            $('#update_btn').show();
            $('#cancel_btn').show();
        } else if (id == 2) {
            var org_reg = "<?= @$reg_no; ?>";
            $('#regno').val(org_reg);
            //document.getElementById("reg_no").value = org_reg;
            $('#regno').prop('disabled', true);
            $('#edit_btn').show();
            $('#update_btn').hide();
            $('#cancel_btn').hide();
        }

    }


    function update_data() {
        var radio = $("input[type='radio'][name='rad']:checked").val();
        var regno = $('#regno').val();
        var org_reg = "<?= @$reg_no; ?>";
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (regno) {
            if (org_reg == regno) {
                alert('New Registration number is same as old one. Updation Failed !!');
            } else {
                
                if ($("#dno").length) {
                    var diary_no = $('#dno').val() + $('#dyr').val();
                }
                else{
                    var diary_no = dno+dyr;
                }  
                
                $('#message').hide();

                var update_url = "<?php echo base_url('ARDRBM/IA/regno_display_change_process'); ?>";

                $.ajax({
                    type: "POST",
                    url: update_url,
                    data: {
                        diary_no: diary_no,
                        radio: radio,
                        option: 2,
                        regno: regno,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },

                    beforeSend: function() {
                        $('#image1').show();
                        $('#message').hide();
                    },

                    complete: function() {
                        $('#image1').hide();
                    },

                    success: function(data) {
                        updateCSRFToken();
                        $('.message').html(data);
                        $('#message').show();
                        $('.record').hide();
                    },

                    error: function() {
                        updateCSRFToken();
                        alert("Error");
                    }
                });
            }
        } else {
            alert('Please enter registration display number');
        }

    }
</script>