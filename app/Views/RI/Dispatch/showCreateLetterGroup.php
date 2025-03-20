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


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">R & I  >> Create Letter Group</h3>
                                </div>
                            </div>
                            <?php if (session()->getFlashdata('infomsg')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('infomsg'); ?></strong>
                                </div>

                            <?php } ?>
                            <?php if (session()->getFlashdata('success_msg')) : ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                </div>
                            <?php endif; ?>



                        </div>


                        <span class="alert alert-error" style="display: none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <span class="form-response"> </span>
                                </span>

                        <?//= view('RI/RIDispatchHeading'); ?>
                        </br></br>

                        <div class="container-fluid">
                            <div class="row">
                            <div class="col-md-12">
                            <?php
                            $attribute = array('class' => 'form-horizontal', 'name' => 'searchMainLetter', 'id' => 'searchMainLetter', 'autocomplete' => 'off', 'method' => 'POST');
                            echo form_open(base_url('#'), $attribute);
                            ?>

                          <!--  <form id="searchMainLetter" method="post">-->

                                <div id="divProcessIdWise" class="row">
                                    <div class="form-group col-sm-2">
                                        <label for="processId">Process Id</label>
                                        <input type="number" id="processId" name="processId" class="form-control number" placeholder="Process Id" value="">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="processYear">Process Year</label>
                                        <select id="processYear" name="processYear" class="form-control">
                                            <?php
                                            for($i=date("Y");$i>1949;$i--)
                                            {
                                                echo "<option value=".$i.">$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                     
                                    <div class="form-group col-sm-2 mt-4">
                                        <label for="from" class="text-right">&nbsp;</label>
                                        <button type="button" id="btnGetCases" class="btn btn-info form-control" onclick="checkFunction();">Search</button>
                                    </div>
                                    
                                </div>
                            <!--</form>-->
                            <?php form_close(); ?>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="dataProcessId"> </div>
                                </div>
                            </div>
                            <!-- /.content -->
                            <!--</div>-->
                            <!-- /.container -->
                        </div>
                        <br>
                        <br>
                        <br>



                </div> <!-- card div -->

            </div>
        <!-- /.col -->
     </div>
        <!-- /.row -->

  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.section -->

<script>

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function checkFunction()
    {
        // alert("rrrr");
       var processId = $("#processId").val();
       var processYear = $("#processYear").val();
       var CSRF_TOKEN = 'CSRF_TOKEN';
       var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
       if (processId == "") {
           alert("Enter Process Id.");
           $("#processId").focus();
           return false;
       }
       var dynamicUrl = "<?php echo base_url('RI/DispatchController/searchMainLetter'); ?>";
        
        $.ajax({
        url: dynamicUrl, 
        type: "POST",
        data: $("#searchMainLetter").serialize(), 
        success: function(data) {
         updateCSRFToken();
         //$('.card-title').hide();
        // $('.page-header').hide();
         $("#dataProcessId").html(data);
        // $("#dispatchDakToRI").hide(); 
        },
        error: function(xhr, status, error) {
            updateCSRFToken();
            console.log("An error occurred: " + error);
        }
    });
    }

    $('.number').keypress(function(event) {

        if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
            return true;

        else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
            event.preventDefault();
    });

    function goNextFunction()
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        validationError = true;
        $("#divProcessIdWise").hide();


        var mainletter =  $("#radioDiv input[type='radio']:checked");
        var mainletter = mainletter.val();
        // alert(mainletter);
        if ( mainletter === undefined) {
            alert("Please Select main letter!!");
            validationError = false;
            return false;
        }else{
           if(validationError) {
               $.ajax({
                   type: "POST",
                   data: {
                       CSRF_TOKEN: CSRF_TOKEN_VALUE,
                       selectedCase: mainletter
                   },
                   // dataType: 'JSON',
                   url: "<?php echo base_url('RI/DispatchController/goToNextPage'); ?>",
                   success: function (data) {
                       // alert(data);
                       $("#dataProcessId").html(data);
                       updateCSRFToken();
                   },
                   error: function (data) {
                       alert(data);
                       updateCSRFToken();
                   }
               });

           }
        }
        // alert("SSS"+selectedCase);

        //$.post("<?//=base_url()?>//index.php/RIController/goToNextPage", {'selectedCase': selectedCase}, function (result) {
        //    $("#dataProcessId").html(result);
        //});
        //$.post("<?//=base_url()?>//index.php/RIController/getConnectedLetters", {'selectedCase': selectedCase}, function (result) {
        //    $("#divConnectedLetters").html(result);
        //});
    }

</script>