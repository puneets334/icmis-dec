<?= view('header') ?>

<style>
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Fresh Cases Causelist (Only Published List) </h3>
                            </div>
                        </div>
                    </div>
                    <?php $attribute = array('class' => 'form-horizontal fresh_matter', 'name' => 'fresh_matter', 'id' => 'fresh_matter', 'autocomplete' => 'off');
                    echo form_open(base_url('#'), $attribute); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Case Stage: </label>
                                                <div class="col-sm-7">
                                                    <select name="main_regular" id="main_regular" class="form-control" onchange="getListingdates();">
                                                        <option value="M">Miscellaneous</option>
                                                        <option value="R">Regular</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Board Type: </label>
                                                <div class="col-sm-7">
                                                    <select name="board_type" id="board_type" class="form-control">
                                                        <option value="0">-ALL-</option>
                                                        <option value="J">Court</option>
                                                        <option value="S">Single Judge</option>
                                                        <option value="C">Chamber</option>
                                                        <option value="R">Registrar</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Main/Suppl.: </label>
                                                <div class="col-sm-7">
                                                    <?php main_supp(); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Listing Date: </label>
                                                <div class="col-sm-7">
                                                    <select name="listing_date" id="listing_date" class="form-control" >
                                                        <?php if (is_array($listingDates) || is_object($listingDates)): ?>
                                                            <?php foreach ($listingDates as $ldates): ?>
                                                                <option value="<?php echo $ldates['next_dt']; ?>"> <?php echo date('d-m-Y', strtotime($ldates['next_dt'])); ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <option value="">No dates available</option>
                                                        <?php endif; ?>


                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Court No.: </label>
                                                <div class="col-sm-7">
                                                    <?php court_no(); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Section: </label>
                                                <div class="col-sm-7">
                                                    <select name="sec_id[]" id="sec_id" class="select2" multiple="multiple" style="width:100%">
                                                        <option value="0" selected="selected" >-ALL-</option>
                                                        <?php foreach ($section as $sec) : ?>
                                                            <option value="<?php echo $sec->id; ?>"> <?php echo $sec->section_name; ?></option>
                                                        <?php endforeach ?>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Received Status: </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="received" id="received">
                                                        <option value="0">ALL</option>
                                                        <option value="1">Received</option>
                                                        <option value="2">Not Received</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Scanned Status: </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="scn_sts" id="scn_sts">
                                                        <option value="0">ALL</option>
                                                        <option value="1">SCANNED</option>
                                                        <option value="2">Not SCANNED</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">MA/RP/Cont./Cur./Jail Petition: </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="ma_cc_crlm" id="ma_cc_crlm">
                                                        <option value="0">Exclude</option>
                                                        <option value="1">Include</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label">Order By: </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="orderby" id="orderby">
                                                        <option value="0">-ALL-</option>
                                                        <option value="1">Court Wise</option>
                                                        <option value="2">Section Wise</option>
                                                        <option value="3">Court & Item Wise</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <center> <button type="submit" class="btn btn-primary" id="submit">Submit</button></center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                </div>
                                <?php form_close();
                                ?>

                                <span id="loader"></span> </center>
                                <div id="result_data"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
    function getListingdates() {
        var m_f = $("#main_regular").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Extension/Causelist/getListingDates'); ?>",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                m_f: m_f
            },
            // beforeSend: function () {
            //     $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");

            // },
            success: function(data) {
                $("#listing_date").html(data);
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }

        });
    }

    $('#fresh_matter').on('submit', function() {

        /*  // var form_data = $(this).serialize();
              // alert(form_data);
          var m_f=$("#main_regular").val();
              var CSRF_TOKEN = 'CSRF_TOKEN';
              var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
              $('.alert-error').hide();
              $("#loader").html('');
          $.ajax({
              type: "POST",
              url: "<?php /*echo base_url('Extension/Causelist/getFreshMatters'); */ ?>",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    m_f:m_f
                },
                /!*beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php /*echo base_url('images/load.gif'); */ ?>'></div>");

                },*!/
                success: function (data) {
                    alert(data);
                    updateCSRFToken();
                },
                error: function (data) {
                    alert(data);
                    updateCSRFToken();
                }

            });*/

        var form_data = $(this).serialize();
        //  if(validateFlag){ //alert('readt post form');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.alert-error').hide();
        $("#loader").html('');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Extension/Causelist/getFreshMatters'); ?>",
            data: form_data,
            beforeSend: function() {
                //  $('.caveat_search').val('Please wait...');
                //    $('.caveat_search').prop('disabled', true);
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");

            },
            success: function(data) {
                updateCSRFToken();
                $('.caveat_search').prop('disabled', false);
                $('.caveat_search').val('Search');
                $("#loader").html('');
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('.alert-error').hide();
                    $(".form-response").html("");
                    $('#result_data').html(resArr[1]);
                } else if (resArr[0] == 3) {
                    $('#result_data').html('');
                    $('.alert-error').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                }


            },
            error: function() {
                updateCSRFToken();
            }

        });
        return false;

    });
</script>