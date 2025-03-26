<?= view('header') ?>

<!-- Main content -->
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }

    .sp_ytx {
        cursor: pointer;
        color: blue;
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
                                <h3 class="card-title">R & I</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Notices >> Acknowledgement</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'dispatchDakFromRI', 'id' => 'dispatchDakFromRI', 'autocomplete' => 'off');
                                            echo form_open(base_url('#'), $attribute); ?>
                                            <!-- <form id="dispatchDakFromRI" method="POST">-->
                                            <!--div date start-->
                                            <div class="form-group col-sm-12">
                                                <span>
                                                    <label for="">Search By :</label>
                                                    <br>
                                                    <br>
                                                    <!-- <label class="radio-inline"><input type="radio" name="searchBy" value="s" checked="">Date & Section</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
                                                    <label class="radio-inline"><input type="radio" name="searchBy" value="c">Case Type</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="radio-inline"><input type="radio" name="searchBy" value="d">Diary No.</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="radio-inline"><input type="radio" name="searchBy" value="p">Process Id</label>&nbsp;
                                                    <input type="hidden" id="status" name="status" value="2">
                                                </span>
                                            </div>

                                            <div id="divCaseTypeWise" class="resetdata" style="display: none;">
                                                <div class="row">
                                                    <div class="form-group col-sm-2">
                                                        <label for="from">Case Type</label>
                                                        <select class="form-control" name="caseType" id="caseType">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if (!empty($caseTypes)) {
                                                                foreach ($caseTypes as $caseType) {
                                                                    echo '<option value="' . $caseType['casecode'] . '">' . $caseType['short_description'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>
                                                    <div class="form-group col-sm-2">
                                                        <label for="caseNo">Case Number</label>
                                                        <input type="number" id="caseNo" name="caseNo" class="form-control"
                                                            placeholder="Case Number" value="">
                                                    </div>
                                                    <div class="form-group col-sm-2">
                                                        <label for="caseYear">Case Year</label>
                                                        <select id="caseYear" name="caseYear" class="form-control">
                                                            <option value="">Select</option>
                                                            <?php
                                                            for ($i = date("Y"); $i > 1949; $i--) {
                                                                echo "<option value=" . $i . ">$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="divDiaryNoWise" class="resetdata" style="display: none;">
                                                <div class="row">
                                                    <div class="form-group col-sm-2">
                                                        <label for="diaryNumber">Diary Number</label>
                                                        <input type="number" id="diaryNumber" name="diaryNumber" class="form-control"
                                                            placeholder="Diary Number" value="">
                                                    </div>
                                                    <div class="form-group col-sm-2">
                                                        <label for="diaryYear">Diary Year</label>
                                                        <select id="diaryYear" name="diaryYear" class="form-control">
                                                            <option value="">Select</option>
                                                            <?php
                                                            for ($i = date("Y"); $i > 1949; $i--) {
                                                                echo "<option value=" . $i . ">$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="divProcessIdWise" class="resetdata" style="display: none;">
                                                <div class="row">
                                                    <div class="form-group col-sm-2">
                                                        <label for="processId">Process Id</label>
                                                        <input type="number" id="processId" name="processId" class="form-control"
                                                            placeholder="Process Id" value="">
                                                    </div>
                                                    <div class="form-group col-sm-2">
                                                        <label for="processYear">Process Year</label>
                                                        <select id="processYear" name="processYear" class="form-control">
                                                            <option value="">Select</option>
                                                            <?php
                                                            for ($i = date("Y"); $i > 1949; $i--) {
                                                                echo "<option value=" . $i . ">$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group col-sm-3">
                                    <label for="from">Dispatch Mode</label>
                                    <select class="form-control" id="dispatchMode" name="dispatchMode">
                                        <option value="0">Select Mode</option>
                                        <?php
                                        if (!empty($dispatchModes)) {
                                            foreach ($dispatchModes as $mode) {
                                                //                                                if ($dispatchMode == $mode['id'])
                                                //                                                    echo '<option value="' . $mode['id'] . '" selected="selected">' . $mode['postal_type_description'] . '</option>';
                                                //                                                else
                                                echo '<option value="' . $mode['id'] . '">' . $mode['postal_type_description'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div> -->


                                            <div class="col-sm-12 col-md-3 mb-3">
                                                <button type="button" name="btn1" id="btn1" class="quick-btn mt-26" onclick="checkFunction();">View</button>
                                            </div>
                                            <?= form_close(); ?>
                                            <!-- </form>-->
                                            <div id="result1"></div>
                                            <div id="dataForDispatch"></div>
                                            <input type="hidden" id="fil_hd"/>
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

$(document).on("focus", ".dtp", function() {

$('.dtp').datepicker({format: 'dd-mm-yyyy', changeMonth: true, changeYear: true, yearRange: '1950:2050'
});
});
    $("input[name$='searchBy']").click(function() {
        $('.resetdata').trigger('reset');
        var searchValue = $(this).val();
        if (searchValue == 's') {
            $('#divSection').show();
            $('#divCaseTypeWise').hide();
            $('#divDiaryNoWise').hide();
            $('#divProcessIdWise').hide();

        } else if (searchValue == 'c') {
            $('#divSection').hide();
            $('#divCaseTypeWise').show();
            $('#divDiaryNoWise').hide();
            $('#divProcessIdWise').hide();
        } else if (searchValue == 'd') {
            $('#divSection').hide();
            $('#divCaseTypeWise').hide();
            $('#divDiaryNoWise').show();
            $('#divProcessIdWise').hide();
        } else if (searchValue == 'p') {
            $('#divSection').hide();
            $('#divCaseTypeWise').hide();
            $('#divDiaryNoWise').hide();
            $('#divProcessIdWise').show();
        }
    });

    $('.number').keypress(function(event) {
        if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
            return true;
        else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
            event.preventDefault();
    });

    function checkFunction() {
        document.getElementById('result1').innerHTML ='';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var searchBy = $("input[name='searchBy']:checked").val();
        //alert(searchBy);
        var fno = '';
        if (searchBy == "s") {
            //    var fromDate = $("#fromDate").val();
            //    var toDate = $("#toDate").val();
            //    if (fromDate == "") {
            //        alert("Select Received From Date.");
            //        $("#fromDate").focus();
            //        return false;
            //    }
            //    if (toDate == "") {
            //        alert("Select Received To Date.");
            //        $("#toDate").focus();
            //        return false;
            //    }
            //    date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
            //    date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
            //    if (date1 > date2) {
            //        alert("To Date must be greater than From date");
            //        $("#toDate").focus();
            //        return false;
            //    }
            alert('No data');
        } else if (searchBy == "c") {
            var caseType = $("#caseType").val();
            var caseNo = $("#caseNo").val();
            if (caseType == 0) {
                alert("Select Case Type.");
                $("#caseType").focus();
                return false;
            }
            if (caseNo == "") {
                alert("Enter Case Number.");
                $("#caseNo").focus();
                return false;
            }
        } else if (searchBy == "d") {
            var diaryNumber = $("#diaryNumber").val();
            if (diaryNumber == "") {
                alert("Enter Diary Number.");
                $("#diaryNumber").focus();
                return false;
            }
            var diaryYear = $("#diaryYear").val();
            if (diaryYear == "") {
                alert("Enter Select Diary year.");
                $("#diaryYear").focus();
                return false;
            }
            var fno = diaryNumber+diaryYear;
        } else if (searchBy == "p") {
            var processId = $("#processId").val();
            if (processId == "") {
                alert("Enter Process Id.");
                $("#processId").focus();
                return false;
            }
        }
        document.getElementById('fil_hd').value=fno;
        $.ajax({
            method: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                data: $("#dispatchDakFromRI").serialize(),
            },
            //dataType: "json",
            url: "<?php echo base_url('RI/DispatchController/getDataToAck'); ?>",
            beforeSend: function (xhr) {
                $('#btn1').prop('disabled',true);
                $("#dataForDispatch").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            success: function(data) {
                updateCSRFToken();
                $("#dataForDispatch").html(data);
                $('#btn1').prop('disabled',false);
                // $("#dispatchDakToRI").hide(); 
            },
            error: function(data) {
                updateCSRFToken();
                $("#dataForDispatch").html('<center><h4 style="color:red;">No Data Found..</h4></center>');
                $('#btn1').prop('disabled',false);

            }
        });

    }


    function show_nt(str) {
        var str1 = str.split('_');
        var hd_talw_id = $('#hd_talw_id' + str1[1]).val();

        var fil_hd = $('#fil_hd').val();
        document.getElementById('ggg').style.width = 'auto';
        document.getElementById('ggg').style.height = ' 500px';
        document.getElementById('ggg').style.overflow = 'scroll';

        document.getElementById('ggg').style.marginLeft = '35px';
        document.getElementById('ggg').style.marginRight = '35px';
        document.getElementById('ggg').style.marginBottom = '25px';
        document.getElementById('ggg').style.marginTop = '40px';
        document.getElementById('dv_sh_hd').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
        document.getElementById('ggg').innerHTML = '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>';
        $.ajax({
            url: "<?php echo base_url(); ?>/RI/DispatchController/openNot",
            type: "GET",
            data: {
                fil_hd: fil_hd,
                hd_talw_id: hd_talw_id
            },
            beforeSend: function() {
                $('#ggg').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>')
            },
            success: function(data) {

                $('#ggg').html(data);
            }
        });
    }

    function closeData() {
        document.getElementById('ggg').scrollTop = 0;

        document.getElementById('dv_fixedFor_P').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";

    }


    function save_record() {
        document.getElementById('result1').innerHTML ='';
        var sta_s = '';
        var bhejo = new Array();
        var total = document.getElementById('total').value;
        var chk_status = 0;
        var status = 0;
        for (var ii = 1; ii < total; ii++) {

            if (document.getElementById('status' + ii))
                var status = document.getElementById('status' + ii).value;
            else {

                $('.cl_chkhc' + ii).each(function() {
                    if ($(this).is(':checked')) {
                        status = '-1';
                    }
                });
                 
            }
            if (status != '0') {
                chk_status++;
            }
        }

        if (chk_status == 0) {
            alert("Please Select atleast one status");
        } else {
            var chk_ord_reg1 = 0;

            var cnt_lhs_hjs = '';
            var ddl_pn_di = '';
            var txt_rem = '';
           
            for (var i = 1; i < total; i++) {
                
                var chk_ord_reg = '';
                var ordinary = '';
                var registry = '';
                var humdust = '';
                var ctn_all = 0;
                var st_rmk = '0';
                var date = '';
                var txt_remark = '';
                var ddl_l_ljs_st = '';
                var ddl_l_hjs_st = '';
                var ddl_t_ljs_st = '';
                var ddl_t_hjs_st = '';
                var txt_l_ljs_st = '';
                var txt_l_hjs_st = '';
                var txt_t_ljs_st = '';
                var txt_t_hjs_st = '';

                var ddlparts_l = '';
                var ddlparts_h = '';
                var status = 0;
                var lw_cases = '';
                

                if ($('.cl_chkhc' + i).length) {
                    //            var c_sno=0;
                    $('.cl_chkhc' + i).each(function() {
                        if ($(this).is(':checked')) {
                            var idd = $(this).attr('id');
                            var sp_idd = idd.split('_');
                            var txthcrmk = $('#txthcrmk_' + sp_idd[1] + '_' + sp_idd[2]).val();
                            //                        alert(idd);
                            if (lw_cases == '')
                                lw_cases = $(this).val() + '$' + txthcrmk;
                            else
                                lw_cases = lw_cases + '@' + $(this).val() + '$' + txthcrmk;                            

                        }
                        

                    });


                }              


                {

                    if (document.getElementById('sta_remark' + i))
                        st_rmk = document.getElementById('sta_remark' + i).value;
                    if (document.getElementById('date' + i))
                        date = document.getElementById('date' + i).value;
                    // var status='0';
                    if (document.getElementById('status' + i))
                        status = document.getElementById('status' + i).value;
                    if (document.getElementById('txt_remark' + i))
                        txt_remark = document.getElementById('txt_remark' + i).value;


                    if (document.getElementById('status' + i)) {
                        if (document.getElementById('status' + i).value == '0' && chk_status == 0) {
                            alert("Please select Status");
                            $('#status' + i).focus();
                            return false;
                        }
                        if (document.getElementById('status' + i).value != '0' && $('#sta_remark' + i).val() == '0') {
                            alert("Please select Status Remark");
                            $('#sta_remark' + i).focus();
                            return false;
                        }
                        if (document.getElementById('date' + i).value == '' && document.getElementById('status' + i).value != '0') {
                            alert("Please select Serve Date");
                            $('#date' + i).focus();
                            return false;
                        }
                    } {

                        //              alert(lw_cases);

                        //                  chk_ord_reg1=chk_ord_reg;

                        var rdt = '';
                        var hd_tw_sn_to = '';
                        var hd_tw_cpsn_to = '';

                        var hd_lst_id = $("#hd_lst_id" + i).val();
                        bhejo.push(hd_lst_id + '^' + rdt + '^' + status + '^' + st_rmk + '^' + date + '^' + chk_ord_reg1 + '^' + txt_remark + '^' + lw_cases);
                        // }
                        // }
                    }
                }
            }

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


            //  alert(bhejo);
            var xmlhttp;
            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
            xmlhttp.onreadystatechange = function() {
                updateCSRFToken();
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    //            alert(xmlhttp.responseText);
                    document.getElementById('result1').innerHTML = xmlhttp.responseText;
                }
            }

            //    xmlhttp.open("POST","save_serve.php"+"?fil_no="+document.getElementById('fil_hd').value+"&bhejo="+bhejo,false);
            xmlhttp.open("POST", "<?php echo base_url(); ?>/RI/DispatchController/save_serve", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("fil_no=" + document.getElementById('fil_hd').value + "&bhejo=" + bhejo+ "&CSRF_TOKEN="+CSRF_TOKEN_VALUE);
        }
    }

    function serveTypelao(val,id)
    {
        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById('sta_remark'+id).innerHTML = xmlhttp.responseText;
            }
        }
        
        xmlhttp.open("GET","<?php echo base_url(); ?>/RI/DispatchController/get_serve_type"+"?val="+val,false);
        
        if(val==0)
            document.getElementById('sta_remark'+id).innerHTML = "<option value='0'>Select</option>";
        else
            xmlhttp.send(null); 
    }

</script>