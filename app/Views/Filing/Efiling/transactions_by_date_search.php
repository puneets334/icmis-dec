<?=view('header'); ?>
 
    <style>
        .custom-radio{float: left; display: inline-block; margin-left: 10px; }
        .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
        .basic_heading{text-align: center;color: #31B0D5}
        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }
        .card-header {
            padding: 5px;
        }
        h4 {
            line-height: 0px;
        }
        .row {
             margin-right: 15px;
             margin-left: 15px;
         }
         
    </style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Efiling >> Admin</h3>
                            </div>
                            <div class="col-sm-2">
                              
                            </div>
                        </div>
                    </div>

                    <?=view('Filing/Efiling/Efiling_breadcrumb');?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                     <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
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
                                   <h5 class="box-title ml-4">Transaction by Date</h5>
                                    
                                    <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'report', 'id' => 'report', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>

                                    <div class="row">
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">From Date:</label>
                                            <div class="form-group">
                                                <!--<div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>-->
                                                <input type="date" class="form-control pickDate" id="from_date" name="from_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">To Date:</label>
                                            <div class="form-group">
                                                <!--<div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>-->
                                                <input type="date" class="form-control pickDate" id="to_date" name="to_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">Transaction Status:</label>
                                            <div class="form-group">
                                                <select class="form-control" id="status" name="status" required>
                                                    <option value="">Select</option>
                                                    <option value="1">Complete</option>
                                                    <option value="2">Failed Transactions</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">Document Type:</label>
                                            <div class="form-group">
                                                <select class="form-control" id="app_type" name="app_type" required>
                                                    <option value="">Select</option>
                                                    <option value="1">All</option>
                                                    <option value="2">Filing</option>
                                                    <option value="3">Additional Documents</option>
                                                    <option value="4">Deficit</option>
                                                    <option value="5">Deficit_DN</option>
                                                    <option value="6">Add Doc Sp</option>
                                                    <option value="7">Refiling</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">Action Completed:</label>
                                            <div class="form-group">
                                                <select class="form-control" id="action_type" name="action_type" required="true">
                                                    <option value="a" selected>ALL</option>
                                                    <option value="Y">Yes</option>
                                                    <option value="N">No</option>


                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label>&nbsp;</label>
                                            <button type="submit" id="btn-shift-assign"  class="btn btn-block  btn-flat pull-right btn btn-primary"><i class="fa fa-save"></i> Search </button>
                                            </button>
                                        </div>

                                    </div>




                                    <?php form_close();?>
                                      <br/>

                                    <center><span id="loader"></span> </center>
                                    <div id="result_data"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="docModal" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div id="printThis" class="modal-body">
                <div class="modal-header">
                    <h4 class="modal-title" id="postData_diary_no" ></h4>
                    <h4 class="modal-title" id="postData_ack_id"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <div id="result_data_modal"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</section>
    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.min.js'); ?>"></script>
    <script>
        $('#report').on('submit', function () {
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
            var diffMilliseconds = Math.abs(date2 - date1);
            var oneDayMilliseconds = 1000 * 60 * 60 * 24;
            var diffDays = Math.ceil(diffMilliseconds / oneDayMilliseconds);

            if (from_date.length == 0) {
                alert("Please select from date.");
                $("#from_date").focus();
                validationError = false;
                return false;
            }
            else if (to_date.length == 0) {
                alert("Please select to date.");
                $("#to_date").focus();
                validationError = false;
                return false;
            }
            if (date1 > date2) {
                alert("To Date must be greater than From date");
                $("#to_date").focus();
                validationError = false;
                return false;
            }
            if (date1 > date2 || diffDays > 5) {
                alert("To Date must be greater than From date and the days interval must be 5days");
                $("#to_date").focus();
                validationError = false;
                return false;
            }


            if ($('#report').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if(validateFlag){
                    $('.alert-error').hide();
                    $("#loader").html('');
                    $('#result_data').html('');
                    $('#reqResult').append('')
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Efiling/transactions_by_date'); ?>",
                        data: form_data,
                        beforeSend: function () {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function (response) {
                            $("#loader").html('');
                            updateCSRFToken();
                            var resArr = response.split('@@@');
                            if (resArr[0] == 1) {
                                $('.alert-error').hide();
                                $(".form-response").html("");
                                $('#result_data').html(resArr[1]);
                            }else if (resArr[0] == 3) {
                                $('#result_data').html('');
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            $('#result_data').html('');
                            alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;

                }
            } else {
                return false;
            }
        });
        $(document).on('click', '.upd_action', function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var transaction_id = $(this).attr("data-transaction_id");
            var action_update = $(this).attr("data-action_update");
            if(action_update=='Y'){ var flgg='N';  }else if(action_update=='N'){ var flgg='Y'; }
           // alert("The data-id of clicked item is: " + transaction_id + "The data-id of clicked item is: " + action_update);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Filing/Efiling/update_action'); ?>",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,transaction_id:transaction_id,action_update:action_update},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (response) {
                    $("#loader").html('');
                    updateCSRFToken();
                    var resArr = response.split('@@@');
                    if (resArr[0] == 1) {
                        $('.alert-error').hide();
                        $(".form-response").html("");
                        $("#loader").html(resArr[1]);
                        $('#transaction_id_'+transaction_id).html("<button  data-transaction_id='"+transaction_id+"' data-action_update='"+flgg+"'  type='button' id='transaction_id_"+action_update+"' class='upd_action btn btn-block bg-olive btn-flat pull-right' ><i class='fa fa-save'></i> &nbsp;"+flgg+"</button>");
                        }else if (resArr[0] == 3) {
                        $('.alert-error').show();
                        $(".form-response").html("<p>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                    }
                },
                error: function() {
                    updateCSRFToken();
                    $('#loader').html('');
                    alert('Something went wrong! please contact computer cell');
                }
            });
        });

        $(document).on('click', '.get_docs', function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var app_flag = $(this).attr("data-app_flag");
            var transaction_id = $(this).attr("data-transaction_id");
            var org_diary_no = $(this).attr("data-org_diary_no");
            var ack_id = $(this).attr("data-ack_id");
            var ack_year = $(this).attr("data-ack_year");
            var diary = org_diary_no;
            var diary_number=diary.substring(0, diary.length-4);
            var diary_year=diary.substring(diary.length-4);
            $('#postData_diary_no').text('Diary No: '+diary_number+'/'+diary_year);
            $('#postData_ack_id').text('Ref ID: '+ack_id);
            var url='';
            var form_data='';
            if(app_flag == 'Add Doc' || app_flag == 'Add Doc Sp' || app_flag == 'Refiling' || app_flag == 'ReFiling' ){
                url = '<?=base_url('Filing/Efiling/docs_from_sc_diary_no'); ?>';
                 form_data={CSRF_TOKEN: CSRF_TOKEN_VALUE,transaction_id:transaction_id,diary_number:diary_number,diary_year:diary_year};
            }
            else if(app_flag == 'Filing'){
                url = '<?=base_url('Filing/Efiling/get_documents'); ?>';
                 form_data={CSRF_TOKEN: CSRF_TOKEN_VALUE,transaction_id:transaction_id,ack_id:ack_id,ack_year:ack_year};
            }

            $('.alert-error').hide(); $(".form-response").html(""); $("#loader").html('');
          if (url !='') {
              $('#result_data_modal').html('');
              $.ajax({
                  type: "POST",
                  url: url,
                  data: form_data,
                  beforeSend: function () {
                      $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                  },
                  success: function (response) {
                      $("#loader").html('');
                      updateCSRFToken();
                      var resArr = response.split('@@@');
                      if (resArr[0] == 1) {
                          $("#result_data_modal").html(resArr[1]);
                          $('#transaction_id_' + transaction_id).html("<button  data-transaction_id='" + transaction_id + "' data-action_update='" + flgg + "'  type='button' id='transaction_id_" + action_update + "' class='upd_action btn btn-block bg-olive btn-flat pull-right' ><i class='fa fa-save'></i> &nbsp;" + flgg + "</button>");
                      } else if (resArr[0] == 3) {
                          $('.alert-error').show();
                          $(".form-response").html("<p>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                      }
                  },
                  error: function () {
                      updateCSRFToken();
                      $('#result_data_modal').html('');
                      alert('Something went wrong! please contact computer cell');
                  }
              });
          }
        });


    </script>