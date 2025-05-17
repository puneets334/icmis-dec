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

    .table thead th,
    .table th {
        width: 50%;
    }

    .basic_heading {
        text-align: center;
        color: #31B0D5;
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
                                <h3 class="card-title">Filing >> Similarities</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>

                        </div>
                    </div>
                    <?php echo view('Filing/filing_breadcrumb', ['show' => 'N']); ?>
                    <br />
                    <?php

                    $attribute = array('class' => 'form-horizontal', 'name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                    //echo form_open(base_url('Caveat/Similarity'), $attribute);
                    echo form_open(base_url('#'), $attribute);
                    ?>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label for="diary_number" class="col-sm-5 col-form-label"> Diary No</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="diary_number" name="diary_number" value="<?= $diary_no; ?>" placeholder="Enter Diary No" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label for="diary_year" class="col-sm-5 col-form-label">Diary Year</label>
                                <div class="col-sm-7">
                                    <?php $sel = '';
                                    $year = 1950;
                                    $current_year = date('Y');
                                    ?>
                                    <select name="diary_year" id="diary_year" class="custom-select" required>
                                        <?php for ($x = $current_year; $x >= $year; $x--) {
                                            if ((!empty($diary_year) &&  $x == $diary_year)) {
                                                $sel = 'selected=selected';
                                            } else {
                                                $sel = '';
                                            } ?>
                                            <option <?= $sel ?> value="<?= $x ?>"><?php echo $x; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <!--<button type="submit" class="btn btn-primary" id="submit">Search</button>-->
                            <button type="button" class="btn btn-primary" id="submit" onclick="getDetails()">Search</button>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <?php form_close(); ?>

                    <div class="row">
                        <center>
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger text-white ">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("message_error")) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata("message_error") ?>
                                </div>
                            <?php } else { ?>
                                <br />
                            <?php } ?>
                        </center>
                    </div>
                    <div id="div_result"></div>

                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>

<script>
    <?php if (!empty($diary_no) && !empty($diary_year)) {?>
        getDetails();
    <?php } ?>

    function getDetails() {
        var diary_number = $("#diary_number").val();
        var diary_year = $("#diary_year :selected").val();
        if (diary_number.length == 0) {
            alert("Please enter Diary number");
            $("#diary_number").focus();
            validationError = false;
            return false;
        } else if (diary_year.length == 0) {
            alert("Please select Diary year");
            $("#diary_year").focus();
            validationError = false;
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: base_url + '/Filing/Similarity/viewSimilarity',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                diary_number: diary_number,
                diary_year: diary_year
            },
            beforeSend: function() {
                //$('#div_result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#div_result').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                //alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
</script>