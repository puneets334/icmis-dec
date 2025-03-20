<?= view('header') ?>
<?php  $uri = current_url(true); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Upload Proceedings</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <?php
                            $attribute = array('class' => 'form-horizontal appearance_search_form','name' => 'appearance_search_form', 'id' => 'appearance_search_form', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data');
                            echo form_open(base_url('Court/CourtMasterController/uploadROP'), $attribute);
                            ?>
                            <div class="row">
                                <div class="col-md-4 diary_section">
                                    <div class="form-group row">
                                        <label for="diary_number" class="col-sm-5 col-form-label">Causelist Date</label>
                                        <div class="col-sm-7">
                                            <input type="date" class="form-control" name="causelistDate" id="causelistDate" placeholder="dd/mm/yyyy" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2 pl-4 mb-3">
                                    <!-- <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block" value="Search"> -->
                                    <!-- <input id="btnGetCases" name="btn_search" type="button" class="btn btn-success btn-block" value="Get Cases" onclick="getCasesForUploading();"> -->

                                    <button type="button" id="btnGetCases" class="btn btn-info form-control" onclick="getCasesForUploading();">Get Cases
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row" id="divCasesForUploading"></div>
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">

    function getCasesForUploading() {
        //alert("1");
        var causelistDate = $('#causelistDate').val();
        var pJudge = $('#pJudge').val();
        var causelistType = $('#causelistType').val();
        var bench = $('#bench').val();
        var usercode=$('#usercode').val();
        if(causelistDate == ""){
            alert("Please Select Causelist Date..");
            $('#causelistDate').focus();
            return false;
        }
        if (causelistDate != "" ){
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('Court/CourtMasterController/getCasesForUploading'); ?>",
                data:
                {
                    'causelistDate': causelistDate,
                    'usercode': usercode,
                },
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    $('.appearance_search').val('Please wait...');
                    $('.appearance_search').prop('disabled', true);
                },
                success: function (result) {
                    $('.appearance_search').prop('disabled', false);
                    $('.appearance_search').val('Search');
                    $("#divCasesForUploading").html(result);
                    $("#loader").html('');

                    updateCSRFToken();
                },
                error: function () {
                    updateCSRFToken();
                    alert('something went wrong. Please try after sometime');
                    return false;
                }

            });
        }
    }
</script>