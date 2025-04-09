<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">

                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Management Report >> Pending >> Subject Category-wise Group Count</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content -->
                    <div class="card-body">

                        <!-- <div class="box box-primary"> -->
                            <form class="form-horizontal" id="push-form">
                                <?= csrf_field(); ?>
                                <div class="box-body col-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label for="category" class="col-sm-12">Select Subject Category</label>
                                            <select class="form-control col-sm-12" id="categoryCode" name="categoryCode" placeholder="Subject Category">
                                                <option value="0">All</option>
                                                <?php
                                                if (!empty($SCategories) > 0) {
                                                    foreach ($SCategories as $SCategory) {
                                                        echo '<option value="' . $SCategory['subcode1'] . '">' . $SCategory['sub_name1'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <label for="type" class="col-sm-12">Matter Type</label>
                                            <select class="form-control col-sm-12" id="matterType" name="matterType">
                                                <option value="MF">All</option>
                                                <option value="M">Miscellaneous</option>
                                                <option value="F">Regular</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="type" class="col-sm-12">Matter Status</label>
                                            <select class="form-control col-sm-12" id="matterStatus" name="matterStatus">
                                                <option value="NR">All</option>
                                                <option value="R">Ready</option>
                                                <option value="N">Not-Ready</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="groupCount" class="col-sm-12">Enter Connected Matter in Nos.</label>
                                            <input type="number" id="groupCount" name="groupCount" class="form-control col-sm-12" placeholder="Enter Group Count" required="required">
                                        </div>
                                        <div class="col-sm-1 mt-5">
                                            <button type="button" id="subject_category_view" name="view" class="btn btn-block btn-primary">View</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                            <div id="loader" style="text-align: center;"></div>
                            <div id="reportTableContainer"></div>
                        <!-- </div> -->
                    </div>
                   

</section>
</div>
</div>
</div>

<script>
    $('#subject_category_view').on('click', function() {
        var categoryCode = $('#categoryCode').val();
        var matterType = $('#matterType').val();
        var matterStatus = $('#matterStatus').val();
        var groupCount = $('#groupCount').val();

        if (groupCount === '') {
            alert('Please enter a valid group count.');
            return;
        }
        $('#subject_category_view').prop('disabled', true);
        $('#reportTableContainer').html("");

        $.ajax({
            url: '<?= base_url('/Report/pendency_reports/5');?>',
            type: 'GET',
            data: {
                categoryCode: categoryCode,
                matterType: matterType,
                matterStatus: matterStatus,
                groupCount: groupCount,
                view: '1',
            },
            beforeSend: function() {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:25%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response) {
                $('#reportTableContainer').html(response);
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            },
            complete: function() {
                $("#loader").html("");
                $('#subject_category_view').prop('disabled', false);
            }
        });
    });    
</script>


</body>

</html>