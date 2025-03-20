<?= view('header') ?>
<style>
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }

    .input-group>.form-control,
    .input-group>.form-floating,
    .input-group>.form-select {
        position: relative;
        flex: 1 1 auto;
        width: 1% !important;
        min-width: 0;
    }

    .input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-left: calc(var(--bs-border-width)* -1);
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-check-input {
        flex-shrink: 0;
        width: 1em;
        height: 1em;
        margin-top: .25em;
        vertical-align: top;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;

    }

    .form-check .form-check-input {
        float: left;
        margin-left: -1.5em;
    }

    .form-check-input[type=checkbox] {
        border-radius: .25em;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div id="res_loader"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="mb-0">Draft List Details</h3>
                    </div>
                    <div class="card-body">
                        <form id="dateForm" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cause_date" class="col-form-label">
                                            Causelist Date</label>
                                        <select class="form-control select-box" name="cause_date" id="cause_date">
                                            <?php if (!empty($dates)): ?>
                                                <option value="" selected>SELECT</option>
                                                <?php foreach ($dates as $data): ?>
                                                    <option value="<?= $data['next_dt']; ?>"><?= date("d-m-Y", strtotime($data['next_dt'])); ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="-1" selected>EMPTY</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="flist" class="col-form-label">Type of List</label>
                                        <select class="form-control select-box" id="flist" name="flist">
                                            <option value="" selected>SELECT</option>
                                            <option value="1">Fresh Civil </option>
                                            <option value="2">Fresh Criminal</option>
                                            <option value="3">Diary Civil Matters</option>
                                            <option value="4">Diary Criminal Matters</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check my-3">
                                        <input class="form-check-input" type="checkbox" id="maCheckbox" name="maCheckbox" value="ma" >
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Include Review/Curative/Contempt
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="getDraftDetail" class="btn btn-primary w-25">SUBMIT</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header text-center" id="message"></div>
                    <div class="card-body">
                        <div class="table-responsive" id="result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $('.select-box').select2({
        selectOnClose: true
    });
    $('.datepicker').datepicker({
        format: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050'
    });
    $('#getDraftDetail').on('click', function(e) {
        e.preventDefault();

        var cl_date = $('#cause_date').val();
        var list_type = $('#flist').val();
        var ma = document.getElementById('maCheckbox').checked ? 1 : 2; // Using a ternary operator for brevity
        var button = $(this);

        // Validate Causelist Date
        if (!cl_date) {
            swal({
                title: "Error!",
                text: "Please select Causelist Date",
                icon: "error",
                button: "error!"
            });
            return;
        }

        // Validate Sorting Type
        if (!list_type) {
            swal({
                title: "Error!",
                text: "Please select List Type!!",
                icon: "error",
                button: "error!"
            });
            return;
        }

        var csrf = $('input[name="<?= csrf_token() ?>"]').val();

        button.prop('disabled', true);
        button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: '<?= base_url('PaperBook/PaperBookController/getAdvanceReport') ?>',
            method: 'POST',
            data: {
                cl_date: cl_date,
                list_type: list_type,
                ma: ma,
                '<?= csrf_token() ?>': csrf,
            },
            beforeSend: function() {
                $('#res_loader').html('<div style="position: absolute; top: 50%; left: 50%; text-align: center; transform: translate(-50%, -50%);"><img src="../../images/load.gif"/></div>');
            },
            success: function(response) { // Make sure to pass the response parameter
                if (response.status == '1') {
                    $('#result').html(response.html);
                } else {
                    swal({
                        title: "Error!",
                        text: response.message,
                        icon: "error",
                        button: "error!"
                    });
                }
            },
            error: function(xhr) {
                var errorMsg = "An error occurred. Please try again.";
                if (xhr.status === 404) {
                    errorMsg = "Requested resource not found.";
                } else if (xhr.status === 500) {
                    errorMsg = "Internal server error. Please contact support.";
                }
                swal({
                    title: "Error!",
                    text: errorMsg,
                    icon: "error",
                    button: "error!"
                });
            },
            complete: function() {
                updateCSRFToken();
                $('#res_loader').html('');
                button.prop('disabled', false); // Enable the button again
                button.html('SUBMIT'); // Reset button text
            }
        });

        function updateCSRFToken() {
            $.get('<?= site_url('PaperBook/PaperBookController/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }
    });
</script>