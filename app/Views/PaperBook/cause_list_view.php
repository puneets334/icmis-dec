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
</style>
<section class="content">
    <div class="container-fluid">
        <div id="res_loader"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="card-title">Cause List Details</h3>
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
                                        <select class="form-control select-box_" id="flist" name="flist" onfocus="resetval()" onchange="chkdata()">
                                            <option value="">---select---</option>
                                            <option value="1">Fresh Matters </option>
                                            <option value="2">Except Fresh</option>
                                            <option value="4">Review/Curative/contempt</option>
                                            <option value="3">All Matters</option>
                                            <option value="5">Other Unallocated Matters</option>
                                            <option value="6">Diary Matters-Civil Cases</option>
                                            <option value="7">Diary Matters-Criminal Cases</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sort" class="col-form-label">Sort By</label>
                                        <select class="form-control select-box_" name="sort" id="sort">
                                            <option value="">--Select--</option>
                                            <option value="diary_no">Diary No.</option>
                                            <option value="active_fil_no">Case No.</option>
                                            <!-- <option value="sec">Section </option> -->
                                            <option value="docnum">IA No.</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="getCauseDetail" class="btn btn-primary w-25">SUBMIT</button>
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
    $('#getCauseDetail').on('click', function(e) {
        e.preventDefault();

        var cl_date = $('#cause_date').val();
        var list_type = $('#flist').val();
        var sorting = $('#sort').val();
        var button = $(this);

        if (!cl_date) {
            swal({
                title: "Error!",
                text: "Please select Causelist Date",
                icon: "error",
                button: "error!"
            });
            return;
        }
        if (!sorting) {
            swal({
                title: "Error!",
                text: "Please select sorting Type!!",
                icon: "error",
                button: "error!"
            });
            return;
        }

        var csrf = $('input[name="<?= csrf_token() ?>"]').val();

        button.prop('disabled', true);
        button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');

        var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

         $.ajax({
            url: '<?= base_url('PaperBook/PaperBookController/getCauseFinalReport') ?>',          
            data: {
                cl_date: cl_date,
                list_type: list_type,
                sort: sorting,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function () {
                $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(response, status) {
                button.prop('disabled', false); // Enable the button again
                button.html('Save'); 
				updateCSRFToken();                
                    $('#result').html(response);
               },
            error: function(xhr) {
                button.prop('disabled', false); // Enable the button again
                button.html('Save'); 
				updateCSRFToken();
                
            }

        });




         
        
    });

    function chkdata() {
        const sortElement = document.getElementById("sort");
        for (let i = 0; i < sortElement.options.length; i++) {
            sortElement.options[i].disabled = false;
        }

        const selectedValue = document.getElementById("flist").value;

        if (selectedValue === "6" || selectedValue === "7") {
            disableOptionByText("sort", "Case No.");
        }

        if (selectedValue === "4" || selectedValue === "3" || selectedValue === "5") {
            disableOptionByText("sort", "IA No.");
        }
    }

    function disableOptionByText(selectId, optionText) {
        const selectElement = document.getElementById(selectId);
        for (let i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].text === optionText) {
                selectElement.options[i].disabled = true;
                break;
            }
        }
    }

    function resetval() {
        document.getElementById("sort").value = '';
    }
</script>