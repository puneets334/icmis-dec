<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Management Report >> Pending >> Year and Nature wise as on pending report of particular head</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $action = "";
                        $attribute = 'name="frm" id="frm" method="POST" onSubmit="return validate()"';
                        echo form_open($action, $attribute);
                        csrf_token();
                        ?>
                        <div id="dv_content1">
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <label for="">SELECT M/F </label>
                                    <select name="mf" id="mf" class="form-control" onChange="return get_subhead();">
                                        <option value="ALL">[M/F BOTH]</option>
                                        <option value="M">Motion</option>
                                        <option value="F">Final/Regular</option>
                                        <option value="N">Not in Motion & Final</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">Sub Heading</label>
                                    <div id="subhead_div">
                                        <select name="subhead[]" id="subhead" class="form-control" multiple="multiple" size="3">
                                            <option value="all" selected="selected">ALL SubHead</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">Subject</label>
                                    <select name="subject[]" id="subject" class="form-control" multiple="multiple" size="3" onChange="getcat(this.value)">
                                        <option value="all" selected="selected">ALL Subhead</option>
                                        <?php
                                        foreach ($subject as $row) {
                                            $m_dcode = $row['stagecode'] ?? '';
                                            if ($row['main_head'] == 'Y')
                                                $color = ';color:#0033FF';
                                            else
                                                $color = '';
                                            if ($row['subcode2'] == 0 &&  $row['subcode3'] == 0 && $row['subcode4'] == 0)
                                                $g = strtoupper($row['sub_name1']);
                                            elseif ($row['subcode3'] == 0 && $row['subcode4'] == 0)
                                                $g = strtoupper($row['sub_name1']) . ' > ' . strtoupper($row['sub_name4']);
                                            elseif ($row['subcode4'] == 0)
                                                $g = strtoupper($row['sub_name1']) . ' > ' . strtoupper($row['sub_name2']) . ' > ' . strtoupper($row['sub_name4']);
                                            elseif ($row['subcode4'] != 0)
                                                $g = strtoupper($row['sub_name1']) . ' > ' . strtoupper($row['sub_name2']) . ' > ' . strtoupper($row['sub_name3']) . ' > ' . strtoupper($row['sub_name4']);
                                            echo "<option value='" . $row['subcode1'] . "' style='font-size:9px" . $color . "'>" . $g . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">Subcat1</label>
                                    <div id="catdiv">
                                        <select name="cat[]" id="cat" class="form-control" multiple="multiple" size="3">
                                            <option value="all" selected="selected">ALL Category</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">Subcat2</label>
                                    <div id="subcatdiv">
                                        <select name="subcat[]" id="subcat" class="form-control" multiple="multiple" size="4">
                                            <option value="all" selected="selected">ALL Sub Category</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">Subcat3</label>
                                    <div id="subcat2div">
                                        <select name="subcat2[]" id="subcat2" class="form-control" multiple="multiple" size="4">
                                            <option value="all" selected="selected">ALL Sub Category</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Or Select Act</label>
                                <div id="subcatdiv">
                                    <select name="act[]" id="act" class="form-control" multiple="multiple" size="4">
                                        <option value="all" selected="selected">ALL Act</option>
                                        <?php
                                        foreach ($act as $row3) {
                                            echo  "<option value='" . $row3['id'] . "'>" . $row3['act_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <label for="">Case Stage</label>
                                <select name="case_status_id[]" id="case_status_id" class="form-control" style="width:100%">
                                    <option value='103' selected="selected">All Pending</option>
                                    <option value='101'>Defective Cases (Neither Registered nor Listed)</option>
                                    <option value='102'>Pendency of Registred Cases</option>
                                    <option value='104'>Unregistered Matters [Non-Defective & Listed Before Court](based on IAs permission to file SLP/ TP / Appeal, Condonation of delay in filing SLP / Appeal)</option>
                                    <option value='105'>Unregistered Matters [Non-Defective & Listed Before Chamber] (based on IAs Withdrawal Of Case, Exemption From Paying Court Fee, Exemption From Surrendering, Exemption From Filing Separate Certificate Of Surrender)</option>
                                    <option value='106'>Unregistered Matters [Defective & Listed before Court due to special directions / Orders]</option>
                                    <option value='107'>Unregistered Matters [Defective, I.A. for c/delay in refilling filed & Listed] [before Chamber (delay > 60 days)]</option>
                                    <option value='108'>Unregistered Matters [Defective, I.A. for c/delay in refilling filed & Listed] [before Registrar (delay <= 60 days)]</option>
                                    <option value='109'>Pendency of Registred Cases + Unregistered Matters [Listed Before Court/Chamber]</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-1 mt-4 pt-2">
                                        <label for="">Bench: </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for=""><br /> </label>
                                        <select name='bench' id='bench' class="form-control">
                                            <option value="all">[ALL]</option>
                                            <option value='2'>Coram 2</option>
                                            <option value='3'>Coram 3</option>
                                            <option value='5'>Coram 5</option>
                                            <option value='7'>Coram 7</option>
                                            <option value='9'>Coram 9</option>
                                            <option value='N'>Not in 2 3 5 7 9</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for=""> From Year:</label>
                                        <input type="text" name="from_year" class="form-control" id="from_year" value="<?php //echo $_POST['from_year'];  
                                                                                                                        ?>" size="4" maxlength="4">
                                    </div>
                                    <div class="col-md-2">
                                        <label for=""> To Year :</label>
                                        <input type="text" class="form-control" name="to_year" id="to_year" value="<?php //echo $_POST['to_year']; 
                                                                                                                    ?>" size="4" maxlength="4">
                                    </div>
                                    <div class="col-md-2">
                                        <label for=""> From filling date:</label>
                                        <input type="text" name="from_fil_dt " id="from_fil_dt" class="form-control dtp" size="10" maxlength="10">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">UPTO filling date:</label>
                                        <input type="text" name="upto_fil_dt" class="form-control dtp" id="upto_fil_dt" size="10" maxlength="10">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-5">
                                        <label for="">Act/Usec1/usec2/desc</label>
                                        <input type="text" name="act_msc" class="form-control" id="act_msc" value="<?php //echo $_POST['act_msc']; 
                                                                                                                    ?>" size="50">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Select Pet./Res.</label>
                                        <select name="pet_res" id="pet_res" class="form-control" >
                                            <option value="">Pet../Res. Both</option>
                                            <option value="P">Pet.</option>
                                            <option value="R">Res.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Party Name</label>
                                        <input type="text" name="party_name" class="form-control" id="party_name" value="<?php //echo $_POST['res'];  
                                                                                                                                                                                 ?>" size="40">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="">AS ON</label>
                                    <select name="ason_type" id="ason_type" class="form-control" onChange="return show_hide();">
                                        <option value="dt">based on disposed date</option>
                                        <option value="ent_dt">based on entry date</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for=""><br /></label>
                                    <span id="span_month" style="display:none">
                                        <b> Select Month</b>
                                        <select name="lst_month" class="form-control" id="lst_month">
                                            <option value="">[SELECT]</option>
                                            <option value="01" <?php // if ($_POST['lst_month'] == 1) echo "selected"; 
                                                                ?>> JANUARY</option>
                                            <option value="02" <?php // if ($_POST['lst_month'] == 2) echo "selected"; 
                                                                ?>> FEBRUARY</option>
                                            <option value="03" <?php // if ($_POST['lst_month'] == 3) echo "selected"; 
                                                                ?>>MARCH</option>
                                            <option value="04" <?php // if ($_POST['lst_month'] == 4) echo "selected"; 
                                                                ?>>APRIL</option>
                                            <option value="05" <?php // if ($_POST['lst_month'] == 5) echo "selected"; 
                                                                ?>>MAY</option>
                                            <option value="06" <?php // if ($_POST['lst_month'] == 6) echo "selected"; 
                                                                ?>>JUNE</option>
                                            <option value="07" <?php // if ($_POST['lst_month'] == 7) echo "selected"; 
                                                                ?>>JULY</option>
                                            <option value="08" <?php // if ($_POST['lst_month'] == 8) echo "selected"; 
                                                                ?>>AUGUST</option>
                                            <option value="09" <?php // if ($_POST['lst_month'] == 9) echo "selected"; 
                                                                ?>>SEPTEMBER</option>
                                            <option value="10" <?php // if ($_POST['lst_month'] == 10) echo "selected"; 
                                                                ?>>OCTOBER</option>
                                            <option value="11" <?php // if ($_POST['lst_month'] == 11) echo "selected"; 
                                                                ?>>NOVEMBER</option>
                                            <option value="12" <?php // if ($_POST['lst_month'] == 12) echo "selected"; 
                                                                ?>>DECEMBER</option>
                                        </select>
                                        Enter Year
                                        <input type="text" class="form-control" name="lst_year" id="lst_year" size="10" maxlength="10" value="<?php echo date('Y'); ?>">
                                    </span>
                                    <span id="span_disp_dt">
                                        <input type="text" name="til_date" id="til_date" class="form-control dtp" size="10" maxlength="10" value="<?php echo date('d-m-Y'); ?>"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Order by</label>
                                    <select name="order_by" id="order_by" class="form-control">
                                        <option value="case">Type,Year,No</option>
                                        <option value="da">DA</option>
                                        <option value="fil_dt">filing dt</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Print Adv_name</label>
                                    <select name="adv_opt" id="adv_opt" class="form-control">
                                        <option value="N">No</option>
                                        <option value="Y">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Report Type</label>
                                    <select name="rpt_type" id="rpt_type" class="form-control">
                                        <option value="year">Year and Nature wise</option>
                                        <option value="bench">Bench and Nature wise</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Rpt Purpose</label>
                                    <select id="rpt_purpose" name="rpt_purpose" class="form-control">
                                        <option value="sw">Statistical Purpose</option>
                                        <option value="cl">Cause List</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Special cases</label>
                                    <select name="spl_case" id="spl_case" class="form-control">
                                        <option value="n">NO special case</option>
                                        <option value="pc">PC act</option>
                                        <option value="women">Offence against women</option>
                                        <option value="children">Offence against children</option>
                                        <option value="land">Land immoveable property</option>
                                        <option value="cr_compound">Criminal Compondable cases</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">concept </label>
                                    <select name="concept" id="concept" class="form-control">
                                        <option value="new">motion + admitted= FH(Pre adm.)</option>
                                        <option value="old">old :motion=MH and Final=FH</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">main/connected</label>
                                    <select name="main_connected" id="main_connected" class="form-control">
                                        <option value="all">ALL</option>
                                        <option value="main">main</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-2">
                                <input type="button" class="btn btn-primary quick-btn" name="create" value="SHOW" onClick="return get_year_head_nature_wise_ason_rpt();" />
                            </div>
                        </div>
                        <div id="cntnt_pending_app" name="cntnt_pending_app" align="center"></div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
     $(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
    });

    function newPopup(url) {
        var popupWindow2 = window.open(
            url, 'popUpWindow2', 'height=700,width=800,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=yes,location=no,directories=no,status=yes')
        popupWindow2.focus();
    }
</script>

<script language="javascript" type="text/javascript">
    function closeData() {
        document.getElementById('ggg').scrollTop = 0;
        document.getElementById('dv_fixedFor_P').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";
    }

    function getXMLHTTP() {
        var xmlhttp = false;
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e1) {
                    xmlhttp = false;
                }
            }
        }
        return xmlhttp;
    }

    function getcat(subjectId) {
        with(document.frm) {
            var xhr1 = getXMLHTTP();
            var subject_count = 0;
            var subject_val = "";
            for (var i = 0; i < subject.options.length; i++) {
                if (subject.options[i].selected == 1) {
                    subject_count++;
                    subject_val = subject_val + subject.options[i].value + "";
                }
            }

            if (subject_count > 1 && subject.value == 'all') {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUBJECT BOX');
                subject.focus();
                return false;
            }
            var xhr1 = getXMLHTTP();
            var str = "<?php echo base_url('ManagementReports/Pending/getcat_multiple'); ?>?subject=" + subject_val + "&subject_length=" + subject_count;
            xhr1.open("GET", str, true);
            xhr1.onreadystatechange = function() {
                if (xhr1.readyState == 4 && xhr1.status == 200) {
                    var data = xhr1.responseText;
                    document.getElementById('catdiv').innerHTML = xhr1.responseText;
                }
            }
        }
        xhr1.send(null);
    }



    function getsubcat() {
        with(document.frm) {
            var xhr2 = getXMLHTTP();
            var subject_count = 0;
            var subject_val = "";
            var cat_count = 0;
            var cat_val = "";
            for (var i = 0; i < subject.options.length; i++) {
                if (subject.options[i].selected == 1) {
                    subject_count++;
                    subject_val = subject_val + subject.options[i].value + ",";
                }
            }
            for (var i = 0; i < cat.options.length; i++) {
                if (cat.options[i].selected == 1) {
                    cat_count++;
                    cat_val = cat_val + cat.options[i].value + ",";
                }
            }
            if (cat_count > 1 && cat.value == 'all') {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY BOX');
                cat.focus();
                return false;
            }
            var str = "<?php echo base_url('ManagementReports/Pending/getsubcat_mul'); ?>?subject=" + subject_val + "&subject_length=" + subject_count + "&cat=" + cat_val + "&cat_length=" + cat_count;
            xhr2.open("GET", str, true);
            xhr2.onreadystatechange = function() {
                if (xhr2.readyState == 4 && xhr2.status == 200) {
                    var data = xhr2.responseText;
                    document.getElementById('subcatdiv').innerHTML = xhr2.responseText;
                }
            }
        }
        xhr2.send(null);

    }


    function getsubcat2() {
        with(document.frm) {

            var xhr5 = getXMLHTTP();
            var subject_count = 0;
            var subject_val = "";
            var cat_count = 0;
            var cat_val = "";
            var subcat_count = 0;
            var subcat_val = "";

            for (var i = 0; i < subject.options.length; i++) {
                if (subject.options[i].selected == 1) {
                    subject_count++;
                    subject_val = subject_val + subject.options[i].value + ",";
                }
            }


            for (var i = 0; i < cat.options.length; i++) {
                if (cat.options[i].selected == 1) {
                    cat_count++;
                    cat_val = cat_val + cat.options[i].value + ",";
                }
            }

            for (var i = 0; i < subcat.options.length; i++) {
                if (subcat.options[i].selected == 1) {
                    subcat_count++;
                    subcat_val = subcat_val + subcat.options[i].value + ",";
                }
            }

            if (cat_count > 1 && cat.value == 'all') {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY BOX');
                cat.focus();
                return false;
            }
            var str = "<?php echo base_url('ManagementReports/Pending/getsubcat2_mul'); ?>?subcat=" + subcat_val + "&subcat_length=" + subcat_count;
            xhr5.open("GET", str, true);
            xhr5.onreadystatechange = function() {
                if (xhr5.readyState == 4 && xhr5.status == 200) {
                    var data = xhr5.responseText;
                    document.getElementById('subcat2div').innerHTML = xhr5.responseText;
                }
            }
        }

        xhr5.send(null);

    }

    function get_subhead() {
        var xhr3 = getXMLHTTP();
        with(document.frm) {
            var str = "<?php echo base_url('ManagementReports/Pending/get_subhead_for_ason'); ?>?m_f=" + mf.value;
            xhr3.open("GET", str, true);
            xhr3.onreadystatechange = function() {
                if (xhr3.readyState == 4 && xhr3.status == 200) {
                    var data = xhr3.responseText;
                    subhead_div.innerHTML = data;
                }
            }
        }
        xhr3.send(null);
    }


    function get_year_head_nature_wise_ason_rpt() {
        var xhr4 = getXMLHTTP();
        with(document.frm) {

            if (til_date.value == "") {
                alert("Please Enter  Date");
                til_date.focus();
                return false;
            }

            var first_index_date1 = til_date.value.indexOf('-');
            var second_index_date1 = til_date.value.indexOf('-', first_index_date1 + 1);
            if (first_index_date1 != 2 && second_index_date1 != 5) {
                alert('Please Enter valid Date in DD-MM-YYYY');
                til_date.focus();
                return false;
            }
            if (first_index_date1 == 2 && second_index_date1 == 5 && til_date.value.substr(0, 2) > 31) {
                alert('Please Enter valid Day in Date');
                til_date.focus();
                return false;
            }
            if (first_index_date1 == 2 && second_index_date1 == 5 && parseInt(til_date.value.substr(3, 2)) > 12) {
                alert('Please Enter valid Month in Date');
                til_date.focus();
                return false;
            }
            var subject_count = 0;
            var subject_val = "";
            var cat_count = 0;
            var cat_val = "";
            var subcat_count = 0;
            var subcat_val = "";
            var subcat2_count = 0;
            var subcat2_val = "";
            var case_status_id_count = 0;
            var case_status_id_val = "";

            for (var i = 0; i < subject.options.length; i++) {
                if (subject.options[i].selected == 1) {
                    subject_count++;
                    subject_val = subject_val + subject.options[i].value + ",";
                }
            }
            for (var i = 0; i < cat.options.length; i++) {
                if (cat.options[i].selected == 1) {
                    cat_count++;
                    cat_val = cat_val + cat.options[i].value + ",";
                }
            }
            for (var i = 0; i < subcat.options.length; i++) {
                if (subcat.options[i].selected == 1) {
                    subcat_count++;
                    subcat_val = subcat_val + subcat.options[i].value + ",";
                }
            }
            for (var i = 0; i < subcat2.options.length; i++) {
                if (subcat2.options[i].selected == 1) {
                    subcat2_count++;
                    subcat2_val = subcat2_val + subcat2.options[i].value + ",";
                }
            }
            if (cat_count > 1 && cat.value == 'all') {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY1 BOX');
                cat.focus();
                return false;
            }
            if (subcat_count > 1 && subcat.value == 'all') {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY2 BOX');
                subcat.focus();
                return false;
            }
            if (subcat2_count > 1 && subcat2.value == 'all') {
                alert('ERROR : Either select "ALL CATEGORY" Or other remaining option from SUB CATEGORY3 BOX');
                subcat.focus();
                return false;
            }
            var act_count = 0;
            var act_val = "";
            for (var i = 0; i < act.options.length; i++) {
                if (act.options[i].selected == 1) {
                    act_count++;
                    act_val = act_val + act.options[i].value + ",";
                }
            }
            if (ason_type.value == 'month' && lst_month.value == '') {
                alert('Plz Select Month');
                lst_month.focus();
                return false;
            }
            if (ason_type.value == 'month' && lst_year.value == '') {
                alert('Plz enter Year');
                lst_year.focus();
                return false;
            }
            var subhead_count = 0;
            var subhead_val = "";
            for (var i = 0; i < subhead.options.length; i++) {
                if (subhead.options[i].selected == 1) {
                    subhead_count++;
                    subhead_val = subhead_val + subhead.options[i].value + ",";
                }
            }
            if (subhead_count > 1 && subhead.value == 'all') {
                alert('ERROR : Either select "ALL" Or other remaining option');
                subhead.focus();
                return false;
            }
            for (var i = 0; i < case_status_id.options.length; i++) {
                if (case_status_id.options[i].selected == 1) {
                    case_status_id_count++;
                    case_status_id_val = case_status_id_val + case_status_id.options[i].value + ",";
                }
            }
            if (case_status_id_count > 1 && case_status_id.value == 'all') {
                alert('ERROR : Either select "ALL" Or other remaining option');
                case_status_id.focus();
                return false;
            }

            var str = "<?php echo base_url('ManagementReports/Pending/get_year_head_nature_wise_ason_rpt'); ?>?subhead=" + subhead_val + "&mf=" + mf.value + "&til_date=" + til_date.value + "&subject=" + subject_val + "&subject_length=" + subject_count + "&cat=" + cat_val + "&cat_length=" + cat_count + "&subcat=" + subcat_val + "&subcat_length=" + subcat_count + "&subcat2=" + subcat2_val + "&subcat2_length=" + subcat2_count + "&from_year=" + from_year.value + "&to_year=" + to_year.value + "&bench=" + bench.value + "&rpt_type=" + rpt_type.value + "&pet_res=" + pet_res.value + "&party_name=" + party_name.value + "&act_msc=" + act_msc.value + "&ason_type=" + ason_type.value + "&lst_month=" + lst_month.value + "&lst_year=" + lst_year.value + "&from_fil_dt=" + from_fil_dt.value + "&upto_fil_dt=" + upto_fil_dt.value + "&rpt_purpose=" + rpt_purpose.value + "&concept=" + concept.value + "&main_connected=" + main_connected.value + "&act=" + act_val + "&act_length=" + act_count + "&order_by=" + order_by.value +
                "&adv_opt=" + adv_opt.value + "&case_status_id=" + case_status_id_val + "&case_status_id_length=" + case_status_id_count;

            document.getElementById('cntnt_pending_app').innerHTML = '<img src="' + base_url + '/images/load.gif"/>';
            xhr4.open("GET", str, true);
            xhr4.onreadystatechange = function() {
                if (xhr4.readyState == 4 && xhr4.status == 200) {
                    var data = xhr4.responseText;
                    document.getElementById('cntnt_pending_app').innerHTML = data;
                }
            }
            xhr4.send(null);
        }
    }

    function show_hide() {
        with(document.frm) {
            if (ason_type.value == "month") {
                span_disp_dt.style.display = "none";
                span_month.style.display = "table-cell";
            } else {
                span_disp_dt.style.display = "table-cell";
                span_month.style.display = "none";
            }
        }
    }

    function validate() {
        with(document.frm) {
            if (date1.value == "") {
                alert("Please Enter From Date");
                date1.focus();
                return false;
            }
            var first_index_date1 = date1.value.indexOf('-');
            var second_index_date1 = date1.value.indexOf('-', first_index_date1 + 1);
            if (first_index_date1 != 2 && second_index_date1 != 5) {
                alert('Please Enter valid From Date in DD-MM-YYYY');
                date1.focus();
                return false;
            }
            if (first_index_date1 == 2 && second_index_date1 == 5 && date1.value.substr(0, 2) > 31) {
                alert('Please Enter valid Day in From Date');
                date1.focus();
                return false;
            }
            if (first_index_date1 == 2 && second_index_date1 == 5 && parseInt(date1.value.substr(3, 2)) > 12) {
                alert('Please Enter valid Month in From Date');
                date1.focus();
                return false;
            }
            if (date2.value == "") {
                alert("Please Enter To Date");
                date2.focus();
                return false;
            }
            var first_index_date2 = date2.value.indexOf('-');
            var second_index_date2 = date2.value.indexOf('-', first_index_date2 + 1);
            if (first_index_date2 != 2 && second_index_date2 != 5) {
                alert('Please Enter valid To Date in DD-MM-YYYY');
                date2.focus();
                return false;
            }
            if (first_index_date2 == 2 && second_index_date2 == 5 && date2.value.substr(0, 2) > 31) {
                alert('Please Enter valid Day in To Date');
                date2.focus();
                return false;
            }
            if (first_index_date2 == 2 && second_index_date1 == 5 && parseInt(date2.value.substr(3, 2)) > 12) {
                alert('Please Enter valid Month in To Date');
                date2.focus();
                return false;
            }
        }
    }

    function CallPrint(strid) {
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    }

    function open_tab(nature_wise_tot, subject, subject_length, cat, cat_length, subcat, subcat_length, year, skey,
    subhead, mf, til_date, from_year, to_year, rpt_type, pet_res, party_name, act_msc, lst_month, lst_year,
    ason_type, from_fil_dt, upto_fil_dt, rpt_purpose, spl_case, concept, main_connected, act, order_by,
    adv_opt, case_status_id, subcat2, subcat2_length) {
    const baseUrl = '<?php echo base_url(); ?>'; 

    const ggg = document.getElementById('ggg');
    ggg.style.width = 'auto';
    ggg.style.height = '550px';
    ggg.style.overflow = 'scroll';
    ggg.style.margin = '40px 18px 25px 18px';

    document.getElementById('dv_sh_hd').style.display = 'block';
    const fixedDiv = document.getElementById('dv_fixedFor_P');
    fixedDiv.style.display = 'block';
    fixedDiv.style.marginTop = '3px';

    // Loading indicator
    ggg.innerHTML = `<table width="100%" align="center">
        <tr><td><img src="${baseUrl}/images/load.gif"/></td></tr>
    </table>`;

    // Construct query parameters
    const params = new URLSearchParams({
        nature_wise_tot, subject, subject_length, cat, cat_length, subcat, subcat_length,
        year, skey, subhead, mf, til_date, from_year, to_year, rpt_type, pet_res, party_name,
        act_msc, lst_month, lst_year, ason_type, from_fil_dt, upto_fil_dt, rpt_purpose, spl_case,
        concept, main_connected, act, order_by, adv_opt, case_status_id, subcat2, subcat2_length
    });

    // Replace with your base URL from PHP
    const url = `${baseUrl}/ManagementReports/Pending/show_case_for_ason?${params.toString()}`;

    // Fetch data
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            ggg.innerHTML = data;
        })
        .catch(error => {
            ggg.innerHTML = `<p style="color:red;">Error loading data: ${error.message}</p>`;
        });
}


    $(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
    });
</script>
