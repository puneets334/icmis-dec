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
                                <h3 class="card-title">Filing Trap</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">File Dispatch Receive</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="POST" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3" id="search_block">
                                                        <label for="stype">Search For</label>
                                                        <select class="form-control" name="stype" id="stype" onChange="handleSearchTypeChange()">
                                                            <option value="select_dno" selected>Diary No.</option>
                                                            <option value="all_dno">All Matters</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3 span_dno">
                                                        <label for="dno">Diary No.</label>
                                                        <input type="text" name="dno" id="dno" maxlength="6" class="form-control" size="5" autofocus />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3 span_dno">
                                                        <label for="dyr">Year</label>
                                                        <input type="text" name="dyr" id="dyr" class="form-control" maxlength="4" size="4" value="<?= date('Y') ?>" />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mt-4">
                                                        <input type="button" value="SHOW" id="showbutton" onclick="handleShowButtonClick()" />
                                                    </div>
                                                </div>
                                                <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
                                                    <tr>
                                                        <th>Incomplete Matters for <span style="color: #d73d5a"></span>
                                                            <span style="color: #737add">[Filing Dispatch Receive]</span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th><span style="color: #d73d5a; align-items: center;font-size: larger">New SC-EFM matters will not be displayed in this Report</span></th>
                                                    </tr>
                                                </table>
                                                <div id="result"></div>
                                            </form>
                                            <div id="newresult"></div>
                                            <div id="dv_content1" class="text-warning text-center"></div>
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
    function handleSearchTypeChange() {
        const searchType = document.querySelector('#stype').value;
        if (searchType === 'all_dno') {
            $('.span_dno').hide();
            fetchData({
                stype: searchType,
                dno: ''
            });
        } else {
            $('.span_dno').show();
            document.getElementById('newresult').innerText = '';
        }
    }

    function handleShowButtonClick() {
        const searchType = document.querySelector('#stype').value;
        const diaryNo = $("#dno").val();
        const diaryYear = $("#dyr").val();

        if (searchType === 'select_dno') {
            if (!validateDiaryInput(diaryNo, diaryYear)) return;
            fetchData({
                stype: searchType,
                dno: diaryNo,
                dyr: diaryYear
            });
        }
    }

    function validateDiaryInput(diaryNo, diaryYear) {
        const regNum = /^[0-9]+$/;
        if (!regNum.test(diaryNo)) {
            alert("Please Enter Diary No in Numeric");
            $("#dno").focus();
            return false;
        }
        if (!regNum.test(diaryYear)) {
            alert("Please Enter Diary Year in Numeric");
            $("#dyr").focus();
            return false;
        }
        if (diaryNo == 0 || diaryYear == 0) {
            alert("Diary No/Year Can't be Zero");
            return false;
        }
        return true;
    }

    function fetchData(data) {
        const CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        data.CSRF_TOKEN = CSRF_TOKEN_VALUE;

        $.ajax({
            url: "<?= base_url('Filing/IncompleteFDR/incompleteFDR_alt') ?>",
            method: 'POST',
            beforeSend: function() {
                $("#dv_content1").html('');
                $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url('images/load.gif') ?>'></div>");
            },
            data: data,
            success: function(response) {
                updateCSRFToken();
                $("#result").html(response);
                $("#newresult").html('');
            },
            error: function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            }
        });
    }
    
    $("#sendSMS").click(function(e) {
        e.preventDefault();
        var usercode = $('#usercode').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('PaperBook/PaperBooksSMS/sms_godown'); ?>",
            type: "POST",
            beforeSend: function() {
                $('#result_main').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                usercode: usercode,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(r) {

                if (r != 0) {
                    alert('Message has been send.');
                } else {
                    alert("There is some problem while sending message please contact computer cell...");
                }
                updateCSRFToken();
            },
            error: function() {
                alert('ERROR');
                updateCSRFToken();
            }
        });
    });
</script>