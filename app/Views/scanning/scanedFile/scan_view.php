<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <form method="post">
                    <?= csrf_field() ?>
                    <div class="card">
                     
                        <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Scanning >> Scaned File -  View</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ddl_court" class="form-label">Select Court</label>
                                        <select class="form-control" name="ddl_court" id="ddl_court">
                                            <!-- <option value="">Select</option> -->
                                            <?php if (!empty($courtData)) : ?>
                                                <?php foreach ($courtData as $court) : ?>
                                                    <option value="<?= $court['id']; ?>" <?= ($court['id'] == 1) ? 'selected="selected"' : ''; ?>>
                                                        <?= $court['court_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>

                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ddl_st_agncy" class="form-label">Select State</label>
                                        <select class="form-control" name="ddl_st_agncy" id="ddl_st_agncy">
                                            <option value="">Select</option>
                                            <?php if (!empty($stateData)) : ?>
                                                <?php foreach ($stateData as $state) : ?>
                                                    <option value="<?= $state['id_no']; ?>"><?= $state['name']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ddl_bench" class="form-label">Select Bench</label>
                                        <select class="form-control" name="ddl_bench" id="ddl_bench">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                              
                            </div>
                        </div>
                        
                    </div>
                </form>
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12 text-center">
                            <div id="dv_case_no"></div>
                        </div>
                        <div class="col-md-12">
                            <div id="dv_fy"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="res_loader"></div>
<script>
    $(document).ready(function() {
        $(document).on('change', '#ddl_st_agncy, #ddl_court', function() {
            get_benches('0');
        });

        $(document).on('change', '#ddl_court', function() {
            var idd = $(this).val();
            if (idd == '4') {
                $('#ddl_st_agncy').val('490506');
                get_benches('1');
            }
        });

        function get_benches(str) {
            var ddl_st_agncy = $('#ddl_st_agncy').val();
            var ddl_court = $('#ddl_court').val();
            // var ddl_bench = $('#ddl_bench').val();
            // var csrf = $('input[name="<?= csrf_token() ?>"]').val(); // Get the current CSRF token

            if (ddl_st_agncy != '' && ddl_court != '') {
                $.ajax({
                    url: '<?= site_url('Scanning/ScanningController/getBench'); ?>',
                    cache: false,
                    async: true,
                    type: 'GET',
                    data: {
                        ddl_st_agncy: ddl_st_agncy,
                        // ddl_bench: ddl_bench,
                        ddl_court: ddl_court,
                        // '<?= csrf_token() ?>': csrf // Make sure the token is included in the request
                    },
                    beforeSend: function() {
                        $('#res_loader').html('<div style="position: absolute;top: 50%;left: 50%;text-align: center;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);"><img src="<?= base_url();?>/images/load.gif"/></div>');
                    },
                    success: function(response) {
                        updateCSRFToken();
                        $('#res_loader').html('');
                        if (response.status == 'success') {
                            // $('#dv_case_no').html(response.html);
                            $('#ddl_bench').html(response.html);
                        } else {
                            $('#dv_case_no').html(response.html);
                        }
                    },
                    error: function(xhr) {
                        $('#res_loader').html('');
                        updateCSRFToken();
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
            }
        }

        // Update the CSRF token dynamically after every request
        function updateCSRFToken() {
            $.get('<?= site_url('Scanning/ScanningController/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }


        $(document).on('change', '#ddl_bench', function() {
            var ddl_st_agncy = $('#ddl_st_agncy').val();
            var ddl_bench = $('#ddl_bench').val();
            var ddl_court = $('#ddl_court').val();
            var csrf = $("input[name='CSRF_TOKEN']").val();
         
            $.ajax({
                url: '<?= site_url('Scanning/ScanningController/get_case_structure'); ?>',
                type: 'POST',
                cache: false,
                async: true,
                data: {
                    ddl_st_agncy: ddl_st_agncy,
                    ddl_bench: ddl_bench,
                    ddl_court: ddl_court,
                    '<?= csrf_token() ?>': csrf // Make sure the token is included in the request
                },
                beforeSend: function() {
                    $('#res_loader').html('<div style="position: absolute;top: 50%;left: 50%;text-align: center;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);"><img src="<?= base_url();?>/images/load.gif"/></div>');
                },
                success: function(data) {
                    console.log(data);
                    $('#res_loader').html(''); // Clear the loader
                    updateCSRFToken();
                    $('#dv_case_no').html(data.html);
                    $('#dv_fy').html('');
                },
                error: function(xhr) {
                    $('#res_loader').html('');
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });
        
    });

    function updateCSRFToken() {
        $.get('<?= site_url('Scanning/ScanningController/getCSRF'); ?>', function(data) {
            $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
        }, 'json');
    }

    $('.select-box').select2({
        selectOnClose: true
    });
</script>