<?= view('header'); ?>
<section class="content">
    <div class="container_">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Copy Lower Court Data</h3>
                            </div>
                            <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <div class="card-body">
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

                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">


                                    <div class="row">
                                        <div class="col-md-3 "></div>
                                        <div class="col-md-3 diary_section">
                                            <label class="form-label">From Diary No</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary No" onblur="getDiaryDetails(1);">
                                            </div>
                                        </div>
                                        <div class="col-md-3 diary_section">

                                            <label for="inputEmail3" class="form-label">From Diary Year</label>
                                            <div class="col-sm-7">
                                                <?php $year = 1950;
                                                $current_year = date('Y');
                                                ?>
                                                <select name="diary_year" id="diary_year" class="custom-select rounded-0" onChange="getDiaryDetails(1);">
                                                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                        <option><?php echo $x; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row" id='div_result'>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3 "></div>
                                        <div class="col-md-3 casetype_section">
                                            <label for=" inputEmail3" class="form-label">To Diary No</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control" id="to_diary_number" name="to_diary_number" placeholder="Enter To Diary No" onblur="getDiaryDetails(2);">
                                            </div>
                                        </div>
                                        <div class="col-md-3 casetype_section">
                                            <label for="inputEmail3" class="form-label">To Diary Year</label>
                                            <div class="col-sm-7">
                                                <?php $year = 1950;
                                                $current_year = date('Y');
                                                ?>
                                                <select name="to_diary_year" id="to_diary_year" class="custom-select rounded-0" onChange="getDiaryDetails(2);">
                                                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                        <option><?php echo $x; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id='div_result1'>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <center>
                                                <button type="submit" class="btn btn-primary" id="submit" onclick="copy_details();">Copy High Court Details</button>
                                            </center>
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                    <div class="row" id='txtHint'>
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
<script>
    function getDiaryDetails(id) {
        updateCSRFToken();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (id == 1) {
            var dno = $("#diary_number").val();
            var dyr = $("#diary_year").val();
            var result_div = "div_result";
        } else {
            var dno = $("#to_diary_number").val();
            var dyr = $("#to_diary_year").val();
            var result_div = "div_result1";
        }

        diary_no = dno + dyr;
        $.ajax({
            url: '<?php echo base_url('Filing/Earlier_court/getCauseTitle'); ?>',
            cache: false,
            async: true,
            data: {
                diary_no: diary_no,
                result_div: result_div,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(data) {
                $('#' + result_div).html(data);
                updateCSRFToken();
            }
        });
    }

    function copy_details() {
        updateCSRFToken();
        // alert("this is copy function");
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var dno = $("#diary_number").val();
        var dyr = $("#diary_year").val();
        var from_diary_no = dno + dyr;

        var dno2 = $("#to_diary_number").val();
        var dyr2 = $("#to_diary_year").val();
        var to_diary_number = dno2 + dyr2;
        //   alert(d1+''+d2);

        if ((dno == '') || (dyr == '')) {
            alert("From diary no/ diary year can't be blank");

            $("#diary_number").focus();
            return;
        }
        if ((dno2 == '') || (dyr2 == '')) {
            alert("To diary no/ diary year can't be blank");
            $("#to_diary_number").focus();
            return;
        }

        $.ajax({
            url: '<?php echo base_url('Filing/Earlier_court/copylowercourt'); ?>',
            cache: false,
            async: true,
            data: {
                from_diary_no: from_diary_no,
                to_diary_number: to_diary_number,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(data) {
                $('#txtHint').html(data);
                updateCSRFToken();
            }
        });



    }
</script>