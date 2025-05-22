<?=view('header') ?>
<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header heading">
                  <div class="row">
                     <div class="col-sm-10">
                        <h3 class="card-title">Delete/Update Mention Memo</h3>
                     </div>
                     <?=view('Filing/filing_filter_buttons'); ?>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="card mt-3">
                        <div class="card-body ">
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
                              $attribute = array('class' => 'form-horizontal','name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                              echo form_open(base_url("court/CourtMentionMemoController/updateMentionMemo_view"), $attribute);
                              ?>
                           <?php echo component_html_mention_meno();?>
                           <center> 
                              <input type='hidden' class="" name="session_id_url" value="<?= $session_id_url; ?>">
                              <button type="submit" class="btn btn-primary" id="submit">View</button>
                           </center>
                           <?php form_close();?>
                           <div id="dv_content1">
								<div id="dv_res1" style="align-content: center"></div>
								<span class="alert alert-error" style="display: none; color: red;">
								   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <span class="form-response"> </span>
								</span>
								<div id="ank"></div>
							</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<section>
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
                        $("#submit").prop('disabled',true);
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('court/CourtMentionMemoController/updateMentionMemo_view'); ?>",
                            data: form_data,
                            beforeSend: function () {
                                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                            },
                            success: function (data) {
								updateCSRFToken();
                                $("#dv_res1").html(data);
                            },
                            complete: function() {
                                updateCSRFToken();
                                $("#submit").prop('disabled',false);
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

