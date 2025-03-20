<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Upload Original Records</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                            <?php if (isset($validationerr)): ?>
                                <div class="alert alert-danger"><?= $validationerr->listErrors() ?></div>
                            <?php endif; ?>
                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger text-white ">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session()->getFlashdata('message_error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata("message_error") ?>
                                </div>
                            <?php } ?>


                            <div class="card" id="alert_header" style="display: none;">
                                <div class="card-body">
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong id="alert_message"></strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <?php
                            $attribute = array('class' => 'form-horizontal appearance_search_form', 'id' => 'appearance_search_form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'method' => 'post');
                            echo form_open(base_url('Judicial/OriginalRecord/UploadScannedFile/handlePostUploadRequest'), $attribute);
                            ?>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="box-title mb-2">Search Option : </h3>
                                    <div class="form-group">
                                        <label class="radio-inline"><input type="radio" name="optradio" value="2" <?=(@$optradio == '2') ? ' checked' : ''; ?>>Diary No.</label>
                                        <label class="radio-inline"><input type="radio" name="optradio" value="1" <?=(@$optradio == '1') ? ' checked' : ''; ?>>Case Type</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>" />
                                    <div class="row">
                                        <div class="col-md-12" id="caseTypeWise" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="caseYear" class="control-label">Case Type</label>
                                                        <select class="form-select" name="caseType" tabindex="1" id="caseType" required>
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($caseTypes as $caseType) {
                                                                echo '<option value="' . $caseType['casecode'] . '">' . $caseType['casename'] . '&nbsp;:&nbsp;' . $caseType['skey'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="caseNo" class="control-label">Case No.</label>
                                                        <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="number" maxlength="10" required="required">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="caseYear" class="control-label">Year</label>
                                                        <select class="form-select" id="caseYear" name="caseYear">
                                                            <option value="">Select</option>
                                                            <?php
                                                            for ($year = date('Y'); $year >= 1950; $year--)
                                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group mt-26">
                                                        <button type="submit" id="view" name="view" onclick="check();" class="btn btn-block btn-primary">View
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="diaryNoWise">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="diaryNumber" class="control-label">Diary No</label>
                                                        <input class="form-control" id="diaryNumber" name="diaryNumber" placeholder="Diary Number" type="number" maxlength="20" required="required">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="diaryYear" class="control-label">Year</label>
                                                        <select class="form-select" id="diaryYear" name="diaryYear" required="required">
                                                            <option value="">Select</option>
                                                            <?php
                                                            for ($year = date('Y'); $year >= 1950; $year--)
                                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group mt-26">
                                                        <button type="submit" id="view" name="view" onclick="check();" class="btn btn-block btn-primary">View
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?= form_close() ?>

                            <!-------------Result Section ------------>
                            <?php if (is_array($caseInfo)) {
                            ?>
                                <hr />
                                <!-- <form id="frmUploadOriginalRecord" enctype="multipart/form-data" action="<?= base_url(); ?>index.php/OriginalRecords/uploadOriginalRecord"  method="post"> -->
                                <?php
                                $attribute = array('class' => 'form-horizontal appearance_search_form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'method' => 'post');
                                echo form_open(base_url('Judicial/OriginalRecord/UploadScannedFile/uploadOriginalRecord'), $attribute);
                                ?>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>" />
                                                <?php
                                                foreach ($caseInfo as $row) { ?>
                                                    <input type="hidden" name="diaryNo" id="diaryNo" value="<?= $row['diary_no'] ?>">
                                                    <input type="hidden" name="diaryYear" id="diaryYear" value="<?= $row['diary_year'] ?>">

                                                    <!-- <div class="form-group col-sm-6">
                                                <label for="fileROPList" class="col-sm-2">Case No.</label>
                                                <span class="col-sm-6"><?= $row['reg_no_display'] ?>&nbsp;(D No.<?= $row['diary_no'] ?>/<?= $row['diary_year'] ?>)</span>
                                            </div> -->

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="fileROPList">Case No.</label>
                                                            <span class="col-md-3"><?= $row['reg_no_display'] ?>&nbsp;(D No.<?= $row['diary_no'] ?>/<?= $row['diary_year'] ?>)</span>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="form-group col-sm-6">
                                                <label for="fileROPList" class="col-sm-2">Status</label>
                                                <span class="col-sm-6">
                                                    <?php if ($row['mainhead'] == 'M')
                                                        echo "Misc";
                                                    elseif ($row['mainhead'] == 'F')
                                                        echo "Regular";
                                                    if ($row['c_status'] == 'P')
                                                        echo '(Pending)';
                                                    elseif ($row['c_status'] == 'D')
                                                        echo '(Disposed)';
                                                    ?>
                                                </span>
                                            </div> -->

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="fileROPList">Status</label>
                                                            <span class="col-md-3">
                                                                <?php if ($row['mainhead'] == 'M')
                                                                    echo "Misc";
                                                                elseif ($row['mainhead'] == 'F')
                                                                    echo "Regular";
                                                                if ($row['c_status'] == 'P')
                                                                    echo '(Pending)';
                                                                elseif ($row['c_status'] == 'D')
                                                                    echo '(Disposed)';
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="form-group col-sm-6">
                                                <label for="fileROPList" class="col-sm-2">Petitioner</label>
                                                <span class="col-sm-6"><?= $row['pet_name'] ?></span>
                                            </div> -->

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="fileROPList">Petitioner</label>
                                                            <span class="col-md-6"><?= $row['pet_name'] ?></span>
                                                        </div>
                                                    </div>


                                                    <!-- <div class="form-group col-sm-6">
                                                <label for="fileROPList" class="col-sm-2">Respondant</label>
                                                <span class="col-sm-6"><?= $row['res_name'] ?></span>
                                            </div> -->

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="fileROPList">Respondant</label>
                                                            <span class="col-sm-6"><?= $row['res_name'] ?></span>
                                                        </div>
                                                    </div>

                                                <?php }
                                                if ($row['c_status'] == 'P') { ?>

                                                    <!-- <div class="form-group col-sm-6">
                                                <label for="fileROPList" class="col-sm-3">Select Digitally Signed File</label>
                                                <div class="col-sm-2">
                                                    <input type="file" name="fileOriginalRecord" id="fileOriginalRecord" accept="application/pdf">
                                                </div>
                                            </div> -->

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="fileROPList">Select Digitally Signed File</label>
                                                            <input type="file" name="fileOriginalRecord" id="fileOriginalRecord" accept="application/pdf" required />
                                                            <label>* Note : Please Upload only pdf file, max size upload is 2MB.</label>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="col-sm-12">
                                                <button type="submit" id="uploadRecord" name="uploadRecord" class="btn btn-block btn-danger">Upload</button>
                                            </div> -->
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="col-md-4">
                                                <button type="submit" id="uploadRecord" name="uploadRecord" class="btn btn-block btn-danger">Upload</button>
                                            </div>
                                        </div>
                                    <?php
                                                } else { ?>
                                        <div class="form-group col-md-12">
                                            <label for="" class="text-danger">Case is disposed.Original records can't be uploaded for this case.</label>
                                        </div>
                                    <?php  }
                                    ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="loader"></div>
<script>
    function check() {
        $("#appearance_search_form").submit();
    }

    $(".alert").delay(4000).slideUp(200, function() {
        $(this).alert('close');
    });
    $(document).ready(function() {
        $("#mmPresentedDate").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        $("#mmDecidedDate").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });


        // $('#diaryNumber').prop("disabled", 'disabled');
        // $('#diaryYear').prop("disabled", 'disabled');
        // $('#diaryNoWise').hide();


        $("input[name$='optradio']").click(function() {
            var searchValue = $(this).val();
            if (searchValue == 1) {
                $('#caseType').removeAttr('disabled');
                $('#caseNo').removeAttr('disabled');
                $('#caseYear').removeAttr('disabled');

                $('#diaryNumber').prop("disabled", 'disabled');
                $('#diaryYear').prop("disabled", 'disabled');

                $('#diaryNoWise').hide();
                $('#caseTypeWise').show();

                $('#diaryNumber').val('');
                $('#diaryYear').val('');
            } else {
                $('#caseType').prop("disabled", 'disabled');
                $('#caseNo').prop("disabled", 'disabled');
                $('#caseYear').prop("disabled", 'disabled');

                $('#caseTypeWise').hide();

                $('#diaryNumber').removeAttr('disabled');
                $('#diaryYear').removeAttr('disabled');

                $('#diaryNoWise').show();

                $('#caseType').val('');
                $('#caseNo').val('');
                $('#caseYear').val('');
            }
            // alert(test);
            // $("div.desc").hide();
            // $("#"+test).show();
        });

        $("input[name='optradio']:checked").trigger('click');
    });
</script>