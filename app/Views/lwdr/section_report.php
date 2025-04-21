<?= view('header.php'); ?>
<style>
    fieldset
    {
        border: 1px solid #ddd !important;
        margin: 0;
        xmin-width: 0;
        padding: 10px;
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;
    }

    legend
    {
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px;
        width: 35%;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 5px 5px 10px;
        background-color: #ffffff;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">

                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">FDR - Cash & Accounts</h3>
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
                                            <label for="section" class="col-form-label">Specific Search</label>
                                            <select name="section" class="form-control sel_sec">
                                <option value="0">All Sections</option>
                                <?php
                                foreach ($sections as $val){
                                    echo "<option value='".$val['id']."'>" . $val['section_name'] . "</option>";
                                }
                                ?>
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
            alert("Please Select Specific Search");
            return false;
        }
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '<?= base_url(); ?>/lwdr/section_report_data',
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