<?= view('header.php'); ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">

                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Report Type: MATTERS OF JUDICIAL SECTION (REGISTERED AND UNREGISTERED)</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" id="push-form" method="post">
                            <?= csrf_field(); ?>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="section" class="col-form-label">MATTERS OF JUDICIAL SECTION REGISTERED AND UNREGISTERED</label>
                                            <select class="form-control sel_sec"  id="sect" name="sect" required>
                                                <option value="0">Select</option>
                                                <?php foreach ($case_result as $result):
                                                    echo '<option value="'. $result['section_name'].'">'. $result['section_name'].'</option>';
                                                endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-2 mt-4">
                                        <div class="form-group">
                                            <button type="submit" id="view" name="view" class="btn btn-primary mt-4">View</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <img id="image" src="<?= base_url(); ?>/images/load.gif" style="display: none;">
                        <div id="table_Result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
 
    $('#push-form').on('submit', function(e) {

        var section = $('.sel_sec').val();

        if (section == '') {
            alert("Please Select Section");
            return false;
        }
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '<?= base_url(); ?>/lwdr/section_reg_data',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#table_Result').html(response);
            },
            beforeSend: function() {
                $('#table_Result').html('');
                $('#view').attr('disabled', true);
                $('#image').show();
            },
            complete: function() {
                $('#view').attr('disabled', false);
                $('#image').hide();
                updateCSRFToken();
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while submitting the form.');
            }
        });
    });
</script>