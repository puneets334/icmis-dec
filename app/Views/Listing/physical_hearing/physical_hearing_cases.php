<?= view('header') ?>
<style>
    #declineButton,#updateAdvocateConsentButton {
        background-attachment: scroll;
        background-clip: border-box;
        background-color: rgb(96, 96, 96);
        background-image: none;
        background-origin: padding-box;
        background-position: 0% 0%;
        background-position-x: 0%;
        background-position-y: 0%;
        background-repeat: repeat;
        background-size: auto auto;
        border-bottom-color: rgb(255, 255, 255);
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        border-bottom-style: none;
        border-bottom-width: 0px;
        border-image-outset: 0;
        border-image-repeat: stretch stretch;
        border-image-slice: 100%;
        border-image-source: none;
        border-image-width: 1;
        border-left-color: rgb(255, 255, 255);
        border-left-style: none;
        border-left-width: 0px;
        border-right-color: rgb(255, 255, 255);
        border-right-style: none;
        border-right-width: 0px;
        border-top-color: rgb(255, 255, 255);
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        border-top-style: none;
        border-top-width: 0px;
        color: rgb(255, 255, 255);
        cursor: pointer;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        line-height: 14px;
        margin-bottom: 0px;
        margin-left: 0px;
        margin-right: 0px;
        margin-top: 0px;
        padding-bottom: 4px;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 4px;
    }
    #declineButton {
      font-size: 20px;
        font-weight: bold;
        margin: 5px;
        padding:8px;
    }
    #declineButton:hover {
        background-color: rgb(200, 26, 26);
    }
    #updateAdvocateConsentButton {
        font-size: 20px;
        font-weight: bold;
        margin: 5px;
        padding:8px;
    }
    #updateAdvocateConsentButton:hover {
        background-color: rgb(200, 26, 26);
    }
</style>
<link rel="stylesheet" href="../../css/jquery-ui.css">

<script src="../../jquery/jquery-1.9.1.js"></script>
<link href="../../css/jquery.dataTables.css" rel="stylesheet" />
<script src="../../js/jquery.dataTables.js"></script>
<script src="../../js/jquery-ui.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> PHYSICAL HEARING CASES CONSENT </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">
                        <?= csrf_field() ?>
                            <div id="tabs">
                                <ul>
                                    <li><a href="#tabs-1">All-Inclusive Consent</a></li>
                                    <li><a href="#tabs-2">Individual Consent</a></li>
                                </ul>
                                <div id="tabs-1">

                                    <div class="col-sm-12" STYLE="text-align: center">
                                        <div class="col-sm-12" id="myform">
                                            <label class="radio-inline">
                                                <input type="radio" class="optradio" name="optradio" checked value="M">&nbsp;Miscellaneous
                                            </label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <label class="radio-inline">
                                                <input type="radio" class="optradio" name="optradio" value="F">&nbsp;Regular
                                            </label>
                                            <span style="margin-left: 40px;">
                                                <input type="button" class="getCases" value="Get Cases" onclick="javascript:getAllCasesList();">
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div id="physicalHearingCases">

                                    </div>
                                </div>
                                <div id="tabs-2">
                                    <?php
                                    // include("advocate_case_consent.php");
                                    ?>
                                    <?= view('Listing/physical_hearing/advocate_case_consent') ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- Main content end -->
                </div> <!-- /.card -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<script>

    $(document).ready(function() {
        //$.fn.dataTableExt.sErrMode = 'throw';
        $('#example1').DataTable({
            "ordering": false
        });

        $("tr:odd").css({
            "background-color":"#F4F4F4"
            });

    } );

    $( function() {
        $( "#tabs" ).tabs();
    } );


    async function getAllCasesList() {
        await updateCSRFTokenSync();
        $('#show_error').html("");
        var caseCategory=$("#myform input[type='radio']:checked").val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();        
        $.ajax({
            url: "<?php echo base_url('Listing/PhysicalHearing/get_vacation_advance_list'); ?>",
            cache: false,
            async: true,
            data: {case_category: caseCategory, CSRF_TOKEN:CSRF_TOKEN_VALUE},
            beforeSend:function(){
                updateCSRFTokenSync();
                $('#result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFTokenSync();
                $("#physicalHearingCases").html(data);
            },
            error: function(xhr) {
                updateCSRFTokenSync();
                $("#physicalHearingCases").html('');
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    }

    // function confirmBeforeDecline() {
    //     var allVals = [];
    //     var noOfCases;
    //     $("#physicalHearingCases#example20 input:radio:checked").each(function() {
    //         allVals.push($(this).val());
    //     });
    //     console.log(allVals);
    //     console.log(allVals);
    //     noOfCases = allVals.length;
    //     if(noOfCases < 1)
    //     {
    //         alert('Please give the consent for atleast one Case ');
    //         return false;
    //     }
    //     else
    //     {

    //         var choice = confirm('Do you really want to decline the case.....?');
    //         if (choice == true) {
    //             declineVacationCase(allVals);
    //         }
    //         else
    //         {
    //             return false;
    //         }
    //     }

    // }

    function confirmBeforeDecline() {
        var allVals = [];
        var noOfCases;

        // Corrected selector (Assuming #physicalHearingCases is the container)
        $("#physicalHearingCases input:radio:checked").each(function() {
            allVals.push($(this).val());
        });

        console.log(allVals); // Log selected values

        noOfCases = allVals.length;

        if (noOfCases < 1) {
            alert('Please give consent for at least one case.');
            return false;
        } else {
            var choice = confirm('Do you really want to decline the case?');
            if (choice) {
                declineVacationCase(allVals);
            }
            return false;
        }
    }


    function confirmBeforeList(diary_no) {
        var choice = confirm('Do you really want to List the Selected Case.....?');
        if(choice == true) {
            ListVacationCase(diary_no);
        }

    }

    async function declineVacationCase(allVals)
    {
        await updateCSRFTokenSync();
        var caseCategory    = $("#myform input[type='radio']:checked").val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var empID   = $('#empid').val();
        var userID  = $('#user_code').val();
        
        $.ajax
        ({
            // url: 'declineVacationListCases.php',
            url: "<?php echo base_url('Listing/PhysicalHearing/decline_vacation_list_cases'); ?>",
            type: "POST",
            data: {diary_no: allVals,empID:empID,userID:userID,CSRF_TOKEN:CSRF_TOKEN_VALUE},
            cache: false,
            success: function (r)
            {
                updateCSRFTokenSync();
                if(r != 0){
                    alert('Selected Cases Consent Successfully Updated');
                }
                else{
                    alert("Invalid Diary No. !! Please try again...");
                }
                getAllCasesList();
            },
            error: function () {
                alert('ERROR');
            }
        });
    }

    function ListVacationCase(diary_no)
    {
        var empID=$('#empid').val();
        var userID=$('#user_code').val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax
        ({
            //url: 'restoreVacationAdvanceList.php',
            url: "<?php echo base_url('Listing/PhysicalHearing/restore_vacation_advance_list'); ?>",
            type: "POST",
            data: {diary_no: diary_no,empID:empID,userID:userID, CSRF_TOKEN:CSRF_TOKEN_VALUE},
            cache: false,
            success: function (r)
            {
                //alert('Selected  documents with ID:'+id+' Successfully Deleted');
                updateCSRFTokenSync();
                if(r!=0){
                    alert('Selected Case with Diary NO:'+diary_no+' Successfully Listed');
                }
                else{
                    alert("Invalid Diary No .!! Please try again...");
                }
                getVacationAdvanceList();

            },
            error: function () {
                updateCSRFTokenSync();
                alert('ERROR');
            }
        });
    }
</script>


