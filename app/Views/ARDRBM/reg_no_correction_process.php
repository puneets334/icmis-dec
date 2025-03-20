<?php
if ($option == 1) {

    if ($radio == 2) {

        $get_dno = $model_ia->searchDiary2($ctype, $cno, $cyr);
        if ($get_dno) {
            $diary_no = $get_dno['dn'] . $get_dno['dy'];
        } else {
            $get_dno = $model_ia->getSearchDiary2($ctype, $cno, $cyr);
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
    //var_dump($dno_data[2]);
    if ($dno_data) {
?>
        <br />
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-8">
                <p class="pdiv1">Diary No. : <?php echo $get_dno['dn'] . '/' . $get_dno['dy'] ?></p>
                <p class="pdiv1">Cause Title : <?= $dno_data['cause_title'] ?></p>
                <p class="pdiv1">Case Status : <?php if ($dno_data['c_status'] == 'P') {
                                                    echo "Pending";
                                                }
                                                if ($dno_data['c_status'] == 'D') {
                                                    echo "Disposed";
                                                } ?></p>
                <?php
                $lowerct_res = $model_ia->getSearchLowerct($diary_no);
                $lowerct_challenging_count = count($lowerct_res);

                if ($lowerct_challenging_count > 0) {
                ?>
                    <p class="pdiv1">Total Challenging No. : <?= $lowerct_challenging_count; ?></p>
                <?php }
                if ($dno_data['active_fil_no'] == null or $dno_data['active_fil_no'] == '' or $dno_data['active_fil_no'] == 0) {
                ?>
                    <div class="alert alert-danger">
                        <strong>Fail!</strong> Matter is not registered.
                    </div>
                <?php
                }
                ?>
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
            <div class="col-sm-10">
                <label for="is_lct" class="pdiv1"> Do you Want to Unchallenge Lower Court Details? &nbsp&nbsp&nbsp</label>
                <input type="checkbox" id="is_lct" name="is_lct" onclick="checkbox_lowerct();" /> YES &nbsp&nbsp&nbsp <span id="checkbox_div" style="display:none; width: 100%;"></span>

            </div>
            <!--<div id="checkbox_div"  style="display:none; width: 100%; "></div>-->

        </div>

        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <label for="lowerct" class="pdiv1"> Select Lower Court Case No. &nbsp&nbsp&nbsp</label>
                <select id="lowerct" name="lowerct" multiple onclick="select_lowerct();">
                    <?php
                    if ($lowerct_challenging_count > 0) {
                        foreach ($lowerct_res as $lowerct_data) {
                    ?>
                            <option value="<?= $lowerct_data['lower_court_id']; ?>"><?= $lowerct_data['caseno']; ?> </option>
                    <?php }
                    }
                    ?>

                </select>
                <span id="lowerct_div" style="display:none; width: 100%;"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-7">
                <label for="regno" class="pdiv1">Registration No. Display&nbsp&nbsp&nbsp</label>
                <input type="text" id="regno" disabled class="regno" value="<?= $dno_data['reg_no_display']; ?>"
                    style="width:60%;">
                <input type="hidden" id="hid_active_fil_no" value="<?= $dno_data['active_fil_no']; ?>" />
                <input type="hidden" id="hid_fil_no" value="<?= $dno_data['fil_no']; ?>" />
            </div>

        </div>
        <?php if ($lowerct_challenging_count > 1) {
            // echo $dno_data[4].' <  ';
            $a = explode('-', $dno_data['active_fil_no']);
            $count = ltrim($a[2], '0') - ltrim($a[1], '0') + 1;
            // echo " $lowerct_challenging_count ".$lowerct_challenging_count;
            // echo " count ".$count;
            if ($lowerct_challenging_count != $count) {

        ?>
                <div class="alert alert-danger">
                    <strong>Fail!</strong> Total no. of Challenging lowercourt details is not equal to Registered No. count. Cannot Update!!!
                </div>
            <?php exit();
            } ?>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-2">
                    <label for="btn">&nbsp</label>
                    <button type="button" id="update_fld" class="form-control btn btn-primary" onclick="update_data()">
                        UPDATE Registration No.
                </div>
            </div>
        <?php
        }
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
    $dno = $diary_no;

    $lowerct = '';
    $lowerct_count = count($lowerct_no);
    foreach ($lowerct_no as $l) {
        $lowerct = $lowerct . ',' . $l;
    }
    $lowerct = ltrim($lowerct, ',');

    $active_fil_no = $active_fil_no;
    $fil_no = $fil_no;

    $a = explode('-', $active_fil_no);

    $casetype = ltrim($a[0], '0');
    $caseno_start_range = $a[1];
    $caseno_end_range = $a[2];
    $caseno_end_range_new = str_pad($caseno_end_range - $lowerct_count, 6, '0', STR_PAD_LEFT);

    $active_fil_no_new = '';
    $reg_no_display_new = '';
    $condition = '';
    $length = 0;

    $reg_no_display_old = ltrim($caseno_start_range, '0') . '-' . ltrim($caseno_end_range, '0');

    if ($caseno_start_range == $caseno_end_range_new) {
        $active_fil_no_new = $a[0] . '-' . $caseno_start_range;
        $condition = $condition . " active_fil_no=>'" . $active_fil_no_new . "',";
        $reg_no_display_new = ltrim($caseno_start_range, '0');
    } else {
        $length = $caseno_end_range_new - $caseno_start_range;
        $active_fil_no_new = $a[0] . '-' . $caseno_start_range . '-' . $caseno_end_range_new;
        $condition = $condition . " active_fil_no=>'" . $active_fil_no_new . "',";
        $reg_no_display_new = ltrim($caseno_start_range, '0') . '-' . ltrim($caseno_end_range_new, '0');
    }
    $length = $caseno_end_range_new - $caseno_start_range;
    /*echo " caseno_end_range_new ".$caseno_end_range_new;
    echo " caseno_start_range ".$caseno_start_range;
    echo " length is >> ".$length;*/


    if ($active_fil_no == $fil_no) {
        $condition = $condition . " fil_no=>'" . $active_fil_no_new . "',";
    } else {
        $condition = $condition . " fil_no_fh=>'" . $active_fil_no_new . "',";
        $b = explode('-', $fil_no);
        /*echo " b0 is ".$b[0]."\n";
        echo " b1 is ".$b[1]."\n";
        echo " b2 is ".$b[2]."\n";*/
        if (count($b) == 2)
            $length1 = 0;
        else {
            if ($b[1] != $b[2] && $b[2] != '')
                $length1 = 1;
            else
                $length1 = $b[2] - $b[1];
        }

        if ($length1 >= 1) {
            $casetype = ltrim($b[0], '0');
            $caseno_start_range = $b[1];
            $caseno_end_range = $b[2];
            $caseno_end_range_new = str_pad($caseno_end_range - $lowerct_count, 6, '0', STR_PAD_LEFT);
            if ($caseno_start_range == $caseno_end_range_new) {
                $fil_no_new = $b[0] . '-' . $caseno_start_range;
                $condition = $condition . " fil_no=>'" . $fil_no_new . "',";
            } else {
                $fil_no_new = $b[0] . '-' . $caseno_start_range . '-' . $caseno_end_range_new;
                $condition = $condition . " fil_no=>'" . $fil_no_new . "',";
            }
            $update_main_history1 = $model_ia->updateHistory($dno, $filNoNew, $filNo);
            $update_main_history2 = $model_ia->updateCasetypeHistory($dno, $filNoNew, $filNo);
        }
    }

    $user_ip = $this->request->getIPAddress();

    if ($is_lct == 1) {
        $lowerCtHistoryData = [
            // Assuming you have the same columns in lowerct_history
            'user_id' => $sessionUserId,
            'created_at' => date('Y-m-d H:i:s'),
            'user_ip' => $userIp,
            // Add your fields from lowerct
        ];
        $model_ia->insertLowerCtHistory($lowerCtHistoryData);
        $result1 = $model_ia->updateLowerCt($lowerct);
    }

    if ($is_lct != 1 || $result1) {
        $result2 = $model_ia->updateMain($condition, $dno, $reg_no_display_old, $reg_no_display_new);
    }

    if ($result2) {
        $result3 = $model_ia->updateCasetypeHistory2($dno, $active_fil_no_new, $active_fil_no);
    }

    if ($result3) { ?>
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
            var org_reg = "<?= $reg_no; ?>";
            $('#regno').val(org_reg);
            //document.getElementById("reg_no").value = org_reg;
            $('#regno').prop('disabled', true);
            $('#edit_btn').show();
            $('#update_btn').hide();
            $('#cancel_btn').hide();
        }

    }

    function checkbox_lowerct() {
        checkBox = document.getElementById('is_lct');
        if (checkBox.checked) {
            document.getElementById('checkbox_div').innerHTML = "<font color='green' style='font-size:140%;'>Lower Court Will Also Be Updated</font>";
            $('#checkbox_div').show();
        } else {
            document.getElementById('checkbox_div').innerHTML = '';
            $('#checkbox_div').hide();
        }
    }

    function select_lowerct() {

        var lowerct_count = document.getElementById("lowerct").length;
        var lowerct_selected = $('#lowerct option:selected').length;
        //alert(lowerct_count);
        //alert($('#lowerct option:selected').length);
        if (lowerct_count == lowerct_selected && lowerct_count != 1) {
            alert('All lowercourt data can not be selected!!');
            //$("#lowerct:selected").removeAttr("selected");
            $("#lowerct option").prop("selected", false);
            $('#lowerct_div').hide();
            document.getElementById("lowerct").focus();
            return;
        }
        if (lowerct_selected == 0) {
            alert('Select at least one lowercourt!!');
            document.getElementById("lowerct").focus();
            return;
        }
        //alert(document.getElementById("regno").value);
        //alert(lowerct_selected)

        if (lowerct_selected > 0 && lowerct_selected != lowerct_count) {
            var b = document.getElementById("regno").value.split("-");
            //alert(" b "+b);
            var c = b[1];
            //alert(" c "+c);
            var d = c.split("/");
            var e = d[0] - lowerct_selected;
            //alert(" e "+e);
            var result = document.getElementById("regno").value.replace(d[0], e);
            // var a=document.getElementById("regno").value+' Will be updated from '+lowerct_count+' no. to '+lowerct_selected+' no. !!';
            var a = document.getElementById("regno").value + ' Will be updated as ' + result + ' !!';
            document.getElementById('lowerct_div').innerHTML = "<font color='green' style='font-size:140%;'>" + a + "</font>";
            $('#lowerct_div').show();
        }
    }

    function update_data() {
        var radio = $("input[type='radio'][name='rad']:checked").val();
        var regno = $('#regno').val();
        var is_lct;
        /* if(document.getElementById("is_lct").checked == che){
             is_lct=1;
         }
         alert(is_lct);*/


        checkBox = document.getElementById('is_lct');
        // Check if the element is selected/checked
        if (checkBox.checked) {
            // Respond to the result
            is_lct = 1;
            //alert(is_lct);

        }

        var org_reg = "<?= $reg_no; ?>";
        var lowerct = $('#lowerct').val();
        var lowerct_count = document.getElementById("lowerct").length;
        var lowerct_selected = $('#lowerct option:selected').length;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        //alert(lowerct_count);
        //alert($('#lowerct option:selected').length);
        if (lowerct_count == lowerct_selected) {
            alert('All lowercourt data can not be unchallenged!!');
            document.getElementById("lowerct").focus();
            return;
        }
        if (lowerct_selected == 0) {
            alert('Select at least one lowercourt!!');
            document.getElementById("lowerct").focus();
            return;
        }
        var active_fil_no = $('#hid_active_fil_no').val();
        var fil_no = $('#hid_fil_no').val();

        /*if(regno)
        {
            if(org_reg == regno)
            {
                alert('New Registration number is same as old one. Updation Failed !!');
            }else
            {*/
        var diary_no = $('#dno').val() + $('#dyr').val();
        $('#message').hide();

        var update_url = "<?php echo base_url('ARDRBM/IA/reg_no_correction_process'); ?>";

        var result = confirm("Are you sure to change registration and lowercourt details?");
        if (result == true) {
            $.ajax({
                type: "POST",
                url: update_url,
                data: {
                    diary_no: diary_no,
                    radio: radio,
                    option: 2,
                    regno: regno,
                    lowerct: lowerct,
                    active_fil_no: active_fil_no,
                    fil_no: fil_no,
                    lowerct_count: lowerct_count,
                    is_lct: is_lct,
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

                    $('.message').html(data);
                    $('#message').show();
                    $('.record').hide();
                },

                error: function() {
                    alert("Error");
                }
            });
        }
        /* }
        }
        else {
            alert('Please enter registration display number');
        }*/

    }
</script>