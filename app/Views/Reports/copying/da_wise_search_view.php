<?php
$attribute = array('class' => 'form-horizontal copy_request_search_form', 'name' => 'copy_request_search_form', 'id' => 'copy_request_search_form', 'autocomplete' => 'off');
echo form_open('#', $attribute); ?>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group row">
            <label for="Year" class="col-sm-5 col-form-label">Section</label>
            <div class="col-sm-7">
                <select class="form-control" name="section" id="section">
                    <option value="0">Select section</option>
                    <?php
                    foreach ($usersection as $section)
                        echo "<option value='" . $section['id'] . "'>" . $section['section_name'] . "</option>";
                    ?>
                </select>
            </div>

        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group row">
            <label for="Year" class="col-sm-5 col-form-label">Users</label>
            <div class="col-sm-7" id="userDropdownContainer">
                <select class="form-control" name="user" id="user">
                    <option value="0">Select user</option>
                </select>
            </div>
        </div>
    </div>
</div>
</div>
<div id='da_data'>
</div>

<div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
    <div id="printable">
        <table id="query_builder_report" class="query_builder_report table table-bordered table-striped">

        </table>
    </div>
</div>
<?= form_close(); ?>
<script>
    $(document).ready(function() {
        $('#section').change(function() {
            var sectionId = $(this).val();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Reports/Copying/Report/section_user'); ?>",
                method: 'POST',
                data: {
                    section_id: sectionId,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                },
                dataType: 'json',
                success: function(response) {
                    updateCSRFToken();

                    $('#user').empty();
                    $.each(response, function(index, user) {
                        $('#user').append('<option value="' + user.usercode + '">' + user.name + ' (' + user.empid + ')</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    updateCSRFToken();
                }
            });
        });
        $(document).ready(function() {
            $('#user').change(function() {
                var usercode = $(this).val();

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    url: "<?php echo base_url('Reports/Copying/Report/getsection_user'); ?>",
                    method: 'POST',
                    data: {
                        usercode: usercode,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    dataType: 'html',
                    success: function(response) {
                        $('#query_builder_wrapper').html(response);

                        updateCSRFToken();

                    },
                    error: function(xhr, status, error) {
                        updateCSRFToken();
                        console.error(xhr.responseText); // Log error response
                    }
                });

            });
        });

    });
</script>
<script>
    $(function() {
        $("#query_builder_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>