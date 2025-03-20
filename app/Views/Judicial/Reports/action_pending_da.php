<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<style>
    .err_msg_class{font-size:11px;}
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial / Report >> Pending Copying Requests</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("message_error")) { ?>
                                <div class="alert alert-danger text-danger" style="color: red;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("message_error") ?>
                                </div>
                            <?php } else { ?>

                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="card-header p-2" style="background-color: #fff;">
                                <?= view('Judicial/Reports/menu') ?>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Page Content Start -->

                                    <div ng-app="copyApp" ng-controller="copyCtrl">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <input type="hidden" name="empid" id="empid" value="<?php echo $empid; ?> ">
                                                        <input type="hidden" name="desig" id="desig" value="<?php echo $desig; ?> ">

                                                    </div>
                                                    <!--<div id="reg_date_grp">-->
                                                    <div class="row align-items-center">
                                                        <div class="col-md-2" id="fromDate">
                                                            <div class="form-group">
                                                                <label for="from_date" class="control-label">From date</label>
                                                                <input type="date" class="form-control" id="from_date" autocomplete="off" ng-model="from_date"
                                                                    placeholder="From Date">
                                                            </div>
                                                            <span class="from_date_err text-danger err_msg_class"></span>
                                                        </div>
                                                        <div class="col-md-2" id="toDate">
                                                            <div class="form-group">
                                                                <label for="to_date" class="control-label">To date</label>
                                                                <input type="date" class="form-control" id="to_date" autocomplete="off" ng-model="to_date"
                                                                    ng-change="check_date()" placeholder="To Date">                                                                
                                                            </div>
                                                            <span class="to_date_err text-danger err_msg_class"></span>
                                                        </div>
                                                        <div class="col-md-3" id="deliveryMode">
                                                            <div class="form-group">
                                                                <label for="from_date" class="control-label">Delivery Mode</label>
                                                                <select class="form-control" id="deliver_mode" ng-model="deliver_mode">
                                                                    <option value="">Select Delivery Mode</option>
                                                                    <option value="1">By Post</option>
                                                                    <option value="2">By Hand</option>
                                                                </select>
                                                                <span class="deliver_mode_err text-danger"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3" id="documentDiv">
                                                            <div class="form-group">
                                                                <label for="to_date" class="control-label">Document</label>
                                                                <select class="form-control" id="order_type" ng-model="order_type">
                                                                    <option value="">Select Document Type</option>
                                                                    <?php
                                                                    foreach ($order_type as $doc)
                                                                        echo '<option value="' . $doc['id'] . '">' . $doc['order_type'] . '</option>';
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php echo csrf_field(); ?>
                                                        <div class="col-md-2">
                                                            <button type="submit" id="btn-shift-assign" class="btn bg-olive" ng-click="getReport()">
                                                                <i class="fa fa-save"></i>
                                                                Submit </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div ng-show="noDataFound==1" class="alert alert-warning text-center">No Data Found between selected Dates</div>
                                        <div class="col-md-12" ng-if="action_pending_list.length > 0">
                                            <div class="well">
                                                <div class="box">
                                                    <div class="box-header">

                                                        <div class="col-xs-6"><button type="submit" style="width:15%;float:left" id="print" name="print"
                                                                onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button></div>
                                                        <div id="printable">
                                                            <div class="col-xs-6">
                                                                <h3 class="box-title">
                                                                    <?php echo "Pending Copying Requests"; ?>
                                                                </h3>
                                                            </div>
                                                            <div class="box-body no-padding">
                                                                <table class="table table-striped">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <th>Application Number</th>
                                                                            <th>Case No</th>
                                                                            <th>Documents</th>
                                                                            <th>Remarks</th>
                                                                            <th>Registered On</th>
                                                                            <th>Pendency<br>(in days)</th>
                                                                            <th>D.A.</th>
                                                                            <th>Case Status</th>
                                                                            <th>Disposal Date<br>Consignment Date</th>
                                                                            <th>Last Updated By</th>
                                                                        </tr>
                                                                        <tr ng-repeat="x in action_pending_list | filter:projectList.search">
                                                                            <td>{{$index +1}}</td>
                                                                            <td>{{x.application_number_display}}</td>
                                                                            <td ng-if="x.diary_no_display!='/'">
                                                                                {{x.diary_no_display}}<br>{{x.reg_no_display}}
                                                                            </td>

                                                                            <td ng-if="x.diary_no_display=='/'"></td>
                                                                            <td>{{x.docs}} </td>
                                                                            <td>{{x.remarks}} </td>
                                                                            <td>{{ x.application_receipt | jsDate | date: 'dd-MM-yyyy hh:mm' }}</td>
                                                                            <td>{{x.diff}}</td>
                                                                            <td ng-if="x.c_status=='P' && x.section_name">{{x.da}}<br>[{{x.section_name}}]
                                                                            </td>
                                                                            <td ng-if="x.c_status!='P' && x.section_name">{{x.da}}<br>[Record Room]</td>

                                                                            <td ng-if="!x.section_name && (x.tentative_da || x.sec) ">
                                                                                {{x.tentative_da}}<br>{{x.sec}}[T]
                                                                            </td>
                                                                            <td ng-if="!x.sec && !x.section_name"></td>
                                                                            <td ng-if="x.c_status=='P'" style="color:green !Important;">Pending</td>
                                                                            <td ng-if="x.c_status!='P'" style="color:red !Important;">Disposed</td>
                                                                            <td ng-if="x.disposal_dt || x.consignment_date">{{x.disposal_dt | jsDate | date:'dd-MM-yyyy' }}<br>{{x.consignment_date | jsDate | date: 'dd-MM-yyyy' }}
                                                                            </td>
                                                                            <td ng-if="!x.disposal_dt && !x.consignment_date"></td>
                                                                            <td ng-if="x.updatedby">{{x.updatedby}}<br>({{x.updatedbysection}}) </td>
                                                                            <td ng-if="!x.updatedby"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Page Content End -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<!-- <script src="<?php echo base_url() ?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script> -->
<script src="<?php echo base_url() ?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- <script src="<?php echo base_url() ?>/assets/js/bootstrap.min.js"></script> -->
<script src="<?php echo base_url() ?>/assets/plugins/fastclick/fastclick.js"></script>
<!-- <script src="<?php echo base_url() ?>/assets/js/app.min.js"></script> -->
<script src="<?php echo base_url() ?>/assets/js/Reports.js"></script>
<!-- <script src="<?php echo base_url() ?>/assets/jsAlert/dist/sweetalert.min.js"></script> -->
<script src="<?php echo base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- <script src="<?php echo base_url() ?>/assets/js/angular.min.js"></script> -->
<script src="<?php echo base_url() ?>/assets/js/Reports.js"></script>
<script type="text/javascript">
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    function updateCSRFToken() {

        // console.log($('[name="CSRF_TOKEN"]').val());

        // Show the loader before the request
        // $('#whole_page_loader').show();

        // Make the AJAX request to get the CSRF token
        $.getJSON("<?php echo base_url('Csrftoken'); ?>")
            .done(function(result) {
                // Update the CSRF token value in the form
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                CSRF_TOKEN_VALUE = result.CSRF_TOKEN_VALUE;
            })
            .fail(function() {
                // Optionally, handle the error here
                // alert('Failed to update CSRF token.');
            })
            .always(function() {
                // Hide the loader after the request completes
                // $('#whole_page_loader').hide();
            });
    }
</script>
<script>
    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });

    var app = angular.module('copyApp', []);
    app.filter("jsDate", function() {
        return function(x) {
            return new Date(x);
        };
    });
    app.controller('copyCtrl', function($scope, $http) {

        $scope.noDataFound = 0;

        $scope.check_date = function() {
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
            date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
            if (date1 > date2) {
                alert("To Date must be greater than From date");
                return false;
            }
        }


        function isEmpty(str) {
            return (!str || 0 === str.length);
        }
        function validationFromDate(){
            var from_date = $('#from_date').val();        
            if(from_date == ''){                            
                $('.from_date_err').text('Date is Required');
                return false;
            }else{          
                
                $('.from_date_err').text('');
                return true;
            }
        }
        function validationToDate(){
            var to_date = $('#to_date').val();        
            if(to_date == ''){                            
                $('.to_date_err').text('Date is Required');
                return false;
            }else{          
                
                $('.to_date_err').text('');
                return true;
            }
        }

        $scope.getReport = function() 
        {            
            solve1 =true;solve2 = true;solve3 = true;solve4 = true;
            solve1 = validationFromDate();
            solve2 = validationToDate();
            if(solve1 == true && solve2 ==true)
            {
                        $scope.noDataFound = 0;
                        $scope.action_pending_list = [];
                        var empid = $('#empid').val();
                        if (!isEmpty($scope.from_date) && !isEmpty($scope.to_date)) {
                            $http.get('<?php echo base_url(); ?>/Judicial/Report/getActionPendingReportDA', {
                                params: {  // Use 'params' to pass query parameters
                                        empid: empid,
                                        from_date: $scope.from_date,
                                        to_date: $scope.to_date,
                                        deliver_mode: $scope.deliver_mode,
                                        order_type: $scope.order_type,
                                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                                    }
                            }).then(function successCallback(response) {
                                updateCSRFToken();
                                var data = response.data;
                                // console.log(data);
                                if (data.length == 0) {
                                    $scope.noDataFound = 1;
                                    // console.log('1');
                                } else {
                                    // console.log('2');
                                    $scope.noDataFound = 0;
                                    $scope.action_pending_list = response.data;
                                }

                            }, function errorCallback(response) {
                                updateCSRFToken();
                            });
                        }    
            }
            
            
            
            
        }
    });
</script>