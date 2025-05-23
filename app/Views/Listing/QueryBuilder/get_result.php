<div class="card col-12 p-0 mt-2">
    <div class="card-header bg-primary text-white font-weight-bolder">
        <u>Total Records Found: <?= isset($report->total_cases) ? $report->total_cases : 0 ?></u>
    </div>
    <div class="card-body">
        <form name="child_form" id="child_form">
        <?= csrf_field() ?>
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <span class="input-group-text">Title</span>
                </div>
                <input type="text" class="form-control" placeholder="Title to display in report" id="input_title" name="input_title" autocomplete="on">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <span class="input-group-text">Columns</span>
                </div>
                <select class="form-control" multiple="multiple" id="add_columns" name="add_columns[]">
                    <option value="case_no_with_dno" selected>Case No. with Diary No.</option>
                    <option value="diary_no">Diary No.</option>
                    <option value="reg_no_display">Registration No.</option>
                    <option value="cause_title" selected>Cause Title</option>
                    <option value="connected_count">No. of Connected</option>
                    <option value="section">Section name</option>
                    <option value="da">Dealing Assistant</option>
                    <option value="category">Subject Category</option>
                    <option value="tentative_date">Tentative Date</option>
                    <option value="coram">Coram</option>
                    <option value="lastorder">Last Order</option>
                    <option value="advocate_name">Advocate Name</option>
                    <option value="notice_date">Notice Date</option>
                    <option value="admitted_on">Admitted On</option>
                </select>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Sort By</span>
                        </div>
                        <select class="form-control" multiple="multiple" id="sort_by" name="sort_by[]">
                            <option value="section">Section name</option>
                            <option value="da">Dealing Assistant</option>
                            <option value="category">Subject Category</option>
                            <option value="coram">Coram</option>
                            <option value="tentative_date">Tentative Date</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <button type="button" name="button_to_add" id="button_to_add" class="btn btn-gray border-primary py-0">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <br><br>
                    <button type="button" name="button_to_remove" id="button_to_remove" class="btn btn-gray border-primary py-0">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
                <div class="col-4">
                    <select class="form-control" multiple="multiple" id="sort_by2" name="sort_by2[]">
                        <option value="diary_no" >Diary No.</option>
                    </select>
                </div>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-append">
                    <span class="input-group-text">No. of Records</span>
                </div>
                <input type="number" class="form-control" name="number_of_rows" id="number_of_rows" placeholder="No. of Rows" value="<?= isset($report->total_cases) ? $report->total_cases : 0 ?>" min="1" max="1000000" />
            </div>

            <div class="row">
                <div class="col-6 text-left">
                    <button type="button" class="btn btn-success" id="diary_nos" data-dnos="<?= isset($report->dnos) ? $report->dnos : '' ?>">Detail Report</button>
                </div>
                <div class="col-6 text-right">
                    <button type="button" id="footerButton" class="diary_nos1 btn btn-success" data-dnos="<?= isset($report->dnos) ? $report->dnos : '' ?>">Generate in Causelist format</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#add_columns').select2({
            placeholder: "Select columns",
            allowClear: true,
            minimumResultsForSearch: 0
        });
        $("#button_to_add").on("click", function(){
            $('#sort_by :selected').each(function(){
                $('#sort_by2').append($('<option>', {
                    value: $(this).val(),
                    text : $(this).text()
                }));
                $(this).remove();
            });
        });

        $("#button_to_remove").on("click", function(){
            $('#sort_by2 :selected').each(function(){
                $('#sort_by').append($('<option>', {
                    value: $(this).val(),
                    text : $(this).text()
                }));
                $(this).remove();
            });
        });
        $('#footerButton').on('click', async function() {
            await updateCSRFTokenSync();
            var inputTitle = $('#input_title').val();
            var dnos = $(this).data('dnos');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            // console.log("Input Title: ", inputTitle);
            // console.log("Diary Numbers: ", dnos);
            var formData = {
                diaryNos: dnos,
                input_title: inputTitle,
                flag: 'report_detail', 
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            };
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Listing/QueryBuilder/generateReport'); ?>", 
                data: formData,
                beforeSend: function() {
                    $('.pendency_result_detail').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                },
                success: function(data) {
                    $(".pendency_result_detail").html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error: ', textStatus, errorThrown);
                }
            });
        });
    });
</script>