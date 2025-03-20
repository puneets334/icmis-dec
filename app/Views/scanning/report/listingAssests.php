<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p id="show_error"></p>
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Cause List (With OR ROP, Inclusion etc.)</h3>
                            </div>
                            <div class="col-sm-2">
                           
                            </div>
                        </div>
                    </div>
                    <form id="push-form" method="POST" action="">
                        <!-- <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" /> -->
                        <?=csrf_field(); ?>
                        <div class="row mt-4">
                        

                            <div class="form-row col-12">


                                <div class="input-group col-3 mb-3">

                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="mainhead_addon">List Type</span>
                                    </div>
                                    <div class="border">
                                    <label class="radio-inline text-black ml-1 mt-0 mb-0 p-1">
                                        <input type="radio" name="mainhead_select" id="radio_m" value="M" checked> Misc.
                                    </label>
                                    <label class="radio-inline mt-0 mb-0 p-1">
                                        <input type="radio" name="mainhead_select" id="radio_f" value="F"> Regular
                                    </label>
                                    </div>
                                </div>

                                <div class="input-group col-3 mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="list_date_addon">List Date</span>
                                    </div>
                                    <input type="date" id="fromDate" name="fromDate" class="form-control list_date" required    placeholder="Date..." style="display:block;">

                                    <!-- <input type="text" class="form-control bg-white list_date"
                                            aria-describedby="list_date_addon" placeholder="Date..." readonly> -->
                                </div>

                                <div class="input-group col-3 mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="courtno_addon">Court No.<span style="color:red;">*</span></span>
                                    </div>
                                    <select class="form-control courtno" aria-describedby="courtno_addon">
                                        <option value="0">-Select-</option>
                                        <?php
                                        $options = [
                                            31 => "1 (VC)", 32 => "2 (VC)", 33 => "3 (VC)", 34 => "4 (VC)", 35 => "5 (VC)",36 => "6 (VC)", 37 => "7 (VC)", 38 => "8 (VC)", 39 => "9 (VC)", 40 => "10 (VC)",41 => "11 (VC)", 42 => "12 (VC)", 43 => "13 (VC)", 44 => "14 (VC)", 45 => "15 (VC)", 46 => "16 (VC)", 47 => "17 (VC)",61 => "1 (VC R1)", 62 => "2 (VC R2)",1 => "1", 2 => "2", 3 => "3", 4 => "4", 5 => "5",  6 => "6", 7 => "7", 8 => "8", 9 => "9", 10 => "10",  11 => "11", 12 => "12", 13 => "13", 14 => "14", 15 => "15",   16 => "16", 17 => "17", 21 => "21 (Registrar)", 22 => "22 (Registrar)"
                                        ];

                                        foreach ($options as $value => $label) {
                                            echo "<option value=\"$value\">$label</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div style="clear:both;"></div>
                                <div class="col-2 pl-4 mb-3">
                                    <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block"
                                        value="Search" style="position: relative;top: 38px;">
                                </div>
                            </div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12 form-row">
                                <div class="input-group col-3 mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="case_search_addon">Search By</span>
                                    </div>
                                    <div class="border">
                                    <label class="radio-inline text-black ml-1 mt-0 mb-0 p-1">
                                        <input type="radio" name="rdbtn_select" id="radioct" value="1" checked> Case No.
                                    </label>
                                    <label class="radio-inline mt-0 mb-0 p-1">
                                        <input type="radio" name="rdbtn_select" id="radiodn" value="0"> Diary No.
                                    </label>
                                    </div>
                                </div>
                                <div class="input-group col-2 mb-3 search_case">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="selct_addon">Case Type</span>
                                    </div>
                                    <select id="selct" name="selct" class="form-control" aria-describedby="selct_addon">
                                        <option value="-1">Select</option>
                                        <?php  
                                            if(count($caseType) > 0){
                                                foreach($caseType as $c) {
                                                    ?>
                                                        <option value="<?=$c['casecode'];?>"><?=$c['casename'];?></option>                                   
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="input-group col-2 mb-3 search_case">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="caseno_addon">Case No.</span>
                                    </div>
                                    <input type="text" class="form-control" aria-describedby="caseno_addon" id="case_no" name="case_no" onkeypress="return isNumber(event)" maxlength="6">
                                </div>
                                <div class="input-group col-2 mb-3 search_case">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="caseyr_addon">Case Year</span>
                                    </div>
                                    <select id="case_yr"  aria-describedby="caseyr_addon" class="form-control">
                                        <?php
                                        $currently_selected = date('Y');
                                        $earliest_year = 1950;
                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="input-group col-3 mb-3 search_diary">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="diaryno_addon">Diary No.</span>
                                    </div>
                                    <input type="text" class="form-control" id="t_h_cno" name="t_h_cno" aria-describedby="diaryno_addon" onkeypress="return isNumber(event)" maxlength="5">
                                </div>
                                <div class="input-group col-3 mb-3 search_diary">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="diaryyr_addon">Diary Year</span>
                                    </div>

                                    <select id="t_h_cyt" aria-describedby="diaryyr_addon"  class="form-control">
                                        <?php
                                        $currently_selected = date('Y');
                                        $earliest_year = 1950;
                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-2 pl-4 mb-3">
                                    <input id="btn_search_case" name="btn_search_case" type="button" class="btn btn-primary btn-block"
                                            value="Search" style="position: relative;top: 38px;">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row col-md-12 m-0 p-0" id="result"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $("#reportTable1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": ["excel", "pdf"]
        });
    });

    /* List Type   Searching*/

    $("#btn_search").click(function(){

        $("#result").html(""); $('#show_error').html("");
        var list_date = $(".list_date").val();
        var courtno = $(".courtno").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


        if ($("#radio_m").is(':checked')) {
            var mainhead = $("#radio_m").val();
        }
        if ($("#radio_f").is(':checked')) {
            var mainhead = $("#radio_f").val();
        }
        
        if (list_date.length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select cause list date</strong></div>');
            $("#from_date").focus();
            return false;
        }
        else if (courtno == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select court number</strong></div>');
            $("#applicant_type").focus();
            return false;
        }
        else{
            $.ajax({
                url: "<?php echo base_url('scanning/listTypeAssetsSearch'); ?>",
                cache: false,
                async: true,
                data: {
                    search_flag:'list_detail',list_date:list_date,courtno:courtno,mainhead:mainhead, CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend:function(){
                    $('#result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $("#result").html(data.html).css({
                        "color": "red",
                        "text-align": "center",
                        "display":"block",
                        "font-weight": "bold" // Optional for emphasis
                    });
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }
    });


    function get_case_details_page()
    {
        var d_no = document.getElementById('t_h_cno').value;
        var d_yr = document.getElementById('t_h_cyt').value;
        var regNum = new RegExp('^[0-9]+$');
        var chk_status = 0;
        var cstype, csno, csyr, fno;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if ($("#radioct").is(':checked')) {
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();
            chk_status = 1;
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
            var t_h_cno = $('#t_h_cno').val();
            var t_h_cyt = $('#t_h_cyt').val();
            chk_status = 2;
            if (t_h_cno.trim() == '') {
                alert("Please enter Diary No.");
                $('#t_h_cno').focus();
                return false;
            }
            if (t_h_cyt.trim() == '') {
                alert("Please enter Diary Year");
                $('#t_h_cyt').focus();
                return false;
            }
            fno = t_h_cno + t_h_cyt;
        }

        $.ajax({
            url: "<?php echo base_url('scanning/searchTypeAssetsSearch'); ?>",

            cache: false,
            async: true,
            beforeSend: function () {
                $('#result').html('<table width="100%" align="center"><tr><td>Loading...</td></tr></table>');
            },
            data: { 
                search_flag: 'case', 
                d_no: d_no, 
                d_yr: d_yr, 
                fno: fno, 
                ct: cstype, 
                cn: csno, 
                cy: csyr, 
                chk_status: chk_status ,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $("#result").html(data.html).css({
                    "color": "red",
                    "text-align": "center",
                    "display":"block",
                    "font-weight": "bold" // Optional for emphasis
                });
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    $(document).on('click','#btn_search_case',function(){
            get_case_details_page();

    });



    


    
</script>