<?= view('header') ?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Court Master</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?= view('Court/Neutral_citation/neutral_citation_breadcrumb'); ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <?php
                                    /*$attribute = array('class' => 'form-horizontal', 'name' => 'courtMaster', 'id' => 'courtMaster', 'autocomplete' => 'off', 'enctype'=> 'multipart/form-data');
                                            echo form_open('Court/CourtMasterController/replaceROP', $attribute);*/

                                    ?>
                                    <div class="tab-content">

                                        <div class="active tab-pane">
                                            <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                                <h4 class="basic_heading">Neutral Citation </h4>
                                            </div>

                                            <?php if (!empty($getDetails)) { ?>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label" style="font-weight:bold;"><span class="text-primary">Diary No. : </span> <?= substr($getDetails[0]['diary_no'], 0, -4) . '/' . substr($getDetails[0]['diary_no'], -4) ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label" style="font-weight:bold;"><span class="text-primary">Case No. : </span> <?= $getDetails[0]['reg_no_display'] ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label" style="font-weight:bold;"><span class="text-primary">Cause Title : </span> <?= $getDetails[0]['pet_name'] . '<b> Vs </b>' . $getDetails[0]['res_name'] ?></label><br><br>
                                                        </div>
                                                    </div>

                                                    <?php if (!empty($neutral_citaion_details)) { ?>
                                                        <div class="col-md-12">
                                                            <b>Neutral Citation of the searched number has been already generated. Do you want to upload?</b><br><br>
                                                            <div class="form-group row">
                                                                <div class="custom-control custom-radio">
                                                                    <input class="custom-control-input party" type="radio" id="radio_selected_court1" name="judgment_type" value="C" onclick="showdiv(this.value);">
                                                                    <label for="radio_selected_court1" class="custom-control-label">Corrigendum</label>
                                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="custom-control custom-radio">
                                                                    <input class="custom-control-input party" type="radio" id="radio_selected_court2" name="judgment_type" value="N" onclick="showdiv(this.value);" checked>
                                                                    <label for="radio_selected_court2" class="custom-control-label">New Judgment</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <!-- <div style="display:contents;"> -->
                                                    <?php
                                                    $attribute = array('class' => 'form-horizontal', 'style' => 'display:contents', 'name' => 'corrigendum', 'id' => 'corrigendum', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                                    echo form_open('Court/Neutral_citation', $attribute);
                                                    ?>
                                                    <input type="hidden" name="diary_number" value="<?php echo $getDetails[0]['diary_no']; ?>">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Judgment Date:</label>
                                                            <div class="col-sm-10">
                                                                <select name="judgment_dates" class="form-control">
                                                                    <option value="">Select Judgment Date</option>
                                                                    <?php foreach ($neutral_citaion_details as $details): ?>
                                                                        <option value="<?= $details['nc_display']; ?>"><?= date('d-m-Y', strtotime($details['dispose_order_date'])) . "#" . $details['nc_display']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Upload File:</label>
                                                            <div class="col-sm-10">
                                                                <input type="file" required name="file_corrigendum" id="file_corrigendum" accept="application/pdf">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <center><input type="submit" name="update" class="btn btn-primary" value="SUBMIT"></center>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                    <!-- </div> -->


                                                    <?php
                                                    $attribute = array('class' => 'form-horizontal', 'style' => 'display:contents', 'onsubmit' => 'return check_selected()', 'name' => 'new_judgment', 'id' => 'new_judgment', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                                    echo form_open('Court/Neutral_citation', $attribute);
                                                    ?>
                                                    <input type="hidden" name="diary_number" value="<?php echo $getDetails[0]['diary_no']; ?>">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Date of Judgment:</label>
                                                            <div class="col-sm-10">
                                                            <input type="text" name="date" required class="form-control dtp" placeholder="Select date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Coram:</label>
                                                            <div class="col-sm-10">
                                                                <select name="judge[]" id="judge" class="form-control" required multiple style="height:250px; width:520px;">
                                                                    <option value="" disabled>Select Judgment Date</option>
                                                                    <?php foreach ($getJudges as $judges): ?>
                                                                        <option value="<?= $judges['jcode']; ?>"><?= $judges['jname']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <p style="color: red">(Select multiple Hon'ble Judges by pressing <b>ctrl</b> button)</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Judgment Pronounced By:</label>
                                                            <div class="col-sm-10">
                                                                <select name="judgment_by" class="form-control" required>
                                                                    <option value="">Select Judge</option>
                                                                    <?php foreach ($getJudges as $judges): ?>
                                                                        <option value="<?= $judges['jcode']; ?>"><?= $judges['jname']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Upload File:</label>
                                                            <div class="col-sm-10">
                                                                <input type="file" required name="file" id="file" accept="application/pdf">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <center><input type="submit" name="insert" class="btn btn-primary" value="SUBMIT"></center>
                                                    </div>
                                                    <?php form_close(); ?>

                                                </div>
                                            <?php } else {
                                                echo '<center><b>No record found!!</b></center>';
                                            } ?>
                                        </div>

                                        <!-- <hr><br>
                                            <div id="showData">
                                                
                                            </div> -->

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

<script type="text/javascript">
    $(document).ready(function() {
		$('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
		$(".dtp").datepicker("setDate", new Date(listed_date));
	});

    $('#corrigendum').hide();

    function showdiv(option) {
        if (option == 'C') {
            //document.getElementById("corrigendum").style.display="block";
            //document.getElementById("new_judgment").style.display="none";
            $('#corrigendum').show();
            $('#new_judgment').hide();
        } else if (option == 'N') {
            //document.getElementById("corrigendum").style.display="none";
            //document.getElementById("new_judgment").style.display="block";
            $('#corrigendum').hide();
            $('#new_judgment').show();
        }
    }

    function check_selected() { //function to check no. of options selected by user
        var selected_options = $('#judge option:selected').length;
        if (selected_options < 2) {
            alert("Please select multiple Judges in Coram by pressing CTRL button");
            return false;
        } else return true;
    }

    $('#file').change(function() {
        var myfile = $(this).val();
        var ext = myfile.split('.').pop();

        if (ext == "pdf") {
            $('#file').val(myfile);
        } else {
            $('#file').val('');
            $('#error_messege').text('Only pdf files are allowed');
        }
    });
    $('#file_corrigendum').change(function() {
        var myfile = $(this).val();
        var ext = myfile.split('.').pop();

        if (ext == "pdf") {
            $('#file_corrigendum').val(myfile);
        } else {
            $('#file_corrigendum').val('');
            $('#error_messege').text('Only pdf files are allowed');
        }
    });
</script>
