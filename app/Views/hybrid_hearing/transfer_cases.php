<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="card-title">Consent for Hearing - Transfer Cases</h3>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">

                                <?= csrf_field() ?>
                                <div class="col-sm-5">
                                    <label>Listing Date<span style="color:red;">*</span></label>
                                    <div class="border">
                                        <select name="listing_dts" id="listing_dts" class="form-control">
                                            <option value="-1">SELECT</option>
                                            <?php if (!empty($dates)): ?>
                                            <?php foreach ($dates as $row): ?>
                                            <option value="<?= $row['next_dt']; ?>">
                                                <?= date("d-m-Y", strtotime($row['next_dt'])); ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <option value="-1">EMPTY</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>



                                <div class="col-sm-2">
                                    <label>Action</label>
                                    <button id="button_search" name="button_search" type="button"
                                        class="btn btn-success btn-block">Search</button>
                                </div>
                            </div>
                        </form>

                        <!-- Result Div -->
                        <div class="row col-md-12 m-0 p-0" id="result" style="margin-top: 20px;">
                            <!-- Results will be dynamically loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).on('click', '#button_search', function() {
    var listing_dts = $("select#listing_dts option:selected").val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    if (listing_dts == '-1') {
        $('#show_error').html('');
        $('#result').html('');
        $('#show_error').append(
            '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select listing date</strong></div>'
            );
        $("#listing_dts").focus();
        return false;
    } else {
        $("#result").html('');
        var listing_dts = $('#listing_dts').val();

        $.ajax({
            url: '<?php echo  base_url('HybridHearing/Transfer_cases/transfer_cases_get'); ?>',
            cache: false,
            async: true,
            data: {
                listing_dts: listing_dts,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },

            dataType: 'html',
            beforeSend: function() {
                $("#button_search").html('Loading <i class="fas fa-sync fa-spin"></i>');
            },
            type: 'POST',
            success: function(res) {
                updateCSRFToken();
                if (res) {
                    $("#result").html(res);
                } else {
                    $("#result").html('');
                }
                $("#button_search").html('Search');
            },
            error: function(xhr) {
                updateCSRFToken();
                console.log("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
});
 
</script>