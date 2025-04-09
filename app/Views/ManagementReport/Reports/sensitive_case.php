<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Sensitive Cases</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php echo form_open();
                                            csrf_token();
                                            ?>
                                            <div id="dv_content1">
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    
                                                    <div class="col-md-2 mt-26">
                                                        <input type="button" class="btn btn-primary quick-btn" value="Submit" id="btn_sensetive" name="btn_sensetive" />
                                                        
                                                    </div>
                                                </div>
                                                <div id="res_loader"></div>
                                                <div id="div_result"></div>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="sensitive_case.js"></script>