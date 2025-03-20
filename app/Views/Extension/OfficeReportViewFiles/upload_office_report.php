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
                                    <h3 class="card-title">Upload Office Report </h3>
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?=base_url('Extension/OfficeReport');?>"><button class="btn btn-info btn-sm" type="button"><i class="fas fa-pencil	" aria-hidden="true"></i></button></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                    <?= view('Extension/OfficeReportViewFiles/office_report_menus'); ?>
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
                                        <? //= view('Extension/OfficeReportViewFiles/office_report_menus'); ?>
                                        <?php
                                        $attribute = array('class' => 'form-horizontal','name' => 'office_report', 'id' => 'component_search', 'autocomplete' => 'off','enctype' => 'multipart/form-data');
                                        echo form_open(base_url('#'), $attribute);
                                        ?>

 
                                        <div >
										<div class="cl_center">
                        <span style="color: red"> (1) For Fresh cases enter order date in 00-00-0000 format.
                        (2) Upload only PDF file
                        </span>  
                    </div>
                                            <div class="row">
												
                                                <div class="col-sm-4">
                                                    <div class="form-group " >
                                                        <label for="from" class="col-sm-12 col-form-label">Listing Date: </label>
                                                        <div class="col-sm-12">
                                                            <input type="text" name="ddl_ord_date" class="form-control dtp" id="ddl_ord_date" size="9" maxlength="10" class="dtp"/>&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="caseNo" class="col-sm-12 col-form-label">File Upload:</label>
                                                        <div class="col-sm-12">
                                                            <input type="file" id="upd_file" name="upd_file"  />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                               <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group ">
                                                        <label for="caseYear" class="col-sm-12 col-form-label">Remark:</label>
                                                        <div class="col-sm-12">
                                                            <textarea placeholder="Enter Summary" class="btn-block summary form-control" cols="6" rows="4" maxlength="500" style="width:100%; color:red;" name="summary" id="summary"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                               </div>
<!--                                            --><?php
//                                            $current_page_url = base_url('Extension/OfficeReport');
//                                            ?>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group row">
                                                        <div class="col-sm-10">
                                                         
                                                        <input type="button" name="sub" value="SUBMIT"  class="btn btn-primary" id="sub"/>
														</div>
                                                    </div>
                                                </div>
                                            </div>


											<div id="div_result" style="text-align: center"></div>

                                        </div>
                                        <?php form_close();?>



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
	<script type="text/javascript" src="<?php echo base_url('filing/upload_office_report.js?random=2')?>"></script>
	<script>
        $(document).on("focus",".dtp",function(){
			$('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
		});
        });
           </script>
    <script>
        /* $(document).ready(function() {
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
                            url: "<?php //echo base_url('Filing/Diary/search'); ?>",
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
        }); */
    </script>
 <?//=view('sci_main_footer') ?>