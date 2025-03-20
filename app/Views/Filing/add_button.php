<div id="cl_center" class="cl_center" style="margin-top: 20px;margin-bottom: 20px;text-align:center">
    <form>
        <input type="button" name="btn_accept" class="btn btn-primary" id="btn_accept" value="Accept" class="cl_a_rv" onclick="verify_records('str_value', 'data_value', 'btn_accept')" /> &nbsp;&nbsp;
        <input type="button" name="btn_reverification" class="btn btn-primary" id="btn_reverification" value="Send to Tagging" class="cl_a_rv" style="<?php if ($tagging_user == 1) echo "display:none"; ?>" onclick="verify_records('str_value', 'data_value', 'btn_reverification')" />
</div>
<input type='hidden' name='hd_flag' id='hd_flag' value='1' />
<div id="dv_load" class="cl_center"></div>

<?php
$cat_code = '';

foreach ($cat as $cat_details) {
    $cat_code .= $cat_details->id;
}
if (!empty($cat_emp1)) {
    $cat_code = $cat_code . '~' . 't';
} else {
    $cat_code = $cat_code . '~' . 'f';
}

?>
<input type="hidden" name="sub_cate" id="sub_cate" value="<?php echo $cat_code ?>" />
</form>
<?php $tagging_user = 0;
?>
<?php
$hd_diary_nos = $_SESSION['filing_details']['diary_no'];
?>
<script>
    $(document).on('click', '.cl_a_rv', function() {
        var hd_tagging_user = $('#hd_tagging_user').val();
        var idd = $(this).attr('id');
        if (hd_tagging_user == 1) {
            chk_user(idd);
        } else {
            verify_records('', '', idd);
        }
    });

    function getSlide(str) {

        var ck_ca_sb = 0;
        if (document.getElementById('hd_ssno').value != '0') {
            cnt_data1 = parseInt(document.getElementById('hd_ssno').value) + 1;
            document.getElementById('hd_ssno').value = '0';
        }
        var hd_co_tot = document.getElementById('hd_co_tot').value;
        var idd = str.split('chk_sno');
        var subject = document.getElementById('hd_subcode' + idd[1]).value;
        var cat = document.getElementById('hd_subcodes' + idd[1]).value;
        var subcat = document.getElementById('hd_subcodess' + idd[1]).value;
        var main_id = document.getElementById('hd_id' + idd[1]).value;
        var exist = other_catg.includes(main_id);
        if (exist == true && document.getElementById(str).checked == true) {
            document.getElementById("otherdiv").style.display = "block";
            document.getElementById("ortext").value = '';

        }
        for (var i = 1; i <= hd_co_tot; i++) {

            if (document.getElementById('hd_sp_a' + i)) {

                if (main_id.trim() == document.getElementById('hd_sp_d' + i).value.trim()) {
                    ck_ca_sb = 1;
                }
            }
        }
        if (ck_ca_sb == 1) {
            alert("Already Selected");
        } else {

            var subject = document.getElementById('sp_subject' + idd[1]).innerHTML;
            var cat = document.getElementById('sp_category' + idd[1]).innerHTML;
            // alert(cat);
            var sp_sub_category = document.getElementById('sp_sub_category' + idd[1]).innerHTML;
            var sp_sub_sub_category = document.getElementById('sp_sub_sub_category' + idd[1]).innerHTML;
            var subcat = document.getElementById('sp_subcategory' + idd[1]).innerHTML;
            var sub_id = document.getElementById('hd_subcode' + idd[1]).value;
            var cat_id = document.getElementById('hd_subcodes' + idd[1]).value;
            var subcat_id = document.getElementById('hd_subcodess' + idd[1]).value;
            var hd_id_z = document.getElementById('hd_id' + idd[1]).value;
            var hd_color = document.getElementById('hd_color' + idd[1]).value;

            {
                var row0 = document.createElement("tr");
                row0.setAttribute('id', 'tr_uo' + cnt_data1);
                var column0 = document.createElement("td");
                var column1 = document.createElement("td");
                var column2 = document.createElement("td");
                var column3 = document.createElement("td");
                var column4 = document.createElement("td");
                var column5 = document.createElement("td");
                var column6 = document.createElement("td");
                var spAddObj = document.getElementById('tb_new');

                var hd_chk_add = document.createElement('input');
                hd_chk_add.setAttribute('type', 'checkbox');
                hd_chk_add.setAttribute('id', 'hd_chk_add' + cnt_data1);
                hd_chk_add.setAttribute('onclick', 'getDone_upd_cat(this.id);');

                var colors = '';
                if (hd_color == 's')
                    colors = 'cl_supreme';
                else
                    colors = 'cl_other';
                var hd_id_txtcnt = document.createElement('span');
                hd_id_txtcnt.setAttribute('id', 'sp_b' + cnt_data1);
                hd_id_txtcnt.setAttribute('class', colors);
                var sp = document.createElement('span');
                sp.setAttribute('id', 'sp_c' + cnt_data1);
                sp.setAttribute('class', colors);
                var sp_e = document.createElement('span');
                sp_e.setAttribute('id', 'sp_e' + cnt_data1);
                sp_e.setAttribute('class', colors);
                var sp_f = document.createElement('span');
                sp_f.setAttribute('id', 'sp_f' + cnt_data1);
                sp_f.setAttribute('class', colors);
                var chkbx = document.createElement('span');
                chkbx.setAttribute('id', 'sp_d' + cnt_data1);
                chkbx.setAttribute('class', colors);
                var hd_1 = document.createElement('input');
                hd_1.setAttribute('type', 'hidden');
                hd_1.setAttribute('id', 'hd_sp_a' + cnt_data1);
                var hd_2 = document.createElement('input');
                hd_2.setAttribute('type', 'hidden');
                hd_2.setAttribute('id', 'hd_sp_b' + cnt_data1);
                var hd_3 = document.createElement('input');
                hd_3.setAttribute('type', 'hidden');
                hd_3.setAttribute('id', 'hd_sp_c' + cnt_data1);
                column0.appendChild(hd_chk_add);
                var hd_4 = document.createElement('input');
                hd_4.setAttribute('type', 'hidden');
                hd_4.setAttribute('id', 'hd_sp_d' + cnt_data1);
                column0.appendChild(hd_chk_add);
                column0.appendChild(hd_1);
                column0.appendChild(hd_2);
                column0.appendChild(hd_3);
                column0.appendChild(hd_4);
                row0.appendChild(column0);
                column4.appendChild(chkbx);
                row0.appendChild(column4);
                column2.appendChild(hd_id_txtcnt);
                row0.appendChild(column2);
                column3.appendChild(sp);
                row0.appendChild(column3);
                column5.appendChild(sp_e);
                row0.appendChild(column5);
                column6.appendChild(sp_f);
                row0.appendChild(column6);
                var tb_res = document.getElementById('tb_new');
                tb_res.appendChild(row0);
                document.getElementById('sp_b' + cnt_data1).innerHTML = subject;
                if (cat_id == '0')
                    document.getElementById('sp_c' + cnt_data1).innerHTML = '-';
                else
                    document.getElementById('sp_c' + cnt_data1).innerHTML = cat;
                if (subcat_id == '0')
                    document.getElementById('sp_d' + cnt_data1).innerHTML = '-';
                else
                    document.getElementById('sp_d' + cnt_data1).innerHTML = subcat;
                document.getElementById('sp_e' + cnt_data1).innerHTML = sp_sub_category;
                document.getElementById('sp_f' + cnt_data1).innerHTML = sp_sub_sub_category;
                document.getElementById('hd_sp_a' + cnt_data1).value = sub_id;
                document.getElementById('hd_sp_b' + cnt_data1).value = cat_id;
                document.getElementById('hd_sp_c' + cnt_data1).value = subcat_id;
                document.getElementById('hd_sp_d' + cnt_data1).value = hd_id_z;
                document.getElementById('hd_chk_add' + cnt_data1).checked = true;;
                document.getElementById('hd_co_tot').value = cnt_data1;
                cnt_data1++;
                cnt_sno++;
                var hd_ck_cf_natue = $('#hd_ck_cf_natue').val();
                if (hd_ck_cf_natue == 0)
                    get_court_fee();
            }
        }
        document.getElementById(str).checked = false;
    }

    function verify_records(str, data, idd) {

        var buttons = document.querySelectorAll('#cl_center input[type="button"]');
        buttons.forEach(function(button) {
            button.disabled = true;
        });
        var other_catg = ['10', '20', '46', '75', '87', '101', '115', '129', '141', '151', '163', '182', '201', '215', '227', '250', '259', '262', '270', '276', '289', '295', '300', '304', '311'];

        var hd_diary_nos = '<?php echo $hd_diary_nos; ?>';
        var flag = $('#hd_flag').val();
        var cat_code = '<?php echo $cat_code ?>';
        var cat_arr = cat_code.split('~');
        var category_code = cat_arr[0];
        var other_cat_rem = cat_arr[1];
        var exist = other_catg.includes(category_code);
        // console.log(category_code );
        var id_val = '';
        if (idd == 'btn_accept')
            id_val = 0;
        else if (idd == 'btn_reverification')
            id_val = 1;
        var d_year = hd_diary_nos.substr(hd_diary_nos.length - 4);
        var d_no = hd_diary_nos.substr(0, (hd_diary_nos.length) - 4);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
       
        if (exist == false || (exist == true && other_cat_rem == 't')) {
            $.ajax({
                url: "<?php echo base_url('Filing/Verification/update_verification'); ?>",
                cache: false,
                async: true,
                data: {
                    hd_diary_nos: hd_diary_nos,
                    id_val: id_val,
                    str: str,
                    c_diary: data,
                    hd_flag: flag,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                },
                beforeSend: function(xhr) {
                    $("#result_main").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },
                type: 'POST',
                success: function(data, status) {
                    alert(data);
                    updateCSRFToken();

                    if (data === 'Caveat matched in this case and Proof of service application not filed yet.') {
                        alert('DA WILL NOT ALLOT AND PROPOSAL WILL NOT MADE IN THIS CASE');
                        get_def_rec();
                    } else {
                        if (id_val == 0) {
                            // find_and_set_da(d_no, d_year);
                        } else {
                            location.reload();

                        }
                    }
                    location.reload();

                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }
            });
        } else {
            alert("Subject Category is updated as OTHERS. Please update or add description of OTHERS by clicking on edit in  the Category.");
        }

    }

    function get_def_rec() {
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');
        if ($("#radioct").is(':checked')) {
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();

            if (!regNum.test(cstype)) {
                alert("Please Select Casetype");
                $("#selct").focus();
                return false;
            }
            if (!regNum.test(csno)) {
                alert("Please Fill Case No in Numeric");
                $("#case_no").focus();
                return false;
            }
            if (!regNum.test(csyr)) {
                alert("Please Fill Case Year in Numeric");
                $("#case_yr").focus();
                return false;
            }
            if (csno == 0) {
                alert("Case No Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if (csyr == 0) {
                alert("Case Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }

        } else if ($("#radiodn").is(':checked')) {
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            if (!regNum.test(diaryno)) {
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if (!regNum.test(diaryyear)) {
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if (diaryno == 0) {
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if (diaryyear == 0) {
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
        } else {
            alert('Please Select Any Option');
            return false;
        }
        // var d_yr=hd_diary_no.substr(-4);
        //   var d_no=hd_diary_no.substr(0,(hd_diary_no.length)-4);


        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Filing/Verification/get_verification_dup'); ?>",

                beforeSend: function(xhr) {
                    $("#dv_dup").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
                },
                data: {
                    d_no: diaryno,
                    d_yr: diaryyear,
                    ct: cstype,
                    cn: csno,
                    cy: csyr
                }
            })
            .done(function(msg) {
                $("#dv_dup").html(msg);
                //$('#hd_diary_nos').val(diaryno+diaryyear);

                if ($('.cl_center').html().trim() != '<b>Matter is Disposed</b>') {
                    if ($('.cl_center').html().trim() != '<b>No Record Found</b>') {
                        if ($('.cl_center').html().trim() != '<b>Record Already Verified</b>') {
                            if ($('.cl_center').html().trim() != '<b>Matter is unregistered and Interlocutary Application not found</b>') {
                                if ($('.cl_center').html().trim() != '<b>No Record Found</b>') {
                                    add_button(diaryno, diaryyear);
                                    $("#edit_lb").html("<input type=button value='Edit' id='editcoram'/>");
                                }
                            }
                        }
                    }
                }

            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
            });


    }
</script>