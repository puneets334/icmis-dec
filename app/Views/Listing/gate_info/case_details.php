<style>
    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
        word-wrap: break-word;
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
        background-color: #536872;
        color: white;
    }

    .button {
        background-color: #008CBA;
        border: none;
        color: white;
        padding: 2px 2px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 18px;
        margin: 2px 2px;
        cursor: pointer;
    }

    .button_w {
        background-color: #536872;
        border: none;
        color: white;
        padding: 2px 2px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 18px;
        margin: 2px 2px;
        cursor: pointer;
        width: 100%;
    }

    .button_r {
        background-color: #f44336;
        border: none;
        color: white;
        padding: 2px 2px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 18px;
        margin: 2px 2px;
        cursor: pointer;
    }
</style>
<?php
 if (isset($case_result) && !empty($case_result)) { ?>
<h6 align="center" style="color:green;margin-top: 35px">Diary No.- <?php echo substr($diary_details['diary_no'], 0, -4) . ' - ' . substr($diary_details['diary_no'], -4); ?>
</h6>
<h6 align="center"><?php echo $diary_details['pet_name'] . ' <span style="color:red;">vs</span> ' . $diary_details['res_name'] ?></h6>
<div class="card">
    <div class="card-body">
            <caption>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Case Details</a>
                            </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div id="result1"></div>
                                <?= $case_status; ?>
                            </div>
                        </div>
                    </div>
            </caption>
            <table id="customers" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:10%;">Caseno.</th>
                        <th style="width:40%;">Case Information</th>
                        <th style="width:25%;">Entered Datetime</th>
                        <th style="width:25%;">User Info</th>
                        <th colspan="2" style="width:25%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($case_result as $data) { ?>
                        <tr>
                            <td><?php echo $data['diary_no']; ?></td>
                            <td><?php echo $data['message']; ?></td>
                            <td><?php echo $data['insert_time']; ?></td>
                            <td><?php echo $data['userinfo']; ?></td>
                            <?php if ($data['if_active'] == 'D') { ?>
                                <td colspan="2">
                                    Deleted By <font color="red"><?php echo $data['deleted_by']; ?></font> on <font color="blue"><?php echo $data['deleted_on']; ?></font>
                                </td>
                            <?php } else { ?>
                                <td>
                                    <input type="button" class="button" value="Edit" onclick="f('<?php echo $data['id']; ?>')" id="">
                                </td>
                                <td> <input type="button" class="button" value="Delete" onclick="delt('<?php echo $data['id']; ?>')" id="">
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
</div>

<br><br>

<div>
    <input type=hidden id='isedit' value=0>
    <table align="center" id='customers' border="none">
        <tr>
            <th colspan='2'>Enter case information :</th>
        </tr>
        <tr>
            <td colspan="2" align="center"><textarea width='100%' name="info" id="info" rows="4" cols=150></textarea></td>
        </tr>

    </table>
    <table align="center">
        <th> <input type="button" class="button" name="btnsub" value="Add Gate Info" onclick="insert()" /></th>
    </table>
</div>
<?php } else { ?>
    <br><br>
    <div class="text-center align-items-center">No Record Found</div>
<?php } ?>



<script>
    function insert() {
        var info = $('#info').val().trim();
        if (info == '') {
            alert('Please Enter Case Information');
            $('#info').focus();
        } else {
            //var info1 = $("#info").val();
            var dno1 = $("#diary_number").val();
            var dyr1 = $("#diary_year").val();
            var dno2 = dno1 + dyr1;
            var isedit = $("#isedit").val();
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_hash(); ?>'
                }
            });

            $.ajax({
                url: "<?php echo base_url('Listing/Caseinfo/insertCase/'); ?>",
                type: "POST",
                data: {
                    info: info,
                    dno: dno2,
                    is_edit: isedit,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                success: function(response) {

                    if (response.status === 'success') {
                        updateCSRFToken();
                        setTimeout(function() { get_details(); }, 50);
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    updateCSRFToken();
                    alert("Error in AJAX call");
                }
            });
        }
    }
</script>

<script>
    function delt(id) {
        if (confirm("Do you really want to delete this case information?")) {

            /*($.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_hash(); ?>'
                }
            });*/
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Listing/Caseinfo/deleteCase'); ?>",
                data: {
                    id: id,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $("#dv_res1").html("Processing...");
                },
                success: function(response) {
                    if (response.status === 'success') {
                        updateCSRFToken();
                        setTimeout(function() { get_details(); }, 50);
                        alert(response.message);
                        //location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    updateCSRFToken();
                    alert("An error occurred: " + xhr.responseText);
                }
            });
        }
    }

    function f(id) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/Caseinfo/case_getinfo'); ?>",
            type: "POST",
            data: {
                case_id: id,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(response) {
                updateCSRFToken();
                $('#info').val(response);
                $('#isedit').val(id);
                $('#info').focus();
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.log("AJAX Error: " + error);
            }
        });
    }


    function get_details() {
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');

        if ($("#search_type_c").is(':checked')) {
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
            /*if(cstype.length==1)
                cstype = '00'+cstype;
            else if(cstype.length==2)
                cstype = '0'+cstype;*/
        } else if ($("#search_type_d").is(':checked')) {
            diaryno = $("#diary_number").val();
            diaryyear = $("#diary_year").val();
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

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/Caseinfo/case_info_process'); ?>",
            type: "POST",
            data: {
                diary_number: diaryno,
                diary_year: diaryyear,
                search_type:"D",
                ct: cstype,
                cn: csno,
                cy: csyr,
                tab: 'Case Details',
                opt: 1,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function () {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response) {
                $("#loader").html('');
                updateCSRFToken();
                var response = response.replace('1@@@', '');
                $(".form-response").html("");
                $('#report_result').html(response);
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.log("AJAX Error: " + error);
            }
        });
    }
</script>