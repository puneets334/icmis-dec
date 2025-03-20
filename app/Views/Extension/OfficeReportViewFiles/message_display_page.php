
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
                                <h3 class="card-title">Office Report </h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?=base_url('Extension/OfficeReport');?>"><button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                    <?php if(session()->getFlashdata('error')){ ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error')?>
                                        </div>
                                    <?php } else if(session("message_error")){ ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?=session()->getFlashdata("message_error")?>
                                        </div>
                                    <?php }else{?>
                                        <br/>
                                    <?php }?>
                                    <?php
                                    $filing_details= session()->get('filing_details');
                                    if (!empty($filing_details))
                                    {?>
                                        <div class="row">
                                            <label class="col-sm-12 col-form-label">
                                                <b>Diary Number :</b> <?=substr($filing_details['diary_no'], 0, -4).'/'.substr($filing_details['diary_no'],-4);?> &nbsp;&nbsp;&nbsp;
                                        <?php if (!empty($filing_details['reg_no_display'])){?><b>Case Number :</b> <?=$filing_details['reg_no_display'];?> <?php } ?> &nbsp;&nbsp;&nbsp;
                                                <b>Case Title :</b> <?=$filing_details['pet_name'].'  <b>Vs</b>  '.$filing_details['res_name'];?> &nbsp;&nbsp;&nbsp;
                                                <b>Filing Date : </b><?=(!empty($filing_details['diary_no_rec_date'])) ? date('d-m-Y',strtotime($filing_details['diary_no_rec_date'])): NULL ?> &nbsp;&nbsp;&nbsp;
                                                <?php if ($filing_details['c_status'] =='P'){ echo '<span class="text-blue">Pending</span>';}else{echo '<span class="text-red">Disposed</span>';} ?>
                                            </label>

                                        </div>
                                    <?php } ?>

                                    <br><br><br><br>

                                    <center>
                                        <div >

                                            <h4 style="color:red">The searched case is disposed. Office report in disposed case is not allowed.</h4>
                                        </div>
                                       </center>

                                        <br><br><br><br>

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
    $(document).ready(function() {
        $('#component_search').on('submit', function () {
            var search_type = $("input[name='search_type']:checked").val();
            if (search_type.length == 0) {
                alert("Please select case type");
                validationError = false;
                return false;
            }
            var diary_number = $("#diary_number").val();
            var diary_year =$('#diary_year :selected').val();

            var case_type =$('#case_type :selected').val();
            var case_number = $("#case_number").val();
            var case_year =$('#case_year :selected').val();

            if (search_type=='D') {
                if (diary_number.length == 0) {
                    alert("Please enter diary number");
                    validationError = false;
                    return false;
                }else if (diary_year.length == 0) {
                    alert("Please select diary year");
                    validationError = false;
                    return false;
                }
            }else if (search_type=='C') {

                if (case_type.length == 0) {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                }else if (case_number.length == 0) {
                    alert("Please enter case number");
                    validationError = false;
                    return false;
                }else if (case_year.length == 0) {
                    alert("Please select case year");
                    validationError = false;
                    return false;
                }

            }

            if ($('#component_search').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if(validateFlag){
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide(); $(".form-response").html("");
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Diary/search'); ?>",
                        data: form_data,
                        beforeSend: function () {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function (data) {
                            $("#loader").html('');
                            updateCSRFToken();
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                //window.location.reload();
                                window.location.href =resArr[1];
                            } else if (resArr[0] == 3) {
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;
                }
            } else {
                return false;
            }
        });
    });
</script>
 <?=view('sci_main_footer') ?>