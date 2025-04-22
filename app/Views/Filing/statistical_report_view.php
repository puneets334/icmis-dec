<?= view('header'); ?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Data Generation</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="row ">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                <?= csrf_field() ?>
                                                    <button type="button" class="btn btn-primary" id="sub" onclick="getDetails()">Data Generation</button>
                                                    

                                                </div>
                                                <label for="inputEmail3" style="color: green;;" id="div_results" class="col-sm-5 col-form-label"><?php echo $data_generation; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</section>


<script>

function getDetails() {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var data = {};
    data[CSRF_TOKEN] = CSRF_TOKEN_VALUE; // Add CSRF token dynamically
    $.ajax({
        url: base_url + '/Filing/Statistical_report/statistical_report_view',
        type: 'GET',
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#div_results').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        success: function (response) {
            updateCSRFToken(); // Update CSRF token if returned in response
            $('#div_results').html(response);
            
            // Other logic (e.g., judge fields update)
            // ...
        },
        error: function (xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });

}

</script>