<?=view('header'); ?>
<section class="content " >
    <div class="container-fluid">
        <div class="row" >
        <div class="col-12" >
        <div class="card" >
        <div class="card-body" >
    <div id="dv_content1"   >
    <?= csrf_field() ?>
        <div style="text-align: center">
            <div class="row">
                <div class="col-sm-12">
                    <?php if(isset($_SESSION['success'])){?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?=$_SESSION['success']; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <?php if(isset($_SESSION['fail'])){?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?=$_SESSION['fail']; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <div  class="col-sm-6 form-group">
                        <label class="text-primary">Search Option : </label>
                        <label class="radio-inline"><input type="radio" name="rdbtn_select" id="radioct">Case Type</label>
                        <label class="radio-inline"><input type="radio" name="rdbtn_select" id="radiodn" checked>Diary No.</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label for="caseType">Case Type</label>
                    <select  class="form-control" name="caseType" tabindex="1" id="selct" disabled="">
                        <option value="">Select</option>
                        <?php
                        foreach(getCaseType() as $caseType){
                            echo '<option value="' . $caseType['casecode'] . '">'. $caseType['short_description']. '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="caseNo">Case No.</label>
                    <input class="form-control" id="case_no" name="case_no" placeholder="Case Number" type="number" maxlength="5" disabled="">
                </div>
                <div class="col-sm-2">
                    <label for="caseYear">Year</label>
                    <select class="form-control" id="case_yr" name="case_yr" disabled="">
                        <?php
                        for($year=date('Y'); $year>=1950; $year--)
                            echo '<option value="'.$year.'">'.$year.'</option>';
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="caseNo">Diary No.</label>
                    <input class="form-control" id="dno" name="dno" placeholder="Diary Number" size="4" value="<?php echo isset($_SESSION['session_diary_no']) ? $_SESSION['session_diary_no'] : ''; ?>">
                </div>
                <div class="col-sm-2">
                    <label for="caseYear">Year</label>
                    <select class="form-control" id="dyr" name="dyr">
                        <?php
                        for($year=date('Y'); $year>=1950; $year--)
                            echo '<option value="'.$year.'">'.$year.'</option>';
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="view">&nbsp;</label>
                    <button type="button" name="btnGetR" class="btn btn-block btn-primary" onclick="fsubmit()">GET DETAILS</button>
                </div>
            </div>
        </div>
        <div  align="center">
            <!--start to pdf related-->
            <form method="post" id="pdfForm" action="<?=base_url()?>/Faster/FasterController/get_cause_title_request_save" target="_blank">
                <?= csrf_field() ?>
                <input type="hidden" name="d_no" id="d_no_filter" value="">
                <input type="hidden" name="d_yr" id="d_yr_filter" value="">
                <input type="hidden" name="ct" id="ct_filter" value="">
                <input type="hidden" name="cn" id="cn_filter" value="">
                <input type="hidden" name="cy" id="cy_filter" value="">
                <input type="hidden" name="tab" id="tab_filter" value="">
                <textarea name="pdfcontent" id="pdfcontent" style="display: none;"></textarea>

                <button id="submitSavePDF" type="submit" style="display:none;">Save</button>
            </form>

        </div>

            <div align="center" id="action_after_load" style="display: none;">

                <input name="prnnt1" id="prnnt1" value="Print" type="button"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" id="clickSavePDF" onclick="savepdf()"  style="display:none;">Save</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <!--start view pdf already exist cause title-->
                <span id="viewpdf"></span>
                <!--end view pdf already exist cause title-->
            </div>
            <div id="dv_res1"> </div>

    </div>

    <div id="overlay" style="display:none;">&nbsp;</div>
        </div>
        </div>
        </div>
        </div>
    </div>
</section>
<script src="<?=base_url()?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>/assets/js/select2.full.min.js"></script>
<!--<script src="<?/*=base_url()*/?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?/*=base_url()*/?>assets/plugins/fastclick/fastclick.js"></script>-->
<script src="<?=base_url()?>/assets/js/app.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jszip.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.colVis.min.js"></script>
<script>
    $(document).ready(function(){
        $(document).on('click','#sub',function(){
            var d_no=document.getElementById('t_h_cno').value;
            var d_yr=document.getElementById('t_h_cyt').value;
            $.ajax({
                url: 'get_ammended_cause_title.php',
                cache: false,
                async: true,
                data: {d_no: d_no,d_yr:d_yr},
                beforeSend:function(){
                    $('#div_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('#div_result').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });

        $(document).on("click","#prnnt1",function() {
            var prtContent = $("#prnnt").html();
            var temp_str = prtContent;
            var mywindow = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=5, cellpadding=5');
            var is_chrome = Boolean(mywindow.chrome);
            mywindow.document.write(prtContent);
            if (is_chrome) {
                setTimeout(function () { // wait until all resources loaded
                    mywindow.document.close(); // necessary for IE >= 10
                    mywindow.focus(); // necessary for IE >= 10
                    mywindow.print();  // change window to winPrint
                }, 20);
            }
            else {
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10
                mywindow.print();
            }
        });

        $("#radiodn").click(function(){
            $("#dno").prop('disabled', false);
            $("#dyr").prop('disabled', false);
            $("#selct").prop('disabled',true);
            $("#case_no").prop('disabled',true);
            $("#case_yr").prop('disabled',true);
            $("#selct").val("-1");
            $("#case_no").val("");
            $("#case_yr").val("");
        });

        $("#radioct").click(function(){
            $("#dno").prop('disabled',true);
            $("#dyr").prop('disabled',true);
            $("#dno").val("");
            $("#dyr").val("");
            $("#selct").prop('disabled', false);
            $("#case_no").prop('disabled', false);
            $("#case_yr").prop('disabled', false);
        });
    });
<?php  if (isset($_GET['diaryno'])){
    $diary_no = substr($_GET['diaryno'], 0, strlen($_GET['diaryno']) - 4);
?>
    $("#dno").val('<?php echo $diary_no?>');
    fsubmit();
<?php }?>
    function savepdf(){
        //alert('Go to savepdf');
        swal({
                title: "Are you sure?",
                text: "You want to save?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Save it!",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {
                    // $( "#submitSavePDF" ).click();
                    $('#pdfForm').submit();
                    swal({title: "Success!",text: "Your PDF has been saved successfully",icon: "success",button: "success!"});
                    setTimeout(function(){
                        fsubmit();
                    }, 2000);
                } else {
                    swal("Cancelled", "Your pdf is not save please try again :)", "error");
                }
            });
    }

    function fsubmit()
    { //alert('fsubmit akg');
        document.getElementById("dv_res1").innerHTML = '';
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');
        if($("#radioct").is(':checked')){
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();

            if(!regNum.test(cstype)){
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
        else if($("#radiodn").is(':checked')){
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
        else{
            alert('Please Select Any Option');
            return false;
        }
        /*start to pdf related*/
        $('#d_no_filter').val(diaryno);
        $('#d_yr_filter').val(diaryyear);
        $('#ct_filter').val(cstype);
        $('#cn_filter').val(csno);
        $('#cy_filter').val(csyr);
        $('#tab_filter').val('Case Details');
        var image_url = "<?php echo base_url('assets/images/load.gif');?>";
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        /*end to pdf related*/
        $.ajax({
            type: 'POST',
            url:"<?=base_url()?>/Faster/FasterController/get_cause_title_request",
            beforeSend: function (xhr) {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='"+image_url+"'></div>");
            },
            data:{CSRF_TOKEN: CSRF_TOKEN_VALUE, d_no:diaryno,d_yr:diaryyear,ct:cstype,cn:csno,cy:csyr,tab:'Case Details'},
            success: function (msg){
                updateCSRFToken();
                if (msg == 404) {
                    var msg_404 = "<p align=center><font color=red>Case Not Found</font></p>";
                    $("#dv_res1").html(msg_404);
                    $("#action_after_load").hide();
                    $("#clickSavePDF").hide();
                    $('#viewpdf').html('');
                } else {
                    $("#dv_res1").html(msg);
                    $("#pdfcontent").html(msg);
                    $("#action_after_load").show();
                    $("#clickSavePDF").show();
                    var viewpdf = $('#viewpdf_load').val();
                    $('#viewpdf').html(viewpdf);
                }
            },
            error: function (xhr) {
                updateCSRFToken();
                console.log("Error: " + xhr.status + " " + xhr.statusText);
            }
        })
    }

</script>
<script>
    function savepdf_stope()
    { 
        alert('savepdf akg');
        document.getElementById("dv_res1").innerHTML = '';
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');

        if($("#radioct").is(':checked')){
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();

            if(!regNum.test(cstype)){
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
        else if($("#radiodn").is(':checked')){
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
        else{
            alert('Please Select Any Option');
            return false;
        }
        var image_url = "<?php echo base_url('assets/images/load.gif');?>";
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url:"<?=base_url()?>/Faster/FasterController/get_cause_title_request",
            beforeSend: function (xhr) {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='"+image_url+"'></div>");
            },
            data:{CSRF_TOKEN: CSRF_TOKEN_VALUE, d_no:diaryno,d_yr:diaryyear,ct:cstype,cn:csno,cy:csyr,tab:'Case Details'},
            success: function (msg){
                updateCSRFToken();
                $("#prnnt").html();
                var pdfcontent=$("#prnnt").html();
                /*start to pdf related*/
                $.ajax({
                    type: 'POST',
                    url:"<?=base_url()?>/Faster/FasterController/get_cause_title_request_save",
                    beforeSend: function (xhr) {
                        $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='"+image_url+"'></div>");
                    },
                    data:{CSRF_TOKEN: CSRF_TOKEN_VALUE, d_no:diaryno,d_yr:diaryyear,ct:cstype,cn:csno,cy:csyr,tab:'Case Details',pdfcontent:pdfcontent},
                    success: function (msg){
                        updateCSRFToken();
                        $("#dv_res1").html(msg);
                        var pdfcontent=msg;
                        alert(pdfcontent);
                    },
                    error: function (xhr) {
                        updateCSRFToken();
                        alert("ERROR, Please Contact Server Room");
                    }
                })
            },
            error: function (xhr) {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            }
        })
    }

</script>
<script>
    $(document).on("focus",".dtp",function(){
        $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
        });
    });
</script>




