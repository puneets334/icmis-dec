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
    <style>
        #address,#case,#postal,#name,#disMode
        {
            display:none;
        }
        #printable
        {
            margin-top: 3%;
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
                                    <h3 class="card-title">R & I >> Receipt </h3>
                                </div>


                            </div>
                            <br><br>

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

                        <?php //= view('RI/RIReceiptHeading'); ?>

                        <br><br>
                        <div class="container-fluid">
                            <h4 class="page-header" style="margin-left: 1%">Received Query</h4>
                            <br><br>


                            <?php
                            $attribute = array('class' => 'form-horizontal','name' =>"recievedQuery", 'id' => "recievedQuery", 'autocomplete' => 'off');
                            echo form_open(base_url('#'), $attribute);
                            ?>



                            <div class="row">
                                <div class="col-sm-2">
                                    <h4 class="box-title" id="selac">Search By : </h4><br>
                                    <div class="form-group ">
                                            <select id="sa" name="sa" class="form-control" onclick="changediv()">
                                                <option value="0">Select search by</option>
                                                <option value="1">Sender Name</option>
                                                <option value="2">Sender Address</option>
                                                <option value="3">Case Number</option>
                                                <option value="4">Postal No</option>
                                            </select>


                                    </div>
                                </div>


                            </div><br>




                                <div class="row">
                                    <!--start 1 sender name-->
                                        <div class="col-sm-3" id="name">
                                            <div class="form-group row " >
                                                <label for="rName" class="col-sm-5 col-form-label">Sender Name: </label>
                                                <div class="col-sm-7">
                                                    <input type="text" id="rName" name="rName" class="form-control" placeholder="Sender Name" value="<?= !empty($rname)?$rname:null; ?>">
                                                </div>

                                            </div>
                                        </div>
                                <!--end 1 sender name-->


                                <!--start 2 sender address-->
<!--                                <div   >-->
<!--                                    <div class="row">-->
                                        <div class="col-sm-3" id="address">
                                            <div class="form-group row " >
                                                <label for="rAdd" class="col-sm-5 col-form-label">Sender Address: </label>
                                                <div class="col-sm-7">
                                                    <input type="text" id="rAdd" name="rAdd" class="form-control" placeholder="Sender Address" value="<?= !empty($rAdd)?$rAdd:null;?>">
                                                </div>
                                            </div>
                                        </div>
<!--                                    </div>-->
<!---->
<!--                                </div>-->
                                <!--end 2 senders address-->


                                <!--start 3 case-->
                                <div class="col-sm-8" id="case">
                                    <div class="row">
                                        <div class="col-sm-4" >
                                            <div class="form-group row">
                                                <label for="caseType" class="col-sm-6 col-form-label">Case Type:</label>
                                                <div class="col-sm-6">
                                                    <select id="caseType" name="caseType" class="form-control ">
                                                        <option value='0'>Select</option>
                                                        <?php
                                                        if(!empty($caseTypes)) {
                                                            foreach ($caseTypes as $caseType) {
                                                                echo "<option value='" . $caseType['casecode'] . "'>" . $caseType['short_description'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4" >
                                            <div class="form-group row">
                                                <label for="caseNo" class="col-sm-6 col-form-label">Case No:</label>
                                                <div class="col-sm-6">
                                                    <input type="number" id="caseNo" name="caseNo" placeholder="Case No" class="form-control" value="<?=!empty($caseNo)?$caseNo:null;?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4" >
                                            <div class="form-group row">
                                                <label for="caseYear" class="col-sm-6 col-form-label">Case Year:</label>
                                                <div class="col-sm-6">
                                                    <select id="caseYear" name="caseYear" class="form-control">
                                                        <?php
                                                        for ($i=date('Y');$i>1948;$i--)
                                                        {
                                                            echo "<option value='".$i."'>".$i."</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                </div>




                                </div>

                                <!--ENd 3 case-->

                                    <div class="col-sm-3" id="postal">
                                        <div class="form-group row">

                                            <label for="postNo" class="col-sm-4 col-form-label">Postal No.:</label>
                                            <div class="col-sm-7">
                                                <input type="text" id="postNo" name="postNo" class="form-control" placeholder="Postal No." value="<?=!empty($postNo)?$postNo:null;?>">
                                            </div>
                                        </div>
                                    </div>




                                    <div class="col-sm-3" id="disMode">
                                        <div class="form-group row">
                                            <label for="dMode" class="col-sm-5 col-form-label">Dispatch Mode:</label>
                                            <div class="col-sm-7">
                                                <select id="dMode" name="dMode" class="form-control">
                                                    <option value='0'>All</option>
                                                    <?php
                                                    if(!empty($dispatchModes)) {
                                                        foreach ($dispatchModes as $dMode) {
                                                            echo "<option value='" . $dMode['postal_type_code'] . "'>" . $dMode['postal_type_description'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                            <br><br><br><br>
                            <div style="display:flex;justify-content:center" >
                                <button type="button" id="btn" name="btn" class="btn btn-primary col-sm-2" onclick="data_fetch()">View</button>
                            </div>

                            <?php form_close();?>

                            <div id="printable"></div>

                            <br><br>






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


    function changediv()
    {


        var searchby = $('#sa').val();

        $('#printable').hide();
        if(searchby!=0)
        {
            $('#disMode').show();
        }
        else
        {
            $('#disMode').hide();
        }

        if(searchby == '1')
        {
            $('#name').show();
            $('#address').hide();
            $('#case').hide();
            $('#postal').hide();


        }
        else if(searchby=='2')
        {
            $('#address').show();
            $('#name').hide();
            $('#case').hide();
            $('#postal').hide();

        }
        else if(searchby=='3')
        {
            $('#case').show();
            $('#name').hide();
            $('#address').hide();
            $('#postal').hide();

        }
        else if(searchby == '4')
        {
            $('#postal').show();
            $('#case').hide();
            $('#name').hide();
            $('#address').hide();


        }
        else if(searchby==0)
        {
            $('#postal').hide();
            $('#case').hide();
            $('#name').hide();
            $('#address').hide();
        }
    }



    function data_fetch()
    {

        // var searchby = $("input[name='optradio']:checked").val();
        var searchby = $('#sa').val();
        var rName = $('#rName').val();
        var rAdd = $('#rAdd').val();
        var caseType = $('#caseType').val();
        var caseNo = $('#caseNo').val();
        var caseYear = $('#caseYear').val();
        var postNo = $('#postNo').val();
        var dMode = $('#dMode').val();
        $('#printable').hide();
        if(searchby==0)
        {
            alert('Please select search By');
        }
        else if(searchby == 1 && rName=='')
        {
            alert('Please enter Sender Name');
            $('#rName').focus();
            return;
        }
        else if(searchby== 2 && rAdd=='')
        {
            alert('Please enter Sender Addresss');
            $('#rAdd').focus();
            return;
        }
        else if(searchby== 3 && caseType==0)
        {
            alert('Please select Case Type');
            $('#caseType').focus();
            return;
        }
        else if(searchby==3 && caseNo=='')
        {
            alert('Please enter Case No');
            $('#caseNo').focus();
            return;
        }
        else if(searchby==4 && postNo=='')
        {
            alert('Please enter Postal Number');
            $('#postNo').focus();
            return;
        }
        else
        {

            $.ajax({
                type: 'POST',
                url:'<?=base_url('/RI/ReceiptController/getReceivedData');?>',
                data:  $("#recievedQuery").serialize(),
                success: function (result) {
                    updateCSRFToken();
                    $("#printable").html(result);
                    $('#printable').show();

                }

            });

            //$.post("<?//=base_url('RI/ReceiptController/getReceivedData')?>//", $("#recievedQuery").serialize(), function (result)
            //{
            //    //alert(result);
            //    $("#printable").html(result);
            //    $('#printable').show();
            //});
        }
    }

</script>