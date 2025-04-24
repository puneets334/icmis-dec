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
                                        <div class="col-sm-2">
                                            <label for="category" class="col-sm-12">From Date :</label>
                                            <input type="text" id="from_date" name="from_date" class="form-control dtp"  placeholder="From Date" required="required">
                                        </div>

                                        <div class="col-sm-2">
                                            <label for="type" class="col-sm-12">To Date :</label>
                                            <input type="text" id="to_date" name="to_date" class="form-control dtp"  placeholder="To Date" required="required">
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="type" class="col-sm-12">Matter Status</label>
                                            <select class="form-control" id="jCode" name="jCode" placeholder="Judges" style="width:100% !important;">
                                                <option value="0">All</option>
                                                <?php
                                                    foreach($Judges as $Judge){
                                                        echo '<option value="'.$Judge['jcode'].'">'.$Judge['jname'].'</option>';
                                                    }
                                                ?>
                                            </select>
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
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var jCode = $('#jCode').val();

       
        $('#subject_category_view').prop('disabled', true);
        $('#reportTableContainer').html("");

        $.ajax({
            url: '<?= base_url('/Report/pendency_reports/6');?>',
            type: 'GET',
            data: {
                from_date: from_date,
                to_date: to_date,
                jCode: jCode,
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