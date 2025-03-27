<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/token-input.css">
<style type="text/css">
    .card-header
    {
        padding: .75rem 0;
    }
</style>
<style>
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

    .inner_1
    {
        margin-left: 30px;
        float: left;
        margin-bottom: 5px;
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
                                <h3 class="card-title">Case File Receiving</h3>
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
                                'id' => 'MessageInboxId',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <?= csrf_field() ?>
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
                                    <select class="form-control" id="selct" name="caseType">
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
                                    <input class="form-control" type="number" size="5" maxlength="5" id="case_no" >
                                </div>
                                <div class="col-md-2 caseNum">
                                    <label for="caseYear" class="text-right">Case Year</label>
                                    <select id="case_yr" name="case_yr" class="form-control">
                                        <option value="">Select Year</option>
                                        <?php
                                        for ($i = date('Y'); $i > 1948; $i--)
                                        {
                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-2 diaryNum">
                                    <label for="dNo" class="text-right">Diary No</label>
                                    <input class="form-control" type="text" id="dno" size="6" value="" placeholder="Diary No">
                                </div>

                                <div class="col-md-2 diaryNum">
                                    <label for="dYear" class="text-right">Year</label>
                                    <select id="dyr" name="dyr" class="form-control">
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
                                    <label for="from" class="text-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="button" name="btnGetR" value="GET DETAILS"/>
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <!-- <center><span id="loader"></span></center> -->
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="dv_res1"></div>
                            <div id="dv_res2"></div>
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

    function changediv()
    {
        var searchby = $('#sa').val();
        if (searchby == 1)
        {
            $('.diaryNum').hide();
            $('.caseNum').show();
            $('#dno').val('');
            $('#dyr').val('');
        }
        else if (searchby == 2)
        {
            $('.caseNum').hide();
            $('.diaryNum').show();

            $('#selct').val('');
            $('#case_no').val('');
            $('#case_yr').val('');
        }
        else if (searchby == '' || searchby == null)
        {
            $('.caseNum').hide();
            $('#selct').val('');
            $('#case_no').val('');
            $('#case_yr').val('');
            
            $('.diaryNum').hide();
            $('#dno').val('');
            $('#dyr').val('');
        }
    }

    $("input[name=btnGetR]").click(function()
    {
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var searchby = $('#sa').val();
         
        if(searchby == 1)
        {
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();
            
            if(!regNum.test(cstype))
            {
                alert("Please Select Casetype");
                $("#selct").focus();
                return false;
            }
            if(!regNum.test(csno)){
                alert("Please Fill Case No in Numeric");
                $("#case_no").focus();
                return false;
            }
            if(!regNum.test(csyr)){
                alert("Please Fill Case Year in Numeric");
                $("#case_yr").focus();
                return false;
            }
            if(csno == 0){
                alert("Case No Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if(csyr == 0){
                alert("Case Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }
        }
        else if(searchby == 2)
        {
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            if(!regNum.test(diaryno)){
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if(!regNum.test(diaryyear)){
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if(diaryno == 0){
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if(diaryyear == 0){
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
        }
        else
        {
            alert('Please Select Any Option');
            return false;
        }
        $.ajax({
            type: 'POST',
            data: 
            {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                d_no:diaryno,
                d_yr:diaryyear,
                ct:cstype,
                cn:csno,
                cy:csyr,
                module:'receive'
            },
            url: "<?= site_url('Exchange/FileMovement/getSFileRec') ?>",
            beforeSend: function ()
            {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response)
            {
                if(response.status == true)
                {
                    $("#dv_res1").html(response.data);
                    $("#dv_res2").html('');
                    if($('input[name="chk[]"]:checked').length>0)
                    {
                        $('#receive').prop('disabled',false);    
                    }
                    else
                    {
                        $('#receive').prop('disabled',true);    
                    }
                }
                updateCSRFToken();
            },
            error: function(xhr, status, error)
            {
                $("#dv_res1").html('');
                alert("ERROR, Please Contact Server Room");
                updateCSRFToken();
            }
        });
    });

    function OptionsSelected(me)
    {
        if(me.id=="ckbCheckAll" && me.checked==true)
        {
            var inputs = document.getElementsByClassName("chk");
            for (var i = 0; i < inputs.length; i++)
            {
                if (inputs[i].disabled == false)
                {
                    if (inputs[i].checked == false)
                    {
                        inputs[i].checked = true;
                    }
                }
            }
        }
        else if (me.id=="ckbCheckAll" && me.checked==false)
        {
            var inputs = document.getElementsByClassName("chk");
            for (var i = 0; i < inputs.length; i++)
            {
                if (inputs[i].disabled == false)
                {
                    if (inputs[i].checked == true)
                    {
                        inputs[i].checked = false;
                    }
                }
            }
        }


        if(me.id=="ckbCheckSetA" && me.checked==true)
        {
            $('.chk').removeAttr('checked');
            var inputs = document.getElementsByClassName("chk_1");
            for (var i = 0; i < inputs.length; i++)
            {
                if (inputs[i].disabled == false)
                {
                    if (inputs[i].checked == false)
                    {
                        inputs[i].checked = true;
                    }
                }
            }
        }
        else if (me.id=="ckbCheckSetA" && me.checked==false)
        {
            var inputs = document.getElementsByClassName("chk_1");
            for (var i = 0; i < inputs.length; i++)
            {
                if (inputs[i].disabled == false)
                {
                    if (inputs[i].checked == true)
                    {
                        inputs[i].checked = false;
                    }
                }
            }
        }

        if(me.id=="ckbCheckSetBCD" && me.checked==true)
        {
            $('.chk').removeAttr('checked');
            var inputs1 = document.getElementsByClassName("chk_2");
            var inputs2 = document.getElementsByClassName("chk_3");
            var inputs3 = document.getElementsByClassName("chk_4");
            for (var i = 0; i < inputs1.length; i++)
            {
                if (inputs1[i].disabled == false)
                {
                    if (inputs1[i].checked == false)
                    {
                        inputs1[i].checked = true;
                    }
                }
            }

            for (var i = 0; i < inputs2.length; i++)
            {
                if (inputs2[i].disabled == false)
                {
                    if (inputs2[i].checked == false)
                    {
                        inputs2[i].checked = true;
                    }
                }
            }

            for (var i = 0; i < inputs3.length; i++)
            {
                if (inputs3[i].disabled == false)
                {
                    if (inputs3[i].checked == false)
                    {
                        inputs3[i].checked = true;
                    }
                }
            }
        }

        else if(me.id=="ckbCheckSetBCD" && me.checked==false)
        {
            var inputs1 = document.getElementsByClassName("chk_2");
            var inputs2 = document.getElementsByClassName("chk_3");
            var inputs3 = document.getElementsByClassName("chk_4");

            for (var i = 0; i < inputs1.length; i++)
            {
                if (inputs1[i].disabled == false)
                {
                    if (inputs1[i].checked == true)
                    {
                        inputs1[i].checked = false;
                    }
                }
            }

            for (var i = 0; i < inputs2.length; i++)
            {
                if (inputs2[i].disabled == false)
                {
                    if (inputs2[i].checked == true)
                    {
                        inputs2[i].checked = false;
                    }
                }
            }

            for (var i = 0; i < inputs3.length; i++)
            {
                if (inputs3[i].disabled == false)
                {
                    if (inputs3[i].checked == true)
                    {
                        inputs3[i].checked = false;
                    }
                }
            }
        }
    }

    $(document).on("click","#receive",function()
    {
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var info_chk=[];

        $('.chk').each(function (index, obj)
        {
            if (this.checked === true)
            {
                var id = $(this).attr("id");
                info_chk.push($(this).val());
            }
        });

        $.ajax({
            url: "<?= site_url('Exchange/FileMovement/saveDispatchedRecord') ?>",
            type: "post",
            data: "chk1= " + info_chk + "&module=receive",
            data: 
            {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                chk1:info_chk,
                module:'receive'
            },
            success: function(data)
            {
                console.log(data);
                /*if(data=='')
                {
                    $('#dv_res2').html('<p align=center><font color=red>Successfully Received</font></p>');
                    setTimeout(function() {
                        $('input[name=btnGetR]').trigger('click');
                    }, 1000);
                }
                else
                {
                    $('#dv_res2').html(data);
                }*/

                $('#dv_res2').html('<p align=center><font color=red>"'+data.message+'"</font></p>');
                    setTimeout(function()
                    {
                        $('input[name=btnGetR]').trigger('click');
                    }, 1000);
                updateCSRFToken();
            },
            error: function(xhr, status, error)
            {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
                return false;
            }
        });
    });
</script>