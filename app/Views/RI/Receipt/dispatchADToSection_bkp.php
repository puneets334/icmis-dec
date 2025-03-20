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

                        <?= view('RI/RIReceiptHeading'); ?>

                        <br><br>
                        <div class="container-fluid">
                            <h4 class="page-header" style="margin-left: 1%">Dispatch AD to Section</h4>
                            <br><br>


                            <?php
                            $attribute = array('class' => 'form-horizontal','name' => 'dispatchADToSection', 'id' => 'dispatchADToSection', 'autocomplete' => 'off');
                            echo form_open(base_url('RI/ReceiptController/dateWiseReceived'), $attribute);
                            ?>



                            <div class="row">
                                <div class="col-sm-5">
                                    <h4 class="box-title">Search By : </h4><br>
                                    <div class="form-group ">

                                        <label class="radio-inline"><input type="radio" name="searchBy" value="s" checked="">Date & Section</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" value="c">Case Type</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" value="d">Diary No.</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" value="p">Process Id</label>

                                    </div>
                                </div>


                            </div><br>

                          <div  id="divSection" style="display: block">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group row " >
                                        <label for="from" class="col-sm-4 col-form-label">From Date: </label>
                                        <div class="col-sm-7">
                                            <input type="date" id="fromDate" name="fromDate" class="form-control datepick" autocomplete="off" placeholder="From Date" value="<?= !empty($fromDate)?$fromDate:null; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="to_date" class="col-sm-4 col-form-label">To Date:</label>
                                        <div class="col-sm-7">
                                            <input type="date" id="toDate" name="toDate" class="form-control datepick" placeholder="From Date" autocomplete="off" value="<?= !empty($toDate)?$toDate:null; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="section" class="col-sm-4 col-form-label">Section:</label>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="dealingSection" id="dealingSection">
                                                <option value="0">All</option>
                                                <?php
                                                //                                        foreach ($dealingSections as $dealingSection) {
                                                //                                            echo '<option value="' . $dealingSection['id'] . '">' . $dealingSection['section_name'] . '</option>';
                                                //                                        }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                         </div>





                            <div  id="divCaseTypeWise" style="display: block;">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group row " >
                                            <label for="from" class="col-sm-4 col-form-label">Case Type: </label>
                                            <div class="col-sm-7">
                                                <select class="form-control" name="caseType" id="caseType">
                                                    <option value="0">Select</option>
                                                    <?php
//                                                    foreach($caseTypes as $caseType){
//                                                        echo '<option value="' . $caseType['casecode'] . '">' . $caseType['short_description'] . '</option>';
//                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group row">
                                            <label for="caseNo" class="col-sm-4 col-form-label">Case Number:</label>
                                            <div class="col-sm-7">
                                                 <input type="number" id="caseNo" name="caseNo" class="form-control" placeholder="Case Number" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group row">
                                            <label for="caseYear" class="col-sm-4 col-form-label">Case Year:</label>
                                            <div class="col-sm-7">
                                                <select id="caseYear" name="caseYear" class="form-control">
                                                    <?php
                                                    for($i=date("Y");$i>1949;$i--){
                                                        echo "<option value=".$i.">$i</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group row">
                                            <label for="section" class="col-sm-4 col-form-label">Section:</label>
                                            <div class="col-sm-7">
                                                <select class="form-control" name="dealingSection" id="dealingSection">
                                                    <option value="0">All</option>
                                                    <?php
                                                    //                                        foreach ($dealingSections as $dealingSection) {
                                                    //                                            echo '<option value="' . $dealingSection['id'] . '">' . $dealingSection['section_name'] . '</option>';
                                                    //                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <?php form_close();?>

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
