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
                                    <h3 class="card-title">PIL(E) >> Pil Entry</h3>
                                </div>


                            </div>
                  

                            <?php if (session()->getFlashdata('infomsg')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('infomsg') ?></strong>
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

                                <?= view('PIL/pilReportHeading'); ?>

                       


                        <br><br>


                        <form class="form-horizontal" id="frmLetterReport" action="<?=base_url('PIL/PilController/getPilDetailByDiaryNumberForLetterGeneration')?>" method="post">
                        <?= csrf_field() ?>

                    <input type="hidden" name="usercode" id="usercode" value="<?php echo $_SESSION['login']['usercode']; ?>"/>


                    <div class="row">
                          
                            <div class="col-sm-2 text-right" id="divDiaryNo">
                                <input class="form-control" placeholder="Inward No" type="text" id="diaryNo" name="diaryNo">
                            </div>
                           
                            <div class="col-sm-2 text-left ">
                                <select class="form-control" id="diaryYear" name="diaryYear">
                                    <?php
                                    for($year=date('Y'); $year>=1950; $year--)
                                        echo '<option value="'.$year.'">'.$year.'</option>';
                                    ?>
                                </select>
                            </div>


                            <div class="col-sm-4">
                                <select  class="form-control" name="reportType" id="reportType">
                                    <option value="0">Select Letter Type</option>
                                    <option value="1">Send To Authority(For Action)</option>
                                    <option value="2">Send To Authority(For Report)</option>
                                    <option value="3">Article 235</option>

                                </select>
                            </div>




                            <div class="col-sm-2 text-left">
                                       <span class="input-group-btn">
                                           <button type="button" name="search" id="search-btn" class="btn btn-flat bg-red" onclick="getValues();"><i class="fa fa-search"></i>
                                        </button>
                                        </span>

                            </div>
                            <div class="col-sm-12">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 pull-right">
                            <span style="color: red"><?=$msg?></span>
                        </div>
                    </div>
                </form>


                       



                    </div><br><br>


                </div>

                <div id="dataForReport"></div>

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
            $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }

        function getValues(){
        var diaryNo=document.getElementById("diaryNo").value;
        var diaryYear=document.getElementById("diaryYear").value;
        var reportType=document.getElementById("reportType").value;
        //var ecPilGroupId=document.getElementById("ecPilGroupId").value;
        /*if(ecPilGroupId==0){
            alert("Please Select Group to add.");
            document.getElementById("ecPilGroupId").focus();
            return false;
        }*/
        if(diaryNo==""){
            alert("Please Enter Inward Number");
            document.getElementById("diaryNo").focus();
            return false;
        }
        if(diaryYear==""){
            alert("Please Enter Inward Year");
            document.getElementById("diaryYear").focus();
            return false;
        }
        if(reportType=="0"){
            alert("Please Enter Letter Type");
            document.getElementById("reportType").focus();
            return false;
        }


        $.post("<?=base_url()?>/PIL/PilController/getSenderAndAddressForLetterGeneration", $("#frmLetterReport").serialize(), function (result) {                /*alert(result);*/
            $("#dataForReport").html(result);
        });
    }
    </script>

