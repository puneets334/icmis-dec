<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <table bgcolor="#FBFFFD" class="table tbl_hr table-hover" border="0" cellpadding="3" align="center">
                                <tr>
                                    <td width="150px" bgcolor='#F4F5F5'>Diary No.</td>
                                    <td><b><span id="cs<?php echo $diary_no ?>"><?php echo $real_diaryno; ?></span></b></td>
                                </tr>
                                <tr>
                                    <td>Case No.</td>
                                    <td>
                                        <div width='100%'><b><?php echo $real_caseno; ?></b></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor='#F4F5F5'>Petitioner</td>
                                    <td><b><?php echo $p; ?></b></td>
                                </tr>
                                <tr>
                                    <td bgcolor='#F4F5F5'>Respondant</td>
                                    <td><b><?php echo $r; ?></b></td>
                                </tr>
                                <tr>
                                    <td bgcolor='#F4F5F5'>Petitioner Advocate(s)</td>
                                    <td><b><?php echo $padv; ?></b></td>
                                </tr>
                                <tr>
                                    <td bgcolor='#F4F5F5'>Respondant Advocate(s)</td>
                                    <td><b><?php echo $radv; ?></b></td>
                                </tr>
                                <tr>
                                    <td bgcolor='#F4F5F5'>Case Category</td>
                                    <td><b><?php echo $category; ?></b></td>
                                </tr>
                                <tr>
                                    <td bgcolor='#F4F5F5'>Status</td>
                                    <td><b><?php echo $cstatus; ?></b></td>
                                </tr>
                                <tr>
                                    <td bgcolor='#F4F5F5'>Last Order</td>
                                    <td><b>
                                            <font color='blue'><?php echo $lastorder; ?></font>
                                        </b></td>
                                </tr>
                                <?php if ($text_list_before != "") { ?>
                                    <tr>
                                        <td bgcolor='#F4F5F5'>LIST BEFORE</td>
                                        <td>
                                            <font color='green'><b><?= $text_list_before; ?></b></font>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if ($text_list_not_before != "") { ?>
                                    <tr>
                                        <td bgcolor='#F4F5F5'>NOT LIST BEFORE</td>
                                        <td>
                                            <font color='red'><b><?= $text_list_not_before; ?></b></font>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php foreach ($listings as $row_listed_after) { ?>
                                    <tr>
                                        <td bgcolor='#F4F5F5'>List On</td>
                                        <td>
                                            <font style='font-weight:bold;color:red;'><span class='blink_me' style='font-size:20px;'>Case is listed on <font color='blue' style='font-size:20px;'><?= $row_listed_after["next_dt"] ?></font> before <font color='blue' style='font-size:20px;'>[ <?php echo stripcslashes($row_listed_after["judgename1"]) ?> ]</font>. PLEASE DO NOT ADD/REMOVE CONNECTED CASES.<span class='blink_me'></font>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <table border='1' class='table tbl_hr table-hover' cellspacing='0' cellpadding='3'>
                            <tr bgcolor='#EDF0EE'>
                                <td align='center' colspan='9'>
                                    <font color='red'><b>CASES</b></font>
                                </td>
                            </tr>
                            <tr bgcolor='#F4F5F5'>
                                <td align='center' width='50px'><input type='checkbox' name='all' id='all' value='' <?= (isset($connected_cases[0]["t_checked_disabled"]) && $connected_cases[0]["t_checked_disabled"]) ? 'disabled=disabled' : ''; ?>></td>
                                <td><b>Diary No.</b></td>
                                <td><b>Case No.</b></td>
                                <td><b>Petitioner vs. Respondant</b></td>
                                <td><b>Category</b></td>
                                <td align='center'><b>Status</b></td>
                                <td><b>Before/Not Before</b></td>
                                <td align='center'><b>List</b></td>
                                <td align='center'><b>Linked/ connected</b></td>
                            </tr>
                            <?php foreach ($connected_cases as $row_conn) { ?>
                                <tr>
                                    <td align='center'><input type='checkbox' name='ccchk<?= $row_conn["diary_no"]; ?>' id='ccchk<?= $row_conn["diary_no"]; ?>' value='<?= $row_conn["diary_no"]; ?>' <?= ($row_conn["t_checked_disabled"]) ? 'disabled=disabled' : ''; ?>></td>
                                    <td><b><span id='cn<?= $row_conn["diary_no"]; ?>'><?= $row_conn["real_diary_no"]; ?></span></b></td>
                                    <td><?= $row_conn["real_caseno"]; ?></td>
                                    <td><?= $row_conn["pet_name"]; ?> vs.<br><?= $row_conn["res_name"]; ?></td>
                                    <td><?php // $row_conn["cccat"]; 
                                        ?></td>
                                    <td align='center'><b><?= $row_conn["t_cs"]; ?></b></td>
                                    <td><?= $row_conn["t_bfnbf"]; ?></td>
                                    <td align='center'><b><?= $row_conn["list"]; ?></b></td>
                                    <td align='center'><b><?= $row_conn["t_ct"] ?></b></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="row form-group">
                        <label for="selct1" class="col-4 col-form-label text-right">Case Type:</label>
                        <div class="col-4">
                            <select id="selct1" <?= ($case_type_disabled) ? 'disabled=disabled' : ''; ?>>
                                <option value="-1">Select</option>
                                <?php foreach ($case_types as $ct_rw) { ?>
                                    <option value="<?php echo $ct_rw['casecode'] ?>" selected><?php echo $ct_rw['short_description']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-4 col-form-label text-right">Order Date:</label>
                        <div class="col-4">
                            <input type="date" name="dtd" id="dtd" size="10" style="font-family:verdana; font-size:9pt;" <?= ($case_type_disabled) ? 'disabled=disabled' : ''; ?> <?php if ($order_date != '') { ?>value="<?php echo $order_date; ?> " disabled <?php } ?> />
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-4 col-form-label text-right"></label>
                        <div class="col-4">
                            <button type="button" class="btn btn-primary" id="add" onclick="generate_case();" <?= ($case_type_disabled) ? 'disabled=disabled' : ''; ?>>Generate</button>
                        </div>
                    </div>
                    <!-- <input class="dtp" type="text" value="<?php /*//print $dtd; */ ?>" name="dtd" id="dtd" size="10" style="font-family:verdana; font-size:9pt;" <?php /*print $t_checked1;*/ ?> >-->

                    <?php if ($order_date != '') { ?>
                        <div style="text-align: center;margin-top: 20px">
                            <font style="color: darkgreen;font-weight: bold">
                                Note: If actual order date doesn't match with the date displayed above, Please update Previous Court Remarks in the actual Order date.
                            </font>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php echo csrf_field(); ?>    
</section>
<script>
    $(document).on('click', '#all', function() {
        $("input[type='checkbox'][name^='ccchk']").each(function() {
            chk_val = $(this).val();
            if (document.getElementById("all").checked) {
                if (!($(this).is(':disabled'))) {
                    $(this).prop('checked', true);
                }
            } else
                $(this).prop('checked', false);
        });
    });

    function compareDate(txt_order_dt) {
        var date = txt_order_dt.substring(0, 2);
        var month = txt_order_dt.substring(3, 5);
        var year = txt_order_dt.substring(6, 10);

        var dateToCompare = new Date(year, month - 1, date);

        var currentDate = new Date();

        if (dateToCompare > currentDate) {
            alert("Registration Order date cannot be greater than Today's Date ");
            $('#txt_order_dt').focus();
            return false;
        }
    }

    async function generate_case() {
        
        await updateCSRFTokenSync();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var url = "<?= base_url("Judicial/Registration/generate_case_no"); ?>";

        var qte_array = new Array();
        var cn = "";
        var ct = $('#selct1').val();
        var dtd = $('#dtd').val();

        $("input[type='checkbox'][name^='ccchk']").each(function() {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked) {
                cn += $('#cn' + $(this).val()).html() + ", ";
                qte_array.push($(this).val());
            }
        });
        if (qte_array.length == 0) {
            alert("Select atleast One Case");
            return;
        } else if ($('#selct1').val() <= 0) {
            alert("Select Case Type");
            return;
        } else if (dtd == '') {
            alert("Select Order Date");
            return;
        } else {
            //alert(dtd);
            compareDate(dtd);
            cn = cn.substr(0, cn.length - 2);

            // swal({
            //     title: "",
            //     text: "Are you sure you want to generate case no. \n" + cn,
            //     type: "warning",
            //     showCancelButton: true,
            //     confirmButtonColor: "#DD6B55",
            //     confirmButtonText: "OK",
            //     cancelButtonText: "Cancel",
            //     closeOnConfirm: true,
            //     closeOnCancel: true
            // }, function(isConfirm) {
                if (confirm("Are you sure you want to generate case no. \n"+cn)) {
                    $("#add").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            qte: qte_array,
                            ct: ct,
                            dtd: dtd,
                            CSRF_TOKEN: CSRF_TOKEN_VALUE
                        },
                        success: function(data) {
                            
                            $("#add").prop('disabled', false);
                            
                            // alert(data);

                            if(data.html != undefined) {
                                document.getElementById("dv_res1").innerHTML = data.html;
                            }

                            // getDetails(); 

                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
            // });
        }
    }
</script>