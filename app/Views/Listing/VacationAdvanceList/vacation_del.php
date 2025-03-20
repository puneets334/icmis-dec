<?= view('header') ?>
<style>
    #declineButton {
        background-attachment: scroll;
        background-clip: border-box;
        background-color: rgb(26, 26, 26);
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

    #declineButton:hover {
        background-color: rgb(200, 26, 26);
    }

    .header {
        padding: 10px 16px;
        background: #555;
        color: #f1f1f1;
    }

    .sticky {
        position: fixed;
        top: 0;
        width: 100%;
    }

    .sticky+.display {
        padding-top: 66px;
    }

    @media print {
        table {
            width: 100% !important;
            margin-left: -0.1% !important;
        }

        .headings {
            display: table-header-group !important;
            margin-bottom: -33.2% !important;
            margin-left: 14.1% !important;
        }

        .headings:not(:first-of-type) {
            display: none !important;
        }

        tfoot {
            display: none !important;
        }

        .print {
            display: none;
        }

    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Decline (Vacation List Cases)</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                    <div>
                        <form method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" id="user_code" value="<?php echo session()->get('login')['usercode']; ?>" />
                            <input type="hidden" id="empid" value="<?php echo session()->get('login')['usercode']; ?>" />
                            <input type="hidden" id="usertype" value=" <?php echo session()->get('login')['usertype']; ?>">
                            <div id='vacationAdvanceList'></div>
                            <div id="dv_content1"></div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        updateCSRFToken();
        getVacationAdvanceList();
    });

    function getVacationAdvanceList() {
        
        var userID = $('#user_code').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $("#vacationAdvanceList").html("<center><img src='../../images/load.gif' alt='Loading...' title='Loading...' /></center>");
        $.ajax({

            url: '<?php echo base_url('Listing/VacationAdvanceList/getVacationAdvanceList'); ?>',
            type: "POST",
            data: {
                userID: userID,
                CSRF_TOKEN: csrf,
            },
            cache: false,
            success: function(r) {
                updateCSRFToken();
                //console.log(r);
                if (r != 0) {
                    $("#vacationAdvanceList").html(r);
                } else {
                    alert("Error in uploading Uploading Vacation Advance List");
                    $("#vacationAdvanceList").html("");
                }
            },
            error: function() {
                updateCSRFToken();
                if (r != 0 || r != '') {
                    alert('Error in get  Vacation Advance List');
                }
            }
        });
    }

    /*$(document).on("focus", ".dtp", function() {
        $("#dtp").datepicker();
    });*/

    function confirmBeforeList(diary_no) {
        var choice = confirm('Do you really want to List the Selected Case.....?');
        if (choice == true) {
            ListVacationCase(diary_no);
        }

    }

    function declineVacationCase(allVals) {
        var empID = $('#empid').val();
        var userID = $('#user_code').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/VacationAdvanceList/declineVacationListCases'); ?>',
            type: "POST",
            data: {
                diary_no: allVals,
                empID: empID,
                userID: userID,
                CSRF_TOKEN: csrf,
            },
            cache: false,
            success: function(r) {
                updateCSRFToken();
                if (r != '') {
                    alert('Selected Case with Diary No:(' + allVals + ') Successfully Declined');
                    //var data = JSON.parse(r);
                    $.each(r, function(index, el) {
                        $('#d_' + index).html(el);
                    });
                } else {
                    alert("Invalid Diary No. !! Please try again...");
                }
            },
            error: function() {
                updateCSRFToken();
                alert('ERROR');
            }
        });
    }

    function ListVacationCase(diary_no) {
        var empID = $('#empid').val();
        var userID = $('#user_code').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/VacationAdvanceList/restoreVacationAdvanceList'); ?>',
            type: "POST",
            data: {
                diary_no: diary_no,
                empID: empID,
                userID: userID,
                CSRF_TOKEN: csrf,
            },
            cache: false,
            success: function(r) {
                updateCSRFToken();
                if (r != '') {
                    alert('Selected Case with Diary NO:' + diary_no + ' Successfully Listed');
                    $('#d_' + diary_no).html(r.html);
                } else {
                    alert("Invalid Diary No .!! Please try again...");
                }
            },
            error: function() {
                updateCSRFToken();
                alert('ERROR');
            }
        });
    }

    function printPage() {
        window.print();
    }
</script>