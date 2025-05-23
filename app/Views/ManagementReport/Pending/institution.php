<?= view('header') ?>
    <style>
        .custom-radio {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .custom_action_menu {
            float: left;
            display: inline-block;
            margin-left: 10px;
        }

        .basic_heading {
            text-align: center;
            color: #31B0D5
        }

        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }

        .card-header {
            padding: 5px;
        }

        h4 {
            line-height: 0px;
        }

        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
        a {color:darkslategrey}      /* Unvisited link  */

        a:hover {color:black}    /* Mouse over link */
        a:active {color:#0000FF;}  /* Selected link   */

        .box.box-success {
            border-top-color: #00a65a;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }
        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }
        .box.box-danger {
            border-top-color: #dd4b39;
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
                                <h3 class="card-title">Management Reports >> Pending >> Institution</h3>
                            </div>
                        </div>
                    </div>    <br><br> 
                    <div class="container-fluid">

                        <form id="dispatchQuery">
                            <?= csrf_field(); ?> 

                            <div class="row">

                                <div class="form-group col-sm-2">
                                <label for="caseType" class="text-right">From Date</label>
                                   <td><input type="text" name="from_date" id="from_date" class="dtp"></td>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="caseNo" class="text-right">To Date</label>
                                    <td><input type="text" name="to_date" id="to_date" class="dtp"></td>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="caseYear">Select Option</label>
                                    <td>
                                        <select name="rpt_type" id="rpt_type" required>
                                            <option value="filing">Diary Filing</option>
                                            <option value="registration">Fresh Registration</option>
                                            <option value="institution">Institution</option>
                                            <option value="defect">Defect</option>
                                            <option value="refiling">Refiling</option>
                                        </select>
                                    </td>
                                </div>

                            </div>
                         

                            <div class="row col-md-2">
                                    <label for="button" class="text-right">&nbsp;</label>
                                    <center><button type="button" style="text-align:center;" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">SHOW REPORT</button></center>
                            </div>
                        </form>
                        <div></div>
                        <div id="printable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    // $(function () {
    //     $('.datepick').datepicker({
    //         format: 'dd-mm-yyyy',
    //         todayHighlight: true,

    //         autoclose:true
    //     });
    // });
  function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
      }

      function check() {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var rpt_type = $('#rpt_type').val();

    // Basic validation
    if (!from_date || !to_date || !rpt_type) {
        alert("Please select From Date, To Date, and Report Type.");
        return false;
    }

    $.ajax({
        type: "POST",
        data: $("#dispatchQuery").serialize(),  
        dataType: 'html', 
        beforeSend: function() {
            $('#printable').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
        },
        url: "<?php echo base_url('/ManagementReports/Pending/institution_report_post'); ?>",
        success: function(data) {
            $("#printable").html(data);  
            $("#dispatchDakFromRI").hide(); 
            updateCSRFToken();  
        },
        error: function() {
            alert('No Data Found');
            updateCSRFToken();  
        }
    });
}

    function check_error(fromDate, toDate, param) {
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);

        if (fromDate!= toDate)
        {
            if (fromDate == "") {
                alert('Enter From Date');
                if (param == 1) {
                    $("#fromStoRI").focus();
                }
                else if (param == 2) {
                    $('#fromRItoS').focus();
                }
                else if (param == 3) {
                    $('#fromRItoR').focus();
                }
                return false;
            }

            else if (toDate == "") {
                alert('Enter To Date');
                if (param == 1) {
                    $("#toStoRI").focus();
                }
                else if (param == 2) {
                    $('#toRItoS').focus();
                }
                else if (param == 3) {
                    $('#toRItoR').focus();
                }
                return false;
            }
            else if (date1 > date2) {
                alert("To Date must be greater than From date");
                if (param == 1) {
                    $("#toStoRI").focus();
                }
                else if (param == 2) {
                    $('#toRItoS').focus();
                }
                else if (param == 3) {
                    $('#toRItoR').focus();
                }
                return false;
            }
            else {
                return true;
            }
        }
    }

    $(document).on("click","#print1",function(){
    var prtContent = $("#prnTable").html();
    var temp_str=prtContent;
    var WinPrint = window.open('','','left=10,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write(temp_str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
});

</script>

