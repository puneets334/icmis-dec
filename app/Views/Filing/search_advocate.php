<?=view('header'); ?>
 
<style>
    .custom-radio{float: left; display: inline-block; margin-left: 10px; }
    .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
    .basic_heading{text-align: center;color: #31B0D5}
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
</style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing >> Advocate</h3>
                                </div>

                                <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <!-- <div class="card-header p-2" style="background-color: #fff;">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#diary_generation_tab_panel" data-toggle="tab">Basic Details</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#petitioner_tab_panel" data-toggle="tab">Petitioner</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#respondent_tab_panel" data-toggle="tab">Respondent</a></li>
                                        </ul>
                                    </div> --><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">

                                            <div class="active tab-pane" id="diary_generation_tab_panel">
                                                <h4 class="basic_heading"> Advocate Search </h4><br>
                                                <?php
                                                $attribute = array('class' => 'form-horizontal', 'name' => 'subordinate_court_details', 'id' => 'subordinate_court_details', 'autocomplete' => 'off');
                                                echo form_open('#', $attribute);

                                                ?>


                                                <div class="row ">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label >Search by Name</label>
                                                            <input type="text" name="" class="form-control" placeholder="Enter Advocate Name">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label >Search by AOR Code</label>
                                                            <input type="number" name="" class="form-control" placeholder="Enter AOR Code">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <div style="display:grid;justify-content: flex-start;">
                                                                    <div class="mb-1" style="font-family: monospace;">
                                                                        <span><strong>Name:</strong></span>
                                                                        <span>SIKANDAR</span>
                                                                    </div>
                                                                    <div class="mb-1" style="font-family: monospace;">
                                                                        <span><strong>AOR/NAOR:</strong></span>
                                                                        <span>AOR</span>
                                                                    </div>
                                                                    <div class="mb-1" style="font-family: monospace;">
                                                                        <span><strong>AOR Code:</strong></span>
                                                                        <span>69458</span>
                                                                    </div>
                                                                    <div class="mb-1" style="font-family: monospace;">
                                                                        <span><strong>Mobile:</strong></span>
                                                                        <span>9844587459</span>
                                                                    </div>
                                                                    <div class="mb-1" style="font-family: monospace;">
                                                                        <span><strong>Email:</strong></span>
                                                                        <span>sikandar@gmail.com</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <!-- /.diary_generation_tab_panel -->




                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
