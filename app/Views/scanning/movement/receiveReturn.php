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
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Case Movement Module</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">                               
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="push-form" method="POST" action="">
                                                <?=csrf_field(); ?>
                                                <div class="row">
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
                                                            <input type="text" class="form-control bg-white list_date"
                                                                aria-describedby="list_date_addon" placeholder="Date..." readonly>
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
                                                        <!--XXXXXXXXXXXXXXXX-->
                                                        <div class="input-group col-3 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" >Diary Movement.<span style="color:red;">*</span></span>
                                                            </div>
                                                            <select class="form-control "  id="mvmnt_type_id">
                                                                <option value="ALL">ALL</option>
                                                                <option value="receive">Eligible To Receive</option>
                                                                <option value="return">Eligible To Return</option>
                                                                <option value="already_return">Already Returned</option>
                                                            </select>
                                                        </div>
                                                        <!--XXXXXXXXXXXXXXX-->
                                                        <div class="col-3 pl-2 mb-3">
                                                        <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block"
                                                            value="Search">
                                                        </div>  
                                                    </div>
                                                </div>
                                                <!-- Second Form Start Here -->
                                                <div class="row pt-3">
                                                    <div class="col-12 form-row">
                                                    <div class="input-group col-3 mb-3">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text" id="case_search_addon">Search By</span>
                                                        </div>
                                                        <div class="border">
                                                        <label class="radio-inline text-black mt-0 mb-0 p-1">
                                                            <input type="radio" name="rdbtn_select" id="radioct" value="1" checked> Case No.
                                                        </label>
                                                        <label class="radio-inline mt-0 mb-0 p-1">
                                                            <input type="radio" name="rdbtn_select" id="radiodn" value="0"> Diary No.
                                                        </label>
                                                        </div>
                                                    </div>
                                                    <div class="input-group col-3 mb-3 search_case">
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
                                                    <div class="input-group col-3 mb-3 search_case">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="caseno_addon">Case No.</span>
                                                        </div>
                                                        <input type="text" class="form-control" aria-describedby="caseno_addon" id="case_no" name="case_no" onkeypress="return isNumber(event)" maxlength="6">
                                                    </div>
                                                    <div class="input-group col-3 mb-3 search_case">
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
                                                        <input type="text" class="form-control" id="t_h_cno" name="t_h_cno" aria-describedby="diaryno_addon" onkeypress="return isNumber(event)" maxlength="5" />
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
                                                    <div class="input-group col-3 mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="movement_type_addon">Movement Type.<span style="color:red;">*</span></span>
                                                        </div>
                                                        <select class="form-control courtno" aria-describedby="movement_type_addon" id="mvmnt_type_case">
                                                            <option value="ALL">ALL</option>
                                                            <option value="receive">Eligible To Received</option>
                                                            <option value="return">Eligible To Return</option>
                                                            <option value="already_return">Already Returned</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-2 pl-4 mb-3">
                                                        <input id="btn_search_case" name="btn_search_case" type="button" class="btn btn-primary btn-block"
                                                            value="Search">
                                                    </div>
                                                </div>
                                                <!-- Second Form End Here -->
                                            </form>
                                            <div class="row col-md-12 m-0 p-0" id="result"></div>
                                            <div id="dv_data">
                                           
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).on("focus", ".list_date", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
    $("#btn_search").click(function() {
        $("#result").html("Record Not Found").css({
            "color": "red",
            "text-align": "center",
            "display":"block",
            "font-weight": "bold" // Optional for emphasis
        });
        return false;
        




        var movement_flag_type = $('#mvmnt_type_id').val();
        $("#result").html("");
        $('#show_error').html("");
        var list_date = $(".list_date").val();
        var courtno = $(".courtno").val();

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
        else {
            $.ajax({
                url: '<?= base_url('scanning/scanMoveProcess') ?>',  // Update this URL for CodeIgniter routing
                cache: false,
                async: true,
                data: {
                    search_flag: 'list_detail',
                    list_date: list_date,
                    courtno: courtno,
                    mainhead: mainhead,
                    movement_flag_type: movement_flag_type
                },
                beforeSend: function() {
                    $('#result').html('<table width="100%" align="center"><tr><td><img src="<?= base_url("images/load.gif") ?>" /></td></tr></table>');
                },
                type: 'POST',
                    success: function(data, status) {
                        $("#result").html(data.html).css({
                        "color": "red",
                        "text-align": "center",
                        "display":"block",
                        "font-weight": "bold" // Optional for emphasis
                    });
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }
    });
    $(document).on('click','#btn_search_case',function(){
        $("#result").html("Record Not Found").css({
            "color": "red",
            "text-align": "center",
            "display":"block",
            "font-weight": "bold" // Optional for emphasis
        });

    });




   
</script>