<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> PHYSICAL HEARING CASES CONSENT REPORT </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="Country" class="col-sm-5 col-form-label">Select Consent :</label>
                                        <div class="col-sm-7">
                                            <select name="consentType" id="consentType" class="form-control">
                                                <option value="P" selected>Physical</option>
                                                <option value="V">Virtual</option>
                                                <option value="N">Not Updated</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <label for="Country" class="col-sm-5 col-form-label">Select Case Category :</label>
                                        <div class="col-sm-7">
                                            <select name="caseCategory" id="caseCategory" class="form-control">
                                                <option value="M" selected>Miscellaneous</option>
                                                <option value="F">Regular</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <input type="button" class="btn btn-primary getCases" value="Get Cases" onclick="javascript:getConsentReport();">
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <?= csrf_field(); ?>
                            <div id="consentReport"></div>

                        </div>
                    </div><!-- Main content end -->
                </div> <!-- /.card -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<script>
    function getConsentReport() {
        $('#show_error').html("");
        var consentType = $('#consentType :selected').val();
        var caseCategory = $('#caseCategory :selected').val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?= base_url('Listing/PhysicalHearing/get_consent_report'); ?>",
            cache: false,
            async: true,
            data: {
                consent_type: consentType,
                case_category: caseCategory,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            beforeSend: function() {
                updateCSRFToken();
                $('#result').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $("#consentReport").html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                $("#consentReport").html();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    }
</script>