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
                                   <h5 class="box-title">E-filed Applications- Refiling Report + Additional Documents report for Scrutiny Assistants (Dealing Assistant wise) </h5>
                                    <br/>
                                    <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'sensitive_report', 'id' => 'sensitive_report', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>

                                    <div class="row">
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">From Date:</label>
                                            <div class="input-group">
                                                <!--<div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>-->
                                                <input type="date" class="form-control pickDate" id="from_date" ng-model="fields.from_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">To Date:</label>
                                            <div class="input-group">
                                                <!--<div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>-->
                                                <input type="date" class="form-control pickDate" id="to_date" ng-model="fields.to_date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">Transaction Status:</label>
                                            <div class="input-group">
                                                <select class="form-control" id="status" ng-model="fields.status" required="true">
                                                    <option value="">Select</option>
                                                    <option value="1">Complete</option>
                                                    <option value="2">Failed Transactions</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="causelistDate">Document Type:</label>
                                            <div class="input-group">
                                                <select class="form-control" id="app_type" ng-model="fields.app_type" required="true">
                                                    <option value="">Select</option>
                                                    <option value="1">All(except additional documents)</option>
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
                                            <label>&nbsp;</label>
                                            <button type="button" id="btn-shift-assign" class="btn btn-block  btn-flat pull-right btn btn-primary" ng-click="get_transaction_details()"><i class="fa fa-save"></i> Search </button>
                                            </button>
                                        </div>

                                    </div>




                                    <?php form_close();?>
                                      <br/>
                                    <div id="result_data"></div>

                                    <center><span id="loader"></span> </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
