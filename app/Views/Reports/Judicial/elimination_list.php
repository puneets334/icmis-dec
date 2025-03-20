<div class="tab-content">
    <div id="load_search_view"><!-- DEBUG-VIEW START 1 APPPATH/Views/Reports/court/gist_module_search_view.php -->

        <div class="active tab-pane" id="Refiling">
            <!--   <form action="http://10.40.186.169:92" class="form-horizontal Elimination_list_form" name="Elimination_list_form" id="Elimination_list_form" autocomplete="off" method="post" accept-charset="utf-8">
            <input type="hidden" name="CSRF_TOKEN" value="44463e12aa543eae72b2dd8cd209aa2ca3abea5c61528f5da895fbe51ca4fea5">    <div class="row"> -->
            <form method="post" action="<?= site_url(uri_string()) ?>" name="Elimination_list_form" id="Elimination_list_form">
                <?= csrf_field() ?>

                <div class="col-md-12">
                <div class="text-center mb-4">
                    <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ELEMINATION LIST</span>
                </div>
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row">
                            <!-- Board Type Dropdown -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="board_type" class="col-form-label">Board Type</label>
                                    <select name="board_type" id="board_type" class="form-control">
                                        <option value="J">Court</option>
                                        <!-- <option value="C">Chamber</option>
                                        <option value="R">Registrar</option> -->
                                    </select>
                                </div>
                            </div>

                            <!-- Dates Input -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="listing_dts" class="col-form-label">Dates</label>
                                    <input type="date" class="form-control" id="listing_dts" name="listing_dts" placeholder="From Date">
                                </div>
                            </div>

                            <!-- Section Name Dropdown -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="sec_id" class="col-form-label">Section Name</label>
                                    <select name="sec_id" id="sec_id" class="form-control">
                                        <option value="0">-ALL-</option>
                                        <?php foreach ($section as $sec) : ?>
                                            <option value="<?php echo $sec->id; ?>"> <?php echo $sec->section_name; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <input type="button" name="Elimination_list" id="Eliminationlist" class="btn btn-primary btn-block mt-4" value="Submit">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div id="result_data"></div>

    <script>
        $('#Eliminationlist').on('click', function() {
            //alert('hi');


            var form_data = $('#Elimination_list_form').serialize();
            if (form_data) { //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/Elimination_list'); ?>",
                    data: form_data,
                    beforeSend: function() {
                        $('#Eliminationlist').val('Please wait...');
                        $('#Eliminationlist').prop('disabled', true);
                    },
                    success: function(data) {
                        //alert(data);
                        $('#Eliminationlist').prop('disabled', false);
                        $('#Eliminationlist').val('Submit');
                        $("#result_data").html(data);

                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }

                });
                return false;
            }
        });
    </script>



    <!-- DEBUG-VIEW ENDED 1 APPPATH/Views/Reports/court/gist_module_search_view.php -->
</div>

</div>