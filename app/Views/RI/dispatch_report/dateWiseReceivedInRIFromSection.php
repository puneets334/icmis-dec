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
                                <h3 class="card-title">R & I >> Datewise Action Taken in R&I</h3>
                            </div>
                        </div>
                    </div>    <br><br> 
                    <div class="container-fluid">
                        <form id="receivedInRIFromSection"  method="post" action= "#" >
                            <div class="row">
                                 <?= csrf_field(); ?>  
                                <div class="form-group col-sm-3">
                                    <label for="from" class="text-right">Action Type</label>
                                    <select class="form-control" id="letterStatus" name="letterStatus">
                                        <option value="0">Select Action Type</option>
                                        <?php
                                        foreach($letterStatus as $status){
                                            if($status['id']>1 && $status['id']<8)
                                                echo '<option value="' . $status['id'] . '">' . $status['description'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="from" class="text-right">From Date</label>
                                    <input type="text" id="fromDate" name="fromDate" autocomplete="off" class="form-control datepick" required placeholder="From Date" value="<?php //=$fromDate?>">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="from" class="text-right">To Date</label>
                                    <input type="text" id="toDate" name="toDate" autocomplete="off" class="form-control datepick" required placeholder="From Date" value="<?php //=$toDate?>">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <button type="button"  style="float:right" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">View</button>
                                </div>
                            </div>
                        </form>
                        <div id="printable"> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--</div>-->

<script>

    $(function () {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
    });
    //   function updateCSRFToken() {
    //     $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
    //         $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
    //     });
    //   }
    function check() {
         var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        if(fromDate==""){
            alert("Enter from date.");
            $("#fromDate").focus();
            return false;
        }
        else if(toDate==""){
            alert("Enter To date.");
            $("#toDate").focus();
            return false;
        }
        else if (date1 > date2) {
            alert("To Date must be greater than From date");
            $("#toDate").focus();
            return false;
        }
        var letterStatus=$("#letterStatus").val();
        if(letterStatus==0){
            alert("Select Action Type.");
            $("#letterStatus").focus();
            return false;
        }
        // $.post("<?=base_url()?>/RI/DispatchController/getDateWiseReceivedInRIFromSection", $("#receivedInRIFromSection").serialize(), function (result) {
        //     //alert(result);
        //     $("#printable").html(result);
        // });
         $.ajax({
            type: "POST",
            data: $("#receivedInRIFromSection").serialize(), 
            //dataType: 'html', 
            url: "<?php echo base_url('RI/DispatchController/getDateWiseReceivedInRIFromSection'); ?>",
            beforeSend: function() {
                    $('#printable').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
            success: function(data) {
                updateCSRFToken();                
                $("#printable").html(data);  // Assuming the server returns HTML                 
            },
            error: function() {
                updateCSRFToken();  // Ensure CSRF token is refreshed even on error
                alert('No Data Found');
               
            }
        });
    }


</script>
