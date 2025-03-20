<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/token-input.css">
<style type="text/css">
    .card-header
    {
        padding: .75rem 0;
    }

    #newb
    {
        position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;
    }
    #overlay
    {
        background-color: #000;
        opacity: 0.7;
        filter:alpha(opacity=70);
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
    }

    .caseNum
    {
        display: none;
    }
    .diaryNum
    {
        display: none;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Old Case IA/DOC Registration</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2" style="width: 100% !important;">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'bulkDispatchId',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="sa" class="text-right">Select</label>
                                    <select id="sa" name="sa" class="form-control" onclick="changediv()">
                                        <option value="">Select</option>
                                        <option value="1">Case Type</option>
                                        <option value="2">Diary Number</option>
                                    </select>
                                </div>

                                <div class="col-md-2 caseNum">
                                    <label for="aci">Case Type</label>
                                    <select class="form-control" id="caseType" name="caseType">
                                        <option value="">Select Case Type</option>
                                        <?php
                                        foreach ($cases as $case)
                                        {
                                            echo '<option value="' . $case["casecode"] . '">' . $case["casename"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 caseNum">
                                    <label for="caseNo" class="text-right">Case No</label>
                                    <input type="number" id="caseNo" name="caseNo" placeholder="Case No" class="form-control" value="">
                                </div>
                                <div class="col-md-2 caseNum">
                                    <label for="caseYear" class="text-right">Case Year</label>
                                    <select id="caseYear" name="caseYear" class="form-control">
                                        <option value="">Select Year</option>
                                        <?php
                                        for ($i = date('Y'); $i > 1948; $i--) {
                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-2 diaryNum">
                                    <label for="dNo" class="text-right">Diary No</label>
                                    <input type="number" id="dNo" name="dNo" placeholder="Diary No" class="form-control" value="">
                                </div>

                                <div class="col-md-2 diaryNum">
                                    <label for="dYear" class="text-right">Year</label>
                                    <select id="dYear" name="dYear" class="form-control">
                                        <option value="">Select Year</option>
                                        <?php
                                        for ($i = date('Y'); $i > 1948; $i--)
                                        {
                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="col-md-2">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <!-- <button type="button" name="btnGetR" style="width: 100%; background-color: #072d74; color: white;">Get Details
                                    </button> -->
                                    <input type="button" name="btnGetR" value="GET DETAILS"/>
                                </div>
                            </div>
                            <!-- <center><input type="button" id="btnGetR" value="Show"/></center> -->
                            <br>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="dv_res1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/js/jquery.tokeninput.js"></script>
<script>
    $(function()
    {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            // yearRange: '1950:2050'
        });
    });
</script>

<script>

    function changediv()
    {
        var searchby = $('#sa').val();
        $('#printable').hide();
        if (searchby == 1)
        {
            $('.diaryNum').hide();
            $('#dNo').val('');
            
            $('.caseNum').show();
            $('#dYear').val('');
        }
        else if (searchby == 2)
        {
            $('.caseNum').hide();
            $('#caseType').val('');
            $('#caseNo').val('');
            $('#caseYear').val('');

            $('.diaryNum').show();
        }
        else if (searchby == '' || searchby == null)
        {
            $('.caseNum').hide();
            $('#caseType').val('');
            $('#caseNo').val('');
            $('#caseYear').val('');
            
            $('.diaryNum').hide();
            $('#dNo').val('');
            $('#dYear').val('');
        }
    }

    $(document).on('click', "input[name=btnGetR]", function ()
    {
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        console.log(CSRF_TOKEN_VALUE);
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');
        var option = $('#sa').val();

        if (option == 1)
        {
            cstype = $("#caseType").val();
            csno = $("#caseNo").val();
            csyr = $("#caseYear").val();

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
        }
        else if (option == 2)
        {
            diaryno = $("#dNo").val();
            diaryyear = $("#dYear").val();

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

        $.ajax({
            type: 'POST',
            url: "<?= base_url('Exchange/MovementOfDoc/MovementOfDocument/oldVerifyProcess'); ?>", // Adjust with your actual controller and method
            beforeSend: function (xhr) {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url('images/load.gif'); ?>'></div>");
            },
            data:
            {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                d_no: diaryno,
                d_yr: diaryyear,
                ct: cstype,
                cn: csno,
                cy: csyr,
                tab: 'Case Details'
            },
            success: function(response) {
                $("#dv_res1").html(response.data);
                // Uncomment and modify as needed
                // initTabs('dhtmlgoodies_tabView1', Array('Case Details', 'Earlier Court Details', 'Connected Matters', 'Listing Dates', 'I.A.', 'Documents', 'Notices', 'Defaults', 'Judgement/Orders', 'Adjustments', 'Mention Memo', 'Restoration Details', 'DropNote', 'Appearance', 'Paper Book'), 0, '100%', '100%');
                // $('#tabViewdhtmlgoodies_tabView1_0').html(msg);
                // get_subheading();
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
                $("#dv_res1").html('');
                alert("ERROR, Please Contact Server Room");
            }
        });
    });





















    function showrpt(str)
    {
        document.getElementById("btnId").value = str;
        if (str == "")
        {
            document.getElementById("txtHint").innerHTML = "";
            return;
        }
        var dtp = document.getElementById("dtp").value;

        $.ajax({
            url: "<?php echo base_url('Exchange/Message/sentboxPro'); ?>?q="+str+"&dtp="+dtp,
            type: "GET",
            beforeSend: function()
            {
                $("#txtHint").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response)
            {
                updateCSRFToken();
                $("#txtHint").html('');
                $("#txtHint").html(response.data);
            },
            error: function(xhr, status, error)
            {
                updateCSRFToken();
                $("#txtHint").html('');
                $("#txtHint").html("<div style='color: red;'>An error occurred. Please try again.</div>");
            }
        });
    }
</script>