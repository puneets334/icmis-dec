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
                                <h3 class="card-title">Management Reports >> Pending >> Institution Disposal</h3>
                            </div>
                        </div>
                    </div>    <br><br> 
                    <div class="container-fluid">

                        <form id="dispatchQuery">
                            <?= csrf_field(); ?> 

                            <div class="row">

                                <div class="form-group col-sm-2">
                                <label for="caseType" class="text-right">Month</label>
                                <td>
                                    <select name="ddlMonth" id="ddlMonth" size='1'>
                                    <?php
                                    for ($i = 0; $i < 12; $i++) {
                                        $time = strtotime(sprintf('%d months', $i));
                                        $label = date('F', $time);
                                        $value = date('n', $time);
                                        echo "<option value='$value'>$label</option>";
                                    }
                                    ?>
                                    </select>
                                </td>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label for="caseNo" class="text-right">Year</label>
                                    <td>
                                        <select name="ddlYear" id="ddlYear" size='1'>
                                            <?php
                                            for ($i = date("Y"); $i > 2016; $i--) {
                                                echo "<option value='$i'>$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </div>
                            </div>
                            <div class="row">
                                    <label for="button" class="text-right">&nbsp;</label>
                                    <button type="button" style="text-align:center; width:98%; margin-left:1%; margin-right:1%;" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">Show Report</button>
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
    $(function () {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,

            autoclose:true
        });
    });
  function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
      }

    function check()
    {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var ddlMonth = $('#ddlMonth').val();
            var ddlYear = $('#ddlYear').val();
           
       
            $.ajax({
            type: "POST",
            data: $("#dispatchQuery").serialize(),  
            dataType: 'html', 
            url: "<?php echo base_url('/ManagementReports/Pending/InstitutionDisposalPost'); ?>",
            success: function(data) {
                alert("Success!");
                // $('.card-title').hide();
                // $('.page-header').hide();
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

</script>

