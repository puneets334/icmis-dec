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
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'coram', 'id' => 'coram', 'autocomplete' => 'off');
                                    echo form_open('#', $attribute);

                                    ?>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane">
                                            <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                                <h4 class="basic_heading">Neutral Citation </h4>
                                            </div>
                                            <?php if (!empty($getDetails) && $getDetails[0] != 'empty') { ?>
                                                <div class="row ">
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
                                                            <label class="col-sm-12 col-form-label" style="font-weight:bold;"><span class="text-primary">Cause Title : </span> <?= $getDetails[0]['pet_name'] . '<b> Vs </b>' . $getDetails[0]['res_name'] ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Judgment Date :</label>
                                                            <div class="col-sm-8">
                                                                <select id="judgment_dates" name="judgment_dates" class="custom-select rounded-0" required>
                                                                    <option value="">Select Judgment Date</option>
                                                                    <?php foreach ($neutral_citaion_details as $details): ?>
                                                                        <option value="<?= $details['dispose_order_date']; ?>"><?= date('d-m-Y', strtotime($details['dispose_order_date'])) . "#" . $details['nc_display']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Reason for deletion :</label>
                                                            <div class="col-sm-8">
                                                                <!-- <input type="text" name="reason" id="reason" class="form-control" onkeypress="return alpha(event)" placeholder="Enter Reason" required> -->
                                                                <textarea name="reason" id="reason" class="form-control" onkeypress="return alpha(event)" placeholder="Enter Reason" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <center><input type="button" name="dele" id="dele" class="btn btn-primary" value="Delete"></center>
                                                    </div>
                                                </div>
                                            <?php } elseif (!empty($getDetails) && $getDetails[0] == 'empty') {
                                                echo '<center><b>No record found!!</b></center>';
                                            } ?>
                                        </div>

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
    function alpha(e) {
        var k;
        document.all ? k = e.keyCode : k = e.which;
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 44 || k == 45 || k == 34 || k == 8 || k == 46 || k == 32 || (k >= 48 && k <= 57));
    }

    $('#dele').click(function() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        if (confirm('Are you sure to delete ?')) {
            var case_number = '<?= (!empty($getDetails[0]['case_no']) ? $getDetails[0]['case_no'] : '') ?>';
            var diary_number = '<?= (!empty($getDetails[0]['diary_no']) ? $getDetails[0]['diary_no'] : '') ?>';
            var date_judgment = $('select[name=judgment_dates] option').filter(':selected').val();
            var ucode = '<?= $ucode ?>';
            var reason = $('#reason').val();

            $.ajax({
                    url: "<?php echo base_url('Court/Neutral_citation/delete_NeutralCitation'); ?>",
                    type: "post",
                    data: {
                        CSRF_TOKEN: csrf,
                        case_number: case_number,
                        diary_number: diary_number,
                        date_judgment: date_judgment,
                        ucode: ucode,
                        reason: reason
                    },
                })
                .done(function(msg) {
                    updateCSRFToken();
                    console.log("msg:: ", msg)
                    if (msg == 1 || msg == '1') {
                        <?php $getDetails = [];  ?>
                        alert('Record Deleted Successfully')
                        setTimeout(() => {
                            // window.location.reload();
                            window.location = "<?= base_url('Court/Neutral_citation/delete') ?>";
                        }, 500);
                    } else if (msg == 2) {
                        alert('Missing required Details for Record Deletion')
                    } else {
                        alert('Error while Deleting record')
                    }
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("Error Occured, Please Contact Server Room");
                });
        } else {
            return false;
        }

    });
</script>

<?= view('sci_main_footer') ?>