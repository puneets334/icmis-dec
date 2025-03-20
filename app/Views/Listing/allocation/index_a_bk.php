<?= view('header') ?>
<style>
    fieldset {
        padding: 5px;
        background-color: #F5FAFF;
        border: 1px solid #0083FF;
    }

    legend {
        background-color: #E2F1FF;
        width: 100%;
        text-align: center;
        border: 1px solid #0083FF;
        font-weight: bold;
    }

    #customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    .class_red {
        color: red;
    }
    .class_green {
        color:green;
    }
    .table3, .subct2, .subct3, .subct4{display:none;}
    .align_center{text-align: center;}
    .row {display: flex;}
    .jud_all_al, .do_allotm {flex: 1;}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">ALLOCATION MODULE</h3>
                            </div>


                        </div>
                    </div>

                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div style="text-align: center">
                                <!-- <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ADVANCE ALLOCATION MODULE</span> -->
                                <?php

                                ?>
                                <table border="0" align="center">
                                    <tr valign="middle">
                                        <td>
                                            <fieldset>
                                                <legend>Pool/Advance</legend>
                                                <select class="ele" name="pool_adv" id="pool_adv">
                                                    <option value="ALL">ALL</option>
                                                    <option value="P">Pool</option>
                                                    <option value="A">Advance</option>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset>
                                                <legend>Listing Date</legend>
                                                <?php

                                                $holiday_str = next_holidays();
                                                $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                                $next_court_work_day = date("d-m-Y", strtotime(next_court_working_date($cur_ddt)));
                                                
                                                ?>
                                                <input type="text" size="10" class="dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />

                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset>
                                                <legend>Mainhead</legend>
                                                <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;
                                                <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;
                                            </fieldset>
                                        </td>
                                        <td style="display: none;">
                                            <fieldset>
                                                <legend>No. of Judges</legend>
                                                <select class="ele" name="sitting_judges" id="sitting_judges">
                                                    <option value="0">All</option>
                                                    <option value="1">1</option>
                                                    <option value="2" selected>2</option>
                                                    <option value="3">3</option>
                                                    <option value="5">5</option>
                                                    <option value="7">7</option>
                                                    <option value="9">9</option>
                                                    <option value="11">11</option>
                                                    <option value="13">13</option>
                                                    <option value="15">15</option>
                                                </select>
                                            </fieldset>
                                        </td>

                                        <td>
                                            <fieldset>
                                                <legend>Bench</legend>

                                                <input type="radio" name="bench" id="bench" value="R">Reg.&nbsp;
                                                <input type="radio" name="bench" id="bench" value="J" checked="checked">Court&nbsp;
                                                <input type="radio" name="bench" id="bench" value="S">SingleJudge &nbsp;
                                                <input type="radio" name="bench" id="bench" value="C">Chamber&nbsp;


                                                <!--<input type="radio" name="bench" id="bench" value="A">All-->
                                            </fieldset>
                                        </td>
                                        <td style="display:none;">
                                            <fieldset>
                                                <legend>Diary/Reg</legend>
                                                <input type="radio" name="diary_reg" id="diary_reg" value="D" title="Diary No.">Diary&nbsp;
                                                <input type="radio" name="diary_reg" id="diary_reg" value="R" title="Registration No." checked="checked">Reg. No.&nbsp;
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset>
                                                <legend>Civil/Criminal</legend>
                                                <input type="checkbox" name="civil_criminal" id="civil" value="C" checked="checked">Civil&nbsp;
                                                <input type="checkbox" name="civil_criminal" id="criminal" value="R" checked="checked">Criminal&nbsp;
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset>
                                                <legend>Is NMD</legend>
                                                <select class="ele" name="is_nmd" id="is_nmd">
                                                    <option value="0" selected>ALL</option>
                                                    <option value="N">No</option>
                                                    <option value="Y">Yes</option>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset>
                                                <legend>More</legend>
                                                <label class="toggle_btn">+</label>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>

                                <table border="0" align="center">
                                    <tr valign="top">
                                        <td class="subhead_class">
                                            <fieldset>
                                                <legend>Subhead</legend>
                                                <select name="subhead" id="subhead" multiple="multiple" size="8">
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php if (!empty($subheadings)): ?>
                                                        <?php foreach ($subheadings as $subheading): ?>
                                                            <option value="<?= esc($subheading['stagecode']) ?>">
                                                                <?= esc(str_replace(["[", "]"], "", $subheading['stagename'])) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No subheadings available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </fieldset>
                                        </td>


                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Purpose of Listing</b></legend>
                                                <select name="listing_purpose" id="listing_purpose" multiple="multiple" size="8">
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php if (!empty($purposes)) { ?>

                                                        <?php foreach ($purposes as $purpose) { ?>

                                                            <option value="<?= esc($purpose['code']); ?>">

                                                                <?= $purpose["code"].'. '.esc($purpose["purpose"]); ?>

                                                            </option>

                                                        <?php }} ?>

                                                    </select>
                                            </fieldset>



                                        </td>
                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Case Type</b></legend>
                                                <select name="case_type"  id="case_type" multiple="multiple" size="8">
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php if (!empty($caseTypes)): ?>
                                                        <?php foreach ($caseTypes as $caseType): ?>
                                                            <?php
                                                            $background = ($caseType['nature'] === 'C') ? '#c8fbe7' : '#f7cad2';
                                                            $description = str_replace("No.", "", $caseType['short_description']);
                                                            ?>
                                                            <option style="background: <?= esc($background); ?>;" value="<?= esc($caseType['casecode']); ?>">
                                                                <?= esc($description); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No case types available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>

                                <table border="0" class="table_sub_cat" align="center">
                                    <tr>
                                        <td style="width:83%">
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Subject Main Category</b></legend>
                                                <select name="subject_cat" id="subject_cat" multiple="multiple" size="10">
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php if (!empty($submasters)) : ?>
                                                        <?php foreach ($submasters as $row) : ?>
                                                            <?php
                                                            // Determine the background color and spacing based on subcode values
                                                            if ($row["subcode2"] == 0 && $row["subcode3"] == 0 && $row["subcode4"] == 0) {
                                                                $bgcolor = "background: #FF7F50;";
                                                                $spaces = "";
                                                            } elseif ($row["subcode2"] > 0 && $row["subcode3"] == 0 && $row["subcode4"] == 0) {
                                                                $bgcolor = "background: #FFE4C4;";
                                                                $spaces = "&nbsp;&nbsp;";
                                                            } elseif ($row["subcode2"] > 0 && $row["subcode3"] > 0 && $row["subcode4"] == 0) {
                                                                $bgcolor = "background: #CCFFCC;";
                                                                $spaces = "&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            } elseif ($row["subcode2"] > 0 && $row["subcode3"] > 0 && $row["subcode4"] > 0) {
                                                                $bgcolor = "background: #CCFFAA;";
                                                                $spaces = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            }
                                                            ?>
                                                            <option style="<?= $bgcolor; ?>" value="<?= esc($row["id"]); ?>">
                                                                <?= $spaces . $row["old_sc_c_kk"] . ' - ' . esc($row["sub_name4"]); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <option>No data available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td style="float:left">
                                            <fieldset>
                                                <legend>Reg. Years</legend>
                                                <select class="ele" name="from_yr" id="from_yr">
                                                    <option value="0" selected>ALL</option>
                                                    <?php
                                                    $curYear = date('Y');
                                                    for ($i = $curYear; $i >= 1950; $i--) {
                                                    ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                TO&nbsp;
                                                <select class="ele" name="to_yr" id="to_yr">
                                                    <option value="0" selected>ALL</option>
                                                    <?php
                                                    $curYear = date('Y');
                                                    for ($i = $curYear; $i >= 1950; $i--) {
                                                    ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </fieldset>
                                            <br>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Reg./Unreg.</b></legend>
                                                <select class="ele" name="reg_unreg" id="reg_unreg">
                                                    <option value="1">With Unreg.</option>
                                                    <option value="2">Only Reg.</option>
                                                </select>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>


                                <table border="0" class="table3" align="center">
                                    <tr valign="top">
                                        <td>
                                            <fieldset>
                                                <legend>Keyword</legend>
                                                <select class="ele" name="kword[]" id="kword" multiple="multiple" size="8" <!--style="padding:5px; width:300px;"-->>
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php foreach ($getKeywords as $keyword): ?>
                                                        <option value="<?= esc($keyword['id']) ?>"><?= esc($keyword['keyword_description']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset>
                                                <legend>IA</legend>
                                                <select class="ele" name="ia[]" id="ia" multiple="multiple" size="5" <!--style="padding:5px; width:400px;"-->>
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php foreach ($getDocs as $doc): ?>
                                                        <option value="<?= esc($doc['doccode1']) ?>"><?= esc($doc['docdesc']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </fieldset>
                                        </td>
                                    </tr>
                                    <tr valign="top">

                                        <td>
                                            <fieldset>
                                                <legend>Act</legend>
                                                <select class="ele" name="act[]" id="act" multiple="multiple" size="5" <!--style="padding:5px; width:500px;"-->>
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php foreach ($getActs as $act): ?>
                                                        <option value="<?= esc($act['id']) ?>"><?= esc($act['act_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset>
                                                <legend>Section</legend>
                                                <input type="text" size="10" name='section' id='section' value=""/> <!--style="padding:5px; width:200px;"--> 
                                            </fieldset>
                                        </td>



                                    </tr>
                                </table>
                                <input type="hidden" id="md_name" name="md_name" value="allocation" />
                                <input type="radio" name="main_supp" id="main_supp" value="1" title="Main Cause List" checked="checked">Main Cause List&nbsp;
                                <input type="radio" name="main_supp" id="main_supp" value="2" title="Supplementary Cause List">Supplementary Cause List&nbsp;
                                <input type="button" name="bt1" id="bt1" value="Get Records" class="btn btn-primary" />
                            </div>
                            <div id="dv_res2"></div>
                            <div id="dv_res1"></div>
                    </form>
                    <!--<div id="jud_all_al" class="jud_all_al"></div>
                    <div class="do_allotm" ></div>-->
                    <div class="row mt-5">
                        <div id="jud_all_al" class="jud_all_al">
                        <fieldset>
                        <legend style="text-align:center;color:#4141E0; font-weight:bold;">CORAM</legend>
                        <?php
                        $cur_ddt = date('Y-m-d', strtotime(' +1 day'));   
                        $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
                        //echo $next_court_work_day;  
                        $mf = "M";
                        $jud_count = "2";
                        $board_type = "J";
                            get_allocation_judge($mf,$next_court_work_day,$jud_count,$board_type); ?> 
                         </fieldset>    
                        </div>
                        <div class="do_allotm"></div>
                    </div>
            </div>

         
        </div>
    </div>
</section>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->

<!-- jQuery UI -->
<!--<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">-->
<!--<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>-->

<script>
        $( document ).ready(function() {    
        $("input[name='mainhead']").change(function(){
            //alert('vkg');
            var list_dt = $("#ldates").val();
            var sitting_judges = $("#sitting_judges").val();
            var mainhead = ""; var bench = "";
            $('input[type=radio]').each(function () {           
                if($(this).attr("name")=="bench" && this.checked)
                    bench = $(this).val();
                if($(this).attr("name")=="mainhead" && this.checked)
                    mainhead = $(this).val();
            });
        ddd(list_dt,mainhead,sitting_judges,bench);
        }); 

        //$(document).on("change","#ldates",function(){
        $(document).on("changeDate","#ldates",function(){
            var list_dt = $("#ldates").val();
            var sitting_judges = $("#sitting_judges").val();
            var mainhead = ""; 
            var bench = "";
            $('input[type=radio]').each(function () {           
                if($(this).attr("name")=="bench" && this.checked)
                    bench = $(this).val();
                if($(this).attr("name")=="mainhead" && this.checked)
                    mainhead = $(this).val();
            });
        ddd(list_dt,mainhead,sitting_judges,bench);
        });
        $(document).on("change","#sitting_judges",function(){
            var list_dt = $("#ldates").val();
            var sitting_judges = $("#sitting_judges").val();
            var mainhead = ""; var bench = "";
            $('input[type=radio]').each(function () {           
                if($(this).attr("name")=="bench" && this.checked)
                    bench = $(this).val();
                if($(this).attr("name")=="mainhead" && this.checked)
                    mainhead = $(this).val();
            });
        ddd(list_dt,mainhead,sitting_judges,bench);
        }); 
        $("input[name='bench']").change(function()
        {
            var list_dt = $("#ldates").val();
            var sitting_judges = $("#sitting_judges").val();
            var mainhead = ""; var bench = "";
            $('input[type=radio]').each(function () {           
                if($(this).attr("name")=="bench" && this.checked)
                    bench = $(this).val();
                if($(this).attr("name")=="mainhead" && this.checked)
                    mainhead = $(this).val();
            });
          
        ddd(list_dt,mainhead,sitting_judges,bench);
        });
        function ddd1(list_dt,mainhead,sitting_judges,bench){
                alert('test..');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            
                $.ajax({
                  
                    url: "<?php echo base_url('Listing/Allocation/get_allocation_judges_p/'); ?>",
                   
                    data: {list_dt: list_dt, mainhead:mainhead, sitting_judges:sitting_judges, bench:bench,CSRF_TOKEN: CSRF_TOKEN_VALUE},
                beforeSend:function(){
                   // $('.jud_all_al').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                    type: 'POST',
                    success: function(data, status) {     
                        updateCSRFToken();           
                    $('#jud_all_al').html(data);
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                        updateCSRFToken();
                    }
                });
        }
        });
        
        
        function chkall1(e){
        var elm=e.name;	
        if(document.getElementById(elm).checked)
        {
        $('input[type=checkbox]').each(function () {
        if($(this).attr("name")=="chk")
        this.checked=true;
        });
        }
        else
        {
        $('input[type=checkbox]').each(function () {
        if($(this).attr("name")=="chk")
        this.checked=false;
        });  
        }

        }
        $(document).on("change","input[name='main_supp']",function()
        {
            var main_supp = "";
            var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('input[type=radio]').each(function () {           
                if($(this).attr("name")=="main_supp" && this.checked)
                main_supp = $(this).val();
            });
                $.ajax({
                   
                    url: "<?php echo base_url('Listing/Allocation/get_listing_purps/'); ?>",
                  
                    data: {main_supp: main_supp,CSRF_TOKEN: CSRF_TOKEN_VALUE},
                    beforeSend:function(){
                    //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                    },
                    type: 'POST',
                    success: function(data, status) {  
                        updateCSRFToken();
                    
                    $('#listing_purpose').html(data);
                    },
                    error: function(xhr) {
                        updateCSRFToken();
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });        
        }); 
</script>

<script>



$(document).on("focus","#section",function(){
    var act = $("#act").val();
    $("#section").autocomplete({
        source:"../common/get_section_autoc.php?act="+act,
        width: 400,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

//var unavailableDates = ["9-3-2020","14-3-2020","15-3-2020"];

var unavailableDates = [<?=$holiday_str;?>];

function unavailable(date) {
    dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
    if ($.inArray(dmy, unavailableDates) == -1) {
        return [true, ""];
    } else {
        return [false,"","Unavailable"];
    }
}

//maxDate : 'today',
// $(document).on("focus",".dtp",function(){
//     $('.dtp').datepicker({minDate: -180, beforeShowDay: unavailable, dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
//     });
// });
$( document ).ready(function() {

    $("#bt1").click(function(){
        get_record_t();
    });


    $(".toggle_btn").click(function(){
        var tg_btn = $(".toggle_btn").html();
        $(".table3").toggle(1000);
        $("#ia option:selected").prop("selected", false)
        $("#act option:selected").prop("selected", false)
        $("#ia option:eq(0)").prop('selected', true);
        $("#act option:eq(0)").prop('selected', true);
        $("#section").val("");
        if(tg_btn == "+"){
            $(".toggle_btn").html("-");
        }
        else{
            $(".toggle_btn").html("+");
        }
    });

    $("input[name='mainhead']").change(function(){
        if($(this).attr("name")=="mainhead" && this.checked)
            mainhead = $(this).val();
        if(mainhead == 'M'){
            $("#subhead").prop("disabled", false);
        }
        else{
            $("#subhead").prop("disabled", true);
            $("#subhead option:selected").prop("selected", false)
            $("#subhead option:eq(0)").prop('selected', true);
        }
    });





    $("input[name='civil_criminal']").change(function(){
        var ct_parm1 = ""; var ct_parm2 = ""; var ct_parm = "";
        if($('#civil').is(":checked") == true)
        {
            ct_parm1 = $('#civil').val();
        }
        if($('#criminal').is(":checked") == true)
        {
            ct_parm2 = $('#criminal').val();
        }
        if(ct_parm1 == 'C' && ct_parm2 == 'R'){
            ct_parm = "";
        }
        else{
            if(ct_parm1 == 'C'){
                ct_parm = "C";
            }
            if(ct_parm2 == 'R'){
                ct_parm = "R";
            }
        }
      

    });


});
function get_record_t(){
    var pool_adv = $("#pool_adv").val();
    var md_name = $("#md_name").val();
    var list_dt = $("#ldates").val();
    var is_nmd = $("#is_nmd").val();
    var forFixedDate = $('input[name=forFixedDate]').is(':checked')
    var sitting_judges = $("#sitting_judges").val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    get_diary_reg();
    get_mainhead();
    var ct_parm1 = ""; var ct_parm2 = ""; var civil_criminal = "";
    if($('#civil').is(":checked") == true){ ct_parm1 = $('#civil').val(); }
    if($('#criminal').is(":checked") == true){  ct_parm2 = $('#criminal').val(); }
    if(ct_parm1 == 'C' && ct_parm2 == 'R'){ civil_criminal = ""; }
    else{
        if(ct_parm1 == 'C'){ civil_criminal = "C"; }
        if(ct_parm2 == 'R'){ civil_criminal = "R"; }
    }
    get_bench();
    var from_yr = $("#from_yr").val();
    var to_yr = $("#to_yr").val();
    var subhead = $("#subhead").val();
    var listing_purpose = $("#listing_purpose").val();
    var case_type = $("#case_type").val();
    var subject_cat = $("#subject_cat").val();
    var ia = $("#ia").val();
    var act = $("#act").val();
    var kword = $("#kword").val();
    var section = $("#section").val();
    var reg_unreg = $("#reg_unreg").val();
    var part_no = ""; var roster_judges_id = ""; var main_supp = "1";
    if(md_name == "allocation"){
        $('input[type=radio]').each(function () {
            if($(this).attr("name")=="main_supp" && this.checked)
                main_supp = $(this).val();
        });
        cchk = "";
        $('input[type=checkbox]').each(function () {
            if($(this).attr("name")=="chk" && this.checked)
                cchk+= $(this).val() + "JG";
        });
        roster_judges_id = cchk;
    }
    if(md_name == "transfer"){
        part_no = $('#part_no').val();
        roster_judges_id = $("#coram_from").val();
        if(part_no == '-1'){ $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="class_red">Please select judge and Enter Part No.</table>'); return false;  }
    }
    $.ajax({
       

        url: "<?php echo base_url('Listing/Allocation/get_records/'); ?>",
       
        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,pool_adv:pool_adv,list_dt: list_dt,diary_reg: get_diary_reg(),mainhead: get_mainhead(),civil_criminal: civil_criminal,bench: get_bench(),from_yr:from_yr,to_yr:to_yr,subhead:subhead,listing_purpose:listing_purpose,case_type:case_type,subject_cat:subject_cat,kword:kword,ia:ia,act:act,section:section,sitting_judges:sitting_judges,md_name:md_name,part_no:part_no,roster_judges_id:roster_judges_id,main_supp:main_supp,reg_unreg:reg_unreg,is_nmd:is_nmd,forFixedDate:forFixedDate},
        beforeSend:function(){
            if(md_name == 'pool' || md_name == 'transfer'){
                //$('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            }
            else{
                //$('.do_allotm').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            }

        },
        type: 'POST',
        success: function(data, status) {
            updateCSRFToken();
            if(md_name == 'pool' || md_name == 'transfer'){
                $('#dv_res1').html(data);
            }
            else{
                $('#dv_res2').html("");
                $('.do_allotm').html(data);

            }
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });

}



function ddd_to_tran(list_dt,mainhead,sitting_judges,bench){
    var list_dt = $('#list_dt').val();  
    var mainhead = $('#mainhead').val(); 
    var sitting_judges = $('#sitting_judges').val(); 
    var bench = $('#bench').val(); 
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        url: "<?php echo base_url('Listing/Allocation/get_ros_to_tans_p/'); ?>",
       
        data: {list_dt: list_dt, mainhead:mainhead, sitting_judges:sitting_judges, bench:bench,CSRF_TOKEN: CSRF_TOKEN_VALUE},
        beforeSend:function(){
            $('.jud_all_tran').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {
            updateCSRFToken();
            $('.jud_all_tran').html(data);
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}
//do allotment
$(document).on("click","#doa",function(){
    $("#doa").hide();
    var pool_adv = $("#pool_adv").val();
    var md_name = $("#md_name").val();
    var list_dt = $("#ldates").val();
    var is_nmd = $("#is_nmd").val();
    var sitting_judges = $("#sitting_judges").val();
    get_diary_reg();
    get_mainhead();
    var ct_parm1 = ""; var ct_parm2 = ""; var civil_criminal = "";
    if($('#civil').is(":checked") == true){ ct_parm1 = $('#civil').val(); }
    if($('#criminal').is(":checked") == true){  ct_parm2 = $('#criminal').val(); }
    if(ct_parm1 == 'C' && ct_parm2 == 'R'){ civil_criminal = ""; }
    else{
        if(ct_parm1 == 'C'){ civil_criminal = "C"; }
        if(ct_parm2 == 'R'){ civil_criminal = "R"; }
    }
    get_bench();
    var from_yr = $("#from_yr").val();
    var to_yr = $("#to_yr").val();
    var subhead = $("#subhead").val();
    var listing_purpose = $("#listing_purpose").val();
    var case_type = $("#case_type").val();
    var subject_cat = $("#subject_cat").val();
    var ia = $("#ia").val();
    var act = $("#act").val();
    var kword = $("#kword").val();
    var section = $("#section").val();
    var reg_unreg = $("#reg_unreg").val();
    /////////////////////////////////
    var noc = $("#noc").val();
    var partno = $("#partno").val();
    var cchk = "";
    $('input[type=checkbox]').each(function () {
        if($(this).attr("name")=="chk" && this.checked)
            cchk+= $(this).val() + "JG";
    });
    if(cchk == ""){
        $('#dv_res2').html('<table width="100%" align="center"><tr><td class="class_red align_center">Select atleast one bench</table>');
        $("#doa").show();
        return false;
    }
    else if(isEmpty(document.getElementById('partno'))){ $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Part No.</table>'); $("#doa").show(); return false;  }
    else if(isEmpty(document.getElementById('noc'))){ $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Number of Cases Per Bench</table>'); $("#doa").show(); return false; }
    else{
        var mainhead = get_mainhead();
        var bench =  get_bench();
        var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: "<?php echo base_url('Listing/Allocation/do_allotment/'); ?>",
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,pool_adv:pool_adv, list_dt: list_dt,diary_reg: get_diary_reg(),mainhead: get_mainhead(),civil_criminal: civil_criminal,bench: get_bench(),from_yr:from_yr,to_yr:to_yr,subhead:subhead,listing_purpose:listing_purpose,case_type:case_type,subject_cat:subject_cat,kword:kword,ia:ia,act:act,section:section,sitting_judges:sitting_judges,md_name:md_name,noc:noc,partno:partno,chked_jud:cchk,main_supp:get_main_supp(),reg_unreg:reg_unreg,is_nmd:is_nmd},
            beforeSend:function(){
                //$('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                $("#dv_res2").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>'); 
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res2').html(data);
                //alert(bench+" "+mainhead+" "+list_dt+" "+sitting_judges);
                $("#doa").show();
                setTimeout(function(){
                    ddd(list_dt,mainhead,sitting_judges,bench);
                }, 500)
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

});


function ddd(list_dt,mainhead,sitting_judges,bench){
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        
        url: "<?php echo base_url('Listing/Allocation/get_allocation_judges_p/'); ?>",
        
        data: {list_dt: list_dt, mainhead:mainhead, sitting_judges:sitting_judges, bench:bench,CSRF_TOKEN: CSRF_TOKEN_VALUE},
        beforeSend:function(){
        // $('.jud_all_al').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {     
            updateCSRFToken();           
            $('#jud_all_al').html(data);
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
            updateCSRFToken();
        }
    });
}


$(document).on("click","#do_trans",function(){
    $("#do_trans, #do_trans_w").hide();
    var pool_adv = $("#pool_adv").val();
    var md_name = $("#md_name").val();
    var list_dt_from = $("#ldates").val();
    var list_dt = $("#tans_to_date").val();
    var is_nmd = $("#is_nmd").val();
    var sitting_judges = $("#sitting_judges").val();
    get_diary_reg();
    get_mainhead();
    var ct_parm1 = ""; var ct_parm2 = ""; var civil_criminal = "";
    if($('#civil').is(":checked") == true){ ct_parm1 = $('#civil').val(); }
    if($('#criminal').is(":checked") == true){  ct_parm2 = $('#criminal').val(); }
    if(ct_parm1 == 'C' && ct_parm2 == 'R'){ civil_criminal = ""; }
    else{
        if(ct_parm1 == 'C'){ civil_criminal = "C"; }
        if(ct_parm2 == 'R'){ civil_criminal = "R"; }
    }
    get_bench();
    var from_yr = $("#from_yr").val();
    var to_yr = $("#to_yr").val();
    var subhead = $("#subhead").val();
    var listing_purpose = $("#listing_purpose").val();
    var case_type = $("#case_type").val();
    var subject_cat = $("#subject_cat").val();
    var ia = $("#ia").val();
    var act = $("#act").val();
    var kword = $("#kword").val();
    var section = $("#section").val();
    var reg_unreg = $("#reg_unreg").val();
    /////////////////////////////////
    var noc = $("#noc").val();
    var partno = $("#partno").val();
    var from_tran_jd_rs = $("#coram_from").val();
    var from_tran_partno = $("#part_no").val();
    var chk_tr = ""; var cchk = "";
    $('input[type=checkbox]').each(function (){
        if($(this).attr("name")=="chk_tr" && this.checked)
            chk_tr += $(this).val() + ",";
        if($(this).attr("name")=="chk" && this.checked)
            cchk += $(this).val() + "JG";
    });
    if(chk_tr == ""){
        $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Select atleast one case</table>');
        $("#do_trans").show();
        return false;
    }
    if(cchk == ""){
        $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Select atleast one bench</table>');
        $("#do_trans").show();
        return false;
    }
    else if(isEmpty(document.getElementById('tans_to_date'))){ $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Transfer to date</table>'); $("#do_trans").show(); return false; }
    else if(isEmpty(document.getElementById('partno'))){ $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Part No.</table>'); $("#do_trans").show(); return false; }
    else{
        var mainhead = get_mainhead();
        var bench =  get_bench();
        var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
          //  url: '../common/do_allotment.php',
            url: "<?php echo base_url('Listing/Allocation/do_allotment/'); ?>",
            // cache: false,
            // async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,pool_adv:pool_adv,list_dt_from:list_dt_from,list_dt: list_dt,diary_reg: get_diary_reg(),mainhead: get_mainhead(),civil_criminal: civil_criminal,bench: get_bench(),from_yr:from_yr,to_yr:to_yr,subhead:subhead,listing_purpose:listing_purpose,case_type:case_type,subject_cat:subject_cat,kword:kword,ia:ia,act:act,section:section,sitting_judges:sitting_judges,md_name:md_name,noc:noc,partno:partno,chked_jud:cchk,main_supp:get_main_supp(),chk_tr:chk_tr,from_tran_jd_rs:from_tran_jd_rs,from_tran_partno:from_tran_partno,reg_unreg:reg_unreg,is_nmd:is_nmd},
            beforeSend:function(){
                $('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res2').html(data);
                get_record_t();
                ddd_to_tran(list_dt,mainhead,sitting_judges,bench);
                $("#do_trans").show();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
});

$(document).on("click","#do_trans_w",function(){
    $("#do_trans, #do_trans_w").hide();
    var md_name = $("#md_name").val();
    var pool_adv = $("#pool_adv").val();
    var list_dt_from = $("#ldates").val();
    var list_dt = $("#tans_to_date").val();
    var is_nmd = $("#is_nmd").val();
    var sitting_judges = $("#sitting_judges").val();
    get_diary_reg();
    get_mainhead();
    var ct_parm1 = ""; var ct_parm2 = ""; var civil_criminal = "";
    if($('#civil').is(":checked") == true){ ct_parm1 = $('#civil').val(); }
    if($('#criminal').is(":checked") == true){  ct_parm2 = $('#criminal').val(); }
    if(ct_parm1 == 'C' && ct_parm2 == 'R'){ civil_criminal = ""; }
    else{
        if(ct_parm1 == 'C'){ civil_criminal = "C"; }
        if(ct_parm2 == 'R'){ civil_criminal = "R"; }
    }
    get_bench();
    var from_yr = $("#from_yr").val();
    var to_yr = $("#to_yr").val();
    var subhead = $("#subhead").val();
    var listing_purpose = $("#listing_purpose").val();
    var case_type = $("#case_type").val();
    var subject_cat = $("#subject_cat").val();
    var ia = $("#ia").val();
    var act = $("#act").val();
    var kword = $("#kword").val();
    var section = $("#section").val();
    var reg_unreg = $("#reg_unreg").val();
    /////////////////////////////////
    var noc = $("#noc").val();
    var partno = $("#partno").val();
    var from_tran_jd_rs = $("#coram_from").val();
    var from_tran_partno = $("#part_no").val();
    var chk_tr = ""; var cchk = "";
    $('input[type=checkbox]').each(function (){
        if($(this).attr("name")=="chk_tr" && this.checked)
            chk_tr += $(this).val() + ",";
        if($(this).attr("name")=="chk" && this.checked)
            cchk += $(this).val() + "JG";
    });
    if(chk_tr == ""){
        $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Select atleast one case</table>');
        $("#do_trans_w").show();
        return false;
    }
    if(cchk == ""){
        $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Select atleast one bench</table>');
        $("#do_trans_w").show();
        return false;
    }
    else if(isEmpty(document.getElementById('tans_to_date'))){ $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Transfer to date</table>'); $("#do_trans").show(); return false; }
    else if(isEmpty(document.getElementById('partno'))){ $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Part No.</table>'); $("#do_trans").show(); return false; }

    else{
        var mainhead = get_mainhead();
        var bench =  get_bench();
        var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
           // url: '../common/transfer_without_coram_check.php',
            url: "<?php echo base_url('Listing/Allocation/transfer_without_coram_check/'); ?>",
            // cache: false,
            // async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,pool_adv:pool_adv,list_dt_from:list_dt_from,list_dt: list_dt,diary_reg: get_diary_reg(),mainhead: get_mainhead(),civil_criminal: civil_criminal,bench: get_bench(),from_yr:from_yr,to_yr:to_yr,subhead:subhead,listing_purpose:listing_purpose,case_type:case_type,subject_cat:subject_cat,kword:kword,ia:ia,act:act,section:section,sitting_judges:sitting_judges,md_name:md_name,noc:noc,partno:partno,chked_jud:cchk,main_supp:get_main_supp(),chk_tr:chk_tr,from_tran_jd_rs:from_tran_jd_rs,from_tran_partno:from_tran_partno,reg_unreg:reg_unreg,is_nmd:is_nmd},
            beforeSend:function(){
                $('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res2').html(data);
                get_record_t();
                ddd_to_tran(list_dt,mainhead,sitting_judges,bench);
                $("#do_trans_w").show();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
});

$(document).on("click","#do_trans_asitis",function(){
    var r = confirm("Are you Verfied this case");
    if (r == true) {
        txt = "You pressed OK!";

        $("#do_trans, #do_trans_w, #do_trans_asitis").hide();
        var md_name = $("#md_name").val();
        var pool_adv = $("#pool_adv").val();
        var list_dt_from = $("#ldates").val();
        var list_dt = $("#tans_to_date").val();
        var sitting_judges = $("#sitting_judges").val();
        get_mainhead();
        get_bench();
    /////////////////////////////////
        var partno = $("#partno").val();
        var from_tran_jd_rs = $("#coram_from").val();
        var from_tran_partno = $("#part_no").val();
        var cchk = "";
        $('input[type=checkbox]').each(function (){
            if($(this).attr("name")=="chk" && this.checked)
                cchk += $(this).val() + "JG";
        });
        if(cchk == ""){
            $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Select atleast one bench</table>');
            $("#do_trans_w").show();
            return false;
        }
        else if(isEmpty(document.getElementById('tans_to_date'))){ $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Transfer to date</table>'); $("#do_trans").show(); return false; }
        else if(isEmpty(document.getElementById('partno'))){ $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Part No.</table>'); $("#do_trans").show(); return false; }
        else{
            var mainhead = get_mainhead();
            var bench =  get_bench();
            var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                //url: '../common/transfer_asitis.php',
                url: "<?php echo base_url('Listing/Allocation/transfer_asitis/'); ?>",
                cache: false,
                async: true,
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,pool_adv:pool_adv,list_dt_from:list_dt_from,list_dt: list_dt,mainhead: get_mainhead(),bench: get_bench(),md_name:md_name,partno:partno,chked_jud:cchk,main_supp:get_main_supp(),from_tran_jd_rs:from_tran_jd_rs,from_tran_partno:from_tran_partno},
                beforeSend:function(){
                    $('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $('#dv_res2').html(data);
                    get_record_t();
                    ddd_to_tran(list_dt,mainhead,sitting_judges,bench);
                    //$("#do_trans, #do_trans_w, #do_trans_asitis").show();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    } else {
        txt = "You pressed Cancel!";
    }
});


function get_diary_reg(){
    var diary_reg = "";
    $('input[type=radio]').each(function () {
        if($(this).attr("name")=="diary_reg" && this.checked)
            diary_reg = $(this).val();
    });
    return diary_reg;
}

function get_mainhead(){
    var mainhead = "";
    $('input[type=radio]').each(function () {
        if($(this).attr("name")=="mainhead" && this.checked)
            mainhead = $(this).val();
    });
    return mainhead;
}
function get_bench(){

    var bench = "";
    $('input[type=radio]').each(function () {
        if($(this).attr("name")=="bench" && this.checked)
            bench = $(this).val();
    });
    return bench;
}
function get_main_supp(){

    var main_supp = "";
    $('input[type=radio]').each(function () {
        if($(this).attr("name")=="main_supp" && this.checked)
            main_supp = $(this).val();
    });
    return main_supp;
}
function isEmpty(xx){
    var yy = xx.value.replace(/^\s*/, "");
    if(yy == ""){xx.focus();return true;}
    return false;
}
$(document).on("click","#prnnt1",function(){
    var prtContent = $("#prnnt").html();
    var mainhead = get_mainhead();
    var list_dt = $("#listing_dts").val();
    var jud_ros = $("#jud_ros").val();
    var part_no = $("#part_no").val();
    var temp_str=prtContent;
    var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write(temp_str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
});

$( function() {
    //$( "#ldates" ).datepicker();
  } );
</script>